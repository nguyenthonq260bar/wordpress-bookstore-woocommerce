<?php
namespace Company\WooCommerce\Search;

defined('ABSPATH') || exit;

class Module
{
    public function init()
    {
        AjaxHandler::init();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets()
    {
        // Search should be available site-wide on the front-end, including homepage, shop and product archives.
        wp_enqueue_style(
            'company-search',
            COMPANY_WOO_URL . 'assets/css/search.css',
            [],
            COMPANY_WOO_VERSION
        );

        wp_enqueue_script(
            'company-search',
            COMPANY_WOO_URL . 'assets/js/search.js',
            ['jquery'],
            COMPANY_WOO_VERSION,
            true
        );

        wp_localize_script('company-search', 'company_search', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('company_search_nonce'),
            'home_url' => home_url('/'),
        ]);
    }
}
