<?php
namespace Company\WooCommerce\ZaloContact;

defined('ABSPATH') || exit;

class AdminPage
{
    public static function init()
    {
        add_action('admin_menu', [self::class, 'add_menu_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_menu_page()
    {
        add_submenu_page(
            'company-settings',
            __('Zalo Contact', 'company-woocommerce'),
            __('Zalo Contact', 'company-woocommerce'),
            'manage_options',
            'company-zalo',
            [self::class, 'render_page']
        );
    }

    public static function register_settings()
    {
        register_setting('company_zalo_settings', 'company_zalo_settings', [self::class, 'sanitize']);

        add_settings_section(
            'company_zalo_section',
            __('Zalo Contact Settings', 'company-woocommerce'),
            null,
            'company-zalo'
        );

        add_settings_field(
            'zalo_phone',
            __('Zalo Phone Number', 'company-woocommerce'),
            [self::class, 'field_phone'],
            'company-zalo',
            'company_zalo_section'
        );

        add_settings_field(
            'zalo_message',
            __('Default Message', 'company-woocommerce'),
            [self::class, 'field_message'],
            'company-zalo',
            'company_zalo_section'
        );

        add_settings_field(
            'zalo_display',
            __('Display on Pages', 'company-woocommerce'),
            [self::class, 'field_display'],
            'company-zalo',
            'company_zalo_section'
        );

        add_settings_field(
            'zalo_position',
            __('Button Position', 'company-woocommerce'),
            [self::class, 'field_position'],
            'company-zalo',
            'company_zalo_section'
        );

        add_settings_field(
            'zalo_hide_mobile',
            __('Hide on Mobile', 'company-woocommerce'),
            [self::class, 'field_hide_mobile'],
            'company-zalo',
            'company_zalo_section'
        );
    }

    public static function sanitize($input)
    {
        $output = [];
        $output['phone'] = isset($input['phone']) ? preg_replace('/[^0-9]/', '', $input['phone']) : '';
        $output['message'] = isset($input['message']) ? sanitize_text_field($input['message']) : '';
        $output['display_mode'] = isset($input['display_mode']) && $input['display_mode'] === 'selected' ? 'selected' : 'all';
        $output['selected_pages'] = isset($input['selected_pages']) && is_array($input['selected_pages'])
            ? array_map('intval', $input['selected_pages'])
            : [];
        $output['position'] = isset($input['position']) && in_array($input['position'], ['bottom-right', 'bottom-left', 'top-right', 'top-left'])
            ? $input['position'] : 'bottom-right';
        $output['show_homepage'] = !empty($input['show_homepage']) ? 1 : 0;
        $output['hide_mobile'] = !empty($input['hide_mobile']) ? 1 : 0;
        return $output;
    }

    public static function field_phone()
    {
        $options = get_option('company_zalo_settings', []);
        $value = $options['phone'] ?? '';
        echo '<input type="text" name="company_zalo_settings[phone]" value="' . esc_attr($value) . '" class="regular-text" placeholder="84912345678">';
        echo '<p class="description">' . __('Enter your Zalo phone number (e.g. 84912345678).', 'company-woocommerce') . '</p>';
    }

    public static function field_message()
    {
        $options = get_option('company_zalo_settings', []);
        $value = $options['message'] ?? '';
        echo '<input type="text" name="company_zalo_settings[message]" value="' . esc_attr($value) . '" class="regular-text" placeholder="Xin chào, tôi cần tư vấn thêm!">';
        echo '<p class="description">' . __('Default message sent when clicking Zalo button.', 'company-woocommerce') . '</p>';
    }

    public static function field_display()
    {
        $options = get_option('company_zalo_settings', []);
        $display_mode = $options['display_mode'] ?? 'all';
        $selected_pages = $options['selected_pages'] ?? [];

        $all_checked = $display_mode !== 'selected' ? 'checked' : '';
        $selected_checked = $display_mode === 'selected' ? 'checked' : '';

        echo '<label style="display:block;margin-bottom:8px">';
        echo '<input type="radio" name="company_zalo_settings[display_mode]" value="all" ' . $all_checked . '> ';
        echo __('Show on all pages', 'company-woocommerce');
        echo '</label>';

        echo '<label style="display:block;margin-bottom:8px">';
        echo '<input type="radio" name="company_zalo_settings[display_mode]" value="selected" ' . $selected_checked . '> ';
        echo __('Show only on selected pages', 'company-woocommerce');
        echo '</label>';

        echo '<div class="zalo-pages-list" style="max-height:300px;overflow-y:auto;border:1px solid #ddd;padding:8px 12px;background:#f9f9f9;margin-top:8px">';

        $show_homepage = !empty($options['show_homepage']);
        echo '<label style="display:block;margin-bottom:6px;font-weight:600">';
        echo '<input type="checkbox" name="company_zalo_settings[show_homepage]" value="1" ' . checked($show_homepage, true, false) . '> ';
        echo __('Homepage (Front Page)', 'company-woocommerce');
        echo '</label>';
        echo '<hr style="margin:6px 0">';

        $pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        if ($pages) {
            foreach ($pages as $page) {
                $checked = in_array($page->ID, $selected_pages) ? 'checked' : '';
                echo '<label style="display:block;margin-bottom:4px">';
                echo '<input type="checkbox" name="company_zalo_settings[selected_pages][]" value="' . esc_attr($page->ID) . '" ' . $checked . '> ';
                echo esc_html($page->post_title) . ' <span style="color:#999">(ID: ' . $page->ID . ')</span>';
                echo '</label>';
            }
        } else {
            echo '<p>' . __('No pages found.', 'company-woocommerce') . '</p>';
        }

        echo '</div>';
        echo '<p class="description">' . __('Select specific pages where the Zalo button should appear.', 'company-woocommerce') . '</p>';
    }

    public static function field_position()
    {
        $options = get_option('company_zalo_settings', []);
        $position = $options['position'] ?? 'bottom-right';
        $positions = [
            'bottom-right' => __('Bottom Right', 'company-woocommerce'),
            'bottom-left'  => __('Bottom Left', 'company-woocommerce'),
            'top-right'    => __('Top Right', 'company-woocommerce'),
            'top-left'     => __('Top Left', 'company-woocommerce'),
        ];
        echo '<select name="company_zalo_settings[position]">';
        foreach ($positions as $val => $label) {
            $selected = $position === $val ? 'selected' : '';
            echo '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }

    public static function field_hide_mobile()
    {
        $options = get_option('company_zalo_settings', []);
        $checked = !empty($options['hide_mobile']) ? 'checked' : '';
        echo '<label><input type="checkbox" name="company_zalo_settings[hide_mobile]" value="1" ' . $checked . '> ' . __('Hide the Zalo button on mobile devices.', 'company-woocommerce') . '</label>';
    }

    public static function render_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('company_zalo_settings');
                do_settings_sections('company-zalo');
                submit_button();
                ?>
            </form>
        </div>
        <script>
        jQuery(function($) {
            $('input[name="company_zalo_settings[display_mode]"]').on('change', function() {
                $('.zalo-pages-list').toggle($(this).val() === 'selected');
            }).filter(':checked').trigger('change');
        });
        </script>
        <?php
    }
}
