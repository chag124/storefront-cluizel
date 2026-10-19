<?php
/**
 * Mes informations – Cluizel Paris
 * Surcharge de woocommerce/templates/myaccount/form-edit-account.php.
 * Maquette : une carte beige en 3 parties (coordonnées, mot de passe, suppression).
 * Les noms de champs et le nonce WooCommerce sont conservés.
 *
 * @package storefront-child
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

$cz_phone = get_user_meta( $user->ID, 'billing_phone', true );

do_action( 'woocommerce_before_edit_account_form' );
?>

<section class="cz-card cz-infos">
	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="cz-infos-part">
			<h2 class="cz-card-title">Mes coordonnées</h2>
			<div class="cz-infos-grid">
				<p class="woocommerce-form-row form-row">
					<label for="account_last_name">Nom</label>
					<input type="text" class="woocommerce-Input input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" required>
				</p>
				<p class="woocommerce-form-row form-row">
					<label for="account_first_name">Prénom</label>
					<input type="text" class="woocommerce-Input input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" required>
				</p>
				<p class="woocommerce-form-row form-row">
					<label for="account_email">E-mail</label>
					<input type="email" class="woocommerce-Input input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" required>
				</p>
				<p class="woocommerce-form-row form-row">
					<label for="account_phone">Téléphone</label>
					<input type="tel" class="woocommerce-Input input-text" name="account_phone" id="account_phone" autocomplete="tel" value="<?php echo esc_attr( $cz_phone ); ?>">
				</p>
			</div>
			<input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>">
			<button type="submit" class="cz-btn" name="save_account_details" value="1">Enregistrer mes coordonnées</button>
		</div>

		<div class="cz-infos-part">
			<h2 class="cz-card-title">Changer de mot de passe</h2>
			<div class="cz-infos-grid cz-infos-grid-3">
				<p class="woocommerce-form-row form-row">
					<label for="password_current">Mot de passe actuel</label>
					<input type="password" class="woocommerce-Input input-text" name="password_current" id="password_current" autocomplete="current-password">
				</p>
				<p class="woocommerce-form-row form-row">
					<label for="password_1">Nouveau mot de passe</label>
					<input type="password" class="woocommerce-Input input-text" name="password_1" id="password_1" autocomplete="new-password">
				</p>
				<p class="woocommerce-form-row form-row">
					<label for="password_2">Confirmer</label>
					<input type="password" class="woocommerce-Input input-text" name="password_2" id="password_2" autocomplete="new-password">
				</p>
			</div>
			<button type="submit" class="cz-btn" name="save_account_details" value="1">Changer mon mot de passe</button>
		</div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<input type="hidden" name="action" value="save_account_details">

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>

	<form class="cz-infos-part cz-delete-account" action="" method="post">
		<h2 class="cz-card-title">Supprimer mon compte</h2>
		<p>Vos informations personnelles seront effacées. Vos commandes en cours doivent être retirées ou annulées avant.</p>
		<?php wp_nonce_field( 'cz_delete_account', 'cz-delete-account-nonce' ); ?>
		<button type="submit" class="cz-btn" name="cz_delete_account" value="1" onclick="return confirm('Supprimer définitivement votre compte Cluizel ?');">Supprimer mon compte</button>
	</form>
</section>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>