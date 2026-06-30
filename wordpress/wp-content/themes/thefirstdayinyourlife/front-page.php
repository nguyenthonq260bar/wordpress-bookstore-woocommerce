<?php get_header(); ?>

<!-- ============================================
     HERO — Split Layout
============================================ -->
<?php
$hero_heading   = get_theme_mod('hero_heading', 'Discover Stories<br />That <span>Inspire</span>');
$hero_subtitle  = get_theme_mod('hero_subtitle', 'Your next great read is waiting. Explore our curated collection of bestselling books, timeless classics, and hidden gems.');
$hero_btn1_text = get_theme_mod('hero_btn1_text', 'Explore Collection');
$hero_btn1_url  = get_theme_mod('hero_btn1_url', '');
$hero_btn2_text = get_theme_mod('hero_btn2_text', 'Browse Books');
$hero_btn2_url  = get_theme_mod('hero_btn2_url', '#featured-products');
$hero_image_id    = get_theme_mod('hero_banner_image', 0);
$hero_bg_id       = get_theme_mod('hero_background_image', 0);
$hero_heading_color = get_theme_mod('hero_heading_color', '');
$hero_subtitle_color = get_theme_mod('hero_subtitle_color', '');

if (empty($hero_btn1_url)) {
	$hero_btn1_url = get_permalink(wc_get_page_id('shop'));
}
if (empty($hero_btn2_url)) {
	$hero_btn2_url = '#featured-products';
}

$hero_has_bg = false;
$hero_attrs = 'class="hero reveal" data-section="1. Hero"';
if ($hero_bg_id) {
	$bg_url = wp_get_attachment_image_url($hero_bg_id, 'full');
	if ($bg_url) {
		$hero_has_bg = true;
		$hero_attrs = 'class="hero reveal hero-has-bg" data-section="1. Hero" style="background: url(' . esc_url($bg_url) . ') center/cover no-repeat;"';
	}
}

$heading_style = $hero_has_bg && $hero_heading_color ? ' style="color:' . esc_attr($hero_heading_color) . ';"' : '';
$subtitle_style = $hero_has_bg && $hero_subtitle_color ? ' style="color:' . esc_attr($hero_subtitle_color) . ';"' : '';
?>
<section <?php echo $hero_attrs; ?>>
	<div class="container">
		<div class="hero-content">
			<h1<?php echo $heading_style; ?>><?php echo wp_kses_post($hero_heading); ?></h1>
			<p<?php echo $subtitle_style; ?>><?php echo esc_html($hero_subtitle); ?></p>
			<div class="hero-actions">
				<a href="<?php echo esc_url($hero_btn1_url); ?>" class="btn btn-cta">
					<?php echo esc_html($hero_btn1_text); ?>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</a>
				<a href="<?php echo esc_url($hero_btn2_url); ?>" class="btn btn-secondary"><?php echo esc_html($hero_btn2_text); ?></a>
			</div>
		</div>
		<div class="hero-visual">
			<?php if ($hero_image_id) :
				$image_url = wp_get_attachment_image_url($hero_image_id, 'full');
			?>
				<img src="<?php echo esc_url($image_url); ?>" alt="" class="hero-banner-image" />
			<?php else : ?>
				<svg viewBox="0 0 480 400" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="340" cy="120" r="140" fill="var(--glacier-blue)" opacity="0.15"/>
					<circle cx="160" cy="280" r="100" fill="var(--deep-lake)" opacity="0.1"/>
					<circle cx="360" cy="300" r="60" fill="var(--accent)" opacity="0.12"/>
					<rect x="180" y="100" width="120" height="160" rx="12" fill="var(--glacier-blue)" opacity="0.2"/>
					<rect x="200" y="130" width="80" height="6" rx="3" fill="var(--white)" opacity="0.5"/>
					<rect x="200" y="146" width="60" height="4" rx="2" fill="var(--white)" opacity="0.3"/>
					<rect x="200" y="158" width="70" height="4" rx="2" fill="var(--white)" opacity="0.3"/>
					<rect x="200" y="170" width="40" height="4" rx="2" fill="var(--white)" opacity="0.3"/>
					<circle cx="240" cy="230" r="18" fill="var(--accent)" opacity="0.25"/>
					<path d="M235 230h10M240 225v10" stroke="var(--accent)" stroke-width="2.5" stroke-linecap="round" opacity="0.4"/>
					<circle cx="120" cy="140" r="24" fill="var(--accent)" opacity="0.08"/>
					<circle cx="380" cy="160" r="12" fill="var(--deep-lake)" opacity="0.15"/>
					<circle cx="100" cy="310" r="16" fill="var(--glacier-blue)" opacity="0.12"/>
				</svg>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ============================================
     FEATURED CATEGORIES
