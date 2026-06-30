<?php get_header(); ?>

<section class="page-section" data-section="Search">
	<?php mytheme_page_banner(); ?>
	<div class="container">

		<?php if (have_posts()) : ?>
			<div class="articles-grid">
				<?php
				$article_index = 0;
				while (have_posts()) :
					the_post();
					$is_featured = ($article_index === 0);
				?>
					<article class="article-card <?php echo $is_featured ? 'article-card--featured' : ''; ?>">
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
					</article>
				<?php
					$article_index++;
				endwhile;
				?>
			</div>

			<div class="pagination">
				<?php
				the_posts_pagination([
					'mid_size'  => 2,
					'prev_text' => __('&laquo; Previous', 'mytheme'),
					'next_text' => __('Next &raquo;', 'mytheme'),
				]);
				?>
			</div>

		<?php else : ?>
			<div class="content-card">
				<p><?php esc_html_e('No results found for your search. Please try different keywords.', 'mytheme'); ?></p>
				<?php get_search_form(); ?>
				<div class="search-back-home">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php esc_html_e('Back to Home', 'mytheme'); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
