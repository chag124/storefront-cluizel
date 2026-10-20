<?php
/**
 * E-mail « Commande prête à retirer » – Cluizel Paris
 * Envoyé quand la commande passe au statut « Prête à retirer »
 * (classe CZ_Email_Ready_Pickup, inc/class-cz-email-ready-pickup.php).
 *
 * @package storefront-child
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

echo '<p>Bonne nouvelle : votre commande N°' . esc_html( $order->get_order_number() ) . ' vous attend en boutique.</p>';

if ( 'cod' === $order->get_payment_method() && ! $order->is_paid() ) {
	echo '<p>Montant à régler au retrait : <strong>' . wp_kses_post( $order->get_formatted_order_total() ) . '</strong></p>';
}

cz_email_track( $order );

/* Instructions de retrait en premier : c'est l'information utile de cet e-mail */
cz_email_pickup_card( $order );

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

cz_email_order_button( $order );

do_action( 'woocommerce_email_footer', $email );