============================================ -->
<section class="categories" data-section="2. Featured Categories">
	<div class="container">

		<?php
		$product_cats = get_terms([
			'taxonomy'   => 'product_cat',
			'number'     => 12,
		]);
		if (!empty($product_cats) && !is_wp_error($product_cats)) :
			shuffle($product_cats);
			$cat_gradients = ['cat-fiction', 'cat-nonfiction', 'cat-science', 'cat-history', 'cat-children', 'cat-art'];
			$cat_emojis = ['📖', '📚', '🔬', '📜', '🧸', '🎨'];
			$i = 0;
		?>
		<div class="categories-outline-row">
			<?php foreach ($product_cats as $cat) :
				$class = $cat_gradients[$i % count($cat_gradients)];
				$emoji = $cat_emojis[$i % count($cat_emojis)];
				$i++;
			?>
				<a href="<?php echo esc_url(get_term_link($cat)); ?>" class="category-pill <?php echo esc_attr($class); ?>">
					<span class="cat-emoji" aria-hidden="true"><?php echo $emoji; ?></span>
					<span class="cat-name"><?php echo esc_html($cat->name); ?></span>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<!-- ============================================
     FEATURED PRODUCTS
============================================ -->
<section class="featured-products" id="featured-products" data-section="3. Featured Products">
	<div class="container">
		<div class="section-header">
			<div>
				<h2><?php echo esc_html(_t('Featured Books', 'Sách Nổi Bật')); ?></h2>
				<p><?php echo esc_html(_t('Our top picks for this month', 'Lựa chọn hàng đầu tháng này')); ?></p>
			</div>
			<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="view-all"><?php echo esc_html(_t('View All', 'Xem Tất Cả')); ?>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
		<div class="products-grid">
			<?php
			$featured_products = wc_get_products([
				'limit'  => 8,
				'status' => ['publish'],
				'orderby' => 'popularity',
				'order'   => 'DESC',
			]);
			$prod_delays = ['reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3', 'reveal-delay-4'];
			$pi = 0;
			foreach ($featured_products as $product) :
				$product_id = $product->get_id();
				$prod_reveal = $prod_delays[$pi % count($prod_delays)];
				$pi++;
			?>
				<div class="product-card reveal <?php echo esc_attr($prod_reveal); ?>">
					<div class="product-card-image">
						<a href="<?php echo esc_url(get_permalink($product_id)); ?>">
							<?php if (has_post_thumbnail($product_id)) : ?>
								<?php echo get_the_post_thumbnail($product_id, 'medium'); ?>
							<?php else : ?>
								<img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" />
							<?php endif; ?>
						</a>
						<?php if ($product->is_on_sale()) : ?>
								<span class="sale-badge-accent"><?php echo esc_html(_t('Sale', 'Giảm giá')); ?></span>
						<?php endif; ?>
					</div>
					<div class="product-card-body">
						<h3><a href="<?php echo esc_url(get_permalink($product_id)); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
						<span class="price"><?php echo $product->get_price_html(); ?></span>
						<a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="add-to-cart-btn add_to_cart_button ajax_add_to_cart" data-quantity="1" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
							<?php esc_html_e('Add to cart', 'woocommerce'); ?>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============================================
     VALUE PROPOSITIONS
