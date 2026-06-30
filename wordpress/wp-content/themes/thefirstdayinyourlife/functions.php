<?php

/**
 * MyTheme functions and definitions
 */

// Fallback for _t() if company-woocommerce plugin is deactivated
if (!function_exists('_t')) {
    function _t($en, $vi) { return $en; }
}

// Theme setup
function mytheme_setup()
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
	add_theme_support('custom-logo', [
		'height'      => 40,
		'width'       => 120,
		'flex-height' => true,
		'flex-width'  => true,
	]);

	register_nav_menus([
		'primary'      => __('Primary Menu', 'mytheme'),
		'footer-bottom' => __('Footer Bottom Links', 'mytheme'),
	]);

	// Load theme translations
	load_theme_textdomain('mytheme', get_template_directory() . '/languages');

	// WooCommerce support
	add_theme_support('woocommerce');
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mytheme_setup');

// Remove WooCommerce default styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Remove default WooCommerce wrapper actions
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Register widget areas
function mytheme_widgets_init()
{
	register_sidebar([
		'name'          => __('Footer Column 1', 'mytheme'),
		'id'            => 'footer-1',
		'before_widget' => '<div id="%1$s" class="footer-col %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	]);
	register_sidebar([
		'name'          => __('Footer Column 2', 'mytheme'),
		'id'            => 'footer-2',
		'before_widget' => '<div id="%1$s" class="footer-col %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	]);
	register_sidebar([
		'name'          => __('Footer Column 3', 'mytheme'),
		'id'            => 'footer-3',
		'before_widget' => '<div id="%1$s" class="footer-col %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	]);
	register_sidebar([
		'name'          => __('Footer Column 4', 'mytheme'),
		'id'            => 'footer-4',
		'before_widget' => '<div id="%1$s" class="footer-col %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	]);

	// Shop sidebar
	register_sidebar([
		'name'          => __('Shop Sidebar', 'mytheme'),
		'id'            => 'shop-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);

	// Blog sidebar
	register_sidebar([
		'name'          => __('Blog Sidebar', 'mytheme'),
		'id'            => 'blog-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	]);
}
add_action('widgets_init', 'mytheme_widgets_init');

// Enqueue scripts and styles
function mytheme_scripts()
{
	$ver = wp_get_theme()->get('Version');
	$uri = get_template_directory_uri();

	wp_enqueue_style('mytheme-base',       $uri . '/css/base.css',            [], $ver);
	wp_enqueue_style('mytheme-header',     $uri . '/css/header.css',          [], $ver);
	wp_enqueue_style('mytheme-hero',       $uri . '/css/hero.css',            [], $ver);
	wp_enqueue_style('mytheme-buttons',    $uri . '/css/buttons.css',         [], $ver);
	wp_enqueue_style('mytheme-front-parts',$uri . '/css/front-parts.css',     [], $ver);
	wp_enqueue_style('mytheme-woocommerce',$uri . '/css/woocommerce.css',     [], $ver);
	wp_enqueue_style('mytheme-woo-sp',     $uri . '/css/woo-single-product.css', [], $ver);
	wp_enqueue_style('mytheme-woo-cart',   $uri . '/css/woo-cart.css',        [], $ver);
	wp_enqueue_style('mytheme-woo-checkout',$uri . '/css/woo-checkout.css',   [], $ver);
	wp_enqueue_style('mytheme-woo-account',$uri . '/css/woo-account.css',     [], $ver);
	wp_enqueue_style('mytheme-layout',     $uri . '/css/layout.css',          [], $ver);

	wp_enqueue_script('mytheme-script', $uri . '/js/main.js', ['jquery'], $ver, true);
}
add_action('wp_enqueue_scripts', 'mytheme_scripts');

// WooCommerce: Cart fragment update (AJAX)
function mytheme_cart_fragments($fragments)
{
	$fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'mytheme_cart_fragments');

// WooCommerce: Number of products per row in shop
function mytheme_loop_columns()
{
	return 4;
}
add_filter('loop_shop_columns', 'mytheme_loop_columns');

// WooCommerce: Number of products per page
function mytheme_products_per_page()
{
	return 12;
}
add_filter('loop_shop_per_page', 'mytheme_products_per_page');

// WooCommerce: Custom placeholder image
function mytheme_placeholder_img_src($src)
{
	return get_template_directory_uri() . '/assets/images/placeholder.png';
}
add_filter('woocommerce_placeholder_img_src', 'mytheme_placeholder_img_src');

// WooCommerce: Remove breadcrumbs
add_action('init', function () {
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
});

// WooCommerce: Remove default sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// WooCommerce: Remove default checkout button (using custom one in cart)
// add_action('init', function() {
// 	remove_action('woocommerce_proceed_to_checkout', 'woocommerce_output_wc_proceed_to_checkout', 10);
// });

// Move notices from top of page into .cart-frame
remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);

// Remove default add-to-cart button in loop (theme has custom one in overlay)
add_action('init', function () {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
});

// WooCommerce: Add to cart button text
function mytheme_add_to_cart_text()
{
	return _t('Add to cart', 'Thêm vào giỏ hàng');
}
add_filter('woocommerce_product_add_to_cart_text', 'mytheme_add_to_cart_text');

// Buy Now redirect to checkout
add_filter('woocommerce_add_to_cart_redirect', function ($url) {
	if (isset($_REQUEST['buy_now']) && $_REQUEST['buy_now']) {
		return wc_get_checkout_url();
	}
	return $url;
});

// Helper: Product background color gradient (based on ID for consistency)
function mytheme_product_color($product_id)
{
	$colors = [
		['var(--tn-blue-dark)', 'var(--tn-blue)'],
		['var(--tn-purple)', 'var(--tn-blue)'],
		['var(--tn-orange)', 'var(--tn-red)'],
		['var(--tn-cyan)', 'var(--tn-blue)'],
		['var(--tn-blue)', 'var(--tn-purple)'],
		['var(--tn-orange)', 'var(--tn-red)'],
		['var(--tn-cyan)', 'var(--tn-blue)'],
		['var(--tn-green)', 'var(--tn-cyan)'],
	];
	$index = $product_id % count($colors);
	return $colors[$index][0] . ', ' . $colors[$index][1];
}

// WooCommerce: Ensure cart page gets correct template
function mytheme_woocommerce_template_overrides($template, $template_name, $template_path)
{
	global $woocommerce;
	$_template = $template;
	if (!$template_name) return $template;
	$template_path = 'woocommerce';
	$plugin_path = $woocommerce->plugin_path() . '/templates/';
	$template = locate_template([$template_path . '/' . $template_name, $template_name]);
	if (!$template && file_exists($plugin_path . $template_name)) {
		$template = $plugin_path . $template_name;
	}
	if (!$template) {
		$template = $_template;
	}
	return $template;
}
add_filter('woocommerce_locate_template', 'mytheme_woocommerce_template_overrides', 10, 3);

add_filter('show_admin_bar', '__return_false');

// ============================================
// MY ACCOUNT CUSTOMIZATIONS
// ============================================

// Flush rewrite rules + ensure testimonial category on theme switch
function mytheme_theme_activation()
{
	flush_rewrite_rules();
	if (!term_exists('testimonial', 'category') && function_exists('wp_insert_category')) {
		wp_insert_category([
			'cat_name' => 'Testimonial',
			'category_nicename' => 'testimonial',
			'category_description' => 'Customer testimonials displayed on the homepage.',
		]);
	}
}
add_action('after_switch_theme', 'mytheme_theme_activation');

// Helper: render prop icon SVG
function mytheme_prop_icon($name)
{
	$icons = [
		'book' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
		'truck' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
		'lock' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
		'heart' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
		'star' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
		'box' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
		'clock' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'card' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
		'shield' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
	];
	return isset($icons[$name]) ? $icons[$name] : $icons['book'];
}

// Stock availability text
add_filter('woocommerce_get_availability_text', function ($text, $product) {
	if ($product->is_in_stock() && $product->managing_stock()) {
		$qty = (int) $product->get_stock_quantity();
		if ($qty > 0) {
			return sprintf(__('%d available', 'woocommerce'), $qty);
		}
	}
	return $text;
}, 10, 2);

// Use password from form instead of auto-generating
add_filter('woocommerce_registration_generate_password', '__return_false', 999);

// Force DB option to 'no' to prevent WooCommerce from sending "set password" email
add_action('init', function () {
    if (get_option('woocommerce_registration_generate_password') !== 'no') {
        update_option('woocommerce_registration_generate_password', 'no');
    }
    if (get_option('woocommerce_registration_generate_username') !== 'no') {
        update_option('woocommerce_registration_generate_username', 'no');
    }
});

add_filter('woocommerce_registration_generate_username', '__return_false', 999);

// Remove privacy policy text from registration form
remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);

// Validate confirm password on registration
add_filter('woocommerce_registration_errors', function ($errors, $username, $email) {
	if (isset($_POST['password2']) && $_POST['password'] !== $_POST['password2']) {
		$errors->add('password_mismatch', __('Passwords do not match.', 'mytheme'));
	}
	return $errors;
}, 10, 3);

// Disable comment flood protection for WooCommerce product reviews to prevent false duplicate detection
add_filter('wp_comment_flood_filter', function ($flood_display, $time_lastcomment, $time_newcomment, $user_id, $post_id) {
	if (is_product() && get_post_type($post_id) === 'product') {
		return false;
	}
	return $flood_display;
}, 10, 5);

// Force reviews to be enabled for all published products (simple, variable, grouped)
add_filter('woocommerce_product_get_reviews_allowed', function ($reviews_allowed, $product) {
	if ($product && $product->get_status() === 'publish') {
		return true;
	}
	return $reviews_allowed;
}, 10, 2);

// Also filter when reading from database
add_filter('woocommerce_product_get_prop_reviews_allowed', function ($reviews_allowed, $product, $prop) {
	if ($product && $product->get_status() === 'publish') {
		return true;
	}
	return $reviews_allowed;
}, 10, 3);

// Force comments_open for all published products (ensures reviews display for variable, grouped etc.)
add_filter('comments_open', function ($open, $post_id) {
	if ($post_id && get_post_type($post_id) === 'product' && get_post_status($post_id) === 'publish') {
		return true;
	}
	return $open;
}, 999, 2);

// ============================================
// PAGE BANNER
// ============================================
function mytheme_page_banner()
{
	if (is_singular() && get_post_meta(get_the_ID(), '_hide_banner', true)) {
		return;
	}

	$bg_image = '';
	if (is_singular() && has_post_thumbnail()) {
		$bg_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
	}
	if (!$bg_image) {
		$default_id = get_theme_mod('page_banner_default_image', 0);
		if ($default_id) {
			$bg_image = wp_get_attachment_url($default_id);
		}
	}

	$title = '';
	$subtitle = '';

	if (is_singular()) {
		$title = get_the_title();
	} elseif (is_search()) {
		$title = sprintf(esc_html__('Search Results: %s', 'mytheme'), '<span>' . get_search_query() . '</span>');
		$count = $GLOBALS['wp_query']->found_posts;
		if ($count) {
			$subtitle = sprintf(esc_html(_n('Found %d result...', 'Found %d results...', $count, 'mytheme')), $count);
		}
	} elseif (is_404()) {
		$title = esc_html__('Page Not Found', 'mytheme');
		$subtitle = esc_html__('Sorry, the page you\'re looking for doesn\'t exist.', 'mytheme');
	} elseif (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag())) {
		$title = woocommerce_page_title(false);
		if (is_product_category()) {
			$desc = term_description();
			if ($desc) {
				$subtitle = $desc;
			}
		}
		if (!$subtitle) {
			$subtitle = esc_html__('Browse our curated collection of books and find your next great read.', 'mytheme');
		}
	} elseif (is_archive()) {
		$title = get_the_archive_title();
		$desc = get_the_archive_description();
		if ($desc) {
			$subtitle = $desc;
		}
	}

	if (!$title) {
		return;
	}

	$classes = 'page-banner';
	if ($bg_image) {
		$classes .= ' has-bg';
	}

	$style = $bg_image ? ' style="background-image: url(' . esc_url($bg_image) . ')"' : '';
?>
	<div class="<?php echo esc_attr($classes); ?>"<?php echo $style; ?>>
		<div class="container">
			<?php if (is_single() && 'post' === get_post_type()) : ?>
				<div class="article-meta">
					<?php
					$categories = get_the_category();
					if (!empty($categories)) :
					?>
						<span class="tag"><?php echo esc_html($categories[0]->name); ?></span>
					<?php endif; ?>
					<span><?php echo get_the_date('F j, Y'); ?></span>
				</div>
			<?php endif; ?>
			<h1 class="page-title"><?php echo wp_kses_post($title); ?></h1>
			<?php if ($subtitle) : ?>
				<p class="page-subtitle"><?php echo wp_kses_post($subtitle); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php
}

// ============================================
// CUSTOMIZER: Hero Banner
// ============================================
function mytheme_customize_register($wp_customize)
{
	$wp_customize->add_section('hero_banner', [
		'title'    => __('Hero Banner', 'mytheme'),
		'priority' => 30,
	]);

	// Background image
	$wp_customize->add_setting('hero_background_image', [
		'sanitize_callback' => 'absint',
	]);

	$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_background_image', [
		'section'   => 'hero_banner',
		'settings'  => 'hero_background_image',
		'label'     => __('Background Image', 'mytheme'),
		'description' => __('Full-width background behind the hero content. Recommended: 1920 × 800px.', 'mytheme'),
		'mime_type' => 'image',
	]));

	// Banner image
	$wp_customize->add_setting('hero_banner_image', [
		'sanitize_callback' => 'absint',
	]);

	$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_banner_image', [
		'section'   => 'hero_banner',
		'settings'  => 'hero_banner_image',
		'label'     => __('Banner Image', 'mytheme'),
		'description' => __('Recommended size: 1920 × 700px. If not set, the default illustration is shown.', 'mytheme'),
		'mime_type' => 'image',
	]));

	// Heading
	$wp_customize->add_setting('hero_heading', [
		'default'           => 'Discover Stories<br />That <span>Inspire</span>',
		'sanitize_callback' => 'wp_kses_post',
	]);

	$wp_customize->add_control('hero_heading', [
		'section' => 'hero_banner',
		'label'   => __('Heading', 'mytheme'),
		'type'    => 'text',
	]);

	// Heading color
	$wp_customize->add_setting('hero_heading_color', [
		'sanitize_callback' => 'sanitize_hex_color',
	]);

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_heading_color', [
		'section' => 'hero_banner',
		'label'   => __('Heading Color', 'mytheme'),
		'description' => __('Only applies when a background image is set.', 'mytheme'),
	]));

	// Subtitle color
	$wp_customize->add_setting('hero_subtitle_color', [
		'sanitize_callback' => 'sanitize_hex_color',
	]);

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_subtitle_color', [
		'section' => 'hero_banner',
		'label'   => __('Subtitle Color', 'mytheme'),
		'description' => __('Only applies when a background image is set.', 'mytheme'),
	]));

	// Subtitle
	$wp_customize->add_setting('hero_subtitle', [
		'default'           => 'Your next great read is waiting. Explore our curated collection of bestselling books, timeless classics, and hidden gems.',
		'sanitize_callback' => 'sanitize_text_field',
	]);

	$wp_customize->add_control('hero_subtitle', [
		'section' => 'hero_banner',
		'label'   => __('Subtitle', 'mytheme'),
		'type'    => 'textarea',
	]);

	// Button 1 text
	$wp_customize->add_setting('hero_btn1_text', [
		'default'           => 'Explore Collection',
		'sanitize_callback' => 'sanitize_text_field',
	]);

	$wp_customize->add_control('hero_btn1_text', [
		'section' => 'hero_banner',
		'label'   => __('Button 1 Text', 'mytheme'),
		'type'    => 'text',
	]);

	// Button 1 URL
	$wp_customize->add_setting('hero_btn1_url', [
		'sanitize_callback' => 'esc_url_raw',
	]);

	$wp_customize->add_control('hero_btn1_url', [
		'section' => 'hero_banner',
		'label'   => __('Button 1 URL', 'mytheme'),
		'type'    => 'url',
		'input_attrs' => [
			'placeholder' => get_permalink(wc_get_page_id('shop')),
		],
	]);

	// Button 2 text
	$wp_customize->add_setting('hero_btn2_text', [
		'default'           => 'Browse Books',
		'sanitize_callback' => 'sanitize_text_field',
	]);

	$wp_customize->add_control('hero_btn2_text', [
		'section' => 'hero_banner',
		'label'   => __('Button 2 Text', 'mytheme'),
		'type'    => 'text',
	]);

	// Button 2 URL
	$wp_customize->add_setting('hero_btn2_url', [
		'sanitize_callback' => 'esc_url_raw',
	]);

	$wp_customize->add_control('hero_btn2_url', [
		'section' => 'hero_banner',
		'label'   => __('Button 2 URL', 'mytheme'),
		'type'    => 'url',
		'input_attrs' => [
			'placeholder' => '#featured-products',
		],
	]);
}
add_action('customize_register', 'mytheme_customize_register');

