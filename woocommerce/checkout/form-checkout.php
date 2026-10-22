<?php
/**
 * Validation de la commande – Cluizel Paris
 * Surcharge de woocommerce/templates/checkout/form-checkout.php.
 * Colonne gauche : coordonnées, retrait ou adresse de livraison, paiement, CGV.
 * Colonne droite : « Ma commande » et bouton « Passer commande ».
 *
 * @package storefront-child
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

cz_checkout_head();

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="Finaliser ma commande">

	<div class="cz-checkout">
		<div class="cz-checkout-main">

			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div id="customer_details">
					<section class="cz-card cz-coords">
						<h2 class="cz-card-title">Mes coordonnées</h2>
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
						<?php if ( ! is_user_logged_in() ) : ?>
							<a class="cz-login-link" href="<?php echo esc_url( add_query_arg( 'redirect_to', rawurlencode( wc_get_checkout_url() ), wc_get_page_permalink( 'myaccount' ) ) ); ?>">J’ai déjà un compte</a>
						<?php endif; ?>
					</section>

					<?php if ( WC()->cart->needs_shipping_address() ) : ?>
						<section class="cz-card cz-delivery">
							<h2 class="cz-card-title">Adresse de livraison</h2>
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</section>
					<?php else : ?>
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					<?php endif; ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>

			<?php
			cz_slot_picker();

			if ( cz_is_local_pickup_chosen() ) {
				cz_pickup_instructions();
			}

			woocommerce_checkout_payment();
			?>
		</div>

		<div class="cz-checkout-side">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<h2 id="order_review_heading">Ma commande</h2>
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
