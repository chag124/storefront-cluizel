<?php
/**
 * Commande reçue – Cluizel Paris
 * Surcharge de woocommerce/templates/checkout/thankyou.php.
 * Colonne gauche : numéro / date / e-mail, informations de paiement, ma commande.
 * Colonne droite : instructions pour le retrait (ou adresse de livraison), retour à l'accueil.
 *
 * @package storefront-child
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order cz-thanks-page">

	<?php if ( $order ) : ?>

		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="cz-thanks-head">
				<h1 class="cz-thanks-title">Votre paiement n’a pas abouti.</h1>
				<p>Votre commande n’a pas pu être validée. Vous pouvez réessayer ou revenir à votre panier.</p>
			</div>
			<p class="cz-thanks-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="cz-btn">Payer</a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="cz-thanks-link">Mon compte</a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<?php
			$is_pickup  = cz_order_is_pickup( $order );
			$first_name = $order->get_billing_first_name();
			$title      = $first_name ? sprintf( 'Merci, %s, votre commande est confirmée.', $first_name ) : 'Merci, votre commande est confirmée.';

			if ( $is_pickup ) {
				$intro = 'Un e-mail récapitulatif vient de vous être envoyé. Nous préparons votre commande, vous la retirerez en boutique avec votre numéro.';
			} else {
				$intro = 'Un e-mail récapitulatif vient de vous être envoyé. Nous préparons votre commande, elle vous sera expédiée à l’adresse indiquée.';
			}
			if ( 'bacs' === $order->get_payment_method() && ! $order->is_paid() ) {
				$intro .= ' Elle sera préparée dès réception de votre virement.';
			}

			$shipping_cost = (float) $order->get_shipping_total();
			if ( 'incl' === get_option( 'woocommerce_tax_display_cart' ) ) {
				$shipping_cost += (float) $order->get_shipping_tax();
			}
			?>

			<div class="cz-thanks-head">
				<h1 class="cz-thanks-title"><?php echo esc_html( $title ); ?></h1>
				<p><?php echo esc_html( $intro ); ?></p>
			</div>

			<div class="cz-thanks">
				<div class="cz-thanks-main">

					<section class="cz-card cz-order-meta" aria-label="Récapitulatif de la commande">
						<dl>
							<div>
								<dt>Numéro de commande</dt>
								<dd>N°<?php echo esc_html( $order->get_order_number() ); ?></dd>
							</div>
							<div>
								<dt>Date</dt>
								<dd><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'j F Y' ) ); ?></dd>
							</div>
							<?php if ( $order->get_billing_email() ) : ?>
								<div>
									<dt>E-mail de confirmation</dt>
									<dd><?php echo esc_html( $order->get_billing_email() ); ?></dd>
								</div>
							<?php endif; ?>
						</dl>
					</section>

					<section class="cz-card cz-order-payment">
						<h2 class="cz-card-title">Informations de paiement</h2>
						<dl class="cz-order-rows">
							<div class="cz-row">
								<dt>Mode de paiement</dt>
								<dd><?php echo esc_html( $order->get_payment_method_title() ); ?></dd>
							</div>
							<div class="cz-row cz-row-gap">
								<dt>Statut</dt>
								<dd><?php echo esc_html( cz_order_payment_status( $order ) ); ?></dd>
							</div>
							<div class="cz-row">
								<dt>Sous-total</dt>
								<dd><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></dd>
							</div>
							<?php if ( $order->get_shipping_method() ) : ?>
								<div class="cz-row">
									<dt><?php echo esc_html( $order->get_shipping_method() ); ?></dt>
									<dd><?php echo wp_kses_post( wc_price( $shipping_cost, array( 'currency' => $order->get_currency() ) ) ); ?></dd>
								</div>
							<?php endif; ?>
							<?php foreach ( $order->get_fees() as $fee ) : ?>
								<div class="cz-row">
									<dt><?php echo esc_html( $fee->get_name() ); ?></dt>
									<dd><?php echo wp_kses_post( wc_price( $fee->get_total(), array( 'currency' => $order->get_currency() ) ) ); ?></dd>
								</div>
							<?php endforeach; ?>
							<div class="cz-row cz-row-total">
								<dt>Total</dt>
								<dd><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></dd>
							</div>
						</dl>

						<?php
						/* Coordonnées bancaires pour un virement (affichées par WooCommerce) */
						ob_start();
						do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
						$gateway_html = trim( ob_get_clean() );
						if ( $gateway_html && 'cod' !== $order->get_payment_method() ) {
							echo '<div class="cz-gateway-details">' . wp_kses_post( $gateway_html ) . '</div>';
						}
						?>
					</section>

					<section class="cz-card cz-order-items">
						<h2 class="cz-card-title">Ma commande</h2>
						<ul>
							<?php
							foreach ( $order->get_items() as $item ) :
								$product = $item->get_product();
								$name    = $item->get_name();
								if ( $product && $product->is_type( 'variation' ) ) {
									$parent = wc_get_product( $product->get_parent_id() );
									$name   = $parent ? $parent->get_name() : $name;
								}
								$options = cz_order_item_options( $item );
								$detail  = trim( $options . ' x' . $item->get_quantity() );
								$thumb   = $product ? $product->get_image( 'woocommerce_thumbnail' ) : wc_placeholder_img( 'woocommerce_thumbnail' );
								?>
								<li>
									<span class="cz-order-thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									<span class="cz-order-text">
										<span class="cz-order-name"><?php echo esc_html( $name ); ?></span>
										<span class="cz-order-options"><?php echo esc_html( $detail ); ?></span>
									</span>
									<span class="cz-order-price"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</section>
				</div>

				<div class="cz-thanks-side">
					<?php if ( $is_pickup ) : ?>
						<?php cz_pickup_instructions(); ?>
					<?php elseif ( $order->get_formatted_shipping_address() ) : ?>
						<section class="cz-card cz-pickup">
							<h2 class="cz-card-title">Adresse de livraison</h2>
							<address><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></address>
						</section>
					<?php endif; ?>

					<a class="cz-btn cz-btn-block" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l’accueil</a>
				</div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<div class="cz-thanks-head">
			<h1 class="cz-thanks-title">Merci, votre commande a bien été reçue.</h1>
		</div>
		<a class="cz-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l’accueil</a>

	<?php endif; ?>

</div>