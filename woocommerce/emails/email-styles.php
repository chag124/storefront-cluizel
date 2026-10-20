<?php
/**
 * Styles des e-mails – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/email-styles.php.
 * On reprend les styles de WooCommerce (tableau de commande, adresses…)
 * puis on ajoute ceux de la charte : fond beige, carte blanche arrondie,
 * titres Montserrat, texte Barlow, noir et blanc.
 * WooCommerce recopie ces styles directement sur les balises (style="…").
 *
 * @package storefront-child
 * @version 11.3.0
 */

defined( 'ABSPATH' ) || exit;

/* 1. Styles d'origine de WooCommerce */
$cz_core_styles = WC()->plugin_path() . '/templates/emails/email-styles.php';
if ( file_exists( $cz_core_styles ) ) {
	include $cz_core_styles;
}

$cz_titre = "'Montserrat', 'Helvetica Neue', Helvetica, Arial, sans-serif";
$cz_texte = "'Barlow', 'Helvetica Neue', Helvetica, Arial, sans-serif";
?>

/* 2. Charte Cluizel */
body,
#outer_wrapper {
	background-color: #f1ede8 !important;
}

#wrapper {
	max-width: 600px;
	padding: 32px 0 !important;
}

#inner_wrapper {
	background-color: transparent !important;
	border-radius: 0 !important;
}

#template_container {
	background-color: #ffffff !important;
	border: 0 !important;
	border-radius: 30px 30px 0 0 !important;
	box-shadow: none !important;
	overflow: hidden;
}

/* Bandeau noir et logo */
#cz_topbar {
	background-color: #000000;
	color: #ffffff;
	font-family: <?php echo $cz_texte; // phpcs:ignore WordPress.Security.EscapeOutput ?>;
	font-size: 13px;
	padding: 12px 20px !important;
	text-align: center;
}

#cz_logo {
	border-bottom: 1px solid #000000;
	padding: 28px 20px !important;
	text-align: center;
}

#cz_logo a,
.cz-logo-text {
	color: #000000 !important;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?>;
	font-size: 24px;
	font-weight: 600;
	letter-spacing: 4px;
	text-decoration: none !important;
}

#cz_logo img {
	display: inline-block;
	max-width: 180px;
	height: auto;
	border: 0;
}

/* Titre de l'e-mail */
#template_header {
	background-color: #ffffff !important;
	border-radius: 0 !important;
	color: #000000 !important;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
}

#header_wrapper {
	padding: 40px 24px 0 !important;
}

#template_header h1,
#template_header h1 a {
	color: #000000 !important;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
	font-size: 30px !important;
	font-weight: 400 !important;
	line-height: 1.25 !important;
	margin: 0 !important;
	text-align: left !important;
	text-shadow: none !important;
}

/* Corps */
#body_content {
	background-color: #ffffff !important;
}

#body_content > table > tbody > tr > td,
#body_content > table > tr > td {
	padding: 24px 24px 40px !important;
}

#body_content_inner {
	color: #000000 !important;
	font-family: <?php echo $cz_texte; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
	font-size: 13px !important;
	line-height: 1.55 !important;
}

#body_content_inner h2,
#body_content_inner h3 {
	color: #000000 !important;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
	font-size: 20px !important;
	font-weight: 400 !important;
	line-height: 1.3 !important;
	margin: 32px 0 16px !important;
}

#body_content_inner a {
	color: #000000 !important;
	font-weight: 400 !important;
	text-decoration: underline !important;
}

#body_content p {
	margin: 0 0 8px;
}

/* Tableau de la commande */
.td,
#body_content_inner table.td th,
#body_content_inner table.td td {
	border: 0 !important;
	border-bottom: 1px solid #e6e0d8 !important;
	color: #000000 !important;
	font-family: <?php echo $cz_texte; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
}

#body_content_inner table.td {
	border: 0 !important;
}

#body_content_inner table.td thead th {
	font-weight: 600 !important;
}

#body_content_inner table.td tfoot th,
#body_content_inner table.td tfoot td {
	font-weight: 400 !important;
}

#body_content_inner .order_item img {
	border-radius: 8px;
}

/* Adresses */
.address {
	background-color: #f1ede8 !important;
	border: 0 !important;
	border-radius: 20px !important;
	color: #000000 !important;
	font-size: 12px !important;
	font-style: normal !important;
	line-height: 1.35 !important;
	margin-top: 16px !important;
	padding: 20px !important;
}

.address-title {
	color: #000000 !important;
	display: block;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
	font-size: 15px !important;
	font-weight: 600 !important;
	margin-top: 8px;
}

/* Résumé de la commande */
#body_content_inner h2.email-order-detail-heading,
#body_content_inner h2.email-order-detail-heading span {
	color: #000000 !important;
	font-family: <?php echo $cz_titre; // phpcs:ignore WordPress.Security.EscapeOutput ?> !important;
	font-size: 15px !important;
	font-weight: 400 !important;
	line-height: 2 !important;
	margin: 40px 0 24px !important;
}

#body_content_inner h2.email-order-detail-heading span {
	margin: 0 !important;
}

