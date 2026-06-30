<?php
defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
	return;
}

$product_id = $product->get_id();
?>
<li <?php wc_product_class('book-card', $product); ?>>
	<?php do_action('woocommerce_before_shop_loop_item'); ?>
	<div class="book-cover" style="--cover-bg: <?php echo mytheme_product_color($product_id); ?>;">
		<a href="<?php the_permalink(); ?>" class="book-cover-link">
			<?php if (has_post_thumbnail($product_id)) : ?>
				<?php echo get_the_post_thumbnail($product_id, 'medium', ['class' => 'book-cover-img']); ?>
			<?php else : ?>
				<span class="cover-title"><?php echo esc_html($product->get_name()); ?></span>
			<?php endif; ?>
		</a>
		<?php if ($product->is_on_sale()) : ?>
			<span class="product-sale-badge"><?php esc_html_e('Sale', 'woocommerce'); ?></span>
		<?php endif; ?>
		<div class="book-cover-overlay">
			<a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
			   class="overlay-btn overlay-btn--cart add_to_cart_button ajax_add_to_cart"
			   data-quantity="1"
			   data-product_id="<?php echo esc_attr($product_id); ?>"
			   data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
			   aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
				<?php esc_html_e('Add to cart', 'woocommerce'); ?>
			</a>
			<a href="<?php the_permalink(); ?>" class="overlay-btn overlay-btn--view">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
				<?php esc_html_e('Quick View', 'mytheme'); ?>
			</a>
		</div>
	</div>
	<div class="book-info">
		<h3><a href="<?php the_permalink(); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
		<div class="author">
			<?php if ($cats = wc_get_product_category_list($product_id)) : ?>
				<span class="book-tax"><?php esc_html_e('Categories:', 'mytheme'); ?> <?php echo $cats; ?></span>
			<?php endif; ?>
			<?php if ($tags = wc_get_product_tag_list($product_id)) : ?>
				<span class="book-tax"><?php esc_html_e('Tags:', 'mytheme'); ?> <?php echo $tags; ?></span>
			<?php endif; ?>
		</div>
		<div class="price-row">
			<span class="price"><?php echo $product->get_price_html(); ?></span>
			<?php if ($product->get_rating_count() > 0) : $avg = round($product->get_average_rating()); ?>
				<div class="rating product-card-rating">
					<span class="rating-stars">
						<?php for ($i = 1; $i <= 5; $i++) : ?>
							<svg viewBox="0 0 24 24" width="12" height="12" fill="<?php echo $i <= $avg ? '#f0883e' : '#C1C7DF'; ?>"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
						<?php endfor; ?>
					</span>
					<span>(<?php echo esc_html($product->get_rating_count()); ?>)</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php do_action('woocommerce_after_shop_loop_item'); ?>
</li>
