<?php
/**
 * E-mail « Commande en préparation » – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/customer-processing-order.php.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email );

cz_email_hello( $order );

if ( cz_order_is_pickup( $order ) ) {
	echo '<p>Merci pour votre commande N°' . esc_html( $order->get_order_number() ) . ' ! Nos chocolatiers la préparent avec soin. Nous vous enverrons un e-mail dès qu’elle sera prête à retirer en boutique.</p>';
} else {
	echo '<p>Merci pour votre commande N°' . esc_html( $order->get_order_number() ) . ' ! Nos chocolatiers la préparent avec soin. Nous vous préviendrons dès son expédition.</p>';
}

cz_email_track( $order );

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

cz_email_order_button( $order );

do_action( 'woocommerce_email_footer', $email );