// Customizer: Page Banner
function mytheme_page_banner_customize($wp_customize)
{
	$wp_customize->add_section('page_banner', [
		'title'    => __('Page Banner', 'mytheme'),
		'priority' => 32,
	]);

	$wp_customize->add_setting('page_banner_default_image', [
		'sanitize_callback' => 'absint',
	]);

	$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'page_banner_default_image', [
		'section'   => 'page_banner',
		'settings'  => 'page_banner_default_image',
		'label'     => __('Default Banner Image', 'mytheme'),
		'description' => __('Fallback image for pages without a featured image.', 'mytheme'),
		'mime_type' => 'image',
	]));
}
add_action('customize_register', 'mytheme_page_banner_customize');

// ============================================
// CUSTOMIZER: Value Propositions
// ============================================
function mytheme_value_props_customize($wp_customize)
{
	$wp_customize->add_section('value_props', [
		'title'    => __('Service Benefits', 'mytheme'),
		'priority' => 35,
	]);

	$prop_defaults = [
		1 => ['title' => 'Curated Selection', 'desc' => 'Hand-picked titles from independent publishers and bestselling authors worldwide.', 'icon' => 'book'],
		2 => ['title' => 'Fast Delivery', 'desc' => 'Free shipping on orders over $30. Delivered to your doorstep within 3&ndash;5 business days.', 'icon' => 'truck'],
		3 => ['title' => 'Secure Payment', 'desc' => '256-bit SSL encryption. Pay with credit card, PayPal, or Apple Pay with confidence.', 'icon' => 'lock'],
	];

	$icons = [
		'book'   => 'Book',
		'truck'  => 'Truck',
		'lock'   => 'Lock',
		'heart'  => 'Heart',
		'star'   => 'Star',
		'box'    => 'Box',
		'clock'  => 'Clock',
		'card'   => 'Credit Card',
		'shield' => 'Shield',
	];

	for ($i = 1; $i <= 3; $i++) {
		$d = $prop_defaults[$i];
		$prefix = "prop_{$i}";

		$wp_customize->add_setting("{$prefix}_show", [
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		]);
		$wp_customize->add_control("{$prefix}_show", [
			'section'  => 'value_props',
			'label'    => sprintf(__('Benefit %d', 'mytheme'), $i),
			'type'     => 'checkbox',
		]);

		$wp_customize->add_setting("{$prefix}_title", [
			'default'           => $d['title'],
			'sanitize_callback' => 'sanitize_text_field',
		]);
		$wp_customize->add_control("{$prefix}_title", [
			'section' => 'value_props',
			'label'   => sprintf(__('Title %d', 'mytheme'), $i),
			'type'    => 'text',
		]);

		$wp_customize->add_setting("{$prefix}_desc", [
			'default'           => $d['desc'],
			'sanitize_callback' => 'sanitize_text_field',
		]);
		$wp_customize->add_control("{$prefix}_desc", [
			'section' => 'value_props',
			'label'   => sprintf(__('Description %d', 'mytheme'), $i),
			'type'    => 'textarea',
		]);

		$wp_customize->add_setting("{$prefix}_icon", [
			'default'           => $d['icon'],
			'sanitize_callback' => 'sanitize_key',
		]);
		$wp_customize->add_control("{$prefix}_icon", [
			'section' => 'value_props',
			'label'   => sprintf(__('Icon %d', 'mytheme'), $i),
			'type'    => 'select',
			'choices' => $icons,
		]);
	}
}
add_action('customize_register', 'mytheme_value_props_customize');

// Meta box: Hide Header / Hide Footer / Hide Banner
add_action('add_meta_boxes', function () {
  add_meta_box('page_options_meta', 'Page Options', function ($post) {
    wp_nonce_field('page_options_nonce', 'page_options_nonce_field');
    $hide_header = get_post_meta($post->ID, '_hide_header', true) ? 'checked' : '';
    $hide_footer = get_post_meta($post->ID, '_hide_footer', true) ? 'checked' : '';
    $hide_banner = get_post_meta($post->ID, '_hide_banner', true) ? 'checked' : '';
    echo '<label style="display:block;margin-bottom:8px"><input type="checkbox" name="_hide_header" ' . $hide_header . '> Hide Header</label>';
    echo '<label style="display:block;margin-bottom:8px"><input type="checkbox" name="_hide_banner" ' . $hide_banner . '> Hide Banner</label>';
    echo '<label style="display:block"><input type="checkbox" name="_hide_footer" ' . $hide_footer . '> Hide Footer</label>';
  }, 'page', 'normal', 'default');
});

add_action('save_post', function ($post_id) {
  if (!isset($_POST['page_options_nonce_field']) || !wp_verify_nonce($_POST['page_options_nonce_field'], 'page_options_nonce')) return;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if (!current_user_can('edit_post', $post_id)) return;

  update_post_meta($post_id, '_hide_header', isset($_POST['_hide_header']) ? 1 : 0);
  update_post_meta($post_id, '_hide_banner', isset($_POST['_hide_banner']) ? 1 : 0);
  update_post_meta($post_id, '_hide_footer', isset($_POST['_hide_footer']) ? 1 : 0);
});

