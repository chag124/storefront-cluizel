<?php
/**
 * Thème enfant Storefront – Cluizel Paris (SAé 3.01)
 * Itération 2 : apparence générale, grille Boutique, fiche produit simple.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. Polices Google (Montserrat pour les titres, Barlow pour le texte,
 *    Poppins pour les noms et prix des cartes produit)
 * ====================================================================== */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'cz-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500&family=Montserrat:wght@300;400;600&family=Poppins:wght@500;600&display=swap',
		array(),
		null
	);
}, 5 );

/* =========================================================================
 * 2. Couleurs et mise en page de Storefront fixées dans le code
 *    (identiques en Dev et en Prod, sans passer par le Customizer)
 * ====================================================================== */
function cz_theme_mods() {
	$mods = array(
		'storefront_heading_color'               => '#000000',
		'storefront_text_color'                  => '#000000',
		'storefront_accent_color'                => '#000000',
		'storefront_header_background_color'     => '#ffffff',
		'storefront_header_text_color'           => '#000000',
		'storefront_header_link_color'           => '#000000',
		'storefront_footer_background_color'     => '#000000',
		'storefront_footer_heading_color'        => '#ffffff',
		'storefront_footer_text_color'           => '#ffffff',
		'storefront_footer_link_color'           => '#ffffff',
		'storefront_button_background_color'     => '#000000',
		'storefront_button_text_color'           => '#ffffff',
		'storefront_button_alt_background_color' => '#000000',
		'storefront_button_alt_text_color'       => '#ffffff',
		'storefront_layout'                      => 'left',
		'background_color'                       => 'ffffff',
	);
	foreach ( $mods as $mod => $value ) {
		add_filter( 'theme_mod_' . $mod, function () use ( $value ) {
			return $value;
		} );
	}
}
cz_theme_mods();

/* =========================================================================
 * 3. On retire les éléments de Storefront remplacés par la maquette
 *    (sur "init" : le thème parent est chargé après le thème enfant)
 * ====================================================================== */
add_action( 'init', function () {
	// En-tête Storefront.
	remove_action( 'storefront_header', 'storefront_social_icons', 10 );
	remove_action( 'storefront_header', 'storefront_site_branding', 20 );
	remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
	remove_action( 'storefront_header', 'storefront_product_search', 40 );
	remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
	remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
	remove_action( 'storefront_header', 'storefront_header_cart', 60 );
	remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
	remove_action( 'storefront_before_content', 'storefront_header_widget_region', 10 );

	// Pied de page Storefront.
	remove_action( 'storefront_footer', 'storefront_footer_widgets', 10 );
	remove_action( 'storefront_footer', 'storefront_credit', 20 );
	remove_action( 'storefront_footer', 'storefront_handheld_footer_bar', 999 );

	// Barres latérales par défaut (remplacées par celle de la carte).
	remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Page Boutique : tri, compteur et pagination du haut.
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );
	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
	remove_action( 'woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10 );

	// Cartes produit.
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Fiche produit.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );
	remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );
	remove_action( 'woocommerce_single_product_summary', 'storefront_edit_post_link', 60 );
}, 20 );

/* Galerie produit sans loupe ni zoom (image fixe comme sur la maquette) */
add_action( 'after_setup_theme', function () {
	remove_theme_support( 'wc-product-gallery-zoom' );
	remove_theme_support( 'wc-product-gallery-lightbox' );
}, 20 );

/* Prix collé au symbole : « 7,60€ » */
add_filter( 'woocommerce_price_format', fn() => '%2$s%1$s' );

/* =========================================================================
 * 4. En-tête : bandeau, logo, menu, Mon compte, Mon panier
 * ====================================================================== */
add_action( 'storefront_before_header', function () {
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) ) {
		return;
	}
	echo '<div class="cz-topbar">Commander en ligne et récupérez sur place</div>';
} );

add_action( 'storefront_header', 'cz_header', 20 );
function cz_header() {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<div class="cz-header">
		<a class="cz-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUIZEL</a>

		<nav class="cz-nav" aria-label="Navigation principale">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'cz-menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<div class="cz-header-actions">
			<a class="cz-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a>
			<a class="cz-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				Mon panier<?php echo $count ? ' <span class="cz-cart-count">(' . (int) $count . ')</span>' : ''; ?>
			</a>
		</div>
	</div>
	<?php
}

