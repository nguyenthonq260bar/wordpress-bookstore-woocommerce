<?php
namespace Company\WooCommerce\ZaloContact;

defined('ABSPATH') || exit;

class Frontend
{
    public static function init()
    {
        add_action('wp_footer', [self::class, 'render_button']);
    }

    public static function render_button()
    {
        $options = get_option('company_zalo_settings', []);
        $phone = $options['phone'] ?? '';
        $message = $options['message'] ?? 'Xin chào, tôi cần tư vấn thêm!';
        $hide_mobile = !empty($options['hide_mobile']);

        if (!$phone) return;
        if ($hide_mobile && wp_is_mobile()) return;

        $display_mode = $options['display_mode'] ?? 'all';
        if ($display_mode === 'selected') {
            $on_homepage = !empty($options['show_homepage']) && is_front_page();
            $selected_pages = $options['selected_pages'] ?? [];
            $on_page = is_array($selected_pages) && in_array(get_the_ID(), $selected_pages);
            if (!$on_homepage && !$on_page) {
                return;
            }
        }

        $zalo_url = 'https://zalo.me/' . preg_replace('/[^0-9]/', '', $phone);
        $zalo_url .= '?message=' . rawurlencode($message);

        echo '<a href="' . esc_url($zalo_url) . '" class="company-zalo-btn" target="_blank" rel="noopener noreferrer" aria-label="Zalo">Zalo</a>';
    }
}