#body_content_inner table.email-order-details thead th {
	border-bottom: 1px solid rgba(0, 0, 0, 0.25) !important;
	font-size: 11px !important;
	font-weight: 600 !important;
	padding: 0 12px 16px !important;
}

#body_content_inner table.email-order-details tbody td {
	border: 0 !important;
	font-size: 13px !important;
	padding: 36px 12px !important;
}

#body_content_inner table.email-order-details tbody tr:last-child td {
	border-bottom: 1px solid rgba(0, 0, 0, 0.25) !important;
}

#body_content_inner .email-order-item-thumbnail img {
	border-radius: 0;
	height: auto;
	width: 64px;
}

#body_content_inner .wc-product-name {
	font-size: 13px !important;
	margin: 0 0 10px !important;
}

#body_content_inner .cz-email-item-options,
#body_content_inner .email-order-item-meta {
	color: #000000 !important;
	font-size: 11px !important;
	line-height: 1.35 !important;
}

/* Totaux en retrait, petits, Total plus grand */
#body_content_inner table.email-order-details .order-totals th,
#body_content_inner table.email-order-details .order-totals td {
	border: 0 !important;
	font-size: 11px !important;
	font-weight: 400 !important;
	padding: 6px 25px !important;
}

#body_content_inner table.email-order-details .order-totals-total th,
#body_content_inner table.email-order-details .order-totals-total td {
	font-size: 14px !important;
	font-weight: 400 !important;
	padding-bottom: 20px !important;
}

#body_content_inner table.email-order-details .order-totals-last th,
#body_content_inner table.email-order-details .order-totals-last td {
	border-bottom: 0 !important;
}

/* Contenu additionnel (« Encore merci… ») */
#body_content table td td.email-additional-content,
.email-additional-content p {
	font-size: 12px !important;
	padding-top: 8px !important;
	text-align: left !important;
}

/* Blocs de la charte : carte beige, frise, bouton */
#body_content_inner .cz-email-card {
	background-color: #f1ede8 !important;
	border-radius: 20px !important;
	margin: 28px 0 0 !important;
}

#body_content_inner .cz-email-card td.cz-email-card-cell {
	padding: 24px 24px 20px !important;
}

#body_content_inner .cz-email-card h2 {
	margin: 0 0 12px !important;
}

#body_content_inner .cz-email-card table.cz-email-hours td {
	border: 0 !important;
	padding: 2px 24px 2px 0 !important;
}

#body_content_inner table.cz-email-track {
	margin: 8px 0 28px !important;
}

#body_content_inner table.cz-email-track td {
	border: 0 !important;
	padding: 0 4px !important;
	vertical-align: top;
	width: 25%;
}

#body_content_inner .cz-email-bar {
	border: 1px solid #000000;
	border-radius: 10px;
	font-size: 0;
	height: 8px;
	line-height: 8px;
}

#body_content_inner .cz-email-bar.is-done {
	background-color: #000000;
}

#body_content_inner .cz-email-step {
	color: #000000;
	font-size: 12px;
	line-height: 1.3;
	padding-top: 8px;
}

#body_content_inner .cz-email-step.is-current {
	font-weight: 600;
}

#body_content_inner a.cz-email-btn {
	background-color: #000000 !important;
	border-radius: 999px !important;
	color: #ffffff !important;
	display: inline-block;
	font-size: 12px !important;
	padding: 12px 32px !important;
	text-decoration: none !important;
}

/* Pied de page noir */
#template_footer {
	background-color: transparent !important;
}

#template_footer td {
	border-radius: 0 !important;
}

#cz_footer {
	background-color: #000000;
	border-radius: 0 0 30px 30px !important;
	color: #ffffff;
	font-family: <?php echo $cz_texte; // phpcs:ignore WordPress.Security.EscapeOutput ?>;
	font-size: 13px;
	line-height: 1.6;
	padding: 40px 24px !important;
	text-align: center;
}

#cz_footer .cz-logo-text {
	color: #ffffff !important;
	font-size: 20px;
}

#cz_footer #credit {
	padding-top: 20px !important;
}

#cz_footer a {
	color: #ffffff !important;
	text-decoration: underline;
}

#cz_footer p {
	margin: 12px 0 0 !important;
}

#template_footer #credit {
	border: 0 !important;
	color: #ffffff !important;
	padding: 0 !important;
}

/* Petits écrans */
@media screen and (max-width: 600px) {
	#wrapper {
		padding: 0 !important;
	}

	#template_container {
		border-radius: 0 !important;
	}

	#cz_footer {
		border-radius: 0 !important;
	}

	#header_wrapper {
		padding: 28px 20px 0 !important;
	}

	#body_content_inner table.email-order-details tbody td {
		padding: 20px 6px !important;
	}

	#body_content_inner table.email-order-details .order-totals th,
	#body_content_inner table.email-order-details .order-totals td {
		padding: 6px 6px !important;
	}

	#template_header h1 {
		font-size: 24px !important;
	}

	#body_content > table > tbody > tr > td,
	#body_content > table > tr > td {
		padding: 20px 20px 32px !important;
	}

	#body_content_inner .cz-email-step {
		font-size: 10px !important;
	}
}