/* Fil d'Ariane : « Accueil / La carte / … » */
add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
	$defaults['delimiter']   = ' / ';
	$defaults['home']        = 'Accueil';
	$defaults['wrap_before'] = '<div class="storefront-breadcrumb"><div class="col-full"><nav class="woocommerce-breadcrumb" aria-label="Fil d\'Ariane">';
	$defaults['wrap_after']  = '</nav></div></div>';
	return $defaults;
}, 30 );

/* =========================================================================
 * 5. Pied de page
 * ====================================================================== */
add_action( 'storefront_footer', 'cz_footer', 10 );
function cz_footer() {
	$cgv_id  = function_exists( 'wc_terms_and_conditions_page_id' ) ? wc_terms_and_conditions_page_id() : 0;
	$cgv     = $cgv_id ? get_permalink( $cgv_id ) : '#';
	$mentions = get_privacy_policy_url() ? get_privacy_policy_url() : '#';
	?>
	<div class="cz-footer">
		<a class="cz-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUIZEL</a>
		<ul class="cz-footer-links">
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">La carte</a></li>
			<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Mon panier</a></li>
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a></li>
		</ul>
		<ul class="cz-footer-links">
			<li><a href="<?php echo esc_url( $mentions ); ?>">Mentions légales</a></li>
			<li><a href="<?php echo esc_url( $cgv ); ?>">Conditions générales de vente</a></li>
		</ul>
	</div>
	<?php
}

/* =========================================================================
 * 6. Mise en page : barre latérale uniquement sur la carte (boutique et
 *    catégories), pleine largeur partout ailleurs
 * ====================================================================== */
function cz_is_catalogue() {
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
}

add_filter( 'body_class', function ( $classes ) {
	if ( cz_is_catalogue() ) {
		$classes = array_diff( $classes, array( 'storefront-full-width-content' ) );
		$classes[] = 'cz-catalogue';
	} else {
		$classes[] = 'storefront-full-width-content';
	}
	return $classes;
}, 20 );

/* Titre de page au-dessus de la barre latérale et de la grille */
add_action( 'storefront_before_content', function () {
	if ( ! cz_is_catalogue() ) {
		return;
	}
	echo '<div class="col-full"><h1 class="cz-page-title">' . esc_html( woocommerce_page_title( false ) ) . '</h1></div>';
}, 20 );

/* =========================================================================
 * 7. Page « La carte » : 3 colonnes, 9 produits, compteur, filtres
 * ====================================================================== */
add_filter( 'loop_shop_columns', fn() => 3, 20 );
add_filter( 'storefront_loop_columns', fn() => 3 );
add_filter( 'loop_shop_per_page', fn() => 9, 20 );
add_filter( 'storefront_products_per_page', fn() => 9 );

add_action( 'woocommerce_before_shop_loop', function () {
	$total = (int) wc_get_loop_prop( 'total' );
	printf( '<p class="cz-result-count">%d %s</p>', $total, $total > 1 ? 'produits' : 'produit' );
}, 20 );

/* Fourchette de prix arrondie aux 5 € (ex. « 5€ à 50€ ») */
function cz_price_range() {
	global $wpdb;
	$row = $wpdb->get_row(
		"SELECT MIN(l.min_price) AS min_price, MAX(l.max_price) AS max_price
		 FROM {$wpdb->wc_product_meta_lookup} l
		 INNER JOIN {$wpdb->posts} p ON p.ID = l.product_id
		 WHERE p.post_type = 'product' AND p.post_status = 'publish'"
	);
	if ( ! $row || null === $row->min_price ) {
		return '';
	}
	$min = floor( $row->min_price / 5 ) * 5;
	$max = ceil( $row->max_price / 5 ) * 5;
	return sprintf( '%d€ à %d€', $min, $max );
}

add_action( 'woocommerce_sidebar', 'cz_catalogue_sidebar', 10 );
function cz_catalogue_sidebar() {
	if ( ! cz_is_catalogue() ) {
		return;
	}
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	$current = is_product_category() ? get_queried_object_id() : 0;
	?>
	<aside id="secondary" class="widget-area cz-sidebar" aria-label="Filtres">
		<p class="cz-sidebar-title cz-sidebar-title--line">Catégories</p>
		<ul class="cz-cat-list">
			<?php foreach ( $categories as $cat ) : ?>
				<li class="<?php echo $cat->term_id === $current ? 'is-active' : ''; ?>">
					<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
						<span><?php echo esc_html( $cat->name ); ?></span>
						<span><?php echo (int) $cat->count; ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php $range = cz_price_range(); ?>
		<?php if ( $range ) : ?>
			<p class="cz-sidebar-title">Prix</p>
			<span class="cz-pill"><?php echo esc_html( $range ); ?></span>
		<?php endif; ?>
	</aside>
	<?php
}

