<?php
/**
 * Template Name: Full Width
 */

get_header();
?>

<section class="page-section" data-section="Page">
	<?php mytheme_page_banner(); ?>
	<div class="container-full">
		<?php
		while (have_posts()) :
			the_post();
		?>
			<div class="page-content">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>
