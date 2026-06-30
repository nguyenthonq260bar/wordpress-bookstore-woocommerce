<?php
namespace Company\WooCommerce\Language;

defined('ABSPATH') || exit;

class Module
{
    public function init()
    {
        add_action('init', [__CLASS__, 'set_lang_cookie']);
        Switcher::init();
    }

    public static function get_lang()
    {
        static $lang = null;
        if ($lang !== null) {
            return $lang;
        }
        if (!empty($_GET['lang']) && in_array($_GET['lang'], ['en', 'vi'], true)) {
            $lang = $_GET['lang'];
            return $lang;
        }
        if (!empty($_COOKIE['company_lang']) && in_array($_COOKIE['company_lang'], ['en', 'vi'], true)) {
            $lang = $_COOKIE['company_lang'];
            return $lang;
        }
        $lang = 'en';
        return $lang;
    }

    public static function set_locale($locale)
    {
        if (self::get_lang() === 'vi') {
            return 'vi';
        }
        return 'en_US';
    }

    public static function set_lang_cookie()
    {
        if (!empty($_GET['lang']) && in_array($_GET['lang'], ['en', 'vi'], true)) {
            setcookie('company_lang', $_GET['lang'], time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
        }
    }
}