/* =========================================================================
 * 8. Carte produit : image sur fond blanc, catégorie, nom, prix, « Je choisis »
 * ====================================================================== */
add_action( 'woocommerce_before_shop_loop_item_title', function () {
	echo '<div class="cz-card-media">';
}, 9 );
add_action( 'woocommerce_before_shop_loop_item_title', function () {
	echo '</div>';
}, 11 );

add_action( 'woocommerce_shop_loop_item_title', function () {
	global $product;
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		echo '<span class="cz-card-cat">' . esc_html( $terms[0]->name ) . '</span>';
	}
}, 5 );

add_action( 'woocommerce_after_shop_loop_item', function () {
	global $product;
	echo '<div class="cz-card-footer">';
	echo '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
	echo '<a class="cz-btn-outline" href="' . esc_url( $product->get_permalink() ) . '">Je choisis</a>';
	echo '</div>';
}, 10 );

/* =========================================================================
 * 9. Fiche produit
 * ====================================================================== */

/* Ordre du résumé : titre, description courte, prix, ajout au panier */
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

/* Champs Secure Custom Fields : ingrédients, conservation, allergènes */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_cz_infos_produit',
			'title'    => 'Informations produit',
			'fields'   => array(
				array(
					'key'   => 'field_cz_ingredients',
					'label' => 'Ingrédients',
					'name'  => 'cz_ingredients',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'   => 'field_cz_conservation',
					'label' => 'Conservation',
					'name'  => 'cz_conservation',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_cz_allergenes',
					'label' => 'Allergènes',
					'name'  => 'cz_allergenes',
					'type'  => 'textarea',
					'rows'  => 2,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'product',
					),
				),
			),
		)
	);
} );

function cz_product_field( $name, $product_id ) {
	$value = function_exists( 'get_field' ) ? get_field( $name, $product_id ) : '';
	return $value ? $value : get_post_meta( $product_id, $name, true );
}

/* Bloc beige sous l'image */
add_action( 'woocommerce_before_single_product_summary', function () {
	global $product;
	$fields = array(
		'cz_ingredients'  => 'Ingrédients',
		'cz_conservation' => 'Conservation',
		'cz_allergenes'   => 'Allergènes',
	);
	$html = '';
	foreach ( $fields as $name => $label ) {
		$value = cz_product_field( $name, $product->get_id() );
		if ( $value ) {
			$html .= '<dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $value ) . '</dd>';
		}
	}
	if ( $html ) {
		echo '<dl class="cz-product-infos">' . $html . '</dl>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
}, 30 );

/* =========================================================================
 * 10. Options de la fiche produit : Format, Supplément, Emballage
 *     + choix de la boutique de retrait (enregistrés dans le panier
 *     et dans la commande). Sans effet sur le prix pour l'instant.
 * ====================================================================== */

/* Boutiques de retrait proposées */
function cz_boutiques() {
	return array(
		'Manufacture Cluizel – 7 avenue Michel Cluizel, 27240 Mesnils-sur-Iton',
	);
}

/* Listes par défaut selon la catégorie du produit */
function cz_default_options( $product ) {
	$slugs = wp_list_pluck( get_the_terms( $product->get_id(), 'product_cat' ) ?: array(), 'slug' );

	$format = array( 'Format unique' );
	if ( in_array( 'tablettes', $slugs, true ) ) {
		$format = array( 'Tablette de 70 g', 'Lot de 2 tablettes de 70 g' );
	} elseif ( in_array( 'boites-et-coffrets', $slugs, true ) ) {
		$format = array( 'Coffret', 'Grand coffret' );
	} elseif ( in_array( 'gourmandises', $slugs, true ) ) {
		$format = array( 'Étui de 4', 'Étui de 8' );
	}

	return array(
		'format'     => $format,
		'supplement' => array( 'Sans supplément', 'Carte message', 'Ruban' ),
		'emballage'  => array( 'Papier cadeau', 'Étui cadeau', 'Sans emballage' ),
	);
}

/* Libellés et noms des champs */
function cz_option_fields() {
	return array(
		'format'     => 'Format',
		'supplement' => 'Supplément',
		'emballage'  => 'Emballage',
	);
}

/* Listes d'un produit : champs SCF (une option par ligne) sinon valeurs par défaut */
function cz_product_options( $product ) {
	$defaults = cz_default_options( $product );
	$options  = array();
	foreach ( cz_option_fields() as $key => $label ) {
		$raw   = cz_product_field( 'cz_options_' . $key, $product->get_id() );
		$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) );
		$options[ $key ] = $lines ? array_values( $lines ) : $defaults[ $key ];
	}
	return $options;
}

