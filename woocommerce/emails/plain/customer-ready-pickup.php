<?php
/**
 * E-mail « Commande prête à retirer » (texte brut) – Cluizel Paris
 *
 * @package storefront-child
 */

defined( 'ABSPATH' ) || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

$first = $order->get_billing_first_name();
echo esc_html( $first ? 'Bonjour ' . $first . ',' : 'Bonjour,' ) . "\n\n";
echo esc_html( 'Bonne nouvelle : votre commande N°' . $order->get_order_number() . ' vous attend en boutique.' ) . "\n\n";

if ( 'cod' === $order->get_payment_method() && ! $order->is_paid() ) {
	echo esc_html( 'Montant à régler au retrait : ' . wp_strip_all_tags( $order->get_formatted_order_total() ) ) . "\n\n";
}

cz_email_pickup_card( $order, true );

echo "\n----------------------------------------\n\n";

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n----------------------------------------\n\n";

if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
