<?php
defined('ABSPATH') || exit;

$page_title = ('billing' === $load_address) ? esc_html__('Billing address', 'woocommerce') : esc_html__('Shipping address', 'woocommerce');

do_action('woocommerce_before_edit_account_address_form'); ?>

<?php if (!$load_address) : ?>
	<?php wc_get_template('myaccount/my-address.php'); ?>
<?php else : ?>
	<div class="myaccount-form">
		<h2><?php echo esc_html(apply_filters('woocommerce_my_account_edit_address_title', $page_title, $load_address)); ?></h2>

		<form method="post">
			<div class="woocommerce-address-fields">
				<?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>

				<div class="woocommerce-address-fields__field-wrapper myaccount-form-grid">
					<?php
					foreach ($address as $key => $field) {
						$field_class = $field['class'] ?? [];
						if (in_array($key, ['billing_email', 'shipping_email', 'billing_phone', 'shipping_phone', 'billing_company', 'shipping_company'])) {
							$field_class = array_diff($field_class, ['form-row-first', 'form-row-last']);
							$field_class[] = 'form-row-wide';
						}
						$field['class'] = $field_class;
						woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
					}
					?>
				</div>

				<?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>

				<div class="myaccount-form-actions">
					<?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
					<input type="hidden" name="action" value="edit_address" />
					<button type="submit" class="btn btn-primary button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_address" value="<?php esc_attr_e('Save address', 'woocommerce'); ?>"><?php esc_html_e('Save address', 'woocommerce'); ?></button>
				</div>
			</div>
		</form>
	</div>
<?php endif; ?>

<?php do_action('woocommerce_after_edit_account_address_form'); ?>
