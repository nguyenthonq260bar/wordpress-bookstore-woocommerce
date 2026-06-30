<?php
namespace Company\WooCommerce\ZaloContact;

defined('ABSPATH') || exit;

class Module
{
    public function init()
    {
        Frontend::init();
        AdminPage::init();

        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public static function position_css($position)
    {
        $positions = [
            'bottom-right' => 'bottom: 20px; right: 20px;',
            'bottom-left'  => 'bottom: 20px; left: 20px;',
            'top-right'    => 'top: 20px; right: 20px;',
            'top-left'     => 'top: 20px; left: 20px;',
        ];
        return $positions[$position] ?? $positions['bottom-right'];
    }

    public function enqueue_assets()
    {
        $options = get_option('company_zalo_settings', []);
        if (empty($options['phone'])) return;

        wp_enqueue_style(
            'company-zalo',
            COMPANY_WOO_URL . 'assets/css/zalo-contact.css',
            [],
            COMPANY_WOO_VERSION
        );

        $position = $options['position'] ?? 'bottom-right';
        $pos_css = self::position_css($position);

        wp_add_inline_style('company-zalo', '
            .company-zalo-btn {
                background: #0068ff;
                color: #fff;
                position: fixed;
                ' . $pos_css . '
                z-index: 9999;
                width: 70px;
                height: 36px;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 700;
                font-family: Arial, sans-serif;
                text-transform: uppercase;
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                cursor: pointer;
                transition: transform 0.2s;
                text-decoration: none;
            }
            .company-zalo-btn:hover { transform: scale(1.1); }

        ');
    }
}
