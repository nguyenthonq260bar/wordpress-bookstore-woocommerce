<?php
namespace Company\WooCommerce\Core;

defined('ABSPATH') || exit;

class TemplateLoader
{
    public static function init()
    {
        add_filter('woocommerce_locate_template', [self::class, 'locate_template'], 20, 3);
    }

    public static function locate_template($template, $template_name, $template_path)
    {
        if (!$template_name) {
            return $template;
        }

        $theme_override = get_stylesheet_directory() . '/woocommerce/company-woocommerce/' . $template_name;
        if (file_exists($theme_override)) {
            return $theme_override;
        }

        $plugin_template = COMPANY_WOO_PATH . 'templates/' . $template_name;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }

        return $template;
    }
}
