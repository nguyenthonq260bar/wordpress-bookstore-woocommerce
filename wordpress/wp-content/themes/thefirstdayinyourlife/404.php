<?php get_header(); ?>

<section class="page-section" data-section="404">
	<?php mytheme_page_banner(); ?>
	<div class="container">
    <div class="content-card content-card--narrow">
      <p class="error-404-message"><?php echo esc_html(_t("It might have been moved or deleted. Try searching or head back to the homepage.", "Trang này có thể đã bị di chuyển hoặc xóa. Hãy thử tìm kiếm hoặc quay về trang chủ.")); ?></p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php echo esc_html(_t('Back to Home', 'Về Trang Chủ')); ?></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
