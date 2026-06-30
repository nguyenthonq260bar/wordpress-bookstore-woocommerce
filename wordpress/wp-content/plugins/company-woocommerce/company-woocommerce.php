<?php
/**
 * Plugin Name: Company WooCommerce
 * Plugin URI: https://themountain.cloud
 * Description: WooCommerce enhancements for company bookstore
 * Version: 1.2.0
 * Author: Nguyen Thong
 * Text Domain: company-woocommerce
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

defined('ABSPATH') || exit;

define('COMPANY_WOO_VERSION', '1.2.0');
define('COMPANY_WOO_PATH', plugin_dir_path(__FILE__));
define('COMPANY_WOO_URL', plugin_dir_url(__FILE__));

require_once COMPANY_WOO_PATH . 'src/Core/Plugin.php';
require_once COMPANY_WOO_PATH . 'src/Language/helpers.php';

Company\WooCommerce\Core\Plugin::get_instance()->init();
