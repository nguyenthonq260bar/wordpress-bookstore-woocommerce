<?php
namespace Company\WooCommerce\Filter {

    use Company\WooCommerce\Core\Assets;
    use Company\WooCommerce\Core\TemplateLoader;

    defined('ABSPATH') || exit;

    class Module
    {
        public function init()
        {
            if (is_admin()) {
                AdminPage::init();
            }
            AjaxHandler::init();
            QueryModifier::init();
            TemplateLoader::init();
            add_action('wp_enqueue_scripts', [Assets::class, 'enqueue']);
            add_action('woocommerce_before_shop_loop', 'company_render_filter_sidebar', 5);

            // Invalidate cache when products or terms change
            add_action('save_post_product', [DataProvider::class, 'clear_cache']);
            add_action('delete_post_product', [DataProvider::class, 'clear_cache']);
            add_action('created_term', [DataProvider::class, 'clear_cache']);
            add_action('edited_term', [DataProvider::class, 'clear_cache']);
            add_action('delete_term', [DataProvider::class, 'clear_cache']);
        }

        public function render_sidebar()
        {
            if (!is_shop() && !is_product_category() && !is_product_tag()) {
                return;
            }

            $data = DataProvider::get_all();
            wc_get_template('filter-sidebar.php', $data, '', COMPANY_WOO_PATH . 'templates/');
        }
    }
}

namespace {
    if (!function_exists('company_render_filter_sidebar')) {
        function company_render_filter_sidebar()
        {
            $module = new \Company\WooCommerce\Filter\Module();
            $module->render_sidebar();
        }
    }
}
