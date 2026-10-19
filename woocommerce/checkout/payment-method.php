<?php
/**
 * Moyen de paiement – Cluizel Paris
 * Surcharge de woocommerce/templates/checkout/payment-method.php.
 * Cadre avec bouton radio, titre et description toujours visibles.
 *
 * @package storefront-child
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<span class="cz-payment-title"><?php echo wp_kses_post( $gateway->get_title() ); ?></span>
		<?php if ( $gateway->get_description() ) : ?>
			<span class="cz-payment-desc"><?php echo wp_kses_post( wp_strip_all_tags( $gateway->get_description() ) ); ?></span>
		<?php endif; ?>
	</label>

	<?php if ( $gateway->has_fields() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>