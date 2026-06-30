<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}
?>

<div class="checkout-frame">
	<?php woocommerce_output_all_notices(); ?>

	<form name="checkout" method="post" class="checkout checkout-layout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

		<div class="checkout-fields">
			<?php if ($checkout->get_checkout_fields()) : ?>

				<?php do_action('woocommerce_checkout_before_customer_details'); ?>

				<div class="checkout-section">
					<?php do_action('woocommerce_checkout_billing'); ?>
				</div>

				<div class="checkout-section">
					<?php do_action('woocommerce_checkout_shipping'); ?>
				</div>

				<?php do_action('woocommerce_checkout_after_customer_details'); ?>

			<?php endif; ?>
		</div>

		<div class="checkout-summary">
			<div class="checkout-summary-card">
				<h3><?php esc_html_e('Your Order', 'woocommerce'); ?></h3>

				<?php do_action('woocommerce_checkout_before_order_review'); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action('woocommerce_checkout_order_review'); ?>
				</div>

				<?php do_action('woocommerce_checkout_after_order_review'); ?>
			</div>
		</div>

	</form>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
