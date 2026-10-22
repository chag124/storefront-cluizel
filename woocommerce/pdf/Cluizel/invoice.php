<?php
/**
 * Facture PDF – Cluizel Paris
 * Modèle personnalisé pour l'extension « PDF Invoices & Packing Slips for WooCommerce ».
 * Emplacement : storefront-child/woocommerce/pdf/Cluizel/invoice.php
 * À choisir dans WooCommerce › Factures PDF › Général › Choisir un modèle.
 * Repris du modèle « Simple » de l'extension, mis aux couleurs de Cluizel :
 * bandeau noir, logo, coordonnées de la boutique, encart beige, tableau à
 * en-tête noir, mentions légales en pied de page.
 *
 * @package storefront-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cz_order    = $this->order;
$cz_is_order = $cz_order instanceof WC_Order;
?>

<?php do_action( 'wpo_wcpdf_before_document', $this->get_type(), $this->order ); ?>

<div class="cz-topbar">Commander en ligne et récupérez sur place</div>

<table class="head container">
	<tr>
		<td class="header">
			<?php if ( $this->has_header_logo() ) : ?>
				<?php do_action( 'wpo_wcpdf_before_shop_logo', $this->get_type(), $this->order ); ?>
				<?php $this->header_logo(); ?>
				<?php do_action( 'wpo_wcpdf_after_shop_logo', $this->get_type(), $this->order ); ?>
			<?php else : ?>
				<div class="cz-logo-text">CLUIZEL</div>
				<div class="cz-logo-sub">PARIS</div>
			<?php endif; ?>
		</td>
		<td class="shop-info">
			<?php do_action( 'wpo_wcpdf_before_shop_name', $this->get_type(), $this->order ); ?>
			<div class="shop-name"><?php $this->shop_name(); ?></div>
			<?php do_action( 'wpo_wcpdf_after_shop_name', $this->get_type(), $this->order ); ?>
			<?php do_action( 'wpo_wcpdf_before_shop_address', $this->get_type(), $this->order ); ?>
			<div class="shop-address"><?php $this->shop_address(); ?></div>
			<?php do_action( 'wpo_wcpdf_after_shop_address', $this->get_type(), $this->order ); ?>
			<?php if ( method_exists( $this, 'get_shop_phone_number' ) && ! empty( $this->get_shop_phone_number() ) ) : ?>
				<div class="shop-phone-number"><?php $this->shop_phone_number(); ?></div>
			<?php endif; ?>
			<?php if ( method_exists( $this, 'get_shop_email_address' ) && ! empty( $this->get_shop_email_address() ) ) : ?>
				<div class="shop-email-address"><?php $this->shop_email_address(); ?></div>
			<?php endif; ?>
			<?php do_action( 'wpo_wcpdf_after_shop_email_address', $this->get_type(), $this->order ); ?>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_document_label', $this->get_type(), $this->order ); ?>
<h1 class="document-type-label"><?php $this->title(); ?></h1>
<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<h3>Facturé à</h3>
			<?php do_action( 'wpo_wcpdf_before_billing_address', $this->get_type(), $this->order ); ?>
			<p><?php $this->billing_address(); ?></p>
			<?php do_action( 'wpo_wcpdf_after_billing_address', $this->get_type(), $this->order ); ?>
			<?php if ( isset( $this->settings['display_email'] ) ) : ?>
				<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php endif; ?>
			<?php if ( isset( $this->settings['display_phone'] ) ) : ?>
				<div class="billing-phone"><?php $this->billing_phone(); ?></div>
			<?php endif; ?>
		</td>
		<td class="address shipping-address">
			<?php if ( $cz_is_order && function_exists( 'cz_order_is_pickup' ) && cz_order_is_pickup( $cz_order ) ) : ?>
				<h3>Retrait en boutique</h3>
				<?php $cz_pickup = function_exists( 'cz_pickup_settings' ) ? cz_pickup_settings() : array( 'address' => '' ); ?>
				<p><?php echo esc_html( $cz_pickup['address'] ); ?></p>
			<?php elseif ( $this->show_shipping_address() ) : ?>
				<h3>Livré à</h3>
				<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->get_type(), $this->order ); ?>
				<p><?php $this->shipping_address(); ?></p>
				<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->get_type(), $this->order ); ?>
			<?php endif; ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<tr class="invoice-number">
					<th><?php $this->number_title(); ?></th>
					<td><?php $this->number( $this->get_type() ); ?></td>
				</tr>
				<tr class="invoice-date">
					<th><?php $this->date_title(); ?></th>
					<td><?php $this->date( $this->get_type() ); ?></td>
				</tr>
				<tr class="order-number">
					<th><?php $this->order_number_title(); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php $this->order_date_title(); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<?php if ( $this->get_payment_method() ) : ?>
					<tr class="payment-method">
						<th><?php $this->payment_method_title(); ?></th>
						<td><?php $this->payment_method(); ?></td>
					</tr>
				<?php endif; ?>
				<?php if ( $cz_is_order && $cz_order->get_date_paid() ) : ?>
					<tr class="paid-date">
						<th>Payée le :</th>
						<td><?php echo esc_html( wc_format_datetime( $cz_order->get_date_paid(), 'd/m/Y' ) ); ?></td>
					</tr>
				<?php endif; ?>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); ?>
			</table>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<?php foreach ( cz_pdf_table_headers( $this ) as $column_class => $column_title ) : ?>
				<th class="<?php echo esc_attr( $column_class ); ?>"><?php echo esc_html( $column_title ); ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $this->get_order_items() as $item_id => $item ) : ?>
			<tr class="<?php echo esc_attr( $item['row_class'] ); ?>">
				<td class="product">
					<p class="item-name"><?php echo esc_html( $item['name'] ); ?></p>
					<?php do_action( 'wpo_wcpdf_before_item_meta', $this->get_type(), $item, $this->order ); ?>
					<div class="item-meta">
						<?php if ( ! empty( $item['sku'] ) ) : ?>
							<p class="sku"><span class="label"><?php $this->sku_title(); ?></span> <?php echo esc_html( $item['sku'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $item['meta'] ) ) : ?>
							<?php echo wp_kses_post( $item['meta'] ); ?>
						<?php endif; ?>
					</div>
					<?php do_action( 'wpo_wcpdf_after_item_meta', $this->get_type(), $item, $this->order ); ?>
				</td>
				<td class="quantity"><?php echo esc_html( $item['quantity'] ); ?></td>
				<td class="price"><?php echo cz_pdf_price( $item['order_price'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<table class="notes-totals">
	<tbody>
		<tr class="no-borders">
			<td class="no-borders notes-cell">
				<?php do_action( 'wpo_wcpdf_before_document_notes', $this->get_type(), $this->order ); ?>
				<?php if ( $this->get_document_notes() ) : ?>
					<div class="document-notes">
						<h3><?php $this->notes_title(); ?></h3>
						<?php $this->document_notes(); ?>
					</div>
				<?php endif; ?>
				<?php do_action( 'wpo_wcpdf_after_document_notes', $this->get_type(), $this->order ); ?>
				<div class="cz-thanks">Merci pour votre commande !<br>À très bientôt chez Cluizel.</div>
			</td>
			<td class="no-borders totals-cell">
				<table class="totals">
					<tfoot>
						<?php foreach ( $this->get_woocommerce_totals() as $key => $total ) : ?>
							<tr class="<?php echo esc_attr( $key ); ?>">
								<th class="description"><?php echo esc_html( $total['label'] ); ?></th>
								<td class="price"><span class="totals-price"><?php echo cz_pdf_price( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></td>
							</tr>
						<?php endforeach; ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<div class="bottom-spacer"></div>

<?php if ( $this->get_footer() ) : ?>
	<htmlpagefooter name="docFooter">
		<div id="footer">
			<?php $this->footer(); ?>
		</div>
	</htmlpagefooter>
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>
