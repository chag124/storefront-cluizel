<?php
/**
 * En-tête des e-mails – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/email-header.php.
 * Bandeau noir, logo (image réglée dans WooCommerce › Réglages › E-mails,
 * ou « CLUIZEL » en texte), puis le titre de l'e-mail.
 * Les identifiants (#wrapper, #template_container…) sont ceux de WooCommerce.
 *
 * @package storefront-child
 * @version 10.7.0
 */

defined( 'ABSPATH' ) || exit;

$store_name = $store_name ?? get_bloginfo( 'name', 'display' );
$img        = get_option( 'woocommerce_email_header_image' );
if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
	$img_transient = get_transient( 'woocommerce_email_header_image' );
	$img           = false !== $img_transient ? $img_transient : $img;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
		<title><?php echo esc_html( $store_name ); ?></title>
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<table width="100%" id="outer_wrapper" role="presentation">
			<tr>
				<td></td>
				<td width="600">
					<div id="wrapper" dir="ltr">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" id="inner_wrapper" role="presentation">
							<tr>
								<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" role="presentation">
										<tr>
											<td id="cz_topbar">Commander en ligne et récupérez sur place</td>
										</tr>
										<tr>
											<td id="cz_logo">
												<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">
													<?php if ( $img ) : ?>
														<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $store_name ); ?>" />
													<?php else : ?>
														<span class="cz-logo-text">CLUIZEL</span>
													<?php endif; ?>
												</a>
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" role="presentation">
													<tr>
														<td id="header_wrapper">
															<h1><?php echo esc_html( $email_heading ); ?></h1>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td align="center" valign="top">
												<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body" role="presentation">
													<tr>
														<td valign="top" id="body_content">
															<table border="0" cellpadding="20" cellspacing="0" width="100%" role="presentation">
																<tr>
																	<td valign="top" id="body_content_inner_cell">
																		<div id="body_content_inner">
