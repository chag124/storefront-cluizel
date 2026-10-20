<?php
/**
 * Pied des e-mails – Cluizel Paris
 * Surcharge de woocommerce/templates/emails/email-footer.php.
 * Bloc noir : logo, liens vers la carte et le compte, texte du pied de page
 * réglé dans WooCommerce › Réglages › E-mails.
 *
 * @package storefront-child
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

$email = $email ?? null;

$email_footer_text = get_option( 'woocommerce_email_footer_text' );
if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
	$text_transient    = get_transient( 'woocommerce_email_footer_text' );
	$email_footer_text = false !== $text_transient ? $text_transient : $email_footer_text;
}
?>
																		</div>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top" style="padding-top: 16px;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_footer" role="presentation">
										<tr>
											<td id="cz_footer">
												<span class="cz-logo-text">CLUIZEL</span>
												<p>
													<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">La carte</a>
													&nbsp;·&nbsp;
													<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a>
												</p>
												<div id="credit">
													<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', $email_footer_text, $email ) ) ) ); ?>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html>
