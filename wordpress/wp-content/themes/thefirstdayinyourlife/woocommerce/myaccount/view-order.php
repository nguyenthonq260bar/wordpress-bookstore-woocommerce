<?php
defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="myaccount-section">
	<div class="view-order-header">
		<h2><?php printf(esc_html__('Order #%s', 'woocommerce'), esc_html($order->get_order_number())); ?></h2>
		<span class="order-status-badge status-<?php echo esc_attr($order->get_status()); ?>">
			<?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
		</span>
	</div>

	<p class="view-order-info">
		<?php
		printf(
			esc_html__('Placed on %s', 'woocommerce'),
			'<mark>' . esc_html(wc_format_datetime($order->get_date_created())) . '</mark>'
		);
		?>
		&mdash;
		<?php
		printf(
			esc_html__('Total: %s', 'woocommerce'),
			wp_kses_post($order->get_formatted_order_total())
		);
		?>
	</p>

	<?php do_action('woocommerce_view_order', $order->get_id()); ?>

	<?php if ($notes) : ?>
		<div class="view-order-notes">
			<h2><?php esc_html_e('Order updates', 'woocommerce'); ?></h2>
			<ol class="woocommerce-OrderUpdates commentlist notes">
				<?php foreach ($notes as $note) : ?>
				<li class="woocommerce-OrderUpdate comment note">
					<div class="woocommerce-OrderUpdate-inner comment_container">
						<div class="woocommerce-OrderUpdate-text comment-text">
							<p class="woocommerce-OrderUpdate-meta meta">
								<?php echo esc_html(date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date))); ?>
							</p>
							<div class="woocommerce-OrderUpdate-description description">
								<?php echo wp_kses_post(wpautop(wptexturize($note->comment_content))); ?>
							</div>
						</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
	<?php endif; ?>
</div>