/* Champs SCF pour personnaliser les listes d'un produit */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	$fields = array();
	foreach ( cz_option_fields() as $key => $label ) {
		$fields[] = array(
			'key'          => 'field_cz_options_' . $key,
			'label'        => $label,
			'name'         => 'cz_options_' . $key,
			'type'         => 'textarea',
			'rows'         => 3,
			'instructions' => 'Une option par ligne. Laisser vide pour les choix par défaut.',
		);
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_cz_options_produit',
			'title'    => 'Options proposées (Format, Supplément, Emballage)',
			'fields'   => $fields,
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'product',
					),
				),
			),
		)
	);
} );

/* Affichage des trois listes dans le formulaire, avant la quantité */
add_action( 'woocommerce_before_add_to_cart_button', function () {
	global $product;
	if ( ! $product || ! $product->is_type( 'simple' ) ) {
		return;
	}
	$options = cz_product_options( $product );
	echo '<div class="cz-options">';
	foreach ( cz_option_fields() as $key => $label ) {
		$id = 'cz-option-' . $key;
		echo '<div class="cz-option-row">';
		echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
		echo '<select id="' . esc_attr( $id ) . '" name="cz_' . esc_attr( $key ) . '" class="cz-select">';
		foreach ( $options[ $key ] as $value ) {
			echo '<option value="' . esc_attr( $value ) . '">' . esc_html( $value ) . '</option>';
		}
		echo '</select></div>';
	}
	echo '</div>';
} );

/* Cadre « Retrait en boutique » dans le formulaire, après le bouton */
add_action( 'woocommerce_after_add_to_cart_button', function () {
	global $product;
	if ( ! $product || ! $product->is_type( array( 'simple', 'variable' ) ) ) {
		return;
	}
	echo '<div class="cz-retrait">';
	echo '<label class="cz-retrait-title" for="cz-boutique">Retrait en boutique</label>';
	echo '<select id="cz-boutique" name="cz_boutique" class="cz-select">';
	echo '<option value="">Choisir ma boutique</option>';
	foreach ( cz_boutiques() as $boutique ) {
		echo '<option value="' . esc_attr( $boutique ) . '">' . esc_html( $boutique ) . '</option>';
	}
	echo '</select></div>';
} );

