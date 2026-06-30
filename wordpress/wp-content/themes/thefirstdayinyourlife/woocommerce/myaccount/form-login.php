<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');

$default_tab = isset($_GET['action']) && 'register' === $_GET['action'] ? 'register' : 'login';
?>

<div class="myaccount-login-wrap">

	<div class="myaccount-login-tabs">
		<button class="login-tab<?php echo 'login' === $default_tab ? ' active' : ''; ?>" data-tab="login"><?php esc_html_e('Login', 'mytheme'); ?></button>
		<button class="login-tab<?php echo 'register' === $default_tab ? ' active' : ''; ?>" data-tab="register"><?php esc_html_e('Register', 'mytheme'); ?></button>
	</div>

	<form id="form-login" class="woocommerce-form-login<?php echo 'login' === $default_tab ? ' active' : ''; ?>" method="post">

		<?php do_action('woocommerce_login_form_start'); ?>

		<p class="form-row">
			<label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
		</p>
		<p class="form-row">
			<label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
		</p>

		<?php do_action('woocommerce_login_form'); ?>

		<p class="form-row form-row--actions">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox">
				<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
			</label>
			<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
			<button type="submit" class="button" name="login" value="<?php esc_attr_e('Login', 'woocommerce'); ?>"><?php esc_html_e('Login', 'woocommerce'); ?></button>
		</p>
		<p class="woocommerce-LostPassword">
			<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
		</p>

		<?php do_action('woocommerce_login_form_end'); ?>

	</form>

	<?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>

	<form id="form-register" class="woocommerce-form-register<?php echo 'register' === $default_tab ? ' active' : ''; ?>" method="post">

		<?php do_action('woocommerce_register_form_start'); ?>

		<p class="form-row">
			<label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
		</p>

		<p class="form-row">
			<label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input type="email" class="input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
		</p>

		<p class="form-row">
			<label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
			<input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" />
		</p>
		<p class="form-row">
			<label for="reg_password2"><?php esc_html_e('Confirm password', 'mytheme'); ?>&nbsp;<span class="required">*</span></label>
			<input type="password" class="input-text" name="password2" id="reg_password2" autocomplete="new-password" />
		</p>

		<?php do_action('woocommerce_register_form'); ?>

		<p class="form-row">
			<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
			<button type="submit" class="button" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
		</p>

		<?php do_action('woocommerce_register_form_end'); ?>

	</form>

	<?php endif; ?>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
