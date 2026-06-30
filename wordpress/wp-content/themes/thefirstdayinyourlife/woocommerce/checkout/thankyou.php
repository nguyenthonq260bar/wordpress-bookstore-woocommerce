<?php
defined('ABSPATH') || exit;
?>
<div class="thankyou-wrap">

	<?php if ($order) :

		do_action('woocommerce_before_thankyou', $order->get_id());

		if ($order->has_status('failed')) : ?>

			<?php do_action('mytheme_before_thankyou_card', $order); ?>

			<div class="thankyou-card thankyou-card--failed">
				<div class="thankyou-icon thankyou-icon--error">!</div>
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
					<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
				</p>
				<div class="thankyou-actions">
					<a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php esc_html_e('Pay', 'woocommerce'); ?></a>
					<?php if (is_user_logged_in()) : ?>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button"><?php esc_html_e('My account', 'woocommerce'); ?></a>
					<?php endif; ?>
				</div>
			</div>

			<?php do_action('mytheme_after_thankyou_card', $order); ?>

		<?php else : ?>

			<?php do_action('mytheme_before_thankyou_card', $order); ?>

			<div class="thankyou-card">
				<?php do_action('mytheme_before_thankyou_hero', $order); ?>

				<div class="thankyou-hero">
					<div class="thankyou-icon">&#10003;</div>
					<h2 class="thankyou-title"><?php esc_html_e('Thank you for your order!', 'woocommerce'); ?></h2>
					<p class="thankyou-subtitle"><?php echo sprintf(esc_html__('Order #%s', 'woocommerce'), $order->get_order_number()); ?></p>
				</div>

				<?php do_action('mytheme_after_thankyou_hero', $order); ?>

				<?php do_action('mytheme_before_thankyou_info', $order); ?>

				<div class="thankyou-info">
					<div class="thankyou-info-item">
						<span class="thankyou-info-label"><?php esc_html_e('Date', 'woocommerce'); ?></span>
						<span class="thankyou-info-value"><?php echo wc_format_datetime($order->get_date_created()); ?></span>
					</div>
					<?php if (is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email()) : ?>
					<div class="thankyou-info-item">
						<span class="thankyou-info-label"><?php esc_html_e('Email', 'woocommerce'); ?></span>
						<span class="thankyou-info-value"><?php echo $order->get_billing_email(); ?></span>
					</div>
					<?php endif; ?>
					<div class="thankyou-info-item thankyou-info-item--total">
						<span class="thankyou-info-label"><?php esc_html_e('Total', 'woocommerce'); ?></span>
						<span class="thankyou-info-value thankyou-info-value--total"><?php echo $order->get_formatted_order_total(); ?></span>
					</div>
					<div class="thankyou-info-item">
						<span class="thankyou-info-label"><?php esc_html_e('Payment', 'woocommerce'); ?></span>
						<span class="thankyou-info-value"><?php echo wp_kses_post($order->get_payment_method_title()); ?></span>
					</div>
				</div>

				<?php do_action('mytheme_after_thankyou_info', $order); ?>

			</div>

			<?php do_action('mytheme_after_thankyou_card', $order); ?>

			<div class="thankyou-after-card">
				<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
				<?php do_action('woocommerce_thankyou', $order->get_id()); ?>
			</div>

		<?php endif; ?>

	<?php else :

		wc_get_template('checkout/order-received.php', array('order' => false));

	endif; ?>

</div>
