<?php
namespace Company\WooCommerce\Wishlist;

defined('ABSPATH') || exit;

class Frontend
{
    public static function init()
    {
        add_action('woocommerce_after_shop_loop_item', [self::class, 'render_toggle_button']);
        add_action('woocommerce_before_single_product_summary', [self::class, 'render_on_image'], 20);
        add_action('woocommerce_cart_actions', [self::class, 'render_wishlist_link_on_cart']);
    }

    private static function heart_svg($filled = true)
    {
        if ($filled) {
            return '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
        }
        return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
    }

    public static function render_toggle_button()
    {
        global $product;
        if (!$product) return;

        $product_id = $product->get_id();
        $in_wishlist = self::is_in_wishlist($product_id);
        $class = 'company-wishlist-btn';
        if ($in_wishlist) $class .= ' active';

        echo '<button class="' . esc_attr($class) . '" data-product-id="' . esc_attr($product_id) . '" title="' . esc_attr__('Wishlist', 'company-woocommerce') . '" aria-label="' . esc_attr__('Toggle Wishlist', 'company-woocommerce') . '">';
        echo '<span class="company-wishlist-btn-icon">' . self::heart_svg($in_wishlist) . '</span>';
        echo '</button>';
    }

    public static function render_on_image()
    {
        global $product;
        if (!$product) return;

        $product_id = $product->get_id();
        $in_wishlist = self::is_in_wishlist($product_id);
        $class = 'company-wishlist-btn company-wishlist-btn--image';
        if ($in_wishlist) $class .= ' active';

        echo '<button class="' . esc_attr($class) . '" data-product-id="' . esc_attr($product_id) . '" title="' . esc_attr__('Wishlist', 'company-woocommerce') . '" aria-label="' . esc_attr__('Toggle Wishlist', 'company-woocommerce') . '">';
        echo '<span class="company-wishlist-btn-icon">' . self::heart_svg($in_wishlist) . '</span>';
        echo '</button>';
    }

    public static function render_wishlist_link_on_cart()
    {
        if (!is_user_logged_in()) return;
        $wishlist = self::get_wishlist();
        if (empty($wishlist)) return;

        echo '<a href="' . esc_url(wc_get_account_endpoint_url('wishlist')) . '" class="button company-wishlist-cart-link" style="margin-top:10px;display:inline-flex;align-items:center;gap:6px">';
        echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
        echo sprintf(__('Wishlist (%d)', 'company-woocommerce'), count($wishlist));
        echo '</a>';
    }

    public static function is_in_wishlist($product_id)
    {
        if (!is_user_logged_in()) return false;
        $wishlist = get_user_meta(get_current_user_id(), '_wishlist_ids', true) ?: [];
        return in_array((int) $product_id, $wishlist);
    }

    public static function get_wishlist()
    {
        if (!is_user_logged_in()) return [];
        $ids = get_user_meta(get_current_user_id(), '_wishlist_ids', true) ?: [];
        return is_array($ids) ? $ids : [];
    }
}
