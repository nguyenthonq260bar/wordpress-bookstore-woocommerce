<?php get_header(); ?>

<!-- ============================================
     HERO
============================================ -->
<section class="hero" data-section="Hero">
	<div class="container">
		<div class="hero-content">
			<div class="hero-badge">
				<span></span>
				<?php echo esc_html(_t('New Arrivals Weekly', 'Sách Mới Mỗi Tuần')); ?>
			</div>
			<h1><?php echo wp_kses_post(_t('Discover Stories<br />That <span>Inspire</span>', 'Khám Phá Những<br />Câu Chuyện <span>Truyền Cảm Hứng</span>')); ?></h1>
			<p>
				<?php echo esc_html(_t('Your next great read is waiting. Explore our curated collection of bestselling books, timeless classics, and hidden gems.', 'Cuốn sách tuyệt vời tiếp theo đang chờ bạn. Khám phá bộ sưu tập sách bán chạy, kinh điển và những viên ngọc ẩn.')); ?>
			</p>
			<div class="hero-search">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
					<svg class="hero-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
					<input type="search" name="s" placeholder="<?php echo esc_attr(_t('Search by title, author, or genre...', 'Tìm theo tên sách, tác giả, hoặc thể loại...')); ?>" value="<?php echo get_search_query(); ?>" />
					<input type="hidden" name="post_type" value="product" />
					<button type="submit"><?php echo esc_html(_t('Search', 'Tìm Kiếm')); ?></button>
				</form>
			</div>
			<div class="hero-actions">
				<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">
					<?php echo esc_html(_t('Explore Collection', 'Khám Phá Bộ Sưu Tập')); ?>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 12h14" />
						<path d="m12 5 7 7-7 7" />
					</svg>
				</a>
				<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-secondary">
					<?php echo esc_html(_t('Browse Categories', 'Duyệt Danh Mục')); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<!-- ============================================
     RECOMMENDED BOOKS — Horizontal Carousel
============================================ -->
<?php
$recommended_products = wc_get_products([
	'limit'   => 10,
	'status'  => ['publish'],
	'orderby' => 'popularity',
	'order'   => 'DESC',
]);
if (!empty($recommended_products)) :
?>
<section class="recommended" data-section="Recommended Books">
	<div class="container">
		<div class="recommended-header">
			<div>
				<h2><?php echo esc_html(_t('Recommended For You', 'Đề Xuất Cho Bạn')); ?></h2>
				<p><?php echo esc_html(_t("Hand-picked titles you'll love", 'Những tựa sách được chọn lọc dành cho bạn')); ?></p>
			</div>
			<div class="recommended-nav">
				<button class="scroll-btn scroll-btn--prev" aria-label="<?php echo esc_attr(_t('Previous', 'Trước')); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
				</button>
				<button class="scroll-btn scroll-btn--next" aria-label="<?php echo esc_attr(_t('Next', 'Sau')); ?>">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
				</button>
			</div>
		</div>
		<div class="recommended-track" id="recommendedTrack">
			<?php foreach ($recommended_products as $product) :
				$product_id   = $product->get_id();
				$rating_count = $product->get_rating_count();
			?>
				<div class="recommended-card">
					<div class="book-cover" style="--cover-bg: <?php echo mytheme_product_color($product_id); ?>;">
						<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="book-cover-link">
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
							<a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="overlay-btn overlay-btn--view">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
								<?php esc_html_e('Quick View', 'mytheme'); ?>
							</a>
						</div>
					</div>
					<div class="book-info">
						<h3><a href="<?php echo esc_url(get_permalink($product_id)); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
						<span class="author"><?php echo wc_get_product_category_list($product_id); ?></span>
						<div class="price-row">
							<span class="price"><?php echo $product->get_price_html(); ?></span>
							<?php if ($rating_count > 0) : ?>
								<div class="rating">
									<?php echo wc_get_rating_html($product->get_average_rating()); ?>
									<span>(<?php echo esc_html($rating_count); ?>)</span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="recommended-dots" id="recommendedDots"></div>
	</div>
</section>
<?php endif; ?>

<!-- ============================================
     VALUE PROPOSITIONS
============================================ -->
<section class="value-props" data-section="Value Propositions">
	<div class="container">
		<div class="props-grid">
			<div class="prop-card">
				<div class="prop-icon">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
				</div>
				<h3><?php echo esc_html(_t('Curated Selection', 'Tuyển Chọn Kỹ Lưỡng')); ?></h3>
				<p><?php echo esc_html(_t('Hand-picked titles from independent publishers and bestselling authors worldwide.', 'Những tựa sách được chọn lọc từ các nhà xuất bản độc lập và tác giả bán chạy toàn cầu.')); ?></p>
			</div>
			<div class="prop-card">
				<div class="prop-icon">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
				</div>
				<h3><?php echo esc_html(_t('Fast Delivery', 'Giao Hàng Nhanh')); ?></h3>
				<p><?php echo esc_html(_t('Free shipping on orders over $30. Delivered to your doorstep within 3-5 business days.', 'Miễn phí giao hàng cho đơn trên $30. Giao tận nơi trong 3-5 ngày làm việc.')); ?></p>
			</div>
			<div class="prop-card">
				<div class="prop-icon">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
				</div>
				<h3><?php echo esc_html(_t('Secure Payment', 'Thanh Toán An Toàn')); ?></h3>
				<p><?php echo esc_html(_t('256-bit SSL encryption. Pay with credit card, PayPal, or Apple Pay with confidence.', 'Mã hóa SSL 256-bit. Thanh toán bằng thẻ tín dụng, PayPal, hoặc Apple Pay an toàn.')); ?></p>
			</div>
		</div>
	</div>
