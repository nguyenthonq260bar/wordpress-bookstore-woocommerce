<?php
namespace Company\WooCommerce\Core;

defined('ABSPATH') || exit;

class Assets
{
    public static function enqueue()
    {
        if (!self::should_enqueue()) {
            return;
        }

        wp_enqueue_style(
            'company-filter',
            COMPANY_WOO_URL . 'assets/css/company-filter.css',
            [],
            COMPANY_WOO_VERSION
        );

        wp_enqueue_script(
            'company-filter-ajax',
            COMPANY_WOO_URL . 'assets/js/filter-ajax.js',
            ['jquery'],
            COMPANY_WOO_VERSION,
            true
        );

        wp_localize_script('company-filter-ajax', 'company_filter', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'filter_nonce' => wp_create_nonce('company_filter_nonce'),
        ]);
    }

    private static function should_enqueue()
    {
        return is_shop() || is_product_category() || is_product_tag();
    }
}
