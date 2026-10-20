<?php
/**
 * E-mail « Commande remboursée » (client) – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/customer-refunded-order.php.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

$cz_refunded = wc_price( $order->get_total_refunded(), array( 'currency' => $order->get_currency() ) );
$cz_method   = strtolower( $order->get_payment_method_title() );

if ( $partial_refund ) {
	echo '<p>Votre commande N°' . esc_html( $order->get_order_number() ) . ' a été partiellement remboursée : <strong>' . wp_kses_post( $cz_refunded ) . '</strong>.</p>';
} else {
	echo '<p>Le remboursement de votre commande N°' . esc_html( $order->get_order_number() ) . ' a été effectué : <strong>' . wp_kses_post( $cz_refunded ) . '</strong>.</p>';
}

echo '<p>Il a été fait sur le moyen de paiement utilisé (' . esc_html( $cz_method ) . '). Selon votre banque, il apparaîtra sur votre compte sous quelques jours.</p>';

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
