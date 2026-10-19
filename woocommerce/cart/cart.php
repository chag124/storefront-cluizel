<?php
/**
 * Panier – Cluizel Paris
 * Surcharge de woocommerce/templates/cart/cart.php (thème enfant Storefront).
 * Les noms de champs, le nonce et les classes utilisées par le script
 * du panier WooCommerce (cart_item, product-remove, woocommerce-cart-form)
 * sont conservés pour que la mise à jour et la suppression fonctionnent.
 *
 * @package storefront-child
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

cz_cart_head();

do_action( 'woocommerce_before_cart' );
?>

<div class="cz-cart">
	<div class="cz-cart-main">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<ul class="cz-cart-items woocommerce-cart-form__contents">
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						continue;
					}

					/* Nom du produit parent pour une déclinaison (les options sont affichées dessous) */
					$parent       = $_product->is_type( 'variation' ) ? wc_get_product( $product_id ) : null;
					$base_name    = $parent ? $parent->get_name() : $_product->get_name();
					$product_name = apply_filters( 'woocommerce_cart_item_name', $base_name, $cart_item, $cart_item_key );
					$permalink    = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					$thumbnail    = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
					$options      = cz_cart_item_options( $cart_item );

					if ( $_product->is_sold_individually() ) {
						$min_quantity = 1;
						$max_quantity = 1;
					} else {
						$min_quantity = 0;
						$max_quantity = $_product->get_max_purchase_quantity();
					}
					?>
					<li class="cz-cart-item woocommerce-cart-form__cart-item cart_item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', '', $cart_item, $cart_item_key ) ); ?>">

						<div class="cz-item-thumb">
							<?php
							if ( $permalink ) {
								printf( '<a href="%s" tabindex="-1" aria-hidden="true">%s</a>', esc_url( $permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput
							} else {
								echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput
							}
							?>
						</div>

						<div class="cz-item-info">
							<p class="cz-item-name">
								<?php
								if ( $permalink ) {
									printf( '<a href="%s">%s</a>', esc_url( $permalink ), wp_kses_post( $product_name ) );
								} else {
									echo wp_kses_post( $product_name );
								}
								?>
							</p>

							<?php if ( $options ) : ?>
								<p class="cz-item-options"><?php echo esc_html( $options ); ?></p>
							<?php endif; ?>

							<?php if ( $permalink ) : ?>
								<a class="cz-item-edit" href="<?php echo esc_url( $permalink ); ?>">Modifier les options</a>
							<?php endif; ?>

							<?php
							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
							}
							?>
						</div>

						<div class="cz-item-qty product-quantity">
							<?php
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $max_quantity,
									'min_value'    => $min_quantity,
									'product_name' => $product_name,
								),
								$_product,
								false
							);
							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput
							?>
						</div>

						<div class="cz-item-price product-subtotal">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>

						<div class="cz-item-remove product-remove">
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="cz-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">Retirer</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr( sprintf( 'Retirer %s du panier', wp_strip_all_tags( $product_name ) ) ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								),
								$cart_item_key
							);
							?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<div class="cz-cart-actions">
				<button type="submit" class="button cz-update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
				<?php do_action( 'woocommerce_cart_actions' ); ?>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
		<?php cz_cart_suggestions(); ?>
	</div>

	<div class="cart-collaterals cz-cart-side">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>