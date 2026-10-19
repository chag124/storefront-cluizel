<?php
/**
 * Tableau de bord du compte – Cluizel Paris
 * Surcharge de woocommerce/templates/myaccount/dashboard.php.
 * Le texte d'accueil par défaut est remplacé par le contenu ajouté
 * dans functions.php (salutation, raccourcis, dernière commande).
 *
 * @package storefront-child
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_account_dashboard' );

/* Anciennes actions conservées pour la compatibilité des extensions */
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );