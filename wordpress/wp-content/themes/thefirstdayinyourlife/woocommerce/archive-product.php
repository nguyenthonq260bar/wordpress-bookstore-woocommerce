<?php
defined('ABSPATH') || exit;

get_header('shop');

echo '<section class="page-section" data-section="Shop">';

mytheme_page_banner();

echo '<div class="container container--wide">';
echo '<div class="content-card content-card--full">';
echo '<div class="shop-layout">';

do_action('woocommerce_before_main_content');

echo '<div class="shop-content">';

do_action('woocommerce_before_shop_loop');

if (woocommerce_product_loop()) :

	echo '<ul class="products books-grid">';

	if (wc_get_loop_prop('total')) {
		while (have_posts()) {
			the_post();
			do_action('woocommerce_shop_loop');
			wc_get_template_part('content', 'product');
		}
	}

	echo '</ul>';

	do_action('woocommerce_after_shop_loop');

else :
	do_action('woocommerce_no_products_found');
endif;

echo '</div>'; // shop-content

do_action('woocommerce_after_main_content');

echo '</div>'; // shop-layout
echo '</div>'; // content-card
echo '</div>'; // container
echo '</section>'; // page-section

get_footer('shop');