/* Vérification : la boutique de retrait est obligatoire */
add_filter( 'woocommerce_add_to_cart_validation', function ( $passed, $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product || ! $product->is_type( array( 'simple', 'variable' ) ) || ! isset( $_POST['cz_boutique'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return $passed;
	}
	$boutique = sanitize_text_field( wp_unslash( $_POST['cz_boutique'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! in_array( $boutique, cz_boutiques(), true ) ) {
		wc_add_notice( 'Choisissez votre boutique de retrait avant d\'ajouter le produit au panier.', 'error' );
		return false;
	}
	return $passed;
}, 10, 2 );

/* Enregistrement des choix avec l'article du panier */
add_filter( 'woocommerce_add_cart_item_data', function ( $data, $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product || ! $product->is_type( array( 'simple', 'variable' ) ) ) {
		return $data;
	}
	$options = $product->is_type( 'simple' ) ? cz_product_options( $product ) : array();
	foreach ( $options ? cz_option_fields() : array() as $key => $label ) {
		if ( isset( $_POST[ 'cz_' . $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$value = sanitize_text_field( wp_unslash( $_POST[ 'cz_' . $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( in_array( $value, $options[ $key ], true ) ) {
				$data['cz_options'][ $label ] = $value;
			}
		}
	}
	if ( isset( $_POST['cz_boutique'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$boutique = sanitize_text_field( wp_unslash( $_POST['cz_boutique'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( in_array( $boutique, cz_boutiques(), true ) ) {
			$data['cz_options']['Retrait'] = $boutique;
		}
	}
	return $data;
}, 10, 2 );

/* Affichage des choix dans le panier et la commande */
add_filter( 'woocommerce_get_item_data', function ( $item_data, $cart_item ) {
	if ( ! empty( $cart_item['cz_options'] ) ) {
		foreach ( $cart_item['cz_options'] as $label => $value ) {
			$item_data[] = array(
				'key'   => $label,
				'value' => $value,
			);
		}
	}
	return $item_data;
}, 10, 2 );

/* Enregistrement des choix dans la commande */
add_action( 'woocommerce_checkout_create_order_line_item', function ( $item, $cart_item_key, $values ) {
	if ( ! empty( $values['cz_options'] ) ) {
		foreach ( $values['cz_options'] as $label => $value ) {
			$item->add_meta_data( $label, $value, true );
		}
	}
}, 10, 3 );

/* Boutons - / + autour de la quantité */
add_action( 'woocommerce_before_quantity_input_field', function () {
	echo '<button type="button" class="cz-qty-btn" data-step="-1" aria-label="Diminuer la quantité">-</button>';
} );
add_action( 'woocommerce_after_quantity_input_field', function () {
	echo '<button type="button" class="cz-qty-btn" data-step="1" aria-label="Augmenter la quantité">+</button>';
} );
add_action( 'wp_footer', function () {
	?>
	<script>
	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.cz-qty-btn');
		if (!btn) return;
		var input = btn.parentNode.querySelector('input.qty');
		if (!input) return;
		var step = parseFloat(input.step) || 1;
		var min = input.min !== '' ? parseFloat(input.min) : 0;
		var max = input.max !== '' ? parseFloat(input.max) : Infinity;
		var value = (parseFloat(input.value) || 0) + (btn.dataset.step > 0 ? step : -step);
		input.value = Math.min(Math.max(value, min), max);
		input.dispatchEvent(new Event('change', { bubbles: true }));
	});
	</script>
	<?php
} );

/* « Pour compléter votre commande » : 3 produits liés */
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;
	return $args;
}, 20 );
add_filter( 'woocommerce_product_related_products_heading', fn() => 'Pour compléter votre commande' );

/* Toujours 3 suggestions : on complète avec des produits d'autres catégories */
add_filter( 'woocommerce_related_products', function ( $ids, $product_id ) {
	if ( count( $ids ) < 3 ) {
		$extra = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 3 - count( $ids ),
				'exclude' => array_merge( $ids, array( $product_id ) ),
				'orderby' => 'rand',
				'return'  => 'ids',
			)
		);
		$ids = array_merge( $ids, $extra );
	}
	return $ids;
}, 10, 2 );

/* =========================================================================
 * 11. Produits variables (itération 3)
 * ====================================================================== */

/* Listes de déclinaisons : même apparence que la maquette */
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', function ( $args ) {
	$args['class'] = trim( ( $args['class'] ?? '' ) . ' cz-select' );
	return $args;
} );

/* Toujours renvoyer le prix de la déclinaison choisie, même si deux prix sont égaux */
add_filter( 'woocommerce_show_variation_price', '__return_true' );

/* Prix d'un produit variable : le plus bas (carte et fiche avant choix) */
add_filter( 'woocommerce_variable_price_html', function ( $html, $product ) {
	return wc_price( $product->get_variation_price( 'min', true ) );
}, 10, 2 );

/* Niveaux de stock : Disponible / Stock faible / Épuisé */
function cz_low_stock_amount( $product ) {
	if ( function_exists( 'wc_get_low_stock_amount' ) ) {
		return (int) wc_get_low_stock_amount( $product );
	}
	return (int) get_option( 'woocommerce_notify_low_stock_amount', 2 );
}

add_filter( 'woocommerce_get_availability', function ( $availability, $product ) {
	if ( ! $product->is_in_stock() ) {
		$availability['availability'] = 'Épuisé';
		$availability['class']        = 'out-of-stock';
		return $availability;
	}
	$qty = $product->get_stock_quantity();
	if ( $product->managing_stock() && null !== $qty && $qty <= cz_low_stock_amount( $product ) ) {
		$availability['availability'] = sprintf( 'Stock faible – plus que %d', $qty );
		$availability['class']        = 'in-stock low-stock';
		return $availability;
	}
	$availability['availability'] = 'Disponible';
	$availability['class']        = 'in-stock';
	return $availability;
}, 20, 2 );

/* Le prix en haut de la fiche suit la déclinaison sélectionnée */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	?>
	<script>
	(function ($) {
		if (!$) return;
		$(function () {
			var $price = $('.summary > p.price').first();
			var original = $price.html();
			$('form.variations_form')
				.on('found_variation', function (event, variation) {
					if (variation.price_html) {
						var inner = $('<div>').html(variation.price_html).find('.price').html();
						$price.html(inner || variation.price_html);
					}
				})
				.on('reset_data', function () {
					$price.html(original);
				});
		});
	})(window.jQuery);
	</script>
	<?php
}, 30 );