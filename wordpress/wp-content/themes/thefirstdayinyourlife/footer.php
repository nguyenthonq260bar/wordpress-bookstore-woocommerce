<!-- ============================================
     FOOTER
============================================ -->
<?php if (!is_singular() || !get_post_meta(get_the_ID(), '_hide_footer', true)) : ?>
<footer data-section="Footer">
	<div class="footer-inner">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-brand">
					<a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
						<?php
						$custom_logo_id = get_theme_mod('custom_logo');
						if ($custom_logo_id) :
							echo wp_get_attachment_image($custom_logo_id, 'thumbnail', false, ['class' => 'logo-image']);
						else :
						?>
							<span class="logo-icon">P</span>
						<?php endif; ?>
						<?php bloginfo('name'); ?>
					</a>
					<p><?php echo _t('Your destination for curated books and literary inspiration. Discover stories that inspire, inform, and transform.', 'Điểm đến dành cho những cuốn sách được tuyển chọn và nguồn cảm hứng văn học. Khám phá những câu chuyện truyền cảm hứng, thông tin và thay đổi.'); ?></p>
				</div>

				<?php
				$footer_sidebars = ['footer-1', 'footer-2', 'footer-3', 'footer-4'];
				foreach ($footer_sidebars as $sidebar) {
					if (is_active_sidebar($sidebar)) {
						dynamic_sidebar($sidebar);
					}
				}
				?>
			</div>

			<div class="footer-bottom">
				<span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php echo _t('All rights reserved.', 'Tất cả quyền được bảo lưu.'); ?></span>
				<?php if (has_nav_menu('footer-bottom')) :
					wp_nav_menu([
						'theme_location'  => 'footer-bottom',
						'container'       => false,
						'menu_class'      => 'footer-bottom-links',
						'depth'           => 1,
						'fallback_cb'     => false,
					]);
				else : ?>
				<div class="footer-bottom-links">
					<a href="#"><?php echo _t('Terms of Service', 'Điều khoản dịch vụ'); ?></a>
					<a href="#"><?php echo _t('Privacy Policy', 'Chính sách bảo mật'); ?></a>
					<a href="#"><?php echo _t('Cookie Policy', 'Chính sách Cookie'); ?></a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>
	<button id="back-to-top" aria-label="<?php echo esc_attr(_t('Back to top', 'Lên đầu trang')); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
			<path d="m18 15-6-6-6 6" />
		</svg>
	</button>
<?php endif; ?>

<?php wp_footer(); ?>
</body>

</html>
