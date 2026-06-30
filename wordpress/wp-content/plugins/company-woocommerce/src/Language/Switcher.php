<?php
namespace Company\WooCommerce\Language;

defined('ABSPATH') || exit;

class Switcher
{
    public static function init()
    {
        add_action('company_language_switcher', [__CLASS__, 'render']);
    }

    public static function render()
    {
        $current = Module::get_lang();
        $other   = $current === 'en' ? 'vi' : 'en';
        $current_url = self::current_url();
        $en_url  = add_query_arg('lang', 'en', $current_url);
        $vi_url  = add_query_arg('lang', 'vi', $current_url);
        ?>
        <div class="lang-switcher">
            <a href="<?php echo esc_url($en_url); ?>"
               class="lang-switcher__link<?php echo $current === 'en' ? ' lang-switcher__link--active' : ''; ?>"
               data-lang="en">EN</a>
            <span class="lang-switcher__sep">|</span>
            <a href="<?php echo esc_url($vi_url); ?>"
               class="lang-switcher__link<?php echo $current === 'vi' ? ' lang-switcher__link--active' : ''; ?>"
               data-lang="vi">VI</a>
        </div>
        <?php
    }

    private static function current_url()
    {
        $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return remove_query_arg('lang', $current_url);
    }
}
