<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

if (WC()->cart->is_empty()) :
?>

	<div class="cart-empty-message">
		<?php do_action('woocommerce_cart_is_empty'); ?>
		<p><?php esc_html_e('Your cart is currently empty.', 'woocommerce'); ?></p>
		<?php if (wc_get_page_id('shop') > 0) : ?>
			<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">
				<?php esc_html_e('Return to shop', 'woocommerce'); ?>
			</a>
		<?php endif; ?>
	</div>

<?php else : ?>

	<div class="cart-frame">
		<?php woocommerce_output_all_notices(); ?>

		<form class="cart-layout" id="cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
			<?php wp_nonce_field('woocommerce-cart', '_wpnonce'); ?>
			<?php do_action('woocommerce_before_cart_contents'); ?>

			<div class="cart-items">
			<?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

				if (!$_product || !$_product->exists() || $cart_item['quantity'] < 1 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
					continue;
				}

				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
				<div class="cart-item-card">
					<div class="cart-item-image">
						<?php
						$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', ['loading' => false]), $cart_item, $cart_item_key);
						if (!$product_permalink) {
							echo $thumbnail;
						} else {
							printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
						}
						?>
					</div>

					<div class="cart-item-info">
						<div class="cart-item-sku"><?php echo esc_html($_product->get_sku() ?: '#' . $_product->get_id()); ?></div>
						<div class="cart-item-name">
							<?php
							if (!$product_permalink) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
							} else {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
							}
							?>
						</div>
						<?php echo wc_get_formatted_cart_item_data($cart_item); ?>
						<?php if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) : ?>
							<div class="cart-item-backorder"><?php esc_html_e('Available on backorder', 'woocommerce'); ?></div>
						<?php endif; ?>
					</div>

					<div class="cart-item-qty">
						<button type="button" class="qty-btn qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'woocommerce'); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
						</button>
						<?php
						if ($_product->is_sold_individually()) {
							$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
						} else {
							$product_quantity = woocommerce_quantity_input([
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $_product->get_max_purchase_quantity(),
								'min_value'    => '0',
								'product_name' => $_product->get_name(),
							], $_product, false);
						}
						echo $product_quantity;
						?>
						<button type="button" class="qty-btn qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'woocommerce'); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
						</button>
					</div>

					<div class="cart-item-total">
						<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
					</div>

					<div class="cart-item-action">
						<?php
						echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="cart-item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">',
							esc_url(wc_get_cart_remove_url($cart_item_key)),
							esc_attr__('Remove this item', 'woocommerce'),
							esc_attr($product_id),
							esc_attr($_product->get_sku())
						), $cart_item_key);
						?>
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
							</svg>
						</a>
					</div>
				</div>
			<?php endforeach; ?>

			<?php do_action('woocommerce_after_cart_contents'); ?>
		</div>

		<div class="cart-summary">
			<div class="cart-summary-card collapsed">
			<div class="cart-summary-toggle" role="button" tabindex="0" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle order summary', 'woocommerce'); ?>">
				<h3><?php esc_html_e('Order Summary', 'woocommerce'); ?></h3>
				<span class="toggle-arrow">▼</span>
			</div>
			<div class="cart-summary-body">

				<?php if (wc_coupons_enabled()) : ?>
					<div class="cart-voucher">
						<input type="text" name="coupon_code" class="cart-voucher-input" placeholder="<?php esc_attr_e('Voucher code', 'woocommerce'); ?>" />
						<button type="submit" class="cart-voucher-apply" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
							<?php esc_html_e('Apply', 'woocommerce'); ?>
						</button>
						<?php do_action('woocommerce_cart_coupon'); ?>
					</div>
				<?php endif; ?>

				<div class="cart-summary-rows">
					<div class="cart-summary-row">
						<span><?php esc_html_e('Sub Total', 'woocommerce'); ?></span>
						<span><?php wc_cart_totals_subtotal_html(); ?></span>
					</div>

					<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
						<div class="cart-summary-row cart-summary-row--coupon">
							<span><?php echo esc_html($code); ?></span>
							<span>-<?php wc_cart_totals_coupon_html($coupon); ?></span>
						</div>
					<?php endforeach; ?>

					<?php if (WC()->cart->needs_shipping()) : ?>
					<?php
					$delivery_text = __('Calculated at checkout', 'woocommerce');
					$packages = WC()->shipping()->get_packages();

					if (!empty($packages)) {
						$first = reset($packages);
						if (!empty($first['rates'])) {
							$chosen = WC()->session->get('chosen_shipping_methods', []);
							$rate_key = $chosen[0] ?? '';
							$rate = isset($first['rates'][$rate_key]) ? $first['rates'][$rate_key] : reset($first['rates']);
							$delivery_text = wc_price($rate->get_cost());
						}
					}
					?>
					<div class="cart-summary-row">
						<span><?php esc_html_e('Delivery', 'woocommerce'); ?></span>
						<span><?php echo $delivery_text; ?></span>
					</div>
				<?php endif; ?>

					<?php foreach (WC()->cart->get_fees() as $fee) : ?>
						<div class="cart-summary-row">
							<span><?php echo esc_html($fee->name); ?></span>
							<span><?php wc_cart_totals_fee_html($fee); ?></span>
						</div>
					<?php endforeach; ?>

					<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
						<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
							<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
								<div class="cart-summary-row">
									<span><?php echo esc_html($tax->label); ?></span>
									<span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
								</div>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="cart-summary-row">
								<span><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
								<span><?php wc_cart_totals_taxes_total_html(); ?></span>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<div class="cart-summary-row cart-summary-row--total">
						<span><?php esc_html_e('Total', 'woocommerce'); ?></span>
						<span><?php wc_cart_totals_order_total_html(); ?></span>
					</div>
				</div>

				<div class="wc-proceed-to-checkout">
					<a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
					   id="checkout-btn-cart"
					   class="checkout-button button alt wc-forward"
					   style="display:block;width:100%;margin:0 auto;text-align:center;padding:16px 24px;border:none;border-radius:var(--radius-sm);background:var(--accent);color:white;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;letter-spacing:0.3px;">
						<?php esc_html_e('Proceed to Checkout', 'woocommerce'); ?>
					</a>
				</div>
			</div>
		</div>
		<?php do_action('woocommerce_after_cart_totals'); ?>
	</div>
	</form>

	<div class="cart-bottom-bar">
			<a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="continue-shopping">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
				<?php esc_html_e('Continue Shopping', 'woocommerce'); ?>
			</a>
			<button type="submit" class="update-cart-btn" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" form="cart-form">
				<?php esc_html_e('Update cart', 'woocommerce'); ?>
			</button>
		</div>
	</div>

	<div class="cart-mobile-checkout">
		<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="cart-mobile-checkout-btn">
			<span><?php esc_html_e('Proceed to Checkout', 'woocommerce'); ?></span>
			<span class="cart-mobile-price"><?php wc_cart_totals_order_total_html(); ?></span>
		</a>
	</div>

<?php endif; ?>

<?php do_action('woocommerce_after_cart'); ?>