============================================ -->
<?php
$prop_defaults = [
	1 => ['title' => 'Curated Selection', 'desc' => 'Hand-picked titles from independent publishers and bestselling authors worldwide.', 'icon' => 'book'],
	2 => ['title' => 'Fast Delivery', 'desc' => 'Free shipping on orders over $30. Delivered to your doorstep within 3&ndash;5 business days.', 'icon' => 'truck'],
	3 => ['title' => 'Secure Payment', 'desc' => '256-bit SSL encryption. Pay with credit card, PayPal, or Apple Pay with confidence.', 'icon' => 'lock'],
];
$prop_delays = ['reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];
$props = [];
for ($i = 1; $i <= 3; $i++) {
	if (!get_theme_mod("prop_{$i}_show", true)) continue;
	$d = $prop_defaults[$i];
	$props[] = [
		'title' => get_theme_mod("prop_{$i}_title", $d['title']),
		'desc'  => get_theme_mod("prop_{$i}_desc", $d['desc']),
		'icon'  => get_theme_mod("prop_{$i}_icon", $d['icon']),
	];
}
if (!empty($props) || is_customize_preview()) :
?>
<section class="value-props" data-section="4. Value Propositions">
	<div class="container">
		<div class="props-grid" style="<?php echo count($props) === 1 ? 'grid-template-columns:1fr;max-width:400px;margin:0 auto;' : (count($props) === 2 ? 'grid-template-columns:1fr 1fr;max-width:700px;margin:0 auto;' : ''); ?>">
			<?php $pi = 0; foreach ($props as $prop) : $delay = $prop_delays[$pi % 3]; $pi++; ?>
			<div class="prop-card reveal <?php echo esc_attr($delay); ?>">
				<div class="prop-icon"><?php echo mytheme_prop_icon($prop['icon']); ?></div>
				<h3><?php echo esc_html($prop['title']); ?></h3>
				<p><?php echo esc_html($prop['desc']); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ============================================
     TESTIMONIALS
============================================ -->
<?php
$testimonial_query = new WP_Query([
	'posts_per_page' => 3,
	'category_name'  => 'testimonial',
	'ignore_sticky_posts' => true,
]);
if ($testimonial_query->have_posts()) :
?>
<section class="testimonials" data-section="5. Testimonials">
	<div class="container">
		<div class="section-header">
			<div>
				<h2><?php echo esc_html(_t('What Our Readers Say', 'Độc Giả Nói Gì')); ?></h2>
				<p><?php echo esc_html(_t('Join thousands of happy readers', 'Tham gia cùng hàng ngàn độc giả')); ?></p>
			</div>
		</div>
		<div class="testimonials-grid">
			<?php
			$ti = 0;
			$testi_delays = ['reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];
			while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
				$testi_reveal = $testi_delays[$ti % count($testi_delays)];
				$ti++;
				$testi_role = get_post_meta(get_the_ID(), '_testimonial_role', true) ?: _t('Verified Reader', 'Độc Giả Đã Xác Minh');
				$avatar_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
			?>
				<div class="testimonial-card reveal <?php echo esc_attr($testi_reveal); ?>">
					<div class="testimonial-stars">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
					</div>
					<p class="testimonial-text"><?php echo esc_html(get_the_excerpt() ?: get_the_content()); ?></p>
					<div class="testimonial-author">
						<?php if ($avatar_url) : ?>
							<div class="testimonial-avatar testimonial-avatar--image"><img src="<?php echo esc_url($avatar_url); ?>" alt="" /></div>
						<?php else : ?>
							<div class="testimonial-avatar" style="background: <?php echo mytheme_product_color(get_the_ID()); ?>"></div>
						<?php endif; ?>
						<div>
							<div class="testimonial-name"><?php the_title(); ?></div>
							<div class="testimonial-role"><?php echo esc_html($testi_role); ?></div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ============================================
     FINAL CTA
============================================ -->
<section class="cta-section reveal" data-section="6. Final CTA">
	<div class="container">
		<h2><?php echo esc_html(_t('Ready to Find Your Next Great Read?', 'Sẵn Sàng Tìm Cuốn Sách Tiếp Theo?')); ?></h2>
		<p><?php echo esc_html(_t('Join thousands of readers who trust us for their literary journey. Start exploring our collection today.', 'Tham gia cùng hàng ngàn độc giả tin tưởng chúng tôi cho hành trình văn học. Bắt đầu khám phá bộ sưu tập ngay hôm nay.')); ?></p>
		<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-cta">
			<?php echo esc_html(_t('Start Shopping', 'Bắt Đầu Mua Sắm')); ?>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
		</a>
	</div>
</section>

<?php get_footer(); ?>
