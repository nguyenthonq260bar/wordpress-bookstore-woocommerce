<?php
/**
 * Inline translation helper: returns Vietnamese if locale is vi_VN, otherwise English.
 * Usage: echo _t('Hello', 'Xin chào');
 */
if (!function_exists('_t')) {
    function _t($en, $vi)
    {
        return \Company\WooCommerce\Language\Module::get_lang() === 'vi' ? $vi : $en;
    }
}
