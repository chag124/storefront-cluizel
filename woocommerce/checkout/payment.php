<?php
/**
 * Paiement – Cluizel Paris
 * Surcharge de woocommerce/templates/checkout/payment.php.
 * Moyens de paiement et case CGV dans la colonne de gauche ;
 * le bouton « Passer commande » est affiché sous « Ma commande » (functions.php).
 *
 * @package storefront-child
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment cz-payment">
	<h2 class="cz-section-title">Paiement</h2>

	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', 'Aucun moyen de paiement n’est disponible pour ce mode de réception.' ), 'notice' );
				echo '</li>';
			}
			?>
		</ul>
	<?php endif; ?>

	<div class="form-row place-order cz-terms">
		<noscript>
			<button type="submit" class="button" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}