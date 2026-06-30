<?php
defined('ABSPATH') || exit;

$order = wc_get_order($order_id);

if (!$order) {
	return;
}

$order_items        = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$downloads          = $order->get_downloadable_items();
$show_customer_details = $order->get_user_id() === get_current_user_id();

if ($show_downloads) {
	wc_get_template('order/order-downloads.php', array(
		'downloads'  => $downloads,
		'show_title' => true,
	));
}
?>

<?php do_action('woocommerce_order_details_before_order_table', $order); ?>

<div class="thankyou-section">
	<h3 class="thankyou-section-title"><?php esc_html_e('Order details', 'woocommerce'); ?></h3>

	<?php do_action('woocommerce_order_details_before_order_table_items', $order); ?>

	<?php foreach ($order_items as $item_id => $item) :
		$product = $item->get_product();
	?>
	<div class="thankyou-item">
		<div class="thankyou-item-left">
			<span class="thankyou-item-name">
				<?php
				$is_visible = $product && $product->is_visible();
				$product_permalink = $is_visible ? $product->get_permalink($item) : '';
				if ($product_permalink) {
					echo '<a href="' . esc_url($product_permalink) . '">' . wp_kses_post($item->get_name()) . '</a>';
				} else {
					echo wp_kses_post($item->get_name());
				}
				?>
			</span>
			<span class="thankyou-item-qty">x <?php echo esc_html($item->get_quantity()); ?></span>
			<?php
			do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);
			wc_display_item_meta($item);
			do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
			?>
		</div>
		<span class="thankyou-item-total"><?php echo $order->get_formatted_line_subtotal($item); ?></span>
	</div>
	<?php endforeach; ?>

	<?php do_action('woocommerce_order_details_after_order_table_items', $order); ?>

	<div class="thankyou-totals">
		<?php foreach ($order->get_order_item_totals() as $key => $total) : ?>
		<div class="thankyou-total-row <?php echo $key === 'order_total' ? 'thankyou-total-row--final' : ''; ?>">
			<span><?php echo esc_html($total['label']); ?></span>
			<span><?php echo wp_kses_post($total['value']); ?></span>
		</div>
		<?php endforeach; ?>

		<?php if ($order->get_customer_note()) : ?>
		<div class="thankyou-total-row">
			<span><?php esc_html_e('Note:', 'woocommerce'); ?></span>
			<span><?php echo wp_kses(nl2br($order->get_customer_note()), array('br' => array())); ?></span>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php do_action('woocommerce_order_details_after_order_table', $order); ?>

<?php
do_action('woocommerce_after_order_details', $order);

if ($show_customer_details) {
	wc_get_template('order/order-details-customer.php', array('order' => $order));
}