</section>

<!-- ============================================
     CATEGORIES
============================================ -->
<section class="categories" data-section="Book Categories">
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
     LATEST ARTICLES
============================================ -->
<section class="articles" data-section="Latest Articles">
	<div class="container">
		<div class="section-header">
			<div>
				<h2><?php echo esc_html(_t('Latest Articles', 'Bài Viết Mới Nhất')); ?></h2>
				<p><?php echo esc_html(_t('Thoughts on reading, writing & culture', 'Suy nghĩ về đọc, viết & văn hóa')); ?></p>
			</div>
			<a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="view-all"><?php echo esc_html(_t('All Articles', 'Tất Cả Bài Viết')); ?>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
		<div class="articles-grid">
			<?php
			$recent_posts = new WP_Query([
				'posts_per_page' => 3,
				'ignore_sticky_posts' => true,
			]);
			if ($recent_posts->have_posts()) :
				$article_index = 0;
				while ($recent_posts->have_posts()) : $recent_posts->the_post();
					$is_featured = ($article_index === 0);
			?>
					<div class="article-card <?php echo $is_featured ? 'article-card--featured' : ''; ?>">
						<?php if ($is_featured) : ?>
							<div class="article-image">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('medium_large'); ?>
								<?php else : ?>
									<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
										<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
										<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
										<line x1="8" y1="7" x2="16" y2="7" />
										<line x1="8" y1="11" x2="14" y2="11" />
									</svg>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="article-body">
							<div class="article-meta">
								<?php
								$categories = get_the_category();
								if (!empty($categories)) :
								?>
									<span class="tag"><?php echo esc_html($categories[0]->name); ?></span>
								<?php endif; ?>
								<span><?php echo get_the_date('F j, Y'); ?></span>
							</div>
							<h3><?php the_title(); ?></h3>
							<p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
							<a href="<?php the_permalink(); ?>" class="article-link"><?php echo esc_html(_t('Read More', 'Đọc Thêm')); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							</a>
						</div>
					</div>
				<?php
					$article_index++;
				endwhile;
				wp_reset_postdata();
			else :
				$fallback_articles = [
					['tag' => _t('Reading', 'Đọc Sách'), 'date' => 'March 12, 2026', 'title' => _t('The Art of Slow Reading', 'Nghệ Thuật Đọc Chậm'), 'excerpt' => _t('Discover why slowing down and savoring every page can transform your relationship with books.', 'Khám phá lý do tại sao đọc chậm và thưởng thức từng trang sách có thể thay đổi mối quan hệ của bạn với sách.')],
					['tag' => _t('Lifestyle', 'Phong Cách Sống'), 'date' => 'March 8, 2026', 'title' => _t('Building a Home Library', 'Xây Dựng Thư Viện Tại Nhà'), 'excerpt' => _t('Tips for curating a personal library that reflects your taste and brings joy to your daily life.', 'Mẹo để xây dựng thư viện cá nhân phản ánh gu thẩm mỹ và mang lại niềm vui cho cuộc sống hàng ngày.')],
					['tag' => _t('Culture', 'Văn Hóa'), 'date' => 'March 1, 2026', 'title' => _t('Books That Changed the Way We Think', 'Những Cuốn Sách Thay Đổi Cách Chúng Ta Suy Nghĩ'), 'excerpt' => _t('A look at the most influential books that have shaped modern thought and culture.', 'Cái nhìn về những cuốn sách có ảnh hưởng nhất đã định hình tư tưởng và văn hóa hiện đại.')],
				];
				foreach ($fallback_articles as $i => $article) :
					$is_featured = ($i === 0);
				?>
					<div class="article-card <?php echo $is_featured ? 'article-card--featured' : ''; ?>">
						<?php if ($is_featured) : ?>
							<div class="article-image">
								<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
									<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
									<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
									<line x1="8" y1="7" x2="16" y2="7" />
									<line x1="8" y1="11" x2="14" y2="11" />
								</svg>
							</div>
						<?php endif; ?>
						<div class="article-body">
							<div class="article-meta">
								<span class="tag"><?php echo esc_html($article['tag']); ?></span>
								<span><?php echo esc_html($article['date']); ?></span>
							</div>
							<h3><?php echo esc_html($article['title']); ?></h3>
							<p><?php echo esc_html($article['excerpt']); ?></p>
							<a href="#" class="article-link"><?php echo esc_html(_t('Read More', 'Đọc Thêm')); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
