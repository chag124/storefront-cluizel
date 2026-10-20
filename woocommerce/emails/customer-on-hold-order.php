<?php
/**
 * E-mail « Commande en attente » (paiement par virement) – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/customer-on-hold-order.php.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

echo '<p>Nous avons bien reçu votre commande N°' . esc_html( $order->get_order_number() ) . '. Elle sera préparée dès réception de votre paiement.</p>';

cz_email_track( $order );

/* Les coordonnées du virement sont ajoutées ici par WooCommerce */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

cz_email_order_button( $order );

do_action( 'woocommerce_email_footer', $email );
