<?php
defined('ABSPATH') || exit;

// Safely collect customer data — avoid calling get_formatted_billing_address()
// which can throw fatal errors with unexpected DB data
$customer_data = null;
try {
	$c = WC()->customer;
	if ($c) {
		$customer_data = (object) [
			'first_name' => $c->get_billing_first_name(),
			'last_name'  => $c->get_billing_last_name(),
			'address_1'  => $c->get_billing_address_1(),
			'address_2'  => $c->get_billing_address_2(),
			'city'       => $c->get_billing_city(),
			'state'      => $c->get_billing_state(),
			'postcode'   => $c->get_billing_postcode(),
			'country'    => $c->get_billing_country(),
			'phone'      => $c->get_billing_phone(),
			'email'      => $c->get_billing_email(),
		];
	}
} catch (Exception $e) {
	$customer_data = null;
}

$has_address = $customer_data && ($customer_data->address_1 || $customer_data->city || $customer_data->postcode);
$show_card  = $has_address;
?>
<div class="woocommerce-billing-fields">
	<?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
		<h3><?php esc_html_e('Billing &amp; Shipping', 'woocommerce'); ?></h3>
	<?php else : ?>
		<h3><?php esc_html_e('Billing details', 'woocommerce'); ?></h3>
	<?php endif; ?>

	<?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

	<?php if ($show_card) : ?>
		<div class="address-card" id="billing-address-card">
			<div class="address-card-header">
				<span class="address-card-label"><?php esc_html_e('Billing address', 'woocommerce'); ?></span>
				<button type="button" class="address-card-edit" data-target="billing"><?php esc_html_e('Edit', 'woocommerce'); ?></button>
			</div>
			<div class="address-card-body">
				<?php
				$name = trim($customer_data->first_name . ' ' . $customer_data->last_name);
				if ($name) {
					echo '<div class="address-card-name">' . esc_html($name) . '</div>';
				}

				$parts = array_filter([
					$customer_data->address_1,
					$customer_data->address_2,
					$customer_data->city,
					$customer_data->state,
					$customer_data->postcode,
				]);
				if ($parts) {
					echo '<div class="address-card-address">' . esc_html(implode(', ', $parts)) . '</div>';
				}

				if ($customer_data->phone) {
					echo '<div class="address-card-phone">' . esc_html($customer_data->phone) . '</div>';
				}
				if ($customer_data->email) {
					echo '<div class="address-card-email">' . esc_html($customer_data->email) . '</div>';
				}
				?>
			</div>
		</div>
	<?php endif; ?>

	<div class="woocommerce-billing-fields__field-wrapper" id="billing-address-fields" style="<?php echo $show_card ? 'display:none' : ''; ?>">
		<?php
		$fields = $checkout->get_checkout_fields('billing');
		foreach ($fields as $key => $field) {
			woocommerce_form_field($key, $field, $checkout->get_value($key));
		}
		?>
	</div>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
	<div class="woocommerce-account-fields">
		<?php if (!$checkout->is_registration_required()) : ?>
			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e('Create an account?', 'woocommerce'); ?></span>
				</label>
			</p>
		<?php endif; ?>

		<?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

		<?php if ($checkout->get_checkout_fields('account')) : ?>
			<div class="create-account">
				<?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
					<?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
	</div>
<?php endif; ?>
