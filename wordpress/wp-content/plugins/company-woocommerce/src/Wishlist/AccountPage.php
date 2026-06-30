<?php
namespace Company\WooCommerce\Wishlist;

defined('ABSPATH') || exit;

class AccountPage
{
    const ENDPOINT = 'wishlist';

    public static function init()
    {
        add_action('init', [self::class, 'add_endpoint']);
        add_filter('woocommerce_account_menu_items', [self::class, 'add_menu_item'], 10, 1);
        add_action('woocommerce_account_' . self::ENDPOINT . '_endpoint', [self::class, 'render_page']);
    }

    public static function add_endpoint()
    {
        add_rewrite_endpoint(self::ENDPOINT, EP_ROOT | EP_PAGES);
    }

    public static function add_menu_item($items)
    {
        $logout = isset($items['customer-logout']) ? $items['customer-logout'] : null;
        if ($logout !== null) {
            unset($items['customer-logout']);
        }

        $items[self::ENDPOINT] = __('Wishlist', 'company-woocommerce');

        if ($logout !== null) {
            $items['customer-logout'] = $logout;
        }

        return $items;
    }

    private static function heart_svg($filled = true)
    {
        if ($filled) {
            return '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
        }
        return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
    }

    private static function cart_svg()
    {
        return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>';
    }

    public static function render_page()
    {
        $product_ids = Frontend::get_wishlist();

        echo '<div class="company-wishlist-wrap">';

        if (empty($product_ids)) {
            echo '<div class="company-wishlist-empty">';
            echo '<div class="company-wishlist-empty-icon">' . self::heart_svg(false) . '</div>';
            echo '<h3>' . __('Your wishlist is empty', 'company-woocommerce') . '</h3>';
            echo '<p>' . __('Save your favorite books here.', 'company-woocommerce') . '</p>';
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '" class="button">' . __('Browse Books', 'company-woocommerce') . '</a>';
            echo '</div>';
            echo '</div>';
            return;
        }

        echo '<div class="company-wishlist-grid">';

        foreach ($product_ids as $pid) {
            $product = wc_get_product($pid);
            if (!$product) continue;

            $in_stock     = $product->is_in_stock();
            $stock_text   = $in_stock ? __('In Stock', 'woocommerce') : __('Out of Stock', 'woocommerce');
            $stock_class  = $in_stock ? 'in-stock' : 'out-of-stock';
            $has_sale     = $product->is_on_sale() && $product->get_regular_price();

            echo '<div class="company-wishlist-card">';

            // Image area
            echo '<div class="company-wishlist-card-img">';
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="company-wishlist-card-thumb">';
            echo $product->get_image('thumbnail', ['class' => 'company-wishlist-card-thumb-img']);
            echo '</a>';
            // Heart on image
            echo '<button class="company-wishlist-btn active" data-product-id="' . esc_attr($pid) . '" title="' . esc_attr__('Remove from Wishlist', 'company-woocommerce') . '" aria-label="' . esc_attr__('Remove from Wishlist', 'company-woocommerce') . '">';
            echo '<span class="company-wishlist-btn-icon">' . self::heart_svg(true) . '</span>';
            echo '</button>';
            echo '</div>';

            // Info
            echo '<div class="company-wishlist-card-body">';
            echo '<a href="' . esc_url($product->get_permalink()) . '" class="company-wishlist-card-title">' . esc_html($product->get_name()) . '</a>';

            echo '<div class="company-wishlist-card-price">';
            echo $product->get_price_html();
            echo '</div>';

            echo '<span class="company-stock-badge ' . esc_attr($stock_class) . '">' . esc_html($stock_text) . '</span>';
            echo '</div>';

            // Actions
            echo '<div class="company-wishlist-card-actions">';
            if ($in_stock) {
                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="button company-wishlist-add-cart" data-product_id="' . esc_attr($pid) . '">';
                echo self::cart_svg();
                echo '<span>' . __('Add to Cart', 'company-woocommerce') . '</span>';
                echo '</a>';
            }
            echo '</div>';

            echo '</div>'; // .company-wishlist-card
        }

        echo '</div>'; // .company-wishlist-grid
        echo '</div>'; // .company-wishlist-wrap
    }
}
