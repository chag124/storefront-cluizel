<?php
/**
 * E-mail « Commande annulée » (client) – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/customer-cancelled-order.php.
 * Envoyé quand une commande en attente ou en préparation est annulée.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

echo '<p>Votre commande N°' . esc_html( $order->get_order_number() ) . ' a bien été annulée.</p>';

if ( cz_order_was_paid_online( $order ) ) {
	echo '<p><strong>Le remboursement de ' . wp_kses_post( $order->get_formatted_order_total() ) . '</strong> sera effectué sur le moyen de paiement utilisé (' . esc_html( strtolower( $order->get_payment_method_title() ) ) . ') sous 5 jours ouvrés. Vous recevrez un e-mail dès qu’il sera fait.</p>';
} else {
	echo '<p>Aucun paiement n’a été encaissé : vous n’avez rien d’autre à faire.</p>';
}

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

echo '<p>Nous espérons vous revoir bientôt chez Cluizel.</p>';

do_action( 'woocommerce_email_footer', $email );
