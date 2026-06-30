<?php
/**
 * Simple product add to cart
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product );

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );
		?>

		<div class="qty-wrapper">
			<button type="button" class="qty-btn qty-btn--minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'woocommerce' ); ?>">
				<svg width="14" height="2" viewBox="0 0 14 2" fill="none">
					<path d="M0 1H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</button>
			<?php
			woocommerce_quantity_input(
				array(
					'min_value'   => $product->get_min_purchase_quantity(),
					'max_value'   => $product->get_max_purchase_quantity(),
					'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
				)
			);
			?>
			<button type="button" class="qty-btn qty-btn--plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'woocommerce' ); ?>">
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none">
					<path d="M7 0V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					<path d="M0 7H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</button>
		</div>

		<?php
		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">

		<div class="cart-buttons">
			<button type="submit" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
				<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
			</button>
			<button type="submit" name="buy_now" value="1" class="single_add_to_cart_button button alt buy-now-btn">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
				<?php esc_html_e( 'Buy Now', 'woocommerce' ); ?>
			</button>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
