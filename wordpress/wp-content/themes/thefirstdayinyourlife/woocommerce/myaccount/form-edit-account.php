<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form'); ?>

<div class="myaccount-form">
	<h2><?php esc_html_e('Account details', 'woocommerce'); ?></h2>

	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

		<?php do_action('woocommerce_edit_account_form_start'); ?>

		<div class="myaccount-form-row">
			<p class="form-row form-row-first">
				<label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr($user->first_name); ?>" />
			</p>
			<p class="form-row form-row-last">
				<label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>" />
			</p>
		</div>

		<div class="myaccount-form-row">
			<p class="form-row form-row-wide">
				<label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" />
				<span class="myaccount-form-hint"><em><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?></em></span>
			</p>
		</div>

		<div class="myaccount-form-row">
			<p class="form-row form-row-wide">
				<label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" />
			</p>
		</div>

		<?php do_action('woocommerce_edit_account_form'); ?>

		<div class="myaccount-form-section">
			<h3><?php esc_html_e('Password change', 'woocommerce'); ?></h3>
			<p class="myaccount-form-hint"><?php esc_html_e('Leave blank to keep your current password.', 'woocommerce'); ?></p>

			<div class="myaccount-form-row">
				<p class="form-row form-row-first">
					<label for="password_1"><?php esc_html_e('New password', 'woocommerce'); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" />
				</p>
				<p class="form-row form-row-last">
					<label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" />
				</p>
			</div>
		</div>

		<?php do_action('woocommerce_edit_account_form_end'); ?>

		<div class="myaccount-form-actions">
			<?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
			<button type="submit" class="btn btn-primary woocommerce-Button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="save_account_details" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</div>
	</form>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
