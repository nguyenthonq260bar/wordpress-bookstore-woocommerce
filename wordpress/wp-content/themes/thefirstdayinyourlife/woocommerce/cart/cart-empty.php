<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="cart-empty-message">
	<div class="cart-empty-icon">
		<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
			<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
			<line x1="3" y1="6" x2="21" y2="6"/>
			<path d="M16 10a4 4 0 0 1-8 0"/>
		</svg>
	</div>

	<h2><?php esc_html_e('Your cart is empty', 'woocommerce'); ?></h2>
	<p><?php esc_html_e('Looks like you haven\'t added any books yet. Start exploring our collection!', 'woocommerce'); ?></p>

	<?php if (wc_get_page_id('shop') > 0) : ?>
		<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-primary">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
			<?php esc_html_e('Browse Books', 'woocommerce'); ?>
		</a>
	<?php endif; ?>

	<?php do_action('woocommerce_after_cart'); ?>
</div>
