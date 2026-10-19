<?php
/**
 * « Ma commande » – Cluizel Paris
 * Surcharge de woocommerce/templates/checkout/review-order.php.
 * La classe woocommerce-checkout-review-order-table est conservée :
 * WooCommerce remplace ce bloc à chaque mise à jour de la commande.
 * Le mode de réception se choisit dans le panier ; il est rappelé ici.
 *
 * @package storefront-child
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table cz-review">

	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

	<ul class="cz-review-items">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				continue;
			}

			$parent  = $_product->is_type( 'variation' ) ? wc_get_product( $cart_item['product_id'] ) : null;
			$name    = apply_filters( 'woocommerce_cart_item_name', $parent ? $parent->get_name() : $_product->get_name(), $cart_item, $cart_item_key );
			$options = cz_cart_item_options( $cart_item );
			$detail  = trim( $options . ' x' . $cart_item['quantity'] );
			?>
			<li class="cart_item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', '', $cart_item, $cart_item_key ) ); ?>">
				<span class="cz-review-thumb"><?php echo $_product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="cz-review-text">
					<span class="cz-review-name"><?php echo wp_kses_post( $name ); ?></span>
					<span class="cz-review-options"><?php echo esc_html( $detail ); ?></span>
				</span>
				<span class="cz-review-price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<dl class="cz-review-totals">
		<div class="cz-row cart-subtotal">
			<dt>Sous-total</dt>
			<dd><?php wc_cart_totals_subtotal_html(); ?></dd>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cz-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<dt><?php wc_cart_totals_coupon_label( $coupon ); ?></dt>
				<dd><?php wc_cart_totals_coupon_html( $coupon ); ?></dd>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php
			do_action( 'woocommerce_review_order_before_shipping' );

			$chosen_methods = WC()->session ? (array) WC()->session->get( 'chosen_shipping_methods' ) : array();
			foreach ( WC()->shipping()->get_packages() as $i => $package ) :
				$chosen = isset( $chosen_methods[ $i ] ) ? $chosen_methods[ $i ] : '';
				$rate   = ( $chosen && isset( $package['rates'][ $chosen ] ) ) ? $package['rates'][ $chosen ] : null;
				?>
				<div class="cz-row shipping">
					<?php if ( $rate ) : ?>
						<?php
						$cost = (float) $rate->get_cost();
						if ( WC()->cart->display_prices_including_tax() ) {
							$cost += array_sum( (array) $rate->get_taxes() );
						}
						?>
						<dt><?php echo esc_html( $rate->get_label() ); ?></dt>
						<dd><?php echo wp_kses_post( wc_price( $cost ) ); ?></dd>
						<input type="hidden" name="shipping_method[<?php echo esc_attr( $i ); ?>]" data-index="<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $chosen ); ?>" class="shipping_method" />
					<?php else : ?>
						<dt>Mode de réception</dt>
						<dd><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">À choisir dans le panier</a></dd>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="cz-row fee">
				<dt><?php echo esc_html( $fee->name ); ?></dt>
				<dd><?php wc_cart_totals_fee_html( $fee ); ?></dd>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="cz-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<dt><?php echo esc_html( $tax->label ); ?></dt>
						<dd><?php echo wp_kses_post( $tax->formatted_amount ); ?></dd>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="cz-row tax-total">
					<dt><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></dt>
					<dd><?php wc_cart_totals_taxes_total_html(); ?></dd>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<div class="cz-row order-total">
			<dt>Total</dt>
			<dd><?php wc_cart_totals_order_total_html(); ?></dd>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
	</dl>
</div>