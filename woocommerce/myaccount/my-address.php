<?php
/**
 * Mes adresses – Cluizel Paris
 * Surcharge de woocommerce/templates/myaccount/my-address.php.
 * Maquette : un conteneur beige avec deux cartes blanches centrées.
 *
 * @package storefront-child
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

$get_addresses = apply_filters(
	'woocommerce_my_account_get_addresses',
	array(
		'billing'  => 'Adresse de facturation',
		'shipping' => 'Adresse de livraison',
	),
	$customer_id
);
?>

<section class="cz-card cz-addresses woocommerce-Addresses">
	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
		<?php $line = cz_address_line( $customer_id, $name ); ?>
		<div class="cz-address woocommerce-Address">
			<h2 class="cz-address-title"><?php echo esc_html( $address_title ); ?></h2>
			<p class="cz-address-line"><?php echo $line ? esc_html( $line ) : 'Aucune adresse enregistrée'; ?></p>
			<a class="cz-address-edit" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>">
				<?php echo $line ? 'Modifier' : 'Ajouter'; ?><span class="screen-reader-text"> l’<?php echo esc_html( lcfirst( $address_title ) ); ?></span>
			</a>
		</div>
	<?php endforeach; ?>
</section>