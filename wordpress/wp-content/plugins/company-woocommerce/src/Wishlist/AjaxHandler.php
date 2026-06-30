<?php
namespace Company\WooCommerce\Wishlist;

defined('ABSPATH') || exit;

class AjaxHandler
{
    public static function init()
    {
        add_action('wp_ajax_company_wishlist_toggle', [self::class, 'toggle']);
        add_action('wp_ajax_nopriv_company_wishlist_toggle', [self::class, 'toggle']);
        add_action('wp_ajax_company_wishlist_remove', [self::class, 'remove']);
    }

    public static function toggle()
    {
        check_ajax_referer('company_wishlist_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Please log in to use wishlist.', 'company-woocommerce')]);
        }

        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        if (!$product_id || !wc_get_product($product_id)) {
            wp_send_json_error(['message' => __('Invalid product.', 'company-woocommerce')]);
        }

        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, '_wishlist_ids', true) ?: [];
        if (!is_array($wishlist)) $wishlist = [];

        if (in_array($product_id, $wishlist)) {
            $wishlist = array_diff($wishlist, [$product_id]);
            $added = false;
        } else {
            $wishlist[] = $product_id;
            $added = true;
        }

        update_user_meta($user_id, '_wishlist_ids', array_values(array_unique($wishlist)));

        wp_send_json_success([
            'added'  => $added,
            'count'  => count($wishlist),
        ]);
    }

    public static function remove()
    {
        check_ajax_referer('company_wishlist_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Please log in.', 'company-woocommerce')]);
        }

        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, '_wishlist_ids', true) ?: [];
        if (!is_array($wishlist)) $wishlist = [];

        $wishlist = array_diff($wishlist, [$product_id]);
        update_user_meta($user_id, '_wishlist_ids', array_values($wishlist));

        wp_send_json_success(['count' => count($wishlist)]);
    }
}
