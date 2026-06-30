<?php get_header(); ?>

<section class="page-section" data-section="Page">
	<?php mytheme_page_banner(); ?>
	<div class="container container--wide">
		<?php
		while (have_posts()) :
			the_post();
		?>
			<div class="content-card <?php if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) echo 'content-card--full'; ?>">
				<?php if (function_exists('is_cart') && is_cart()) : ?>
					<?php echo do_shortcode('[woocommerce_cart]'); ?>
				<?php elseif (function_exists('is_checkout') && is_checkout()) : ?>
					<?php echo do_shortcode('[woocommerce_checkout]'); ?>
				<?php else : ?>
					<div class="page-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>
