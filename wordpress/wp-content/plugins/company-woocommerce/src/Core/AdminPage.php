<?php
namespace Company\WooCommerce\Core;

defined('ABSPATH') || exit;

class AdminPage
{
    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu'], 10);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_admin_menu()
    {
        add_menu_page(
            'Company Settings',
            'Company',
            'manage_options',
            'company-settings',
            [__CLASS__, 'render_modules_page'],
            'dashicons-admin-generic',
            58
        );

        add_submenu_page(
            'company-settings',
            'Modules',
            'Modules',
            'manage_options',
            'company-settings',
            [__CLASS__, 'render_modules_page']
        );
    }

    public static function register_settings()
    {
        register_setting('company_modules', 'company_modules_settings', [
            'sanitize_callback' => [__CLASS__, 'sanitize_modules'],
        ]);
    }

    public static function sanitize_modules($input)
    {
        if (!is_array($input)) {
            return [];
        }
        $plugin = Plugin::get_instance();
        $clean = [];
        foreach ($plugin->get_modules() as $key => $config) {
            $clean[$key] = !empty($input[$key]) ? 1 : 0;
        }
        return $clean;
    }

    public static function render_modules_page()
    {
        $plugin = Plugin::get_instance();
        $saved = get_option('company_modules_settings', []);
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Company Modules', 'company-woocommerce'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('company_modules'); ?>
                <table class="form-table">
                    <?php foreach ($plugin->get_modules() as $key => $config):
                        $checked = isset($saved[$key]) ? (bool) $saved[$key] : $config['enabled'];
                    ?>
                    <tr>
                        <th scope="row"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="company_modules_settings[<?php echo esc_attr($key); ?>]" value="1" <?php checked($checked); ?>>
                                <?php esc_html_e('Enable', 'company-woocommerce'); ?>
                            </label>
                            <?php if (!empty($config['description'])) : ?>
                                <p class="description" style="margin-top:4px;max-width:500px">
                                    <?php echo esc_html($config['description']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($config['usage'])) : ?>
                                <details style="margin-top:8px;max-width:600px;cursor:pointer">
                                    <summary style="font-weight:500;color:#2271b1;font-size:13px">
                                        <?php esc_html_e('Hướng dẫn sử dụng', 'company-woocommerce'); ?>
                                    </summary>
                                    <div style="margin-top:8px;padding:12px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:4px;cursor:auto;font-size:13px;line-height:1.6">
                                        <?php echo wp_kses_post($config['usage']); ?>
                                    </div>
                                </details>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
