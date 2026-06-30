<?php get_header(); ?>

<section class="page-section" data-section="Article">
	<?php mytheme_page_banner(); ?>
	<div class="container">
		<div class="blog-layout blog-layout--single">
			<div class="blog-main">
				<?php
				while (have_posts()) :
					the_post();
				?>

					<?php if (has_post_thumbnail()) : ?>
						<div class="content-card content-card--media">
							<?php the_post_thumbnail('large'); ?>
						</div>
					<?php endif; ?>

					<div class="content-card">
						<?php the_content(); ?>
					</div>
				<?php endwhile; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
