<?php
namespace Company\WooCommerce\Wishlist;

defined('ABSPATH') || exit;

class Module
{
    public function init()
    {
        Frontend::init();
        AccountPage::init();
        AjaxHandler::init();

        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets()
    {
        wp_enqueue_script(
            'company-wishlist',
            COMPANY_WOO_URL . 'assets/js/wishlist.js',
            ['jquery'],
            COMPANY_WOO_VERSION,
            true
        );

        wp_localize_script('company-wishlist', 'company_wishlist', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('company_wishlist_nonce'),
        ]);

        // Wishlist CSS is in the theme: css/woo-account.css
    }
}
