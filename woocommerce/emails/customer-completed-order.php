<?php
/**
 * E-mail « Commande terminée » (retirée ou livrée) – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/customer-completed-order.php.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

if ( cz_order_is_pickup( $order ) ) {
	echo '<p>Votre commande N°' . esc_html( $order->get_order_number() ) . ' a bien été retirée. Merci de votre visite, et bonne dégustation !</p>';
	if ( 'cod' === $order->get_payment_method() ) {
		echo '<p>Nous confirmons la réception de votre paiement de ' . wp_kses_post( $order->get_formatted_order_total() ) . ' en boutique.</p>';
	}
} else {
	echo '<p>Votre commande N°' . esc_html( $order->get_order_number() ) . ' a été expédiée et arrive bientôt chez vous. Merci de votre confiance, et bonne dégustation !</p>';
}

cz_email_track( $order );

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

echo '<p>À très bientôt chez Cluizel.</p>';

do_action( 'woocommerce_email_footer', $email );
