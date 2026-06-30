<?php
defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>

<div class="thankyou-section">
	<h3 class="thankyou-section-title"><?php esc_html_e('Billing address', 'woocommerce'); ?></h3>
	<div class="thankyou-address">
		<?php echo wp_kses_post($order->get_formatted_billing_address(esc_html__('N/A', 'woocommerce'))); ?>
		<?php if ($order->get_billing_phone()) : ?>
			<span class="thankyou-address-line"><?php echo esc_html($order->get_billing_phone()); ?></span>
		<?php endif; ?>
		<?php if ($order->get_billing_email()) : ?>
			<span class="thankyou-address-line"><?php echo esc_html($order->get_billing_email()); ?></span>
		<?php endif; ?>
		<?php do_action('woocommerce_order_details_after_customer_address', 'billing', $order); ?>
	</div>
</div>

<?php if ($show_shipping) : ?>
<div class="thankyou-section">
	<h3 class="thankyou-section-title"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h3>
	<div class="thankyou-address">
		<?php echo wp_kses_post($order->get_formatted_shipping_address(esc_html__('N/A', 'woocommerce'))); ?>
		<?php if ($order->get_shipping_phone()) : ?>
			<span class="thankyou-address-line"><?php echo esc_html($order->get_shipping_phone()); ?></span>
		<?php endif; ?>
		<?php do_action('woocommerce_order_details_after_customer_address', 'shipping', $order); ?>
	</div>
</div>
<?php endif; ?>

<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>
