<?php
/**
 * Template Name: Submit Testimonial
 */

get_header();
?>

<div class="page-section">
    <div class="container" style="margin:48px auto;">
        <h1 style="font-size:28px;margin-bottom:8px;text-align:center;">
            <?php esc_html_e('Submit Your Testimonial', 'mytheme'); ?>
        </h1>
        <p style="color:var(--color-body,#6172B0);margin-bottom:32px;text-align:center;">
            <?php esc_html_e('Share your experience with our community.', 'mytheme'); ?>
        </p>

        <?php echo do_shortcode('[submit_testimonial_form]'); ?>
    </div>
</div>

<?php get_footer(); ?>
