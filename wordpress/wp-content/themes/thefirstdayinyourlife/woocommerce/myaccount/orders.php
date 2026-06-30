<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_account_orders', $has_orders); ?>

<?php if ($has_orders) : ?>

	<div class="orders-card-grid">
		<?php foreach ($customer_orders->orders as $customer_order) :
			$order      = wc_get_order($customer_order);
			$item_count = $order->get_item_count() - $order->get_item_count_refunded();
		?>
			<div class="order-card woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?>">
				<div class="order-card-row order-card-row--primary">
					<div class="order-card-meta">
						<a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="order-card-number">
							<?php echo esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number()); ?>
						</a>
						<span class="order-card-sep">&bull;</span>
						<time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>">
							<?php echo esc_html(wc_format_datetime($order->get_date_created())); ?>
						</time>
					</div>
					<span class="order-status-badge status-<?php echo esc_attr($order->get_status()); ?>">
						<?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
					</span>
				</div>

				<div class="order-card-row">
					<div class="order-card-total-line">
						<?php echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count)); ?>
					</div>

					<?php
					$custom_columns = wc_get_account_orders_columns();
					foreach ($custom_columns as $column_id => $column_name) :
						if (in_array($column_id, ['order-number', 'order-date', 'order-status', 'order-total', 'order-actions'])) {
							continue;
						}
						if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) :
					?>
							<span class="order-card-sep">&bull;</span>
							<span class="order-card-custom-line" data-title="<?php echo esc_attr($column_name); ?>">
								<?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>
							</span>
					<?php
						endif;
					endforeach;
					?>
				</div>

				<?php
				$actions = wc_get_account_orders_actions($order);
				if (!empty($actions)) :
				?>
					<div class="order-card-row order-card-row--actions">
						<?php foreach ($actions as $key => $action) :
							if (empty($action['aria-label'])) {
								$action_aria_label = sprintf(__('%1$s order number %2$s', 'woocommerce'), $action['name'], $order->get_order_number());
							} else {
								$action_aria_label = $action['aria-label'];
							}
						?>
							<a href="<?php echo esc_url($action['url']); ?>"
							   class="order-card-action order-card-action--<?php echo esc_attr($key); ?>"
							   aria-label="<?php echo esc_attr($action_aria_label); ?>">
								<?php echo esc_html($action['name']); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php do_action('woocommerce_before_account_orders_pagination'); ?>

	<?php if (1 < $customer_orders->max_num_pages) : ?>
		<div class="orders-pagination">
			<?php if (1 !== $current_page) : ?>
				<a class="btn-outline" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page - 1)); ?>">
					<?php esc_html_e('Previous', 'woocommerce'); ?>
				</a>
			<?php endif; ?>

			<?php if (intval($customer_orders->max_num_pages) !== $current_page) : ?>
				<a class="btn-primary" href="<?php echo esc_url(wc_get_endpoint_url('orders', $current_page + 1)); ?>">
					<?php esc_html_e('Next', 'woocommerce'); ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="content-card orders-empty">
		<p><?php esc_html_e('No order has been made yet.', 'woocommerce'); ?></p>
		<a class="btn btn-primary" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
			<?php esc_html_e('Browse products', 'woocommerce'); ?>
		</a>
	</div>
<?php endif; ?>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
