<?php
defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
		'billing'  => __('Billing address', 'woocommerce'),
		'shipping' => __('Shipping address', 'woocommerce'),
	), $customer_id);
} else {
	$get_addresses = apply_filters('woocommerce_my_account_get_addresses', array(
		'billing' => __('Billing address', 'woocommerce'),
	), $customer_id);
}
?>

<div class="woocommerce-Addresses addresses">
	<?php foreach ($get_addresses as $name => $address_title) :
		$address = wc_get_account_formatted_address($name);
	?>
		<div class="woocommerce-Address">
			<header class="woocommerce-Address-title title">
				<h3><?php echo esc_html($address_title); ?></h3>
				<a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit">
					<?php echo $address ? esc_html__('Edit', 'woocommerce') : esc_html__('Add', 'woocommerce'); ?>
				</a>
			</header>
			<address>
				<?php echo $address ? wp_kses_post($address) : esc_html_e('You have not set up this type of address yet.', 'woocommerce'); ?>
			</address>
		</div>
	<?php endforeach; ?>
</div>
