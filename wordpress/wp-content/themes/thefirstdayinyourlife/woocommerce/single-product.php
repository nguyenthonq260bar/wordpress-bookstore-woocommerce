<?php
defined('ABSPATH') || exit;

get_header('shop');

echo '<section class="page-section" data-section="Product">';
echo '<div class="container">';

do_action('woocommerce_before_main_content');

while (have_posts()) :
	the_post();

	global $product;
	if (!is_a($product, WC_Product::class)) {
		continue;
	}

	do_action('woocommerce_before_single_product');
?>
	<div class="product-detail">
		<div class="product-gallery">
			<?php
			$main_image_id = get_post_thumbnail_id();
			$attachment_ids = $product->get_gallery_image_ids();
			$all_image_ids = array_filter(array_merge(
				$main_image_id ? [$main_image_id] : [],
				$attachment_ids ?: []
			));
			?>
			<?php if ($all_image_ids) : ?>
			<div class="gallery-thumb-rail">
				<?php $i = 0; foreach ($all_image_ids as $img_id) : ?>
				<a href="<?php echo esc_url(wp_get_attachment_url($img_id)); ?>"
				   class="thumb-rail-item<?php echo $i === 0 ? ' active' : ''; ?>"
				   data-image="<?php echo esc_url(wp_get_attachment_url($img_id)); ?>">
					<?php echo wp_get_attachment_image($img_id, 'thumbnail'); ?>
				</a>
				<?php $i++; endforeach; ?>
			</div>
			<?php endif; ?>
			<div class="gallery-main">
				<div class="gallery-main-container" id="productImageMain">
					<?php if ($main_image_id) : ?>
						<?php the_post_thumbnail('large', ['loading' => 'lazy', 'id' => 'galleryMainImage']); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="product-summary">
			<?php
			$cat_list = wc_get_product_category_list($product->get_id());
			if ($cat_list) :
			?>
			<div class="product-category-badge"><?php echo $cat_list; ?></div>
			<?php endif; ?>

			<h1 class="product-title"><?php the_title(); ?></h1>

			<?php if ($product->get_rating_count() > 0) : $avg = round($product->get_average_rating()); ?>
			<div class="product-rating">
				<span class="product-rating-stars">
					<?php for ($i = 1; $i <= 5; $i++) : ?>
						<svg viewBox="0 0 24 24" width="16" height="16" fill="<?php echo $i <= $avg ? '#f0883e' : '#C1C7DF'; ?>"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
					<?php endfor; ?>
				</span>
				<a href="#product-reviews" class="product-rating-link"><?php echo esc_html($product->get_rating_count()); ?> <?php echo _n('review', 'reviews', $product->get_rating_count(), 'woocommerce'); ?></a>
			</div>
			<?php endif; ?>

			<div class="product-price-block">
				<?php if ($product->is_on_sale()) :
					$regular = (float) $product->get_regular_price();
					$sale = (float) $product->get_sale_price();
					$discount = $regular > 0 ? round((($regular - $sale) / $regular) * 100) : 0;
				?>
					<span class="price-discounted"><?php echo wc_price($sale); ?></span>
					<span class="price-original"><?php echo wc_price($regular); ?></span>
					<span class="discount-badge">-<?php echo $discount; ?>%</span>
				<?php else : ?>
					<span class="price-regular"><?php echo $product->get_price_html(); ?></span>
				<?php endif; ?>
			</div>

			<div class="product-description">
				<?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?>
			</div>

			<div class="product-add-to-cart">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>

			<div class="product-metadata">
				<?php
				$categories = wc_get_product_category_list($product->get_id());
				$tags = wc_get_product_tag_list($product->get_id());
				?>
				<?php if ($categories) : ?>
				<div class="metadata-item">
					<span class="metadata-label"><?php esc_html_e('Categories', 'woocommerce'); ?></span>
					<span class="metadata-value"><?php echo $categories; ?></span>
				</div>
				<?php endif; ?>
				<?php if ($tags) : ?>
				<div class="metadata-item">
					<span class="metadata-label"><?php esc_html_e('Tags', 'woocommerce'); ?></span>
					<span class="metadata-value"><?php echo $tags; ?></span>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
	$has_description = !empty(get_the_content());
	$show_dims = apply_filters('wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions());
	$visible_attrs = array_filter($product->get_attributes(), 'wc_attributes_array_filter_visible');
	$has_details = ($show_dims || $visible_attrs);
	$show_tabs = ($has_description && $has_details);
	?>

	<div class="product-tabs<?php echo $show_tabs ? '' : ' product-tabs--simple'; ?>">
		<?php if ($show_tabs) : ?>
		<nav class="product-tabs-nav">
			<button class="product-tab-btn active" data-tab="description"><?php echo esc_html(_t('Description', 'Mô Tả')); ?></button>
			<button class="product-tab-btn" data-tab="details"><?php echo esc_html(_t('Product Details', 'Chi Tiết Sản Phẩm')); ?></button>
		</nav>
		<?php endif; ?>

		<?php if ($has_description) : ?>
		<div class="product-tab-panel<?php echo $show_tabs ? ' active' : ''; ?>" id="tab-description">
			<?php the_content(); ?>
		</div>
		<?php endif; ?>

		<?php if ($has_details) : ?>
		<div class="product-tab-panel<?php echo $show_tabs ? '' : ' active'; ?>" id="tab-details">
			<table class="product-attributes-table">
				<?php
				$has_weight_or_dims = false;
				if ($show_dims && $product->has_weight()) :
					$has_weight_or_dims = true;
				?>
				<tr>
					<th><?php echo esc_html(_t('Weight', 'Cân Nặng')); ?></th>
					<td><?php echo wc_format_weight($product->get_weight()); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($show_dims && $product->has_dimensions()) :
					$has_weight_or_dims = true;
				?>
				<tr>
					<th><?php echo esc_html(_t('Dimensions', 'Kích Thước')); ?></th>
					<td><?php echo wc_format_dimensions($product->get_dimensions(false)); ?></td>
				</tr>
				<?php endif; ?>
				<?php
				if ($has_weight_or_dims && $visible_attrs) :
				?>
				<tr class="product-attributes-separator"><td colspan="2"></td></tr>
				<?php endif; ?>
				<?php
				foreach ($visible_attrs as $attr) :
					$values = array();
					if ($attr->is_taxonomy()) {
						$terms = wc_get_product_terms($product->get_id(), $attr->get_name(), ['fields' => 'names']);
						$values = $terms;
					} else {
						$values = $attr->get_options();
					}
					if (empty($values)) continue;
				?>
				<tr>
					<th><?php echo wc_attribute_label($attr->get_name()); ?></th>
					<td><?php echo wp_kses_post(implode(', ', $values)); ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php endif; ?>
	</div>

	<div class="product-reviews" id="product-reviews">
		<?php
		if (wc_reviews_enabled()) {
			comments_template('/woocommerce/single-product-reviews.php');
		}
		?>
	</div>

	<?php
	$related = wc_get_related_products($product->get_id(), 4);
	if ($related) :
	?>
		<div class="related-products">
			<h2><?php echo esc_html(_t('Related Products', 'Sản Phẩm Liên Quan')); ?></h2>
			<div class="related-products-grid">
				<?php foreach ($related as $related_id) :
					$related_product = wc_get_product($related_id);
					if (!$related_product) continue;
				?>
					<div class="related-product-card">
						<a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="related-card-image" style="--cover-bg: <?php echo mytheme_product_color($related_id); ?>;">
							<?php if (has_post_thumbnail($related_id)) : ?>
								<?php echo get_the_post_thumbnail($related_id, 'medium', ['class' => 'related-card-img']); ?>
							<?php else : ?>
								<span class="cover-title"><?php echo esc_html($related_product->get_name()); ?></span>
							<?php endif; ?>
							<span class="related-card-add-cart add_to_cart_button ajax_add_to_cart"
								  data-product_id="<?php echo esc_attr($related_id); ?>"
								  data-quantity="1"
								  data-product_sku="<?php echo esc_attr($related_product->get_sku()); ?>"
								  aria-label="<?php echo esc_attr($related_product->add_to_cart_description()); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
							</span>
						</a>
						<div class="related-card-info">
							<h4><a href="<?php echo esc_url(get_permalink($related_id)); ?>"><?php echo esc_html($related_product->get_name()); ?></a></h4>
							<span class="price"><?php echo $related_product->get_price_html(); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

<?php
	do_action('woocommerce_after_single_product');
endwhile;

do_action('woocommerce_after_main_content');
?>

</div> <!-- container -->
</section> <!-- page-section -->

<?php get_footer('shop'); ?>
