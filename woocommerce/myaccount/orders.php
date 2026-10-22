<?php
/**
 * Mes commandes – Cluizel Paris
 * Surcharge de woocommerce/templates/myaccount/orders.php.
 * Maquette : une carte beige « Dernière commande » avec une carte blanche par article,
 * puis la liste des commandes précédentes.
 *
 * @package storefront-child
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );

$cz_orders = wc_get_orders(
	array(
		'customer_id' => get_current_user_id(),
		'limit'       => 11,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'status'      => array_keys( wc_get_order_statuses() ),
	)
);

if ( ! $cz_orders ) : ?>
	<section class="cz-card cz-account-orders">
		<h2 class="cz-card-title">Dernière commande</h2>
		<p>Vous n’avez pas encore passé de commande.</p>
		<a class="cz-btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Découvrir la carte</a>
	</section>
	<?php
else :
	$last = array_shift( $cz_orders );
	?>
	<section class="cz-card cz-order-items cz-account-orders">
		<div class="cz-account-orders-head">
			<h2 class="cz-card-title">Dernière commande</h2>
			<p class="cz-account-orders-meta">
				<a href="<?php echo esc_url( $last->get_view_order_url() ); ?>">N°<?php echo esc_html( $last->get_order_number() ); ?></a>
				· <?php echo esc_html( wc_format_datetime( $last->get_date_created(), 'd/m/Y' ) ); ?>
				· <?php echo esc_html( wc_get_order_status_name( $last->get_status() ) ); ?>
			</p>
			<?php if ( cz_order_slot_text( $last ) ) : ?>
				<p class="cz-account-orders-slot"><?php echo esc_html( cz_order_slot_text( $last ) ); ?></p>
			<?php endif; ?>
		</div>
		<ul>
			<?php
			foreach ( $last->get_items() as $item ) :
				$product = $item->get_product();
				$name    = $item->get_name();
				if ( $product && $product->is_type( 'variation' ) ) {
					$parent = wc_get_product( $product->get_parent_id() );
					$name   = $parent ? $parent->get_name() : $name;
				}
				$detail = trim( cz_order_item_options( $item ) . ' x' . $item->get_quantity() );
				$thumb  = $product ? $product->get_image( 'woocommerce_thumbnail' ) : wc_placeholder_img( 'woocommerce_thumbnail' );
				?>
				<li>
					<span class="cz-order-thumb"><?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<span class="cz-order-text">
						<span class="cz-order-name"><?php echo esc_html( $name ); ?></span>
						<span class="cz-order-options"><?php echo esc_html( $detail ); ?></span>
					</span>
					<span class="cz-order-price"><?php echo wp_kses_post( $last->get_formatted_line_subtotal( $item ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="cz-account-orders-total">
			<span>Total</span>
			<strong><?php echo wp_kses_post( $last->get_formatted_order_total() ); ?></strong>
		</p>
		<div class="cz-account-orders-actions">
			<?php cz_invoice_button( $last ); ?>
			<?php cz_cancel_order_button( $last ); ?>
		</div>
	</section>

	<?php if ( $cz_orders ) : ?>
		<section class="cz-card cz-account-history">
			<h2 class="cz-card-title">Commandes précédentes</h2>
			<ul>
				<?php foreach ( $cz_orders as $order ) : ?>
					<li>
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">N°<?php echo esc_html( $order->get_order_number() ); ?></a>
						<span><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'd/m/Y' ) ); ?></span>
						<span><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
						<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
						<span class="cz-account-history-invoice"><?php cz_invoice_button( $order, true ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
