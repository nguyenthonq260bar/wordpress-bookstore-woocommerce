<?php
namespace Company\WooCommerce\Filter;

defined('ABSPATH') || exit;

class AdminPage
{
    const OPTION_KEY = 'company_filter_settings';

    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'add_submenu_page'], 20);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_submenu_page()
    {
        add_submenu_page(
            'company-settings',
            'Filter Settings',
            'Filter Settings',
            'manage_options',
            'filter-settings',
            [__CLASS__, 'render_page']
        );
    }

    public static function register_settings()
    {
        register_setting('company_filter', self::OPTION_KEY, [
            'sanitize_callback' => [__CLASS__, 'sanitize_settings'],
        ]);
    }

    public static function sanitize_settings($input)
    {
        if (!is_array($input)) {
            return self::get_defaults();
        }

        $all_filters = self::get_available_filter_types();

        $enabled_filters = [];
        foreach ($all_filters as $key => $label) {
            $enabled_filters[$key] = !empty($input['enabled_filters'][$key]) ? 1 : 0;
        }

        $enabled_attributes = [];
        if (!empty($enabled_filters['attributes'])) {
            $attr_taxonomies = wc_get_attribute_taxonomies();
            foreach ($attr_taxonomies as $attr) {
                $tax = wc_attribute_taxonomy_name($attr->attribute_name);
                $enabled_attributes[$tax] = !empty($input['enabled_attributes'][$tax]) ? 1 : 0;
            }
        }

        return [
            'enabled_filters'    => $enabled_filters,
            'enabled_attributes' => $enabled_attributes,
        ];
    }

    public static function get_defaults()
    {
        $defaults = [];
        foreach (self::get_available_filter_types() as $key => $label) {
            $defaults['enabled_filters'][$key] = in_array($key, ['categories', 'tags', 'price', 'rating'], true) ? 1 : 0;
        }
        $defaults['enabled_attributes'] = [];
        return $defaults;
    }

    public static function get_available_filter_types()
    {
        return [
            'categories' => _t('Categories', 'Danh mục'),
            'tags'       => _t('Tags', 'Thẻ'),
            'brands'     => _t('Brands', 'Thương hiệu'),
            'attributes' => _t('Attributes', 'Thuộc tính'),
            'price'      => _t('Price', 'Giá'),
            'rating'     => _t('Rating', 'Đánh giá'),
        ];
    }

    public static function get_settings()
    {
        $saved = get_option(self::OPTION_KEY, []);
        if (!is_array($saved)) {
            $saved = [];
        }
        return wp_parse_args($saved, self::get_defaults());
    }

    public static function render_page()
    {
        $settings = self::get_settings();
        $attr_taxonomies = wc_get_attribute_taxonomies();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Filter Settings', 'company-woocommerce'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('company_filter'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enabled Filters', 'company-woocommerce'); ?></th>
                        <td>
                            <?php foreach (self::get_available_filter_types() as $key => $label) : ?>
                                <label style="display: block; margin-bottom: 6px;">
                                    <input type="checkbox"
                                           name="company_filter_settings[enabled_filters][<?php echo esc_attr($key); ?>]"
                                           value="1"
                                           <?php checked(!empty($settings['enabled_filters'][$key])); ?>>
                                    <?php echo esc_html($label); ?>
                                </label>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php if (!empty($attr_taxonomies)) : ?>
                    <tr id="company-filter-attributes-row"
                        style="<?php echo empty($settings['enabled_filters']['attributes']) ? 'display:none;' : ''; ?>">
                        <th scope="row"><?php esc_html_e('Product Attributes', 'company-woocommerce'); ?></th>
                        <td>
                            <p class="description"><?php esc_html_e('Chọn attribute cụ thể để hiển thị trong filter.', 'company-woocommerce'); ?></p>
                            <?php foreach ($attr_taxonomies as $attr) :
                                $tax = wc_attribute_taxonomy_name($attr->attribute_name);
                            ?>
                                <label style="display: block; margin-bottom: 4px;">
                                    <input type="checkbox"
                                           name="company_filter_settings[enabled_attributes][<?php echo esc_attr($tax); ?>]"
                                           value="1"
                                           <?php checked(!empty($settings['enabled_attributes'][$tax])); ?>>
                                    <?php echo esc_html($attr->attribute_label); ?> (<?php echo esc_html($tax); ?>)
                                </label>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <script>
                    jQuery(function($) {
                        $('[name="company_filter_settings[enabled_filters][attributes]"]').on('change', function() {
                            $('#company-filter-attributes-row').toggle(this.checked);
                        });
                    });
                    </script>
                    <?php endif; ?>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
