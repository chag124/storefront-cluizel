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

/* style.css rechargé à chaque modification : sa version est la date du fichier
   (sinon le navigateur et le serveur gardent l'ancienne feuille « ver=2.0.0 ») */
add_filter( 'style_loader_src', function ( $src, $handle ) {
	if ( 'storefront-child-style' === $handle ) {
		$file = get_stylesheet_directory() . '/style.css';
		if ( file_exists( $file ) ) {
			$src = add_query_arg( 'ver', filemtime( $file ), remove_query_arg( 'ver', $src ) );
		}
	}
	return $src;
}, 10, 2 );

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
	remove_action( 'storefront_page', 'storefront_edit_post_link', 30 );
	remove_action( 'storefront_single_post_bottom', 'storefront_edit_post_link', 5 );
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
	if ( function_exists( 'is_cart' ) && ( is_cart() || ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) ) ) {
		return;
	}
	echo '<div class="cz-topbar">Commander en ligne et récupérez sur place</div>';
} );

add_action( 'storefront_header', 'cz_header', 20 );
function cz_header() {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<div class="cz-header">
		<button type="button" class="cz-burger" aria-controls="cz-nav" aria-expanded="false" aria-label="Ouvrir le menu">
			<span></span><span></span><span></span>
		</button>

		<a class="cz-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUIZEL</a>

		<nav class="cz-nav" id="cz-nav" aria-label="Navigation principale">
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
			<a class="cz-nav-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a>
		</nav>

		<div class="cz-header-actions">
			<a class="cz-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a>
			<a class="cz-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<span class="cz-cart-label">Mon panier<?php echo $count ? ' <span class="cz-cart-count">(' . (int) $count . ')</span>' : ''; ?></span>
				<?php if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) : ?>
					<span class="cz-back-label">Retour au panier</span>
				<?php endif; ?>
			</a>
		</div>
	</div>
	<?php
}

/* Menu burger (mobile) : ouverture / fermeture */
add_action( 'wp_footer', function () {
	?>
	<script>
	( function () {
		var header = document.querySelector( '.cz-header' );
		var button = document.querySelector( '.cz-burger' );
		if ( ! header || ! button ) {
			return;
		}
		function toggle( open ) {
			header.classList.toggle( 'is-open', open );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			button.setAttribute( 'aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu' );
		}
		button.addEventListener( 'click', function () {
			toggle( ! header.classList.contains( 'is-open' ) );
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key && header.classList.contains( 'is-open' ) ) {
				toggle( false );
				button.focus();
			}
		} );
		window.matchMedia( '(min-width: 768px)' ).addEventListener( 'change', function ( mq ) {
			if ( mq.matches ) {
				toggle( false );
			}
		} );
	} )();
	</script>
	<?php
} );

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
	$cgv      = cz_legal_url( 'cgv' );
	$mentions = cz_legal_url( 'mentions' );
	?>
	<div class="cz-footer">
		<a class="cz-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">CLUIZEL</a>
		<ul class="cz-footer-links">
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">La carte</a></li>
			<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Mon panier</a></li>
			<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Mon compte</a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'cz_equipe' ) ); ?>">Notre équipe</a></li>
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
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() || ( is_search() && 'product' === get_query_var( 'post_type' ) ) );
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

/* Catégories de la carte (sans « Non classé ») */
function cz_catalogue_categories() {
	return get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'orderby'    => 'menu_order', /* ordre choisi par glisser-déposer dans Produits › Catégories */
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
}

/* Compteur + bouton « Filtrer » (mobile ; sur ordinateur les filtres sont dans la barre latérale) */
function cz_shop_bar( $total ) {
	$active = count( cz_active_filters() );
	echo '<div class="cz-shop-bar">';
	printf( '<p class="cz-result-count">%d %s</p>', (int) $total, $total > 1 ? 'produits' : 'produit' );
	echo '<details class="cz-filters-mobile"><summary class="cz-pill">Filtrer' . ( $active ? ' (' . (int) $active . ')' : '' ) . '</summary>';
	cz_filters_form( 'm' );
	echo '</details></div>';
	cz_active_filters_html();
}

add_action( 'woocommerce_before_shop_loop', function () {
	cz_shop_bar( (int) wc_get_loop_prop( 'total' ) );
}, 20 );

/* Aucun résultat : on garde les filtres et on propose de les effacer */
add_action( 'woocommerce_no_products_found', function () {
	if ( ! cz_active_filters() ) {
		return;
	}
	cz_shop_bar( 0 );
	echo '<p class="cz-no-results">Aucun produit ne correspond à votre recherche. <a href="' . esc_url( cz_filter_base_url() ) . '">Effacer les filtres</a></p>';
}, 5 );

/* Mobile : catégories en ligne sous le titre */
add_action( 'storefront_before_content', function () {
	if ( ! cz_is_catalogue() ) {
		return;
	}
	$is_collection = is_tax( 'cz_collection' );
	$current       = ( is_product_category() || $is_collection ) ? get_queried_object_id() : 0;
	$terms         = $is_collection ? cz_collections() : cz_catalogue_categories();
	echo '<div class="col-full"><nav class="cz-cat-bar" aria-label="' . ( $is_collection ? 'Collections' : 'Catégories' ) . '"><ul>';
	foreach ( $terms as $cat ) {
		$active = $cat->term_id === $current;
		printf(
			'<li%s><a href="%s"%s>%s</a></li>',
			$active ? ' class="is-active"' : '',
			esc_url( get_term_link( $cat ) ),
			$active ? ' aria-current="page"' : '',
			esc_html( $cat->name )
		);
	}
	echo '</ul></nav></div>';
}, 21 );

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
	$categories = cz_catalogue_categories();
	$current    = is_product_category() ? get_queried_object_id() : 0;
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
		<?php
		$collections = cz_collections();
		$current_col = is_tax( 'cz_collection' ) ? get_queried_object_id() : 0;
		if ( $collections ) :
			?>
			<p class="cz-sidebar-title cz-sidebar-title--line">Collections</p>
			<ul class="cz-cat-list">
				<?php foreach ( $collections as $col ) : ?>
					<li class="<?php echo $col->term_id === $current_col ? 'is-active' : ''; ?>">
						<a href="<?php echo esc_url( get_term_link( $col ) ); ?>">
							<span><?php echo esc_html( $col->name ); ?></span>
							<span><?php echo (int) $col->count; ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<p class="cz-sidebar-title cz-sidebar-title--line">Affiner</p>
		<?php cz_filters_form( 'd' ); ?>
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
	$address = function_exists( 'cz_pickup_settings' ) ? cz_pickup_settings()['address'] : '';
	return array( 'Manufacture Cluizel – ' . ( $address ? $address : '7 avenue Michel Cluizel, 27240 Mesnils-sur-Iton' ) );
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
	/* Itération 5 : le mode de réception se choisit dans le panier, la boutique devient facultative */
	if ( '' !== $boutique && ! in_array( $boutique, cz_boutiques(), true ) ) {
		wc_add_notice( 'Cette boutique de retrait n\'est pas disponible.', 'error' );
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


/* =========================================================================
 * 12. Composition et valeurs nutritionnelles (itération 4)
 *     Champs SCF dans le back office, affichés sur la fiche avant le prix.
 * ====================================================================== */

/* Valeurs nutritionnelles pour 100 g : nom du champ => [libellé, unité] */
function cz_nutrition_fields() {
	return array(
		'cz_nut_energie'   => array( 'Énergie', 'kcal' ),
		'cz_nut_graisses'  => array( 'Matières grasses', 'g' ),
		'cz_nut_satures'   => array( 'dont acides gras saturés', 'g' ),
		'cz_nut_glucides'  => array( 'Glucides', 'g' ),
		'cz_nut_sucres'    => array( 'dont sucres', 'g' ),
		'cz_nut_proteines' => array( 'Protéines', 'g' ),
		'cz_nut_sel'       => array( 'Sel', 'g' ),
	);
}

/* Groupe de champs SCF */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$fields = array(
		array(
			'key'   => 'field_cz_tab_composition',
			'label' => 'Composition',
			'type'  => 'tab',
		),
		array(
			'key'     => 'field_cz_cacao',
			'label'   => 'Pourcentage de cacao',
			'name'    => 'cz_cacao',
			'type'    => 'number',
			'min'     => 0,
			'max'     => 100,
			'append'  => '%',
			'wrapper' => array( 'width' => '33' ),
		),
		array(
			'key'          => 'field_cz_poids',
			'label'        => 'Poids net',
			'name'         => 'cz_poids',
			'type'         => 'text',
			'instructions' => 'Ex. : 70 g',
			'wrapper'      => array( 'width' => '33' ),
		),
		array(
			'key'          => 'field_cz_origine',
			'label'        => 'Origine du cacao',
			'name'         => 'cz_origine',
			'type'         => 'text',
			'instructions' => 'Ex. : République dominicaine',
			'wrapper'      => array( 'width' => '33' ),
		),
		array(
			'key'   => 'field_cz_tab_nutrition',
			'label' => 'Valeurs nutritionnelles (pour 100 g)',
			'type'  => 'tab',
		),
	);

	foreach ( cz_nutrition_fields() as $name => $info ) {
		$fields[] = array(
			'key'     => 'field_' . $name,
			'label'   => $info[0],
			'name'    => $name,
			'type'    => 'number',
			'min'     => 0,
			'step'    => 0.01,
			'append'  => $info[1],
			'wrapper' => array( 'width' => '25' ),
		);
	}

	acf_add_local_field_group(
		array(
			'key'        => 'group_cz_composition',
			'title'      => 'Composition et valeurs nutritionnelles',
			'fields'     => $fields,
			'menu_order' => 1,
			'location'   => array(
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

/* Nombre au format français : 45.5 -> « 45,5 » */
function cz_format_number( $value ) {
	$value = (float) str_replace( ',', '.', (string) $value );
	$decimals = ( floor( $value ) == $value ) ? 0 : ( ( round( $value, 1 ) == $value ) ? 1 : 2 );
	return number_format_i18n( $value, $decimals );
}

/* Affichage sur la fiche, entre la description courte (20) et le prix (25) */
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product ) {
		return;
	}
	$id = $product->get_id();

	/* Pastilles : cacao, poids, origine */
	$chips  = array();
	$cacao  = cz_product_field( 'cz_cacao', $id );
	$poids  = cz_product_field( 'cz_poids', $id );
	$origin = cz_product_field( 'cz_origine', $id );
	if ( '' !== (string) $cacao ) {
		$chips[] = array( 'Cacao', cz_format_number( $cacao ) . ' %' );
	}
	if ( $poids ) {
		$chips[] = array( 'Poids net', $poids );
	}
	if ( $origin ) {
		$chips[] = array( 'Origine', $origin );
	}

	/* Tableau nutritionnel */
	$rows = array();
	foreach ( cz_nutrition_fields() as $name => $info ) {
		$value = cz_product_field( $name, $id );
		if ( '' !== (string) $value ) {
			$rows[] = array( $info[0], cz_format_number( $value ) . ' ' . $info[1], 0 === strpos( $info[0], 'dont' ) );
		}
	}

	if ( ! $chips && ! $rows ) {
		return;
	}

	echo '<div class="cz-specs">';

	if ( $chips ) {
		echo '<ul class="cz-specs-chips">';
		foreach ( $chips as $chip ) {
			echo '<li><span>' . esc_html( $chip[0] ) . '</span>' . esc_html( $chip[1] ) . '</li>';
		}
		echo '</ul>';
	}

	if ( $rows ) {
		echo '<details class="cz-nutrition">';
		echo '<summary>Valeurs nutritionnelles <span>pour 100 g</span></summary>';
		echo '<table><tbody>';
		foreach ( $rows as $row ) {
			echo '<tr' . ( $row[2] ? ' class="is-sub"' : '' ) . '><th scope="row">' . esc_html( $row[0] ) . '</th><td>' . esc_html( $row[1] ) . '</td></tr>';
		}
		echo '</tbody></table>';
		echo '</details>';
	}

	echo '</div>';
}, 22 );

/* =========================================================================
 * 13. Panier (itération 5)
 *     Gabarit : woocommerce/cart/cart.php (liste des articles + suggestions)
 *     Récapitulatif : gabarit WooCommerce d'origine, adapté par filtres et CSS
 * ====================================================================== */

/* Pas de fil d'Ariane ni de titre Storefront sur le panier : le gabarit affiche « Étape 1 sur 2 » */
add_action( 'wp', function () {
	if ( ! function_exists( 'is_cart' ) ) {
		return;
	}
	if ( is_cart() || is_checkout() ) {
		remove_action( 'storefront_page', 'storefront_page_header', 10 );
		remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
	}
} );

/* Les suggestions par défaut sont remplacées par « Vous aimerez aussi » */
add_action( 'init', function () {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
}, 20 );

/* En-tête du panier (aussi quand il est vide) */
function cz_cart_head() {
	echo '<div class="cz-cart-head">';
	echo '<p class="cz-steps">Étape 1 sur 2 <span aria-hidden="true">·</span> Panier</p>';
	echo '<h1 class="cz-cart-title">Mon panier</h1>';
	echo '</div>';
}
add_action( 'woocommerce_cart_is_empty', 'cz_cart_head', 5 );

/* Options d'un article : « Tablette de 70 g / Sans supplément / Étui cadeau » */
function cz_cart_item_options( $cart_item ) {
	$parts = array();
	if ( ! empty( $cart_item['variation'] ) ) {
		foreach ( $cart_item['variation'] as $attribute => $value ) {
			$taxonomy = str_replace( 'attribute_', '', $attribute );
			if ( taxonomy_exists( $taxonomy ) ) {
				$term  = get_term_by( 'slug', $value, $taxonomy );
				$value = $term ? $term->name : $value;
			}
			if ( '' !== $value ) {
				$parts[] = $value;
			}
		}
	}
	if ( ! empty( $cart_item['cz_options'] ) ) {
		foreach ( $cart_item['cz_options'] as $label => $value ) {
			if ( 'Retrait' !== $label ) {
				$parts[] = $value;
			}
		}
	}
	return implode( ' / ', $parts );
}

/* « Vous aimerez aussi » : ventes croisées, complétées par d'autres produits */
function cz_cart_suggestions() {
	if ( ! WC()->cart || WC()->cart->is_empty() ) {
		return;
	}
	$in_cart = wp_list_pluck( WC()->cart->get_cart(), 'product_id' );
	/* 1. Ventes croisées des articles du panier (choisies dans le back office) */
	$ids = array_values( array_diff( WC()->cart->get_cross_sells(), $in_cart ) );
	$ids = array_values( array_filter( $ids, 'cz_is_suggestable' ) );
	/* 2. Sinon, produits de la même catégorie */
	if ( count( $ids ) < 2 ) {
		foreach ( $in_cart as $pid ) {
			$ids = array_merge( $ids, wc_get_related_products( $pid, 4, array_merge( $in_cart, $ids ) ) );
		}
		$ids = array_values( array_unique( array_filter( $ids, 'cz_is_suggestable' ) ) );
	}
	/* 3. Sinon, au hasard */
	if ( count( $ids ) < 2 ) {
		$extra = wc_get_products(
			array(
				'status'     => 'publish',
				'visibility' => 'catalog',
				'limit'      => 2 - count( $ids ),
				'exclude'    => array_merge( $ids, $in_cart ),
				'orderby'    => 'rand',
				'return'     => 'ids',
			)
		);
		$ids = array_merge( $ids, $extra );
	}
	$ids = array_slice( $ids, 0, 2 );
	if ( ! $ids ) {
		return;
	}
	echo '<section class="cz-suggestions" aria-labelledby="cz-suggestions-title">';
	echo '<h2 id="cz-suggestions-title" class="cz-suggestions-title">Vous aimerez aussi</h2><ul>';
	foreach ( $ids as $id ) {
		$p = wc_get_product( $id );
		if ( ! $p ) {
			continue;
		}
		echo '<li><a href="' . esc_url( $p->get_permalink() ) . '">';
		echo '<span class="cz-suggestion-img">' . $p->get_image( 'woocommerce_thumbnail' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
		echo '<span class="cz-suggestion-text"><span class="cz-suggestion-name">' . esc_html( $p->get_name() ) . '</span>';
		echo '<span class="cz-suggestion-price">' . wp_kses_post( $p->get_price_html() ) . '</span></span>';
		echo '</a></li>';
	}
	echo '</ul></section>';
}

/* Textes du récapitulatif */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translation;
	}
	if ( 'Cart totals' === $text ) {
		return 'Récapitulatif';
	}
	if ( 'Proceed to checkout' === $text ) {
		return 'Passer commande';
	}
	return $translation;
}, 20, 3 );

add_filter( 'woocommerce_shipping_package_name', fn() => 'Mode de réception' );

/* Mode de réception en pilule : « Retrait en boutique … Gratuit » */
add_filter( 'woocommerce_cart_shipping_method_full_label', function ( $label, $method ) {
	$cost = (float) $method->get_cost();
	if ( WC()->cart && WC()->cart->display_prices_including_tax() ) {
		$cost += array_sum( (array) $method->get_taxes() );
	}
	$price = $cost > 0 ? wc_price( $cost ) : 'Gratuit';
	return '<span class="cz-ship-name">' . esc_html( $method->get_label() ) . '</span><span class="cz-ship-price">' . $price . '</span>';
}, 10, 2 );

/* Ligne « Retrait en boutique 0,00€ » au-dessus du total */
add_action( 'woocommerce_cart_totals_before_order_total', function () {
	if ( ! WC()->cart->needs_shipping() ) {
		return;
	}
	$chosen   = WC()->session ? (array) WC()->session->get( 'chosen_shipping_methods' ) : array();
	$packages = WC()->shipping()->get_packages();
	$rate     = ( $chosen && isset( $packages[0]['rates'][ $chosen[0] ] ) ) ? $packages[0]['rates'][ $chosen[0] ] : null;
	if ( ! $rate ) {
		return;
	}
	$cost = (float) WC()->cart->get_shipping_total();
	if ( WC()->cart->display_prices_including_tax() ) {
		$cost += (float) WC()->cart->get_shipping_tax();
	}
	echo '<tr class="cz-shipping-line"><th>' . esc_html( $rate->get_label() ) . '</th><td>' . wp_kses_post( wc_price( $cost ) ) . '</td></tr>';
} );

/* Lien « Continuer mes achats » sous le bouton */
add_action( 'woocommerce_proceed_to_checkout', function () {
	echo '<a class="cz-continue" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">Continuer mes achats</a>';
}, 30 );

/* Mise à jour automatique du panier quand la quantité change */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) {
		return;
	}
	?>
	<script>
	(function ($) {
		if (!$) return;
		var timer;
		$(document.body).on('change', '.woocommerce-cart-form input.qty', function () {
			clearTimeout(timer);
			timer = setTimeout(function () {
				$('.woocommerce-cart-form [name="update_cart"]').prop('disabled', false).trigger('click');
			}, 500);
		});
	})(window.jQuery);
	</script>
	<?php
}, 30 );

/* =========================================================================
 * 14. Validation de la commande (maquette « Finaliser ma commande »)
 *     Gabarits : woocommerce/checkout/form-checkout.php, review-order.php,
 *     payment.php et payment-method.php
 * ====================================================================== */

/* Pas de code promo, pas de rappel de connexion, paiement à gauche */
add_action( 'init', function () {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
	remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
}, 20 );

/* Récapitulatif fixe de Storefront désactivé (la mise en page est différente) */
add_filter( 'storefront_sticky_order_review', '__return_false' );

/* En-tête de la page */
function cz_checkout_head() {
	echo '<div class="cz-cart-head">';
	echo '<p class="cz-steps">Étape 2 sur 2 <span aria-hidden="true">·</span> Validation</p>';
	echo '<h1 class="cz-cart-title">Finaliser ma commande</h1>';
	echo '</div>';
}

/* Le mode de réception choisi est-il le retrait en boutique ? */
function cz_is_local_pickup_chosen() {
	$chosen = ( WC()->session ) ? (array) WC()->session->get( 'chosen_shipping_methods' ) : array();
	foreach ( $chosen as $id ) {
		if ( 0 === strpos( (string) $id, 'local_pickup' ) || 0 === strpos( (string) $id, 'pickup_location' ) ) {
			return true;
		}
	}
	return false;
}

/* Carte « Instructions pour le retrait » (texte réglé dans WooCommerce › Retrait en boutique) */
function cz_pickup_instructions() {
	$s = cz_pickup_settings();
	echo '<section class="cz-card cz-pickup">';
	echo '<h2 class="cz-card-title">' . esc_html( $s['title'] ) . '</h2>';
	if ( $s['address'] ) {
		echo '<p>Retrait en boutique : ' . esc_html( $s['address'] ) . '</p>';
	}
	$rows = cz_pickup_hours_rows( $s['hours'] );
	if ( $rows ) {
		echo '<p>Heures d’ouverture :</p>';
		echo '<table class="cz-hours"><tbody>';
		foreach ( $rows as $row ) {
			echo '<tr><th scope="row">' . esc_html( $row[0] ) . '</th><td>' . esc_html( $row[1] ) . '</td></tr>';
		}
		echo '</tbody></table>';
	}
	if ( $s['message'] ) {
		echo '<p>' . nl2br( esc_html( $s['message'] ) ) . '</p>';
	}
	echo '</section>';
}

/* Coordonnées : Prénom, Nom, E-mail, Téléphone (l'adresse n'est demandée que pour la livraison) */
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	foreach ( array( 'billing_company', 'billing_country', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode' ) as $key ) {
		unset( $fields['billing'][ $key ] );
	}
	unset( $fields['shipping']['shipping_company'] );

	$layout = array(
		'billing_first_name' => array( 'Prénom', 'form-row-first', 10, true ),
		'billing_last_name'  => array( 'Nom', 'form-row-last', 20, true ),
		'billing_email'      => array( 'E-mail', 'form-row-first', 30, true ),
		'billing_phone'      => array( 'Téléphone', 'form-row-last', 40, true ),
	);
	foreach ( $layout as $key => $conf ) {
		if ( ! isset( $fields['billing'][ $key ] ) ) {
			continue;
		}
		$fields['billing'][ $key ]['label']       = $conf[0];
		$fields['billing'][ $key ]['class']       = array( $conf[1] );
		$fields['billing'][ $key ]['priority']    = $conf[2];
		$fields['billing'][ $key ]['required']    = $conf[3];
		$fields['billing'][ $key ]['placeholder'] = '';
	}
	return $fields;
}, 20 );

/* Pas de notes de commande ; l'adresse de livraison est toujours celle saisie dans sa carte */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );

/* Case CGV : « J’accepte les conditions générales de vente » */
add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', fn() => 'J’accepte les [terms]' );
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'terms and conditions' === $text ) {
		return 'conditions générales de vente';
	}
	return $translation;
}, 20, 3 );

/* Bouton « Passer commande » sous le récapitulatif (colonne de droite) */
add_filter( 'woocommerce_order_button_text', fn() => 'Passer commande' );

add_action( 'woocommerce_checkout_order_review', function () {
	$text = apply_filters( 'woocommerce_order_button_text', 'Passer commande' );
	echo '<div class="cz-place-order">';
	echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput
		'woocommerce_order_button_html',
		'<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $text ) . '" data-value="' . esc_attr( $text ) . '">' . esc_html( $text ) . '</button>'
	);
	echo '</div>';
}, 30 );

/* Retrait en boutique : pas d'adresse de livraison à saisir */
add_filter( 'woocommerce_cart_needs_shipping_address', function ( $needs ) {
	return $needs && ! cz_is_local_pickup_chosen();
} );

/* Champs de coordonnées vides, même pour un compte connecté */
add_filter( 'woocommerce_checkout_get_value', function ( $value, $input ) {
	if ( 'billing_email' === $input ) {
		return '';
	}
	return $value;
}, 20, 2 );

/* =========================================================================
 * 15. Instructions pour le retrait (itération 6)
 *     Réglables dans le back office : WooCommerce › Retrait en boutique.
 *     Affichées sur la page de validation, la page de confirmation
 *     et dans les e-mails envoyés au client pour une commande en retrait.
 * ====================================================================== */

/* Valeurs par défaut */
function cz_pickup_defaults() {
	return array(
		'title'   => 'Instructions pour le retrait',
		'address' => '7 Avenue Michel Cluizel, 27240 Mesnils-sur-Iton',
		'hours'   => "Lundi : 10h00 - 18h00\nMardi : 10h00 - 18h00\nMercredi : 10h00 - 18h00\nJeudi : 10h00 - 18h00\nVendredi : 10h00 - 18h00\nSamedi : 10h00 - 18h00\nDimanche : fermé",
		'message' => 'Présentez votre numéro de commande à l’accueil',
	);
}

/* Réglages enregistrés, complétés par les valeurs par défaut */
function cz_pickup_settings() {
	$saved = (array) get_option( 'cz_pickup_settings', array() );
	$saved = array_filter( $saved, fn( $v ) => is_string( $v ) && '' !== trim( $v ) );
	return wp_parse_args( $saved, cz_pickup_defaults() );
}

/* Horaires : une ligne par jour, « Jour : horaire » */
function cz_pickup_hours_rows( $text ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts  = preg_split( '/\s*[:|]\s*/', $line, 2 );
		$rows[] = array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
	}
	return $rows;
}

/* Page de réglages : WooCommerce › Retrait en boutique */
add_action( 'admin_menu', function () {
	add_submenu_page(
		'woocommerce',
		'Instructions pour le retrait',
		'Retrait en boutique',
		'manage_woocommerce',
		'cz-retrait',
		'cz_pickup_settings_page'
	);
}, 60 );

add_action( 'admin_init', function () {
	register_setting(
		'cz_pickup',
		'cz_pickup_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => function ( $input ) {
				$input = (array) $input;
				return array(
					'title'   => sanitize_text_field( $input['title'] ?? '' ),
					'address' => sanitize_text_field( $input['address'] ?? '' ),
					'hours'   => sanitize_textarea_field( $input['hours'] ?? '' ),
					'message' => sanitize_textarea_field( $input['message'] ?? '' ),
				);
			},
		)
	);
} );

/* Les gestionnaires de boutique peuvent enregistrer ces réglages */
add_filter( 'option_page_capability_cz_pickup', fn() => 'manage_woocommerce' );

function cz_pickup_settings_page() {
	$s = cz_pickup_settings();
	?>
	<div class="wrap">
		<h1>Instructions pour le retrait</h1>
		<p>Ce texte s’affiche sur la page de validation de la commande, sur la page de confirmation et dans l’e-mail envoyé au client, lorsque le mode de réception choisi est le retrait en boutique.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'cz_pickup' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cz-pickup-title">Titre</label></th>
					<td><input type="text" id="cz-pickup-title" name="cz_pickup_settings[title]" value="<?php echo esc_attr( $s['title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-pickup-address">Adresse de la boutique</label></th>
					<td><input type="text" id="cz-pickup-address" name="cz_pickup_settings[address]" value="<?php echo esc_attr( $s['address'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-pickup-hours">Heures d’ouverture</label></th>
					<td>
						<textarea id="cz-pickup-hours" name="cz_pickup_settings[hours]" rows="8" class="large-text" aria-describedby="cz-pickup-hours-help"><?php echo esc_textarea( $s['hours'] ); ?></textarea>
						<p class="description" id="cz-pickup-hours-help">Une ligne par jour, au format « Jour : horaire ». Exemple : Lundi : 10h00 - 18h00</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-pickup-message">Message</label></th>
					<td><textarea id="cz-pickup-message" name="cz_pickup_settings[message]" rows="3" class="large-text"><?php echo esc_textarea( $s['message'] ); ?></textarea></td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer les instructions' ); ?>
		</form>
	</div>
	<?php
}

/* La commande est-elle en retrait en boutique ? */
function cz_order_is_pickup( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return false;
	}
	foreach ( $order->get_shipping_methods() as $method ) {
		if ( in_array( $method->get_method_id(), array( 'local_pickup', 'legacy_local_pickup', 'pickup_location' ), true ) ) {
			return true;
		}
	}
	return false;
}

/* Page de confirmation : gabarit woocommerce/checkout/thankyou.php
   (le tableau de commande par défaut est remplacé par les cartes de la maquette) */
add_action( 'init', function () {
	remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
}, 20 );

/* Statut affiché dans « Informations de paiement » */
function cz_order_payment_status( $order ) {
	if ( 'cod' === $order->get_payment_method() ) {
		return 'À régler en boutique';
	}
	if ( $order->is_paid() ) {
		return 'Payée';
	}
	if ( 'bacs' === $order->get_payment_method() ) {
		$labels = array(
			'virement' => 'En attente de votre virement',
			'paypal'   => 'En attente de votre paiement PayPal',
			'wero'     => 'En attente de votre paiement Wero',
		);
		return $labels[ cz_order_bacs_option( $order ) ] ?? $labels['virement'];
	}
	return wc_get_order_status_name( $order->get_status() );
}

/* Options d'une ligne de commande : « Tablette de 70 g / Sans supplément / Coffret classique » */
function cz_order_item_options( $item ) {
	$parts = array();
	foreach ( $item->get_formatted_meta_data( '_', true ) as $meta ) {
		if ( 'Retrait' === $meta->key || 'Retrait' === wp_strip_all_tags( $meta->display_key ) ) {
			continue;
		}
		$parts[] = trim( wp_strip_all_tags( $meta->display_value ) );
	}
	return implode( ' / ', array_filter( $parts ) );
}

/* Les instructions de retrait des e-mails sont dans la section 17 */

/* =========================================================================
 * 16. Compte client (itération 7)
 *     Connexion / inscription et espace « Mon compte » aux couleurs de la charte.
 * ====================================================================== */

/* Menu du compte : libellés en français simple, sans « Téléchargements » */
add_filter( 'woocommerce_account_menu_items', function ( $items ) {
	$labels = array(
		'dashboard'       => 'Tableau de bord',
		'orders'          => 'Mes commandes',
		'edit-address'    => 'Mes adresses',
		'edit-account'    => 'Mes informations',
		'customer-logout' => 'Déconnexion',
	);
	$menu = array();
	foreach ( $labels as $key => $label ) {
		if ( isset( $items[ $key ] ) ) {
			$menu[ $key ] = $label;
		}
	}
	return $menu;
}, 20 );

/* Titre de la page : toujours « Mon compte » (comme sur la maquette) */
add_filter( 'the_title', function ( $title, $id = null ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() && in_the_loop() && (int) $id === (int) wc_get_page_id( 'myaccount' ) ) {
		return 'Mon compte';
	}
	return $title;
}, 20, 2 );

/* Pas de fil d'Ariane sur le compte (le titre suffit, comme sur les autres pages du parcours) */
add_action( 'wp', function () {
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
	}
}, 20 );

/* Statut « Prête à retirer » (étape 3 du suivi de commande) */
add_action( 'init', function () {
	register_post_status(
		'wc-ready-pickup',
		array(
			'label'                     => 'Prête à retirer',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: nombre de commandes */
			'label_count'               => _n_noop( 'Prête à retirer <span class="count">(%s)</span>', 'Prêtes à retirer <span class="count">(%s)</span>' ),
		)
	);
} );

add_filter( 'wc_order_statuses', function ( $statuses ) {
	$new = array();
	foreach ( $statuses as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'wc-processing' === $key ) {
			$new['wc-ready-pickup'] = 'Prête à retirer';
		}
	}
	return $new;
} );

/* Statuts d'une commande « en cours » */
function cz_active_order_statuses() {
	return array( 'pending', 'on-hold', 'processing', 'ready-pickup' );
}

/* Étapes du suivi : retrait ou livraison */
function cz_order_steps( $order ) {
	$labels = cz_order_is_pickup( $order )
		? array( 'Commande reçue', 'En préparation', 'Prête à retirer', 'Retirée' )
		: array( 'Commande reçue', 'En préparation', 'Prête à expédier', 'Expédiée' );
	$map = array(
		'pending'      => 0,
		'on-hold'      => 0,
		'processing'   => 1,
		'ready-pickup' => 2,
		'completed'    => 3,
	);
	$current = $map[ $order->get_status() ] ?? 0;
	return array( $labels, $current );
}

/* Adresse sur une ligne : « 15 rue Claude Bernard, Limoges 87000 » */
function cz_address_line( $customer_id, $type ) {
	$get   = fn( $field ) => trim( (string) get_user_meta( $customer_id, $type . '_' . $field, true ) );
	$parts = array_filter( array( $get( 'address_1' ), $get( 'address_2' ), trim( $get( 'city' ) . ' ' . $get( 'postcode' ) ) ) );
	return implode( ', ', $parts );
}

/* Tableau de bord : salutation et « Ma commande en cours » */
add_action( 'woocommerce_account_dashboard', function () {
	$user = wp_get_current_user();
	$name = $user->first_name ? $user->first_name : $user->display_name;
	echo '<h2 class="cz-account-hello">Bonjour ' . esc_html( $name ) . ', bienvenue dans votre espace Cluizel</h2>';

	$orders = wc_get_orders(
		array(
			'customer_id' => get_current_user_id(),
			'status'      => cz_active_order_statuses(),
			'limit'       => 1,
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);

	echo '<section class="cz-card cz-current-order">';
	echo '<div class="cz-current-order-head"><h2 class="cz-card-title">Ma commande en cours</h2>';

	if ( ! $orders ) {
		echo '</div><p class="cz-current-order-empty">Vous n’avez pas de commande en cours.</p>';
		echo '<a class="cz-btn" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">Découvrir la carte</a>';
		echo '</section>';
		return;
	}

	$order = $orders[0];
	list( $labels, $current ) = cz_order_steps( $order );

	echo '<a class="cz-current-order-number" href="' . esc_url( $order->get_view_order_url() ) . '">Numéro de commande : N°' . esc_html( $order->get_order_number() ) . '</a></div>';

	echo '<ol class="cz-track" aria-label="Suivi de la commande">';
	foreach ( $labels as $i => $label ) {
		$class = $i <= $current ? 'is-done' : '';
		$aria  = $i === $current ? ' aria-current="step"' : '';
		echo '<li class="' . esc_attr( $class ) . '"' . $aria . '><span class="cz-track-bar" aria-hidden="true"></span><span class="cz-track-label">' . esc_html( $label ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</ol>';

	echo '<div class="cz-current-order-box">';
	echo '<p class="cz-current-order-status">' . esc_html( $labels[ $current ] ) . '</p>';
	if ( function_exists( 'cz_order_slot_text' ) && cz_order_slot_text( $order ) ) {
		echo '<p class="cz-current-order-slot"><strong>' . esc_html( cz_order_slot_text( $order ) ) . '</strong></p>';
	}

	if ( cz_order_is_pickup( $order ) ) {
		$s    = cz_pickup_settings();
		$rows = cz_pickup_hours_rows( $s['hours'] );
		echo '<p>Retrait en boutique : ' . esc_html( $s['address'] ) . '</p>';
		if ( $rows ) {
			echo '<p>Horaires :</p><table class="cz-hours"><tbody>';
			foreach ( $rows as $row ) {
				echo '<tr><th scope="row">' . esc_html( $row[0] ) . '</th><td>' . esc_html( $row[1] ) . '</td></tr>';
			}
			echo '</tbody></table>';
		}
		echo '<p>' . nl2br( esc_html( $s['message'] ) ) . '</p>';
	} elseif ( $order->get_formatted_shipping_address() ) {
		echo '<p>Livraison à :</p><address>' . wp_kses_post( $order->get_formatted_shipping_address() ) . '</address>';
	}

	echo '</div>';
	cz_cancel_order_button( $order );
	echo '</section>';
} );

/* Le texte d'accueil WooCommerce est remplacé par le nôtre */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translation;
	}
	$replace = array(
		'Login'                     => 'Se connecter',
		'Register'                  => 'Créer un compte',
		'Log in'                    => 'Se connecter',
		'Lost your password?'       => 'Mot de passe oublié ?',
		'Remember me'               => 'Se souvenir de moi',
		'Username or email address' => 'E-mail ou identifiant',
	);
	return $replace[ $text ] ?? $translation;
}, 20, 3 );


/* Inscription : prénom et nom demandés, puis enregistrés sur le compte */
add_action( 'woocommerce_register_form_start', function () {
	// phpcs:disable WordPress.Security.NonceVerification
	$first = isset( $_POST['billing_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
	$last  = isset( $_POST['billing_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : '';
	// phpcs:enable
	?>
	<div class="cz-form-grid">
		<p class="woocommerce-form-row form-row">
			<label for="reg_billing_first_name">Prénom</label>
			<input type="text" class="woocommerce-Input input-text" name="billing_first_name" id="reg_billing_first_name" autocomplete="given-name" value="<?php echo esc_attr( $first ); ?>" required>
		</p>
		<p class="woocommerce-form-row form-row">
			<label for="reg_billing_last_name">Nom</label>
			<input type="text" class="woocommerce-Input input-text" name="billing_last_name" id="reg_billing_last_name" autocomplete="family-name" value="<?php echo esc_attr( $last ); ?>" required>
		</p>
	</div>
	<?php
} );

add_filter( 'woocommerce_registration_errors', function ( $errors ) {
	// phpcs:disable WordPress.Security.NonceVerification
	if ( empty( $_POST['billing_first_name'] ) ) {
		$errors->add( 'billing_first_name_error', 'Merci d’indiquer votre prénom.' );
	}
	if ( empty( $_POST['billing_last_name'] ) ) {
		$errors->add( 'billing_last_name_error', 'Merci d’indiquer votre nom.' );
	}
	// phpcs:enable
	return $errors;
} );

add_action( 'woocommerce_created_customer', function ( $customer_id ) {
	// phpcs:disable WordPress.Security.NonceVerification
	foreach ( array( 'first_name', 'last_name' ) as $field ) {
		if ( ! empty( $_POST[ 'billing_' . $field ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ 'billing_' . $field ] ) );
			update_user_meta( $customer_id, $field, $value );
			update_user_meta( $customer_id, 'billing_' . $field, $value );
		}
	}
	// phpcs:enable
} );

/* Connexion depuis la page de commande : retour à la commande après connexion */
add_action( 'woocommerce_login_form_end', function () {
	$redirect = isset( $_GET['redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ), '' ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	if ( $redirect ) {
		echo '<input type="hidden" name="redirect" value="' . esc_url( $redirect ) . '">';
	}
} );

/* Mes informations : téléphone enregistré avec le compte */
add_action( 'woocommerce_save_account_details', function ( $user_id ) {
	if ( isset( $_POST['account_phone'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- nonce vérifié par WooCommerce
		update_user_meta( $user_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['account_phone'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	}
} );

/* Supprimer mon compte (client uniquement, sans commande en cours) */
add_action( 'template_redirect', function () {
	if ( empty( $_POST['cz_delete_account'] ) || ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	$nonce = isset( $_POST['cz-delete-account-nonce'] ) ? sanitize_key( wp_unslash( $_POST['cz-delete-account-nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'cz_delete_account' ) ) {
		wc_add_notice( 'La demande a expiré, merci de réessayer.', 'error' );
		return;
	}

	$user_id = get_current_user_id();
	if ( user_can( $user_id, 'manage_woocommerce' ) ) {
		wc_add_notice( 'Un compte administrateur ne peut pas être supprimé depuis cette page.', 'error' );
		return;
	}

	$active = wc_get_orders(
		array(
			'customer_id' => $user_id,
			'status'      => cz_active_order_statuses(),
			'limit'       => 1,
			'return'      => 'ids',
		)
	);
	if ( $active ) {
		wc_add_notice( 'Vos commandes en cours doivent être retirées ou annulées avant de supprimer votre compte.', 'error' );
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/user.php';
	$admins = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => 'ID',
		)
	);
	wp_logout();
	wp_delete_user( $user_id, $admins ? (int) $admins[0] : null );
	wp_safe_redirect( add_query_arg( 'compte-supprime', '1', home_url( '/' ) ) );
	exit;
} );

/* Message après la suppression du compte */
add_action( 'storefront_before_content', function () {
	if ( isset( $_GET['compte-supprime'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="col-full"><div class="woocommerce-message cz-notice" role="status">Votre compte a bien été supprimé.</div></div>';
	}
}, 5 );

/* Texte de confidentialité de l'inscription en français */
add_filter( 'woocommerce_get_privacy_policy_text', function ( $text, $type ) {
	if ( 'registration' === $type ) {
		return 'Vos données personnelles servent à gérer votre compte et vos commandes, comme indiqué dans notre [privacy_policy].';
	}
	return $text;
}, 10, 2 );

/* Après la déconnexion : retour à la page Mon compte avec un message */
add_filter( 'woocommerce_logout_default_redirect_url', function () {
	return add_query_arg( 'deconnecte', '1', wc_get_page_permalink( 'myaccount' ) );
} );

add_action( 'woocommerce_before_customer_login_form', function () {
	if ( isset( $_GET['deconnecte'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		echo '<div class="woocommerce-message" role="status">Déconnexion réussie. À bientôt chez Cluizel !</div>';
	}
} );

/* Classe sur la page de confirmation (le menu burger y reste visible) */
add_filter( 'body_class', function ( $classes ) {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
		$classes[] = 'woocommerce-order-received';
	}
	return array_unique( $classes );
} );

/* =========================================================================
 * 17. E-mails transactionnels (itération 8)
 *     Gabarits : woocommerce/emails/ (en-tête, pied, styles, e-mails client)
 *     Nouvel e-mail : « Commande prête à retirer » (inc/class-cz-email-ready-pickup.php)
 * ====================================================================== */

/* Nouvel e-mail « Commande prête à retirer » */
add_filter( 'woocommerce_email_classes', function ( $emails ) {
	require_once get_stylesheet_directory() . '/inc/class-cz-email-ready-pickup.php';
	$emails['CZ_Email_Ready_Pickup'] = new CZ_Email_Ready_Pickup();
	return $emails;
} );

/* WooCommerce déclenche l'e-mail quand la commande passe à « Prête à retirer » */
add_filter( 'woocommerce_email_actions', function ( $actions ) {
	$actions[] = 'woocommerce_order_status_ready-pickup';
	return $actions;
} );

/* Liste des commandes : action groupée « Changer le statut en Prête à retirer » */
foreach ( array( 'bulk_actions-edit-shop_order', 'bulk_actions-woocommerce_page_wc-orders' ) as $cz_bulk_hook ) {
	add_filter( $cz_bulk_hook, function ( $actions ) {
		$actions['mark_ready-pickup'] = 'Changer le statut en Prête à retirer';
		return $actions;
	}, 20 );
}

/* « Bonjour Prénom, » */
function cz_email_hello( $order ) {
	$first = $order->get_billing_first_name();
	echo '<p>' . esc_html( $first ? 'Bonjour ' . $first . ',' : 'Bonjour,' ) . '</p>';
}

/* Frise des 4 étapes, comme dans l'espace client */
function cz_email_track( $order ) {
	list( $labels, $current ) = cz_order_steps( $order );
	echo '<table class="cz-email-track" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr>';
	foreach ( $labels as $i => $label ) {
		$done = $i <= $current ? ' is-done' : '';
		$now  = $i === $current ? ' is-current' : '';
		echo '<td><div class="cz-email-bar' . esc_attr( $done ) . '">&nbsp;</div><div class="cz-email-step' . esc_attr( $now ) . '">' . esc_html( $label ) . '</div></td>';
	}
	echo '</tr></table>';
}

/* Carte beige « Instructions pour le retrait » (HTML ou texte brut) */
function cz_email_pickup_card( $order, $plain_text = false ) {
	if ( ! cz_order_is_pickup( $order ) ) {
		return;
	}
	$s    = cz_pickup_settings();
	$rows = cz_pickup_hours_rows( $s['hours'] );

	if ( $plain_text ) {
		echo "\n" . esc_html( mb_strtoupper( $s['title'] ) ) . "\n\n";
		echo esc_html( 'Retrait en boutique : ' . $s['address'] ) . "\n\n";
		foreach ( $rows as $row ) {
			echo esc_html( $row[0] . ' : ' . $row[1] ) . "\n";
		}
		echo "\n" . esc_html( $s['message'] ) . "\n";
		return;
	}

	echo '<table class="cz-email-card" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="cz-email-card-cell">';
	echo '<h2>' . esc_html( $s['title'] ) . '</h2>';
	echo '<p><strong>Retrait en boutique :</strong> ' . esc_html( $s['address'] ) . '</p>';
	if ( $rows ) {
		echo '<p><strong>Heures d’ouverture :</strong></p><table class="cz-email-hours" border="0" cellpadding="0" cellspacing="0" role="presentation">';
		foreach ( $rows as $row ) {
			echo '<tr><td>' . esc_html( $row[0] ) . '</td><td>' . esc_html( $row[1] ) . '</td></tr>';
		}
		echo '</table>';
	}
	echo '<p style="margin-top:14px;">' . nl2br( esc_html( $s['message'] ) ) . '</p>';
	echo '</td></tr></table>';
}

/* Instructions de retrait dans les e-mails concernés (après le tableau de la commande) */
add_action( 'woocommerce_email_after_order_table', function ( $order, $sent_to_admin, $plain_text, $email = null ) {
	$ids = array( 'customer_on_hold_order', 'customer_processing_order', 'customer_invoice' );
	if ( $sent_to_admin || ! $email || ! in_array( $email->id, $ids, true ) ) {
		return;
	}
	cz_email_pickup_card( $order, $plain_text );
}, 10, 4 );

/* Bouton « Voir ma commande » (clients ayant un compte) */
function cz_email_order_button( $order ) {
	if ( ! $order->get_customer_id() ) {
		return;
	}
	echo '<p style="margin-top:32px;text-align:center;"><a class="cz-email-btn" href="' . esc_url( $order->get_view_order_url() ) . '">Voir ma commande</a></p>';
}

/* =========================================================================
 * 18. Paiement à distance : virement bancaire, PayPal ou Wero
 *     Sous-choix du moyen de paiement « virement » (bacs). Pas de vrai
 *     paiement en ligne : le client reçoit les instructions du moyen choisi
 *     (page de confirmation + e-mail), la commande reste « En attente ».
 *     Réglages : WooCommerce › Paiement à distance.
 * ====================================================================== */

/* Réglages PayPal et Wero : WooCommerce › Paiement à distance
   (la nouvelle page « Virement bancaire » de WooCommerce n'accepte pas de champs ajoutés) */
add_action( 'admin_menu', function () {
	add_submenu_page(
		'woocommerce',
		'Paiement à distance',
		'Paiement à distance',
		'manage_woocommerce',
		'cz-paiement',
		'cz_remote_pay_page'
	);
}, 61 );

add_action( 'admin_init', function () {
	register_setting(
		'cz_remote_pay',
		'cz_remote_pay',
		array(
			'type'              => 'array',
			'sanitize_callback' => function ( $input ) {
				$input = (array) $input;
				return array(
					'cz_paypal' => sanitize_text_field( $input['cz_paypal'] ?? '' ),
					'cz_wero'   => sanitize_text_field( $input['cz_wero'] ?? '' ),
				);
			},
		)
	);
} );

add_filter( 'option_page_capability_cz_remote_pay', fn() => 'manage_woocommerce' );

function cz_remote_pay_page() {
	?>
	<div class="wrap">
		<h1>Paiement à distance</h1>
		<p>Ces choix s’affichent sous le moyen de paiement « virement bancaire » (WooCommerce › Réglages › Paiements). Le client reçoit les instructions du moyen choisi sur la page de confirmation et dans l’e-mail. Laisser un champ vide pour ne pas proposer ce moyen.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'cz_remote_pay' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cz-paypal">PayPal</label></th>
					<td>
						<input type="text" id="cz-paypal" name="cz_remote_pay[cz_paypal]" value="<?php echo esc_attr( cz_bacs_setting( 'cz_paypal' ) ); ?>" class="regular-text" aria-describedby="cz-paypal-help">
						<p class="description" id="cz-paypal-help">Adresse e-mail PayPal ou lien https://paypal.me/…</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-wero">Wero</label></th>
					<td>
						<input type="text" id="cz-wero" name="cz_remote_pay[cz_wero]" value="<?php echo esc_attr( cz_bacs_setting( 'cz_wero' ) ); ?>" class="regular-text" aria-describedby="cz-wero-help">
						<p class="description" id="cz-wero-help">Numéro de téléphone ou e-mail Wero.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}

/* Réglage PayPal / Wero */
function cz_bacs_setting( $key ) {
	$settings = (array) get_option( 'cz_remote_pay', array() );
	return isset( $settings[ $key ] ) ? trim( (string) $settings[ $key ] ) : '';
}

/* Choix proposés (PayPal et Wero seulement s'ils sont réglés) */
function cz_bacs_options() {
	$options = array(
		'virement' => array( 'Virement bancaire', 'Coordonnées bancaires après la commande' ),
	);
	if ( cz_bacs_setting( 'cz_paypal' ) ) {
		$options['paypal'] = array( 'PayPal', 'Lien ou adresse PayPal après la commande' );
	}
	if ( cz_bacs_setting( 'cz_wero' ) ) {
		$options['wero'] = array( 'Wero', 'Paiement depuis votre application bancaire' );
	}
	return $options;
}

/* Choix envoyé par le formulaire (aussi lors des mises à jour automatiques de la page) */
function cz_bacs_posted_option() {
	// phpcs:disable WordPress.Security.NonceVerification
	$value = '';
	if ( isset( $_POST['cz_bacs_option'] ) ) {
		$value = sanitize_key( wp_unslash( $_POST['cz_bacs_option'] ) );
	} elseif ( isset( $_POST['post_data'] ) ) {
		parse_str( wp_unslash( $_POST['post_data'] ), $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$value = isset( $data['cz_bacs_option'] ) ? sanitize_key( $data['cz_bacs_option'] ) : '';
	}
	// phpcs:enable
	return array_key_exists( $value, cz_bacs_options() ) ? $value : 'virement';
}

/* Liste des choix sous « Par virement » (appelée dans checkout/payment-method.php) */
function cz_bacs_options_field() {
	$options = cz_bacs_options();
	if ( count( $options ) < 2 ) {
		return;
	}
	$current = cz_bacs_posted_option();
	echo '<fieldset class="cz-pay-options"><legend class="screen-reader-text">Comment souhaitez-vous payer ?</legend>';
	foreach ( $options as $key => $option ) {
		printf(
			'<label class="cz-pay-option"><input type="radio" name="cz_bacs_option" value="%1$s" %2$s><span><span class="cz-pay-option-title">%3$s</span><span class="cz-pay-option-desc">%4$s</span></span></label>',
			esc_attr( $key ),
			checked( $current, $key, false ),
			esc_html( $option[0] ),
			esc_html( $option[1] )
		);
	}
	echo '</fieldset>';
}

/* Enregistrement du choix sur la commande + intitulé du moyen de paiement */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
	if ( 'bacs' !== $order->get_payment_method() ) {
		return;
	}
	$option = cz_bacs_posted_option();
	$titles = array(
		'virement' => 'Par virement bancaire',
		'paypal'   => 'Par PayPal',
		'wero'     => 'Par Wero',
	);
	$order->update_meta_data( '_cz_bacs_option', $option );
	$order->set_payment_method_title( $titles[ $option ] );
} );

function cz_order_bacs_option( $order ) {
	$option = $order ? (string) $order->get_meta( '_cz_bacs_option' ) : '';
	return in_array( $option, array( 'virement', 'paypal', 'wero' ), true ) ? $option : 'virement';
}

/* PayPal / Wero : pas de coordonnées bancaires ni d'instructions du virement */
add_filter( 'woocommerce_bacs_accounts', function ( $accounts, $order_id = 0 ) {
	$order = $order_id ? wc_get_order( $order_id ) : null;
	return ( $order && 'virement' !== cz_order_bacs_option( $order ) ) ? array() : $accounts;
}, 10, 2 );

function cz_bacs_mute_instructions( $order ) {
	if ( ! $order || 'bacs' !== $order->get_payment_method() || 'virement' === cz_order_bacs_option( $order ) ) {
		return;
	}
	$gateways = WC()->payment_gateways() ? WC()->payment_gateways()->payment_gateways() : array();
	if ( isset( $gateways['bacs'] ) ) {
		$gateways['bacs']->instructions = '';
	}
}

/* Instructions PayPal / Wero (page de confirmation et e-mail) */
function cz_bacs_alt_instructions( $order, $plain_text = false ) {
	$option = cz_order_bacs_option( $order );
	if ( 'virement' === $option ) {
		return;
	}
	$total = wp_strip_all_tags( $order->get_formatted_order_total() );
	$ref   = 'N°' . $order->get_order_number();

	if ( 'paypal' === $option ) {
		$target = cz_bacs_setting( 'cz_paypal' );
		$title  = 'Payer avec PayPal';
		$text   = sprintf( 'Envoyez %1$s %2$s, en indiquant la référence %3$s.', $total, 0 === strpos( $target, 'http' ) ? 'avec le lien ci-dessous' : 'à ' . $target, $ref );
		$link   = 0 === strpos( $target, 'http' ) ? $target : '';
	} else {
		$target = cz_bacs_setting( 'cz_wero' );
		$title  = 'Payer avec Wero';
		$text   = sprintf( 'Depuis votre application bancaire, envoyez %1$s par Wero à %2$s, en indiquant la référence %3$s.', $total, $target, $ref );
		$link   = '';
	}

	if ( $plain_text ) {
		echo "\n" . esc_html( mb_strtoupper( $title ) ) . "\n" . esc_html( $text ) . "\n" . ( $link ? esc_url( $link ) . "\n" : '' ) . "\n";
		return;
	}
	echo '<section class="cz-alt-pay"><h2>' . esc_html( $title ) . '</h2><p>' . esc_html( $text ) . '</p>';
	if ( $link ) {
		echo '<p><a class="cz-btn cz-email-btn" href="' . esc_url( $link ) . '" target="_blank" rel="noopener">Payer avec PayPal</a></p>';
	}
	echo '<p>Votre commande sera préparée dès réception du paiement.</p></section>';
}

/* Page de confirmation */
add_action( 'woocommerce_thankyou_bacs', function ( $order_id ) {
	cz_bacs_mute_instructions( wc_get_order( $order_id ) );
}, 5 );
add_action( 'woocommerce_thankyou_bacs', function ( $order_id ) {
	cz_bacs_alt_instructions( wc_get_order( $order_id ) );
}, 20 );

/* E-mails envoyés au client (même condition que WooCommerce : commande en attente) */
add_action( 'woocommerce_email_before_order_table', function ( $order, $sent_to_admin ) {
	if ( ! $sent_to_admin ) {
		cz_bacs_mute_instructions( $order );
	}
}, 5, 2 );
add_action( 'woocommerce_email_before_order_table', function ( $order, $sent_to_admin, $plain_text ) {
	if ( $sent_to_admin || 'bacs' !== $order->get_payment_method() || ! $order->has_status( 'on-hold' ) ) {
		return;
	}
	cz_bacs_alt_instructions( $order, $plain_text );
}, 20, 3 );

/* --- Tableau de la commande dans les e-mails (maquette v8.0) --- */

/* On repère quand WooCommerce affiche le tableau d'un e-mail */
add_action( 'woocommerce_email_before_order_table', function () {
	$GLOBALS['cz_in_email_table'] = true;
}, 1 );
add_action( 'woocommerce_email_after_order_table', function () {
	$GLOBALS['cz_in_email_table'] = false;
}, 99 );

function cz_in_email_table() {
	return ! empty( $GLOBALS['cz_in_email_table'] );
}

/* Vignettes plus grandes (64 px) */
add_filter( 'woocommerce_email_order_items_args', function ( $args ) {
	$args['image_size'] = array( 64, 64 );
	return $args;
} );

/* Options sur une ligne : « Tablette de 70 g / Sans supplément / Étui cadeau » */
add_filter( 'woocommerce_display_item_meta', function ( $html, $item ) {
	if ( ! cz_in_email_table() || ! $item instanceof WC_Order_Item_Product ) {
		return $html;
	}
	$options = cz_order_item_options( $item );
	return $options ? '<span class="cz-email-item-options">' . esc_html( $options ) . '</span>' : '';
}, 10, 2 );

/* Totaux : Sous-total, Retrait en boutique 0,00 €, Total, puis Mode de paiement */
add_filter( 'woocommerce_get_order_item_totals', function ( $totals, $order ) {
	if ( ! cz_in_email_table() ) {
		return $totals;
	}
	if ( isset( $totals['shipping'] ) && $order->get_shipping_method() ) {
		$cost = (float) $order->get_shipping_total();
		if ( 'incl' === get_option( 'woocommerce_tax_display_cart' ) ) {
			$cost += (float) $order->get_shipping_tax();
		}
		$totals['shipping']['label'] = $order->get_shipping_method();
		$totals['shipping']['value'] = wc_price( $cost, array( 'currency' => $order->get_currency() ) );
		unset( $totals['shipping']['meta'] );
	}
	if ( isset( $totals['payment_method'] ) ) {
		$payment = $totals['payment_method'];
		unset( $totals['payment_method'] );
		$payment['label']          = 'Mode de paiement';
		$totals['payment_method'] = $payment;
	}
	return $totals;
}, 20, 2 );

/* =========================================================================
 * 19. Commandes (itération 9)
 *     Annulation par le client, e-mails d'avancement selon le paiement,
 *     colonne « Réception » dans la liste des commandes de l'admin.
 * ====================================================================== */

/* Le client peut annuler une commande en attente ou en préparation
   (pas une commande prête à retirer, ni terminée) */
add_filter( 'woocommerce_valid_order_statuses_for_cancel', function ( $statuses ) {
	return array_unique( array_merge( $statuses, array( 'on-hold', 'processing' ) ) );
}, 20 );

function cz_order_can_be_cancelled( $order ) {
	return $order instanceof WC_Order
		&& $order->get_customer_id() === get_current_user_id()
		&& $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) );
}

/* Bouton « Annuler ma commande » (tableau de bord, Mes commandes, détail d'une commande) */
function cz_cancel_order_button( $order ) {
	if ( ! cz_order_can_be_cancelled( $order ) ) {
		return;
	}
	$url = $order->get_cancel_order_url( wc_get_account_endpoint_url( 'orders' ) );
	$msg = $order->get_date_paid() && 'cod' !== $order->get_payment_method()
		? 'Annuler la commande N°' . $order->get_order_number() . ' ? Vous serez remboursé(e).'
		: 'Annuler la commande N°' . $order->get_order_number() . ' ?';
	printf(
		'<p class="cz-cancel-order"><a class="cz-btn-outline" href="%1$s" onclick="return confirm(%2$s);">Annuler ma commande</a></p>',
		esc_url( $url ),
		esc_attr( wp_json_encode( $msg ) )
	);
}

add_action( 'woocommerce_view_order', function ( $order_id ) {
	cz_cancel_order_button( wc_get_order( $order_id ) );
}, 20 );

/* Message après l'annulation */
add_filter( 'woocommerce_order_cancelled_notice', fn() => 'Votre commande a bien été annulée. Un e-mail de confirmation vient de vous être envoyé.' );

/* Paiement reçu ? (virement validé par la boutique ; le paiement au retrait est encaissé en boutique) */
function cz_order_was_paid_online( $order ) {
	return 'cod' !== $order->get_payment_method() && (bool) $order->get_date_paid();
}

/* Liste des commandes de l'admin : colonne « Réception » (retrait ou livraison) */
function cz_admin_reception_label( $order ) {
	if ( ! $order instanceof WC_Order ) {
		return '';
	}
	return cz_order_is_pickup( $order ) ? 'Retrait en boutique' : ( $order->get_shipping_method() ? $order->get_shipping_method() : '—' );
}

$cz_add_column = function ( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'order_status' === $key ) {
			$new['cz_reception'] = 'Réception';
		}
	}
	return $new;
};
add_filter( 'manage_woocommerce_page_wc-orders_columns', $cz_add_column, 20 );
add_filter( 'manage_edit-shop_order_columns', $cz_add_column, 20 );

add_action( 'manage_woocommerce_page_wc-orders_custom_column', function ( $column, $order ) {
	if ( 'cz_reception' === $column ) {
		echo esc_html( cz_admin_reception_label( $order ) );
	}
}, 10, 2 );
add_action( 'manage_shop_order_posts_custom_column', function ( $column, $post_id ) {
	if ( 'cz_reception' === $column ) {
		echo esc_html( cz_admin_reception_label( wc_get_order( $post_id ) ) );
	}
}, 10, 2 );

/* =========================================================================
 * 20. Promotions : codes promo (itération 10)
 *     Champ « Code promo » dans le récapitulatif du panier, ligne de réduction
 *     avec « Retirer », messages clairs (code erroné, expiré, indisponible).
 * ====================================================================== */

/* Champ « Code promo » dans le récapitulatif, avant « Passer commande »
   (formulaire séparé : la page se recharge et affiche le message) */
add_action( 'woocommerce_proceed_to_checkout', function () {
	if ( ! wc_coupons_enabled() ) {
		return;
	}
	?>
	<form class="cz-coupon" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<label for="cz_coupon_code">Code promo</label>
		<div class="cz-coupon-field">
			<input type="text" name="coupon_code" id="cz_coupon_code" class="input-text" autocomplete="off" placeholder="Ex. : CLUIZEL10" required>
			<button type="submit" class="cz-btn" name="apply_coupon" value="1">Appliquer</button>
		</div>
		<input type="hidden" name="woocommerce-cart-nonce" value="<?php echo esc_attr( wp_create_nonce( 'woocommerce-cart' ) ); ?>">
	</form>
	<?php
}, 5 );

/* Ligne de réduction : « Code promo CLUIZEL10 » … « -2,96 € Retirer » */
add_filter( 'woocommerce_cart_totals_coupon_label', function ( $label, $coupon ) {
	return 'Code promo <span class="cz-coupon-code">' . esc_html( strtoupper( $coupon->get_code() ) ) . '</span>';
}, 10, 2 );

add_filter( 'woocommerce_cart_totals_coupon_html', function ( $html ) {
	return str_replace( array( '[Retirer]', '[Remove]', '[Supprimer]' ), 'Retirer', $html );
} );

/* Messages d'erreur clairs */
add_filter( 'woocommerce_coupon_error', function ( $err, $err_code, $coupon ) {
	$code = $coupon instanceof WC_Coupon ? strtoupper( $coupon->get_code() ) : '';
	switch ( $err_code ) {
		case WC_Coupon::E_WC_COUPON_NOT_EXIST:
			return sprintf( 'Le code « %s » n’existe pas. Vérifiez son orthographe.', $code );
		case WC_Coupon::E_WC_COUPON_EXPIRED:
			return sprintf( 'Le code « %s » a expiré.', $code );
		case WC_Coupon::E_WC_COUPON_USAGE_LIMIT_REACHED:
			return sprintf( 'Le code « %s » n’est plus disponible : il a déjà été utilisé le nombre de fois prévu.', $code );
		case WC_Coupon::E_WC_COUPON_NOT_APPLICABLE:
		case WC_Coupon::E_WC_COUPON_EXCLUDED_PRODUCTS:
		case WC_Coupon::E_WC_COUPON_EXCLUDED_CATEGORIES:
		case WC_Coupon::E_WC_COUPON_NOT_VALID_SALE_ITEMS:
			return sprintf( 'Le code « %s » n’est valable pour aucun produit de votre panier.', $code );
		case WC_Coupon::E_WC_COUPON_ALREADY_APPLIED:
			return sprintf( 'Le code « %s » est déjà appliqué à votre panier.', $code );
		case WC_Coupon::E_WC_COUPON_PLEASE_ENTER:
			return 'Saisissez un code promo.';
	}
	return $err;
}, 10, 3 );

/* Messages de confirmation */
add_filter( 'woocommerce_coupon_message', function ( $msg, $msg_code, $coupon ) {
	$code = $coupon instanceof WC_Coupon ? strtoupper( $coupon->get_code() ) : '';
	if ( WC_Coupon::WC_COUPON_SUCCESS === $msg_code ) {
		return sprintf( 'Le code « %s » est appliqué à votre panier.', $code );
	}
	if ( WC_Coupon::WC_COUPON_REMOVED === $msg_code ) {
		return sprintf( 'Le code « %s » a été retiré.', $code );
	}
	return $msg;
}, 10, 3 );

/* Retrait d'un code (lien « Retirer », traité en AJAX par WooCommerce) */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Coupon has been removed.' === $text ) {
		return 'Le code promo a été retiré.';
	}
	return $translation;
}, 20, 3 );

/* =========================================================================
 * 21. Équipe (itération 11)
 *     Type de contenu « Membres de l'équipe » : une page par salarié
 *     (/equipe/prenom-nom/) et une page qui les liste tous (/equipe/).
 *     Gabarits : archive-cz_equipe.php et single-cz_equipe.php
 * ====================================================================== */

add_action( 'init', function () {
	register_post_type(
		'cz_equipe',
		array(
			'labels'             => array(
				'name'                  => 'Équipe',
				'singular_name'         => 'Membre de l’équipe',
				'menu_name'             => 'Équipe',
				'all_items'             => 'Tous les membres',
				'add_new'               => 'Ajouter un membre',
				'add_new_item'          => 'Ajouter un membre de l’équipe',
				'edit_item'             => 'Modifier le membre',
				'new_item'              => 'Nouveau membre',
				'view_item'             => 'Voir le profil',
				'view_items'            => 'Voir l’équipe',
				'search_items'          => 'Rechercher un membre',
				'not_found'             => 'Aucun membre',
				'not_found_in_trash'    => 'Aucun membre dans la corbeille',
				'featured_image'        => 'Photo du membre',
				'set_featured_image'    => 'Choisir la photo',
				'remove_featured_image' => 'Retirer la photo',
				'use_featured_image'    => 'Utiliser comme photo',
				'archives'              => 'Notre équipe',
			),
			'public'             => true,
			'has_archive'        => 'equipe',
			'rewrite'            => array(
				'slug'       => 'equipe',
				'with_front' => false,
			),
			'menu_icon'          => 'dashicons-groups',
			'menu_position'      => 26,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'       => false, /* éditeur classique : plus simple pour une fiche */
			'show_in_nav_menus'  => true,
		)
	);
} );

/* Titre : « Prénom Nom » */
add_filter( 'enter_title_here', function ( $text, $post ) {
	return 'cz_equipe' === $post->post_type ? 'Prénom Nom' : $text;
}, 10, 2 );

/* Champs du profil (Secure Custom Fields) */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'                   => 'group_cz_equipe',
			'title'                 => 'Profil',
			'position'              => 'acf_after_title',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'fields'                => array(
				array(
					'key'          => 'field_cz_poste',
					'label'        => 'Poste',
					'name'         => 'cz_poste',
					'type'         => 'text',
					'required'     => 1,
					'placeholder'  => 'Ex. : Maître chocolatier',
					'wrapper'      => array( 'width' => 50 ),
				),
				array(
					'key'          => 'field_cz_depuis',
					'label'        => 'Chez Cluizel depuis (année)',
					'name'         => 'cz_depuis',
					'type'         => 'number',
					'min'          => 1948,
					'max'          => 2100,
					'placeholder'  => 'Ex. : 2015',
					'wrapper'      => array( 'width' => 25 ),
				),
				array(
					'key'          => 'field_cz_specialite',
					'label'        => 'Spécialité',
					'name'         => 'cz_specialite',
					'type'         => 'text',
					'placeholder'  => 'Ex. : Ganaches et pralinés',
					'wrapper'      => array( 'width' => 25 ),
				),
				array(
					'key'          => 'field_cz_citation',
					'label'        => 'Citation',
					'name'         => 'cz_citation',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'Une phrase courte, affichée en grand sur le profil.',
				),
				array(
					'key'           => 'field_cz_prefere',
					'label'         => 'Chocolat préféré',
					'name'          => 'cz_prefere',
					'type'          => 'post_object',
					'post_type'     => array( 'product' ),
					'return_format' => 'id',
					'allow_null'    => 1,
					'ui'            => 1,
					'instructions'  => 'Produit de la carte mis en avant sur le profil.',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'cz_equipe',
					),
				),
			),
			'hide_on_screen'        => array( 'permalink', 'excerpt', 'discussion', 'comments', 'author', 'format', 'categories', 'tags', 'send-trackbacks' ),
		)
	);
} );

/* Lecture d'un champ (SCF si actif, sinon la métadonnée) */
function cz_member_field( $name, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$value   = function_exists( 'get_field' ) ? get_field( $name, $post_id ) : get_post_meta( $post_id, $name, true );
	return is_string( $value ) ? trim( $value ) : $value;
}

/* Liste : tous les membres, dans l'ordre choisi (attribut « Ordre ») */
add_action( 'pre_get_posts', function ( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'cz_equipe' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
} );

/* Admin : colonnes Photo et Poste, tri par ordre */
add_filter( 'manage_cz_equipe_posts_columns', function ( $columns ) {
	return array(
		'cb'        => $columns['cb'],
		'cz_photo'  => 'Photo',
		'title'     => 'Nom',
		'cz_poste'  => 'Poste',
		'cz_ordre'  => 'Ordre',
		'date'      => $columns['date'],
	);
} );

add_action( 'manage_cz_equipe_posts_custom_column', function ( $column, $post_id ) {
	if ( 'cz_photo' === $column ) {
		echo get_the_post_thumbnail( $post_id, array( 48, 48 ), array( 'style' => 'border-radius:50%;object-fit:cover;width:48px;height:48px;' ) );
	} elseif ( 'cz_poste' === $column ) {
		echo esc_html( (string) cz_member_field( 'cz_poste', $post_id ) );
	} elseif ( 'cz_ordre' === $column ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}, 10, 2 );

add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() && $query->is_main_query() && 'cz_equipe' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
} );

/* Réglages de la page « Notre équipe » : Équipe › Page Équipe */
function cz_team_settings() {
	return wp_parse_args(
		array_filter( (array) get_option( 'cz_team_settings', array() ) ),
		array(
			'title' => 'Notre équipe',
			'intro' => 'Depuis 1948, nos chocolatiers, nos conseillers et nos artisans font vivre la maison Cluizel. Faites leur connaissance.',
		)
	);
}

add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=cz_equipe', 'Page Équipe', 'Page Équipe', 'edit_pages', 'cz-team', 'cz_team_settings_page' );
} );

add_action( 'admin_init', function () {
	register_setting(
		'cz_team',
		'cz_team_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => function ( $input ) {
				$input = (array) $input;
				return array(
					'title' => sanitize_text_field( $input['title'] ?? '' ),
					'intro' => sanitize_textarea_field( $input['intro'] ?? '' ),
				);
			},
		)
	);
} );

add_filter( 'option_page_capability_cz_team', fn() => 'edit_pages' );

function cz_team_settings_page() {
	$s = cz_team_settings();
	?>
	<div class="wrap">
		<h1>Page Équipe</h1>
		<p>Titre et texte d’introduction de la page qui liste tous les membres : <a href="<?php echo esc_url( get_post_type_archive_link( 'cz_equipe' ) ); ?>" target="_blank"><?php echo esc_html( get_post_type_archive_link( 'cz_equipe' ) ); ?></a>. L’ordre des membres se règle dans chaque fiche (Attributs › Ordre).</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'cz_team' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cz-team-title">Titre</label></th>
					<td><input type="text" id="cz-team-title" name="cz_team_settings[title]" value="<?php echo esc_attr( $s['title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-team-intro">Introduction</label></th>
					<td><textarea id="cz-team-intro" name="cz_team_settings[intro]" rows="4" class="large-text"><?php echo esc_textarea( $s['intro'] ); ?></textarea></td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}

/* Fil d'Ariane : « Accueil / Notre équipe / Prénom Nom » */
add_filter( 'woocommerce_get_breadcrumb', function ( $crumbs ) {
	if ( is_post_type_archive( 'cz_equipe' ) || is_singular( 'cz_equipe' ) ) {
		foreach ( $crumbs as $i => $crumb ) {
			if ( isset( $crumb[1] ) && get_post_type_archive_link( 'cz_equipe' ) === $crumb[1] || ( is_post_type_archive( 'cz_equipe' ) && $i === count( $crumbs ) - 1 ) ) {
				$crumbs[ $i ][0] = cz_team_settings()['title'];
			}
		}
	}
	return $crumbs;
} );

/* Carte d'un membre (liste et « Les autres membres ») */
function cz_member_card( $post_id ) {
	$poste = (string) cz_member_field( 'cz_poste', $post_id );
	?>
	<li class="cz-member-card">
		<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
			<span class="cz-member-photo">
				<?php
				if ( has_post_thumbnail( $post_id ) ) {
					echo get_the_post_thumbnail( $post_id, 'large', array( 'alt' => '' ) );
				} else {
					echo '<span class="cz-member-initials" aria-hidden="true">' . esc_html( mb_substr( get_the_title( $post_id ), 0, 1 ) ) . '</span>';
				}
				?>
			</span>
			<span class="cz-member-body">
				<span class="cz-member-name"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
				<?php if ( $poste ) : ?>
					<span class="cz-member-role"><?php echo esc_html( $poste ); ?></span>
				<?php endif; ?>
				<span class="cz-btn-outline">Voir le profil</span>
			</span>
		</a>
	</li>
	<?php
}

/* =========================================================================
 * 22. Taxonomie « Collections » (itération 12)
 *     Nouveau classement des produits, indépendant des catégories.
 *     Back office : Produits › Collections. Page dédiée : /collection/nom/
 *     (même grille que la carte). Page « Nos collections » : code court
 *     [cz_collections].
 * ====================================================================== */

add_action( 'init', function () {
	register_taxonomy(
		'cz_collection',
		array( 'product' ),
		array(
			'labels'            => array(
				'name'                       => 'Collections',
				'singular_name'              => 'Collection',
				'menu_name'                  => 'Collections',
				'all_items'                  => 'Toutes les collections',
				'edit_item'                  => 'Modifier la collection',
				'view_item'                  => 'Voir la collection',
				'update_item'                => 'Mettre à jour la collection',
				'add_new_item'               => 'Ajouter une collection',
				'new_item_name'              => 'Nom de la nouvelle collection',
				'search_items'               => 'Rechercher une collection',
				'not_found'                  => 'Aucune collection',
				'back_to_items'              => '← Retour aux collections',
				'parent_item'                => 'Collection parente',
				'parent_item_colon'          => 'Collection parente :',
			),
			'public'            => true,
			'hierarchical'      => true, /* cases à cocher dans la fiche produit */
			'show_admin_column' => true, /* colonne dans la liste des produits */
			'show_in_quick_edit'=> true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'collection',
				'with_front' => false,
			),
		)
	);
}, 5 );

/* Collections non vides, dans l'ordre alphabétique */
function cz_collections() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'cz_collection',
			'hide_empty' => true,
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/* Image de la collection (Secure Custom Fields) */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_cz_collection',
			'title'    => 'Collection',
			'fields'   => array(
				array(
					'key'           => 'field_cz_collection_image',
					'label'         => 'Image de la collection',
					'name'          => 'cz_collection_image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'instructions'  => 'Affichée sur la page « Nos collections ».',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'cz_collection',
					),
				),
			),
		)
	);
} );

function cz_collection_image_id( $term ) {
	$id = function_exists( 'get_field' ) ? get_field( 'cz_collection_image', $term ) : get_term_meta( $term->term_id, 'cz_collection_image', true );
	return (int) $id;
}

/* Liste des produits (admin) : filtre par collection */
add_action( 'restrict_manage_posts', function ( $post_type ) {
	if ( 'product' !== $post_type ) {
		return;
	}
	wp_dropdown_categories(
		array(
			'taxonomy'        => 'cz_collection',
			'name'            => 'cz_collection',
			'value_field'     => 'slug',
			'show_option_all' => 'Toutes les collections',
			'selected'        => isset( $_GET['cz_collection'] ) ? sanitize_title( wp_unslash( $_GET['cz_collection'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
			'hide_empty'      => false,
			'hierarchical'    => true,
		)
	);
} );

/* Page d'une collection : description sous le titre */
add_action( 'storefront_before_content', function () {
	if ( ! is_tax( 'cz_collection' ) ) {
		return;
	}
	$term = get_queried_object();
	if ( $term && $term->description ) {
		echo '<div class="col-full"><div class="cz-collection-intro">' . wp_kses_post( wpautop( $term->description ) ) . '</div></div>';
	}
}, 20 );

/* Fil d'Ariane : « Accueil / Nos collections / Nom » (lien vers la page qui contient [cz_collections]) */
add_filter( 'woocommerce_get_breadcrumb', function ( $crumbs ) {
	if ( ! is_tax( 'cz_collection' ) ) {
		return $crumbs;
	}
	$page = cz_collections_page_url();
	foreach ( $crumbs as $i => $crumb ) {
		if ( 'Collections' === $crumb[0] ) {
			$crumbs[ $i ] = array( 'Nos collections', $page );
		}
	}
	return $crumbs;
} );

function cz_collections_page_url() {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			's'              => '[cz_collections',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	return $pages ? get_permalink( $pages[0] ) : '';
}

/* Fiche produit : « Collection : … » sous le bouton d'ajout au panier */
add_action( 'woocommerce_single_product_summary', function () {
	$terms = get_the_terms( get_the_ID(), 'cz_collection' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}
	$links = array_map( fn( $t ) => '<a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>', $terms );
	echo '<p class="cz-product-collection">' . ( count( $terms ) > 1 ? 'Collections : ' : 'Collection : ' ) . implode( ', ', $links ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput
}, 35 );

/* Code court [cz_collections] : cartes des collections (page « Nos collections ») */
add_shortcode( 'cz_collections', function () {
	$collections = cz_collections();
	if ( ! $collections ) {
		return '<p>Les collections seront bientôt présentées ici.</p>';
	}
	ob_start();
	echo '<ul class="cz-collections">';
	foreach ( $collections as $col ) {
		$image_id = cz_collection_image_id( $col );
		if ( ! $image_id ) {
			/* Sans image : celle du premier produit de la collection */
			$first    = get_posts(
				array(
					'post_type'      => 'product',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'tax_query'      => array( array( 'taxonomy' => 'cz_collection', 'terms' => $col->term_id ) ), // phpcs:ignore WordPress.DB.SlowDBQuery
				)
			);
			$image_id = $first ? (int) get_post_thumbnail_id( $first[0] ) : 0;
		}
		?>
		<li class="cz-collection-card">
			<a href="<?php echo esc_url( get_term_link( $col ) ); ?>">
				<span class="cz-collection-image"><?php echo $image_id ? wp_get_attachment_image( $image_id, 'woocommerce_single', false, array( 'alt' => '' ) ) : ''; ?></span>
				<span class="cz-collection-body">
					<span class="cz-collection-name"><?php echo esc_html( $col->name ); ?></span>
					<span class="cz-collection-count"><?php echo (int) $col->count; ?> <?php echo 1 < $col->count ? 'produits' : 'produit'; ?></span>
					<?php if ( $col->description ) : ?>
						<span class="cz-collection-desc"><?php echo esc_html( wp_trim_words( $col->description, 22 ) ); ?></span>
					<?php endif; ?>
					<span class="cz-btn-outline">Découvrir</span>
				</span>
			</a>
		</li>
		<?php
	}
	echo '</ul>';
	return ob_get_clean();
} );

/* =========================================================================
 * 23. Ventes croisées (itération 13)
 *     Back office : fiche produit › Données produit › Produits liés
 *       - « Produits suggérés » et « Ventes croisées » : affichés sur la fiche
 *         produit (« Pour compléter votre commande »), en premier ;
 *       - « Ventes croisées » : affichées dans le panier (« Vous aimerez aussi »).
 *     Si aucun produit n'est choisi, on complète avec la même catégorie.
 * ====================================================================== */

/* Produit publié, visible et en stock */
function cz_is_suggestable( $id ) {
	$p = wc_get_product( $id );
	return $p && 'publish' === $p->get_status() && $p->is_visible() && $p->is_in_stock();
}

/* Les 3 suggestions de la fiche produit, dans l'ordre :
   produits suggérés, ventes croisées, même catégorie, puis au hasard */
function cz_product_suggestion_ids( $product, $limit = 3 ) {
	$self = $product->get_id();
	$ids  = array_merge( $product->get_upsell_ids(), $product->get_cross_sell_ids() );
	$ids  = array_values( array_unique( array_filter( array_diff( $ids, array( $self ) ), 'cz_is_suggestable' ) ) );

	if ( count( $ids ) < $limit ) {
		$related = wc_get_related_products( $self, $limit + 3, $ids );
		$ids     = array_values( array_unique( array_merge( $ids, array_filter( $related, 'cz_is_suggestable' ) ) ) );
	}
	if ( count( $ids ) < $limit ) {
		$extra = wc_get_products(
			array(
				'status'       => 'publish',
				'visibility'   => 'catalog',
				'stock_status' => 'instock',
				'limit'        => $limit - count( $ids ),
				'exclude'      => array_merge( $ids, array( $self ) ),
				'orderby'      => 'rand',
				'return'       => 'ids',
			)
		);
		$ids   = array_merge( $ids, $extra );
	}
	return array_slice( $ids, 0, $limit );
}

/* Fiche produit : « Pour compléter votre commande » avec nos suggestions
   (même gabarit et mêmes cartes qu'avant, mais dans l'ordre choisi) */
add_action( 'init', function () {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	/* Les « produits suggérés » sont déjà dans nos suggestions : pas de seconde liste */
	remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
}, 30 );

add_action( 'woocommerce_after_single_product_summary', function () {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$products = array_filter( array_map( 'wc_get_product', cz_product_suggestion_ids( $product, 3 ) ) );
	if ( ! $products ) {
		return;
	}
	wc_set_loop_prop( 'name', 'related' );
	wc_set_loop_prop( 'columns', 3 );
	wc_get_template(
		'single-product/related.php',
		array(
			'related_products' => $products,
			'columns'          => 3,
			'posts_per_page'   => 3,
			'orderby'          => 'none',
		)
	);
}, 20 );

/* =========================================================================
 * 24. Avis clients (itération 14)
 *     Gabarit : woocommerce/single-product-reviews.php
 *     Section « Avis clients » sous la fiche produit, note moyenne sous le
 *     titre, avis réservés aux clients qui ont acheté le produit.
 * ====================================================================== */

/* Une commande « Prête à retirer » est payée ou en passe de l'être :
   elle compte comme un achat (pour les avis « Achat vérifié ») */
add_filter( 'woocommerce_order_is_paid_statuses', function ( $statuses ) {
	$statuses[] = 'ready-pickup';
	return array_unique( $statuses );
} );

/* Le client connecté peut-il laisser un avis sur ce produit ? */
function cz_customer_can_review( $product ) {
	if ( ! is_user_logged_in() || ! $product instanceof WC_Product || ! comments_open( $product->get_id() ) ) {
		return false;
	}
	if ( 'yes' !== get_option( 'woocommerce_review_rating_verification_required' ) ) {
		return true;
	}
	return wc_customer_bought_product( '', get_current_user_id(), $product->get_id() );
}

/* Sécurité : refus côté serveur si le client n'a pas acheté le produit */
add_filter( 'preprocess_comment', function ( $data ) {
	$post_id = isset( $data['comment_post_ID'] ) ? (int) $data['comment_post_ID'] : 0;
	if ( 'product' !== get_post_type( $post_id ) || ( isset( $data['comment_type'] ) && 'review' !== $data['comment_type'] && '' !== $data['comment_type'] && 'comment' !== $data['comment_type'] ) ) {
		return $data;
	}
	if ( is_admin() && current_user_can( 'moderate_comments' ) ) {
		return $data; /* réponse de la boutique depuis l'admin */
	}
	if ( ! cz_customer_can_review( wc_get_product( $post_id ) ) ) {
		wp_die(
			esc_html( 'Seuls les clients connectés ayant acheté ce produit peuvent laisser un avis.' ),
			esc_html( 'Avis non autorisé' ),
			array( 'response' => 403, 'back_link' => true )
		);
	}
	return $data;
}, 5 );

/* Étoiles (note sur 5, demi-étoiles possibles) */
function cz_stars_html( $rating ) {
	$rating = max( 0, min( 5, (float) $rating ) );
	$label  = sprintf( 'Note : %s sur 5', number_format_i18n( $rating, 1 ) );
	return '<span class="cz-stars" role="img" aria-label="' . esc_attr( $label ) . '"><span class="cz-stars-fill" style="width:' . esc_attr( $rating / 5 * 100 ) . '%"></span></span>';
}

/* Un avis de la liste */
function cz_review_item( $comment, $args, $depth ) {
	$rating   = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
	$verified = function_exists( 'wc_review_is_from_verified_owner' ) && wc_review_is_from_verified_owner( $comment->comment_ID );
	$is_shop  = user_can( (int) $comment->user_id, 'manage_woocommerce' );
	$name     = get_comment_author( $comment );
	?>
	<li <?php comment_class( 'cz-review' . ( $depth > 1 ? ' cz-review-reply' : '' ), $comment ); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="cz-review-card">
			<header class="cz-review-head">
				<span class="cz-review-avatar" aria-hidden="true"><?php echo esc_html( mb_strtoupper( mb_substr( $name, 0, 1 ) ) ); ?></span>
				<span class="cz-review-who">
					<span class="cz-review-author"><?php echo esc_html( $is_shop ? 'Cluizel Paris' : $name ); ?></span>
					<time class="cz-review-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>"><?php echo esc_html( get_comment_date( 'j F Y', $comment ) ); ?></time>
				</span>
				<?php if ( $verified && ! $is_shop ) : ?>
					<span class="cz-review-verified">Achat vérifié</span>
				<?php elseif ( $is_shop ) : ?>
					<span class="cz-review-verified">Réponse de la boutique</span>
				<?php endif; ?>
			</header>
			<?php if ( $rating && 1 === (int) $depth ) : ?>
				<?php echo cz_stars_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php endif; ?>
			<?php if ( '0' === (string) $comment->comment_approved ) : ?>
				<p class="cz-review-pending">Votre avis est en attente de validation. Merci !</p>
			<?php endif; ?>
			<div class="cz-review-text"><?php comment_text( $comment ); ?></div>
		</article>
	<?php
	/* La balise </li> est fermée par WordPress */
}

/* Section « Avis clients » sous la fiche, avant « Pour compléter votre commande » */
add_action( 'woocommerce_after_single_product_summary', function () {
	if ( comments_open() ) {
		comments_template();
	}
}, 15 );

/* Sous le titre : étoiles, note et lien vers les avis */
add_action( 'woocommerce_single_product_summary', function () {
	global $product;
	if ( ! $product instanceof WC_Product || 'no' === get_option( 'woocommerce_enable_reviews' ) || ! comments_open() ) {
		return;
	}
	$count = (int) $product->get_review_count();
	echo '<p class="cz-product-rating">';
	if ( $count ) {
		echo cz_stars_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput
		echo ' <span>' . esc_html( number_format_i18n( (float) $product->get_average_rating(), 1 ) ) . '</span>';
		echo ' <a href="#reviews">' . esc_html( $count . ' avis' ) . '</a>';
	} else {
		echo '<a href="#reviews">Aucun avis – donnez le vôtre</a>';
	}
	echo '</p>';
}, 6 );

/* Messages de WooCommerce et WordPress pour les avis */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	$map = array(
		'Please select a rating'          => 'Veuillez choisir une note (de 1 à 5 étoiles).',
		'Please type your comment text.'  => 'Écrivez quelques mots pour votre avis.',
		'Duplicate comment detected; it looks as though you&#8217;ve already said that!' => 'Vous avez déjà publié cet avis.',
	);
	return $map[ $text ] ?? $translation;
}, 20, 3 );

/* =========================================================================
 * 25. Frais de préparation (itération 15)
 *     Si le montant des articles est inférieur au seuil, des frais fixes
 *     s'ajoutent au panier. Réglages : WooCommerce › Frais de préparation.
 * ====================================================================== */

function cz_prep_fee_defaults() {
	return array(
		'enabled'   => 'yes',
		'label'     => 'Frais de préparation',
		'amount'    => '2.50',
		'threshold' => '25',
	);
}

function cz_prep_fee_settings() {
	$saved = (array) get_option( 'cz_prep_fee', array() );
	return wp_parse_args( $saved, cz_prep_fee_defaults() );
}

/* Montant pris en compte : articles TTC après codes promo, hors livraison */
function cz_prep_fee_basis( $cart ) {
	return (float) $cart->get_cart_contents_total() + (float) $cart->get_cart_contents_tax();
}

/* Page de réglages */
add_action( 'admin_menu', function () {
	add_submenu_page( 'woocommerce', 'Frais de préparation', 'Frais de préparation', 'manage_woocommerce', 'cz-frais', 'cz_prep_fee_page' );
}, 62 );

add_action( 'admin_init', function () {
	register_setting(
		'cz_prep_fee',
		'cz_prep_fee',
		array(
			'type'              => 'array',
			'sanitize_callback' => function ( $input ) {
				$input = (array) $input;
				$num   = fn( $v ) => max( 0, (float) str_replace( ',', '.', (string) $v ) );
				return array(
					'enabled'   => empty( $input['enabled'] ) ? 'no' : 'yes',
					'label'     => sanitize_text_field( $input['label'] ?? '' ) ?: 'Frais de préparation',
					'amount'    => wc_format_decimal( $num( $input['amount'] ?? 0 ), 2 ),
					'threshold' => wc_format_decimal( $num( $input['threshold'] ?? 0 ), 2 ),
				);
			},
		)
	);
} );

add_filter( 'option_page_capability_cz_prep_fee', fn() => 'manage_woocommerce' );

function cz_prep_fee_page() {
	$s = cz_prep_fee_settings();
	?>
	<div class="wrap">
		<h1>Frais de préparation</h1>
		<p>Des frais fixes s’ajoutent automatiquement au panier quand le montant des articles (TTC, après codes promo, hors livraison) est <strong>inférieur au seuil</strong>. Ils disparaissent dès que le seuil est atteint.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'cz_prep_fee' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">Activer</th>
					<td><label><input type="checkbox" name="cz_prep_fee[enabled]" value="yes" <?php checked( 'yes', $s['enabled'] ); ?>> Appliquer les frais de préparation</label></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-fee-label">Libellé</label></th>
					<td><input type="text" id="cz-fee-label" name="cz_prep_fee[label]" value="<?php echo esc_attr( $s['label'] ); ?>" class="regular-text"><p class="description">Affiché dans le panier, la commande et les e-mails.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-fee-amount">Montant des frais (€)</label></th>
					<td><input type="number" id="cz-fee-amount" name="cz_prep_fee[amount]" value="<?php echo esc_attr( $s['amount'] ); ?>" min="0" step="0.01" class="small-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-fee-threshold">Seuil (€)</label></th>
					<td><input type="number" id="cz-fee-threshold" name="cz_prep_fee[threshold]" value="<?php echo esc_attr( $s['threshold'] ); ?>" min="0" step="0.01" class="small-text"><p class="description">Les frais s’appliquent si le montant des articles est strictement inférieur à ce seuil.</p></td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}

/* Calcul : à chaque mise à jour du panier, les frais sont ajoutés ou non */
add_action( 'woocommerce_cart_calculate_fees', function ( $cart ) {
	if ( is_admin() && ! wp_doing_ajax() ) {
		return;
	}
	$s = cz_prep_fee_settings();
	if ( 'yes' !== $s['enabled'] || (float) $s['amount'] <= 0 || $cart->is_empty() ) {
		return;
	}
	if ( cz_prep_fee_basis( $cart ) < (float) $s['threshold'] ) {
		$cart->add_fee( $s['label'], (float) $s['amount'], false );
	}
} );

/* Message sous les totaux : combien il manque, ou frais offerts */
function cz_prep_fee_hint_html() {
	$cart = WC()->cart;
	$s    = cz_prep_fee_settings();
	if ( ! $cart || $cart->is_empty() || 'yes' !== $s['enabled'] || (float) $s['amount'] <= 0 ) {
		return '';
	}
	$missing = (float) $s['threshold'] - cz_prep_fee_basis( $cart );
	if ( $missing > 0 ) {
		return sprintf(
			'Plus que <strong>%1$s</strong> d’achats pour ne plus payer les %2$s (%3$s).',
			wc_price( $missing ),
			esc_html( mb_strtolower( $s['label'] ) ),
			wc_price( (float) $s['amount'] )
		);
	}
	return esc_html( $s['label'] ) . ' offerts à partir de ' . wc_price( (float) $s['threshold'] ) . ' d’achats.';
}

add_action( 'woocommerce_cart_totals_after_order_total', function () {
	$html = cz_prep_fee_hint_html();
	if ( $html ) {
		echo '<tr class="cz-fee-hint"><td colspan="2">' . wp_kses_post( $html ) . '</td></tr>';
	}
} );

add_action( 'woocommerce_review_order_after_order_total', function () {
	$html = cz_prep_fee_hint_html();
	if ( $html ) {
		echo '<div class="cz-row cz-fee-hint"><dd>' . wp_kses_post( $html ) . '</dd></div>';
	}
} );

/* =========================================================================
 * 26. Page d'accueil : les 3 derniers produits (itération 16)
 *     Code court [cz_nouveautes] à placer dans la page d'accueil.
 *     Options : nombre="3" titre="Nouveautés" texte="…" bouton="" (vide = pas de bouton)
 *     Les produits sont lus à chaque affichage : un nouveau produit publié
 *     apparaît tout seul, sans toucher à la page.
 * ====================================================================== */

add_shortcode( 'cz_nouveautes', function ( $atts ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return '';
	}
	$atts = shortcode_atts(
		array(
			'nombre' => 3,
			'titre'  => 'Nouveautés',
			'texte'  => 'Les dernières créations de nos chocolatiers, tout juste arrivées à la carte.',
			'bouton' => '', /* pas de bouton (choix de la maquette) ; ex. bouton="Voir la carte" pour l'afficher */
		),
		$atts,
		'cz_nouveautes'
	);

	$products = wc_get_products(
		array(
			'status'     => 'publish',
			'visibility' => 'catalog',
			'limit'      => max( 1, min( 12, (int) $atts['nombre'] ) ),
			'orderby'    => 'date',
			'order'      => 'DESC',
		)
	);
	if ( ! $products ) {
		return '';
	}

	ob_start();
	?>
	<section class="cz-new" aria-labelledby="cz-new-title">
		<div class="cz-new-head">
			<div>
				<h2 id="cz-new-title" class="cz-new-title"><?php echo esc_html( $atts['titre'] ); ?></h2>
				<?php if ( $atts['texte'] ) : ?>
					<p class="cz-new-text"><?php echo esc_html( $atts['texte'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $atts['bouton'] ) : ?>
				<a class="cz-btn cz-new-all" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html( $atts['bouton'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php
		wc_setup_loop(
			array(
				'name'    => 'cz_new',
				'columns' => 3,
			)
		);
		echo '<ul class="products columns-3 cz-new-grid">';
		foreach ( $products as $product ) {
			$post_object = get_post( $product->get_id() );
			setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			wc_get_template_part( 'content', 'product' );
		}
		echo '</ul>';
		wp_reset_postdata();
		wc_reset_loop();
		?>
	</section>
	<?php
	return ob_get_clean();
} );

/* Pastille « Nouveau » sur les cartes de cette section */
add_action( 'woocommerce_before_shop_loop_item', function () {
	if ( 'cz_new' === wc_get_loop_prop( 'name' ) ) {
		echo '<span class="cz-new-badge">Nouveau</span>';
	}
}, 5 );

/* =========================================================================
 * 27. Page d'accueil (maquette « Accueil », itération 16)
 *     Gabarit : front-page.php (utilisé pour la page d'accueil statique).
 *     Textes réglables dans la page d'accueil (cadre « Page d'accueil »),
 *     visuel = image mise en avant de la page, univers = catégories de produits.
 * ====================================================================== */

function cz_home_defaults() {
	return array(
		'cz_hero_titre'  => 'Des gourmandises artisanales à retirer en boutique',
		'cz_hero_texte'  => 'Chocolatier depuis 1948, ingrédients nobles. Composez votre commande en ligne, récupérez-la sur place sans attendre',
		'cz_hero_bouton' => 'Voir la carte',
		'cz_etape1_titre' => 'Je compose mon panier',
		'cz_etape1_texte' => 'Je choisis mes produits et leurs options : format, garniture, emballage.',
		'cz_etape2_titre' => 'Je valide ma commande',
		'cz_etape2_texte' => 'Je paie par virement ou au retrait et je reçois un e-mail de confirmation.',
		'cz_etape3_titre' => 'Je retire en boutique',
		'cz_etape3_texte' => 'Je viens récupérer ma commande sur place, gratuitement.',
	);
}

/* Texte de la page d'accueil (valeur saisie, sinon celle de la maquette) */
function cz_home_text( $name ) {
	$id    = (int) get_option( 'page_on_front' );
	$value = $id ? ( function_exists( 'get_field' ) ? get_field( $name, $id ) : get_post_meta( $id, $name, true ) ) : '';
	$value = is_string( $value ) ? trim( $value ) : '';
	return '' !== $value ? $value : ( cz_home_defaults()[ $name ] ?? '' );
}

/* Champs de la page d'accueil (Secure Custom Fields) */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	$d      = cz_home_defaults();
	$fields = array(
		array( 'key' => 'field_cz_tab_hero', 'label' => 'Bandeau d’accueil', 'type' => 'tab' ),
		array( 'key' => 'field_cz_hero_titre', 'label' => 'Titre', 'name' => 'cz_hero_titre', 'type' => 'text', 'placeholder' => $d['cz_hero_titre'] ),
		array( 'key' => 'field_cz_hero_texte', 'label' => 'Texte', 'name' => 'cz_hero_texte', 'type' => 'textarea', 'rows' => 2, 'placeholder' => $d['cz_hero_texte'] ),
		array( 'key' => 'field_cz_hero_bouton', 'label' => 'Texte du bouton', 'name' => 'cz_hero_bouton', 'type' => 'text', 'placeholder' => $d['cz_hero_bouton'], 'instructions' => 'Le visuel est l’image mise en avant de la page (colonne de droite).' ),
		array( 'key' => 'field_cz_tab_etapes', 'label' => 'Comment ça marche ?', 'type' => 'tab' ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		$fields[] = array( 'key' => "field_cz_etape{$i}_titre", 'label' => "Étape $i – titre", 'name' => "cz_etape{$i}_titre", 'type' => 'text', 'placeholder' => $d[ "cz_etape{$i}_titre" ], 'wrapper' => array( 'width' => 40 ) );
		$fields[] = array( 'key' => "field_cz_etape{$i}_texte", 'label' => "Étape $i – texte", 'name' => "cz_etape{$i}_texte", 'type' => 'text', 'placeholder' => $d[ "cz_etape{$i}_texte" ], 'wrapper' => array( 'width' => 60 ) );
	}
	acf_add_local_field_group(
		array(
			'key'      => 'group_cz_home',
			'title'    => 'Page d’accueil',
			'fields'   => $fields,
			'position' => 'acf_after_title',
			'location' => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'instructions' => 'Laisser un champ vide pour garder le texte de la maquette.',
		)
	);
} );

/* Image d'une catégorie : sa miniature, sinon l'image d'un de ses produits */
function cz_category_image_id( $term ) {
	$id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
	if ( ! $id ) {
		$ids = wc_get_products(
			array(
				'status'   => 'publish',
				'limit'    => 1,
				'category' => array( $term->slug ),
				'return'   => 'ids',
			)
		);
		$id  = $ids ? (int) get_post_thumbnail_id( $ids[0] ) : 0;
	}
	return $id;
}

/* Pas de fil d'Ariane sur l'accueil */
add_action( 'wp', function () {
	if ( is_front_page() ) {
		remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
	}
}, 20 );

/* Nouveautés : image entière (taille « produit » non recadrée,
   au lieu de la miniature carrée qui coupe les coffrets sur les côtés) */
add_filter( 'single_product_archive_thumbnail_size', function ( $size ) {
	return 'cz_new' === wc_get_loop_prop( 'name' ) ? 'woocommerce_single' : $size;
} );

/* =========================================================================
 * 28. Factures PDF (itération 17)
 *     Extension « PDF Invoices & Packing Slips for WooCommerce ».
 *     Modèle personnalisé : woocommerce/pdf/Cluizel/ (logo, coordonnées,
 *     couleurs). Ici : le bouton « Télécharger ma facture » dans Mon compte
 *     (nos gabarits n'affichent pas la colonne « Actions » de WooCommerce).
 * ====================================================================== */

/* Lien de la facture fourni par l'extension (vide si pas encore disponible) */
function cz_invoice_url( $order ) {
	if ( ! $order instanceof WC_Order || ! function_exists( 'wc_get_account_orders_actions' ) ) {
		return '';
	}
	$actions = wc_get_account_orders_actions( $order );
	return isset( $actions['invoice']['url'] ) ? $actions['invoice']['url'] : '';
}

function cz_invoice_button( $order, $small = false ) {
	$url = cz_invoice_url( $order );
	if ( ! $url ) {
		return;
	}
	printf(
		'<a class="cz-invoice-link%1$s" href="%2$s" target="_blank" rel="noopener">%3$s<span class="screen-reader-text"> (PDF, commande N°%4$s)</span></a>',
		$small ? ' is-small' : '',
		esc_url( $url ),
		$small ? 'Facture' : 'Télécharger ma facture',
		esc_html( $order->get_order_number() )
	);
}

/* Détail d'une commande (Mon compte › Voir la commande) */
add_action( 'woocommerce_view_order', function ( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( cz_invoice_url( $order ) ) {
		echo '<p class="cz-invoice-row">';
		cz_invoice_button( $order );
		echo '</p>';
	}
}, 15 );

/* =========================================================================
 * 29. Recherche et filtrage (itération 18)
 *     Formulaire dans la barre latérale (et bouton « Filtrer » sur mobile) :
 *     recherche par mot-clé, prix minimum / maximum, attribut « Format »
 *     (la taille) et « Emballage ». Les paramètres sont ceux que WooCommerce
 *     comprend nativement : s, min_price, max_price, filter_format,
 *     filter_emballage (+ query_type_… = or).
 * ====================================================================== */

/* Attributs proposés en filtre : slug => libellé */
function cz_filter_attributes() {
	return array(
		'format'    => 'Format',
		'emballage' => 'Emballage',
	);
}

/* Cases cochées → « a,b » (format attendu par WooCommerce) */
add_action( 'init', function () {
	foreach ( array_keys( cz_filter_attributes() ) as $slug ) {
		$key = 'filter_' . $slug;
		if ( isset( $_GET[ $key ] ) && is_array( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$_GET[ $key ] = implode( ',', array_map( 'sanitize_title', wp_unslash( $_GET[ $key ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}
	}
}, 1 );

/* Adresse de la page en cours sans les filtres (carte, catégorie ou collection) */
function cz_filter_base_url() {
	if ( is_product_taxonomy() ) {
		$link = get_term_link( get_queried_object() );
		if ( ! is_wp_error( $link ) ) {
			return $link;
		}
	}
	return wc_get_page_permalink( 'shop' );
}

/* Filtres choisis : [ [ libellé, adresse pour le retirer ], … ] */
function cz_active_filters() {
	// phpcs:disable WordPress.Security.NonceVerification
	$active = array();
	$here   = remove_query_arg( 'paged', add_query_arg( null, null ) );
	$here   = preg_replace( '#/page/\d+/?#', '/', $here );
	$search = get_search_query();
	if ( '' !== $search ) {
		$active[] = array( '« ' . $search . ' »', remove_query_arg( array( 's', 'post_type' ), $here ) );
	}
	$min = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : '';
	$max = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : '';
	if ( '' !== $min || '' !== $max ) {
		$label    = '' !== $min && '' !== $max ? sprintf( '%s € à %s €', $min, $max ) : ( '' !== $min ? 'À partir de ' . $min . ' €' : 'Jusqu’à ' . $max . ' €' );
		$active[] = array( $label, remove_query_arg( array( 'min_price', 'max_price' ), $here ) );
	}
	foreach ( cz_filter_attributes() as $slug => $name ) {
		$key    = 'filter_' . $slug;
		$chosen = isset( $_GET[ $key ] ) && '' !== $_GET[ $key ] ? array_filter( explode( ',', wc_clean( wp_unslash( $_GET[ $key ] ) ) ) ) : array();
		foreach ( $chosen as $term_slug ) {
			$term = get_term_by( 'slug', $term_slug, 'pa_' . $slug );
			if ( ! $term ) {
				continue;
			}
			$rest     = array_diff( $chosen, array( $term_slug ) );
			$url      = $rest ? add_query_arg( $key, implode( ',', $rest ), $here ) : remove_query_arg( array( $key, 'query_type_' . $slug ), $here );
			$active[] = array( $term->name, $url );
		}
	}
	// phpcs:enable
	return $active;
}

/* Pastilles des filtres actifs, au-dessus de la grille */
function cz_active_filters_html() {
	$active = cz_active_filters();
	if ( ! $active ) {
		return;
	}
	echo '<div class="cz-active-filters" aria-label="Filtres actifs"><span class="cz-active-filters-label">Filtres :</span>';
	foreach ( $active as $filter ) {
		printf(
			'<a class="cz-filter-chip" href="%1$s">%2$s<span aria-hidden="true"> ×</span><span class="screen-reader-text"> (retirer ce filtre)</span></a>',
			esc_url( $filter[1] ),
			esc_html( $filter[0] )
		);
	}
	echo '<a class="cz-filters-clear" href="' . esc_url( cz_filter_base_url() ) . '">Tout effacer</a></div>';
}

/* Le formulaire de filtres ($id : « d » ordinateur, « m » mobile, pour des id uniques) */
function cz_filters_form( $id ) {
	// phpcs:disable WordPress.Security.NonceVerification
	$search = get_search_query();
	$min    = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : '';
	$max    = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : '';
	$range  = cz_price_bounds();
	?>
	<form class="cz-filters" action="<?php echo esc_url( cz_filter_base_url() ); ?>" method="get" role="search">
		<div class="cz-filter">
			<label class="cz-filter-title" for="cz-s-<?php echo esc_attr( $id ); ?>">Rechercher</label>
			<input type="search" id="cz-s-<?php echo esc_attr( $id ); ?>" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Nom, ingrédient…">
			<input type="hidden" name="post_type" value="product">
		</div>

		<fieldset class="cz-filter">
			<legend class="cz-filter-title">Prix</legend>
			<div class="cz-price-fields">
				<label><span class="screen-reader-text">Prix minimum</span>
					<input type="number" name="min_price" min="0" step="1" inputmode="numeric" value="<?php echo esc_attr( $min ); ?>" placeholder="<?php echo esc_attr( $range[0] ); ?> €">
				</label>
				<span aria-hidden="true">à</span>
				<label><span class="screen-reader-text">Prix maximum</span>
					<input type="number" name="max_price" min="0" step="1" inputmode="numeric" value="<?php echo esc_attr( $max ); ?>" placeholder="<?php echo esc_attr( $range[1] ); ?> €">
				</label>
			</div>
		</fieldset>

		<?php foreach ( cz_filter_attributes() as $slug => $name ) : ?>
			<?php
			$terms  = get_terms(
				array(
					'taxonomy'   => 'pa_' . $slug,
					'hide_empty' => true,
				)
			);
			$chosen = isset( $_GET[ 'filter_' . $slug ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ 'filter_' . $slug ] ) ) ) : array();
			if ( ! $terms || is_wp_error( $terms ) ) {
				continue;
			}
			?>
			<fieldset class="cz-filter">
				<legend class="cz-filter-title"><?php echo esc_html( $name ); ?></legend>
				<ul class="cz-filter-options">
					<?php foreach ( $terms as $term ) : ?>
						<li>
							<label>
								<input type="checkbox" name="filter_<?php echo esc_attr( $slug ); ?>[]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $chosen, true ) ); ?>>
								<span><?php echo esc_html( $term->name ); ?></span>
								<span class="cz-filter-count"><?php echo (int) $term->count; ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
				<input type="hidden" name="query_type_<?php echo esc_attr( $slug ); ?>" value="or">
			</fieldset>
		<?php endforeach; ?>

		<div class="cz-filter-actions">
			<button type="submit" class="cz-btn">Appliquer</button>
			<?php if ( cz_active_filters() ) : ?>
				<a class="cz-filters-clear" href="<?php echo esc_url( cz_filter_base_url() ); ?>">Effacer</a>
			<?php endif; ?>
		</div>
	</form>
	<?php
	// phpcs:enable
}

/* Prix le plus bas / le plus haut de la carte (arrondis aux 5 €), pour les exemples des champs */
function cz_price_bounds() {
	global $wpdb;
	$row = $wpdb->get_row(
		"SELECT MIN(l.min_price) AS min_price, MAX(l.max_price) AS max_price
		 FROM {$wpdb->wc_product_meta_lookup} l
		 INNER JOIN {$wpdb->posts} p ON p.ID = l.product_id
		 WHERE p.post_type = 'product' AND p.post_status = 'publish'"
	);
	if ( ! $row || null === $row->min_price ) {
		return array( 0, 100 );
	}
	return array( (int) floor( $row->min_price / 5 ) * 5, (int) ceil( $row->max_price / 5 ) * 5 );
}

/* Champs vides retirés de l'adresse (pas de « ?s=&min_price= ») */
add_action( 'wp_footer', function () {
	if ( ! cz_is_catalogue() ) {
		return;
	}
	?>
	<script>
	document.querySelectorAll( '.cz-filters' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function () {
			var s = form.querySelector( '[name="s"]' );
			form.querySelectorAll( 'input' ).forEach( function ( input ) {
				if ( ( input.type === 'search' || input.type === 'number' ) && input.value === '' ) {
					input.disabled = true;
				}
				if ( input.name === 'post_type' && s && s.value === '' ) {
					input.disabled = true;
				}
				if ( input.name.indexOf( 'query_type_' ) === 0 ) {
					var slug = input.name.replace( 'query_type_', '' );
					if ( ! form.querySelector( '[name="filter_' + slug + '[]"]:checked' ) ) {
						input.disabled = true;
					}
				}
			} );
		} );
	} );
	</script>
	<?php
} );

/* Recherche de produits : on reste sur la mise en page de la carte, avec un titre clair */
add_filter( 'woocommerce_page_title', function ( $title ) {
	if ( is_search() && 'product' === get_query_var( 'post_type' ) ) {
		return 'Recherche : « ' . get_search_query() . ' »';
	}
	return $title;
} );

/* Factures PDF : compatibilité avec les différentes versions de l'extension
   (certaines fonctions n'existent que dans les versions récentes) */
function cz_pdf_table_headers( $document ) {
	if ( function_exists( 'wpo_wcpdf_get_simple_template_default_table_headers' ) ) {
		return wpo_wcpdf_get_simple_template_default_table_headers( $document );
	}
	return array(
		'product'  => 'Produit',
		'quantity' => 'Quantité',
		'price'    => 'Prix',
	);
}

function cz_pdf_price( $price ) {
	if ( function_exists( 'wpo_ips_kses_price' ) ) {
		return wpo_ips_kses_price( $price );
	}
	return wp_kses_post( $price );
}

/* =========================================================================
 * 30. Livraison selon le poids (itération 19)
 *     Mode de livraison « Livraison selon le poids » : 3 tranches réglables
 *     dans WooCommerce › Réglages › Expédition › zone › Modifier.
 * ====================================================================== */

add_action( 'woocommerce_shipping_init', function () {
	require_once get_stylesheet_directory() . '/inc/class-cz-shipping-weight.php';
} );

add_filter( 'woocommerce_shipping_methods', function ( $methods ) {
	$methods['cz_weight'] = 'CZ_Shipping_Weight';
	return $methods;
} );

/* Poids total (kg) des articles à expédier, quelle que soit l'unité de la boutique */
function cz_package_weight( $contents ) {
	$weight = 0;
	foreach ( (array) $contents as $item ) {
		$product = $item['data'] ?? null;
		if ( ! $product || ! $product->needs_shipping() || '' === $product->get_weight() ) {
			continue;
		}
		$weight += wc_get_weight( (float) $product->get_weight(), 'kg' ) * (float) $item['quantity'];
	}
	return round( $weight, 3 );
}

/* 0.45 -> « 450 g » ; 1.2 -> « 1,2 kg » */
function cz_format_weight( $kg ) {
	$kg = (float) $kg;
	if ( $kg > 0 && $kg < 1 ) {
		return cz_format_number( round( $kg * 1000 ) ) . ' g';
	}
	return cz_format_number( round( $kg, 2 ) ) . ' kg';
}

/* « Jusqu'à 500 g », « De 500 g à 2 kg », « Plus de 2 kg » */
function cz_tier_label( $tier ) {
	if ( null === $tier['max'] ) {
		return 'Plus de ' . cz_format_weight( $tier['min'] );
	}
	if ( $tier['min'] <= 0 ) {
		return 'Jusqu’à ' . cz_format_weight( $tier['max'] );
	}
	return 'De ' . cz_format_weight( $tier['min'] ) . ' à ' . cz_format_weight( $tier['max'] );
}

/* Mode « Livraison selon le poids » actif dans la zone du panier (ou null) */
function cz_weight_method_for_cart() {
	if ( ! WC()->cart || ! WC()->cart->needs_shipping() ) {
		return null;
	}
	$packages = WC()->shipping()->get_packages();
	if ( empty( $packages[0] ) ) {
		return null;
	}
	$zone = WC_Shipping_Zones::get_zone_matching_package( $packages[0] );
	foreach ( $zone->get_shipping_methods( true ) as $method ) {
		if ( 'cz_weight' === $method->id ) {
			return $method;
		}
	}
	return null;
}

/* Grille des tarifs : poids du panier, 3 tranches, tranche actuelle en gras */
function cz_weight_grid_html() {
	$method = cz_weight_method_for_cart();
	if ( ! $method ) {
		return '';
	}
	$packages = WC()->shipping()->get_packages();
	$weight   = cz_package_weight( $packages[0]['contents'] ?? array() );
	$current  = $method->get_tier_index( $weight );

	$html  = '<p class="cz-weight-total">' . esc_html( $method->title ) . ' selon le poids · votre commande : <strong>' . esc_html( cz_format_weight( $weight ) ) . '</strong></p>';
	$html .= '<ul class="cz-weight-tiers">';
	foreach ( $method->get_tiers() as $i => $tier ) {
		$html .= sprintf(
			'<li class="%1$s"><span>%2$s</span><span>%3$s</span></li>',
			$i === $current ? 'is-current' : '',
			esc_html( cz_tier_label( $tier ) ),
			wp_kses_post( wc_price( $tier['cost'] ) )
		);
	}
	$html .= '</ul>';
	if ( null === $current ) {
		$html .= '<p class="cz-weight-over">Votre commande dépasse le poids maximum livrable : seul le retrait en boutique est possible.</p>';
	}
	return $html;
}

add_action( 'woocommerce_cart_totals_after_shipping', function () {
	$html = cz_weight_grid_html();
	if ( $html ) {
		echo '<tr class="cz-weight-grid"><td colspan="2">' . $html . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	}
} );

/* Sous la pilule « Livraison à domicile » : poids et tranche */
function cz_weight_rate_note( $rate ) {
	if ( ! $rate || 'cz_weight' !== $rate->get_method_id() ) {
		return '';
	}
	$meta = $rate->get_meta_data();
	if ( empty( $meta['Poids total'] ) ) {
		return '';
	}
	return '<span class="cz-ship-weight">Poids : ' . esc_html( $meta['Poids total'] ) . ' · ' . esc_html( mb_strtolower( $meta['Tranche'] ) ) . '</span>';
}

add_action( 'woocommerce_after_shipping_rate', function ( $rate ) {
	echo cz_weight_rate_note( $rate ); // phpcs:ignore WordPress.Security.EscapeOutput
} );

/* Back office : colonne « Poids » dans la liste des produits */
add_filter( 'manage_edit-product_columns', function ( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'price' === $key ) {
			$new['cz_weight'] = 'Poids';
		}
	}
	return $new;
}, 20 );

add_action( 'manage_product_posts_custom_column', function ( $column, $post_id ) {
	if ( 'cz_weight' !== $column ) {
		return;
	}
	$product = wc_get_product( $post_id );
	if ( ! $product ) {
		return;
	}
	$weights = array();
	if ( $product->is_type( 'variable' ) ) {
		foreach ( $product->get_children() as $child_id ) {
			$child = wc_get_product( $child_id );
			if ( $child && '' !== $child->get_weight() ) {
				$weights[] = wc_get_weight( (float) $child->get_weight(), 'kg' );
			}
		}
	} elseif ( '' !== $product->get_weight() ) {
		$weights[] = wc_get_weight( (float) $product->get_weight(), 'kg' );
	}
	if ( ! $weights ) {
		echo '<span style="color:#b32d2e">À renseigner</span>';
		return;
	}
	$min = min( $weights );
	$max = max( $weights );
	echo esc_html( $min === $max ? cz_format_weight( $min ) : cz_format_weight( $min ) . ' à ' . cz_format_weight( $max ) );
}, 10, 2 );

/* =========================================================================
 * 31. Créneaux de livraison et de retrait (itération 20)
 *     Le client choisit un jour et une plage horaire à la validation de la
 *     commande. Réglages : WooCommerce › Créneaux.
 * ====================================================================== */

function cz_slot_defaults() {
	return array(
		'pickup_days'    => array( 1, 2, 3, 4, 5, 6 ),
		'pickup_times'   => "10h00 - 12h00\n12h00 - 14h00\n14h00 - 16h00\n16h00 - 18h00",
		'pickup_delay'   => 1,
		'delivery_days'  => array( 2, 3, 4, 5, 6 ),
		'delivery_times' => "9h00 - 12h00\n14h00 - 18h00",
		'delivery_delay' => 2,
		'nb_days'        => 7,
		'capacity'       => 5,
	);
}

function cz_slot_settings() {
	$saved = get_option( 'cz_slots' );
	return is_array( $saved ) ? wp_parse_args( $saved, cz_slot_defaults() ) : cz_slot_defaults();
}

function cz_day_names() {
	return array( 1 => 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche' );
}

function cz_month_names() {
	return array( 1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' );
}

/* Plages horaires d'un type (« pickup » ou « delivery »), une par ligne */
function cz_slot_times( $type ) {
	$s = cz_slot_settings();
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $s[ $type . '_times' ] ) ) ) );
}

/* « 10h00 - 12h00 » -> 600 (heure de début en minutes) */
function cz_slot_start( $time ) {
	if ( preg_match( '/(\d{1,2})\s*[h:]\s*(\d{0,2})/i', $time, $m ) ) {
		return (int) $m[1] * 60 + (int) ( $m[2] ?: 0 );
	}
	return 0;
}

/* Nombre de commandes déjà prises par créneau : [ 'type|2026-10-27|10h00 - 12h00' => 3 ] */
function cz_slot_bookings() {
	static $counts = null;
	if ( null !== $counts ) {
		return $counts;
	}
	$counts = array();
	$today  = ( new DateTimeImmutable( 'now', wp_timezone() ) )->format( 'Y-m-d' );
	$orders = wc_get_orders(
		array(
			'limit'      => -1,
			'status'     => array( 'pending', 'on-hold', 'processing', 'ready-pickup', 'completed' ),
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => '_cz_slot_day',
					'value'   => $today,
					'compare' => '>=',
				),
			),
		)
	);
	foreach ( $orders as $order ) {
		$key            = $order->get_meta( '_cz_slot_type' ) . '|' . $order->get_meta( '_cz_slot_day' ) . '|' . $order->get_meta( '_cz_slot_time' );
		$counts[ $key ] = ( $counts[ $key ] ?? 0 ) + 1;
	}
	return $counts;
}

/* Jours proposés : [ [ 'date' => 'Y-m-d', 'ts' => DateTime, 'full' => [ plages indisponibles ] ], … ] */
function cz_slot_days( $type ) {
	$s        = cz_slot_settings();
	$open     = array_map( 'intval', (array) $s[ $type . '_days' ] );
	$times    = cz_slot_times( $type );
	$capacity = (int) $s['capacity'];
	$bookings = cz_slot_bookings();
	$now      = new DateTimeImmutable( 'now', wp_timezone() );
	$minutes  = (int) $now->format( 'G' ) * 60 + (int) $now->format( 'i' );
	$day      = $now->setTime( 0, 0 )->modify( '+' . max( 0, (int) $s[ $type . '_delay' ] ) . ' days' );
	$days     = array();

	if ( ! $open || ! $times ) {
		return $days;
	}
	for ( $i = 0; $i < 60 && count( $days ) < max( 1, (int) $s['nb_days'] ); $i++, $day = $day->modify( '+1 day' ) ) {
		if ( ! in_array( (int) $day->format( 'N' ), $open, true ) ) {
			continue;
		}
		$full = array();
		foreach ( $times as $time ) {
			$past   = $day->format( 'Y-m-d' ) === $now->format( 'Y-m-d' ) && cz_slot_start( $time ) <= $minutes + 60;
			$booked = $capacity > 0 && ( $bookings[ $type . '|' . $day->format( 'Y-m-d' ) . '|' . $time ] ?? 0 ) >= $capacity;
			if ( $past || $booked ) {
				$full[] = $time;
			}
		}
		if ( count( $full ) < count( $times ) ) {
			$days[] = array( 'date' => $day->format( 'Y-m-d' ), 'day' => $day, 'full' => $full );
		}
	}
	return $days;
}

/* « 2026-10-27 » -> « mardi 27 octobre » */
function cz_slot_date_label( $date ) {
	$d = DateTimeImmutable::createFromFormat( 'Y-m-d', $date, wp_timezone() );
	if ( ! $d ) {
		return $date;
	}
	return cz_day_names()[ (int) $d->format( 'N' ) ] . ' ' . $d->format( 'j' ) . ' ' . cz_month_names()[ (int) $d->format( 'n' ) ];
}

/* « 10h00 - 12h00 » -> « entre 10h00 et 12h00 » */
function cz_slot_time_label( $time ) {
	$parts = array_map( 'trim', preg_split( '/\s*[-–]\s*/', $time, 2 ) );
	return 2 === count( $parts ) ? 'entre ' . $parts[0] . ' et ' . $parts[1] : $time;
}

/* Type de créneau du panier en cours */
function cz_cart_slot_type() {
	return cz_is_local_pickup_chosen() ? 'pickup' : 'delivery';
}

/* Carte « Créneau de retrait / de livraison » sur la page de validation */
function cz_slot_picker() {
	if ( ! WC()->cart || ! WC()->cart->needs_shipping() ) {
		return;
	}
	$type   = cz_cart_slot_type();
	$days   = cz_slot_days( $type );
	$times  = cz_slot_times( $type );
	$pickup = 'pickup' === $type;
	$short  = array( 1 => 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.', 'dim.' );
	$months = array( 1 => 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.' );

	echo '<section class="cz-card cz-slots">';
	echo '<h2 class="cz-card-title">' . ( $pickup ? 'Créneau de retrait' : 'Créneau de livraison' ) . '</h2>';

	if ( ! $days ) {
		echo '<p>Aucun créneau n’est disponible pour le moment. Merci de nous contacter.</p></section>';
		return;
	}

	echo '<p class="cz-slots-help">' . ( $pickup ? 'Choisissez le jour et l’heure auxquels vous viendrez chercher votre commande en boutique.' : 'Choisissez le jour et la plage horaire où vous serez présent pour recevoir votre commande.' ) . '</p>';

	echo '<fieldset class="cz-slot-group"><legend>Jour <abbr class="required" title="obligatoire">*</abbr></legend><div class="cz-slot-days">';
	foreach ( $days as $d ) {
		printf(
			'<label class="cz-slot-day"><input type="radio" name="cz_slot_day" value="%1$s" data-full="%2$s"><span><small>%3$s</small><strong>%4$s</strong><small>%5$s</small></span></label>',
			esc_attr( $d['date'] ),
			esc_attr( wp_json_encode( $d['full'] ) ),
			esc_html( $short[ (int) $d['day']->format( 'N' ) ] ),
			esc_html( $d['day']->format( 'j' ) ),
			esc_html( $months[ (int) $d['day']->format( 'n' ) ] )
		);
	}
	echo '</div></fieldset>';

	echo '<fieldset class="cz-slot-group"><legend>Plage horaire <abbr class="required" title="obligatoire">*</abbr></legend><div class="cz-slot-times">';
	foreach ( $times as $time ) {
		printf(
			'<label class="cz-slot-time"><input type="radio" name="cz_slot_time" value="%1$s"><span>%2$s<small class="cz-slot-full">Complet</small></span></label>',
			esc_attr( $time ),
			esc_html( $time )
		);
	}
	echo '</div></fieldset>';
	echo '<input type="hidden" name="cz_slot_type" value="' . esc_attr( $type ) . '">';
	echo '</section>';
}

/* Plages complètes ou passées : désactivées selon le jour choisi */
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
		return;
	}
	?>
	<script>
	document.addEventListener( 'change', function ( e ) {
		if ( 'cz_slot_day' !== e.target.name ) {
			return;
		}
		var full = JSON.parse( e.target.dataset.full || '[]' );
		document.querySelectorAll( 'input[name="cz_slot_time"]' ).forEach( function ( input ) {
			var off = full.indexOf( input.value ) !== -1;
			input.disabled = off;
			input.closest( 'label' ).classList.toggle( 'is-full', off );
			if ( off ) {
				input.checked = false;
			}
		} );
	} );
	</script>
	<?php
} );

/* Vérification : jour et plage obligatoires et encore disponibles */
add_action( 'woocommerce_after_checkout_validation', function ( $data, $errors ) {
	if ( ! WC()->cart || ! WC()->cart->needs_shipping() ) {
		return;
	}
	$type   = cz_cart_slot_type();
	$what   = 'pickup' === $type ? 'de retrait' : 'de livraison';
	$day    = sanitize_text_field( wp_unslash( $_POST['cz_slot_day'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$time   = sanitize_text_field( wp_unslash( $_POST['cz_slot_time'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$days   = wp_list_pluck( cz_slot_days( $type ), 'full', 'date' );

	if ( '' === $day ) {
		$errors->add( 'cz_slot', 'Veuillez choisir un jour ' . $what . '.' );
	} elseif ( ! isset( $days[ $day ] ) ) {
		$errors->add( 'cz_slot', 'Ce jour ' . $what . ' n’est plus disponible, merci d’en choisir un autre.' );
	}
	if ( '' === $time ) {
		$errors->add( 'cz_slot', 'Veuillez choisir une plage horaire ' . $what . '.' );
	} elseif ( ! in_array( $time, cz_slot_times( $type ), true ) || ( isset( $days[ $day ] ) && in_array( $time, $days[ $day ], true ) ) ) {
		$errors->add( 'cz_slot', 'Cette plage horaire est complète, merci d’en choisir une autre.' );
	}
}, 10, 2 );

/* Enregistrement dans la commande */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
	if ( empty( $_POST['cz_slot_day'] ) || empty( $_POST['cz_slot_time'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	$order->update_meta_data( '_cz_slot_type', cz_cart_slot_type() );
	$order->update_meta_data( '_cz_slot_day', sanitize_text_field( wp_unslash( $_POST['cz_slot_day'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$order->update_meta_data( '_cz_slot_time', sanitize_text_field( wp_unslash( $_POST['cz_slot_time'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
} );

/* « Retrait le mardi 27 octobre, entre 10h00 et 12h00 » (vide si pas de créneau) */
function cz_order_slot_text( $order, $with_type = true ) {
	if ( ! $order instanceof WC_Order || ! $order->get_meta( '_cz_slot_day' ) ) {
		return '';
	}
	$text = cz_slot_date_label( $order->get_meta( '_cz_slot_day' ) ) . ', ' . cz_slot_time_label( $order->get_meta( '_cz_slot_time' ) );
	if ( ! $with_type ) {
		return $text;
	}
	return ( 'pickup' === $order->get_meta( '_cz_slot_type' ) ? 'Retrait le ' : 'Livraison le ' ) . $text;
}

/* Carte « Mon créneau » (page de confirmation, détail de commande) */
function cz_slot_card( $order ) {
	$text = cz_order_slot_text( $order );
	if ( $text ) {
		echo '<section class="cz-card cz-slot-card"><h2 class="cz-card-title">Mon créneau</h2><p>' . esc_html( $text ) . '</p></section>';
	}
}

add_action( 'woocommerce_view_order', function ( $order_id ) {
	cz_slot_card( wc_get_order( $order_id ) );
}, 5 );

/* E-mails : créneau rappelé après le tableau de la commande */
add_action( 'woocommerce_email_after_order_table', function ( $order, $sent_to_admin, $plain_text ) {
	$text = cz_order_slot_text( $order );
	if ( ! $text ) {
		return;
	}
	if ( $plain_text ) {
		echo "\n" . esc_html( mb_strtoupper( 'Votre créneau' ) . ' : ' . $text ) . "\n";
		return;
	}
	echo '<table class="cz-email-card" border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="cz-email-card-cell">';
	echo '<h2>' . ( $sent_to_admin ? 'Créneau choisi' : 'Votre créneau' ) . '</h2><p><strong>' . esc_html( $text ) . '</strong></p>';
	echo '</td></tr></table>';
}, 5, 3 );

/* Back office : créneau dans la commande et dans la colonne « Réception » */
add_action( 'woocommerce_admin_order_data_after_shipping_address', function ( $order ) {
	$text = cz_order_slot_text( $order );
	if ( $text ) {
		echo '<p><strong>Créneau :</strong><br>' . esc_html( $text ) . '</p>';
	}
} );

$cz_slot_column = function ( $column, $order ) {
	$order = $order instanceof WC_Order ? $order : wc_get_order( $order );
	if ( 'cz_reception' === $column && $order && $order->get_meta( '_cz_slot_day' ) ) {
		$d = DateTimeImmutable::createFromFormat( 'Y-m-d', $order->get_meta( '_cz_slot_day' ) );
		echo '<br><small>' . esc_html( ( $d ? $d->format( 'd/m' ) : '' ) . ' · ' . $order->get_meta( '_cz_slot_time' ) ) . '</small>';
	}
};
add_action( 'manage_woocommerce_page_wc-orders_custom_column', $cz_slot_column, 20, 2 );
add_action( 'manage_shop_order_posts_custom_column', $cz_slot_column, 20, 2 );

/* Page de réglages : WooCommerce › Créneaux */
add_action( 'admin_menu', function () {
	add_submenu_page( 'woocommerce', 'Créneaux de livraison et de retrait', 'Créneaux', 'manage_woocommerce', 'cz-creneaux', 'cz_slot_settings_page' );
}, 63 );

add_action( 'admin_init', function () {
	register_setting(
		'cz_slots',
		'cz_slots',
		array(
			'type'              => 'array',
			'sanitize_callback' => function ( $input ) {
				$input = (array) $input;
				$days  = fn( $key ) => array_values( array_intersect( array_map( 'intval', (array) ( $input[ $key ] ?? array() ) ), range( 1, 7 ) ) );
				return array(
					'pickup_days'    => $days( 'pickup_days' ),
					'pickup_times'   => sanitize_textarea_field( $input['pickup_times'] ?? '' ),
					'pickup_delay'   => max( 0, (int) ( $input['pickup_delay'] ?? 0 ) ),
					'delivery_days'  => $days( 'delivery_days' ),
					'delivery_times' => sanitize_textarea_field( $input['delivery_times'] ?? '' ),
					'delivery_delay' => max( 0, (int) ( $input['delivery_delay'] ?? 0 ) ),
					'nb_days'        => min( 30, max( 1, (int) ( $input['nb_days'] ?? 7 ) ) ),
					'capacity'       => max( 0, (int) ( $input['capacity'] ?? 0 ) ),
				);
			},
		)
	);
} );

add_filter( 'option_page_capability_cz_slots', fn() => 'manage_woocommerce' );

function cz_slot_settings_page() {
	$s    = cz_slot_settings();
	$jour = cz_day_names();
	?>
	<div class="wrap">
		<h1>Créneaux de livraison et de retrait</h1>
		<p>À la validation de sa commande, le client choisit un jour puis une plage horaire, selon son mode de réception.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'cz_slots' ); ?>
			<?php foreach ( array( 'pickup' => 'Retrait en boutique', 'delivery' => 'Livraison à domicile' ) as $type => $title ) : ?>
				<h2><?php echo esc_html( $title ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">Jours proposés</th>
						<td>
							<?php foreach ( $jour as $n => $name ) : ?>
								<label style="margin-right:14px;"><input type="checkbox" name="cz_slots[<?php echo esc_attr( $type ); ?>_days][]" value="<?php echo esc_attr( $n ); ?>" <?php checked( in_array( $n, array_map( 'intval', (array) $s[ $type . '_days' ] ), true ) ); ?>> <?php echo esc_html( ucfirst( $name ) ); ?></label>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cz-<?php echo esc_attr( $type ); ?>-times">Plages horaires</label></th>
						<td>
							<textarea id="cz-<?php echo esc_attr( $type ); ?>-times" name="cz_slots[<?php echo esc_attr( $type ); ?>_times]" rows="4" class="regular-text"><?php echo esc_textarea( $s[ $type . '_times' ] ); ?></textarea>
							<p class="description">Une plage par ligne, par exemple : 10h00 - 12h00</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cz-<?php echo esc_attr( $type ); ?>-delay">Délai de préparation (jours)</label></th>
						<td>
							<input type="number" min="0" id="cz-<?php echo esc_attr( $type ); ?>-delay" name="cz_slots[<?php echo esc_attr( $type ); ?>_delay]" value="<?php echo esc_attr( $s[ $type . '_delay' ] ); ?>" class="small-text">
							<p class="description">1 = premier créneau proposé demain. 0 = dès aujourd’hui (les plages qui commencent dans moins d’une heure sont masquées).</p>
						</td>
					</tr>
				</table>
			<?php endforeach; ?>
			<h2>Pour les deux</h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cz-nb-days">Nombre de jours proposés</label></th>
					<td><input type="number" min="1" max="30" id="cz-nb-days" name="cz_slots[nb_days]" value="<?php echo esc_attr( $s['nb_days'] ); ?>" class="small-text"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cz-capacity">Commandes maximum par créneau</label></th>
					<td>
						<input type="number" min="0" id="cz-capacity" name="cz_slots[capacity]" value="<?php echo esc_attr( $s['capacity'] ); ?>" class="small-text">
						<p class="description">Quand ce nombre est atteint, la plage s’affiche « Complet ». 0 = sans limite.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer les créneaux' ); ?>
		</form>
	</div>
	<?php
}

/* =========================================================================
 * 32. SEO : compléments à l'extension Yoast SEO (itération 21)
 *     Yoast gère la balise Title, la meta description et l'Open Graph
 *     (champs SEO sous chaque produit, catégorie et collection).
 *     Le thème précise seulement le partage des fiches produit.
 * ====================================================================== */

/* Fiche produit partagée : type « product » au lieu de « article » */
add_filter( 'wpseo_opengraph_type', function ( $type ) {
	return ( function_exists( 'is_product' ) && is_product() ) ? 'product' : $type;
} );

/* Prix et disponibilité pour les réseaux sociaux (Facebook, Pinterest…) */
add_action( 'wpseo_head', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	$product = wc_get_product( get_queried_object_id() );
	if ( ! $product || '' === $product->get_price() ) {
		return;
	}
	printf( '<meta property="product:price:amount" content="%s" />' . "\n", esc_attr( wc_format_decimal( wc_get_price_to_display( $product ), 2 ) ) );
	printf( '<meta property="product:price:currency" content="%s" />' . "\n", esc_attr( get_woocommerce_currency() ) );
	printf( '<meta property="product:availability" content="%s" />' . "\n", $product->is_in_stock() ? 'in stock' : 'out of stock' );
}, 40 );

/* Liste des produits : on retire les colonnes de score Yoast pour garder la liste lisible
   (les scores restent visibles dans chaque fiche) */
add_filter( 'manage_edit-product_columns', function ( $columns ) {
	unset( $columns['wpseo-score'], $columns['wpseo-score-readability'], $columns['wpseo-links'], $columns['wpseo-linked'] );
	return $columns;
}, 30 );

/* =========================================================================
 * 33. Mentions légales et CGV (itération 22)
 *     Pages « Mentions légales » (slug mentions-legales) et « Conditions
 *     générales de vente » (page CGV choisie dans WooCommerce › Réglages ›
 *     Avancé). Acceptation obligatoire des CGV avant de commander.
 * ====================================================================== */

/* Adresse des pages légales : 'mentions', 'cgv' ou 'confidentialite' */
function cz_legal_url( $which ) {
	if ( 'cgv' === $which ) {
		$id = function_exists( 'wc_terms_and_conditions_page_id' ) ? wc_terms_and_conditions_page_id() : 0;
		if ( ! $id ) {
			$page = get_page_by_path( 'conditions-generales-de-vente' );
			$id   = $page ? $page->ID : 0;
		}
		return $id ? get_permalink( $id ) : '#';
	}
	if ( 'confidentialite' === $which ) {
		return get_privacy_policy_url() ?: '#';
	}
	$page = get_page_by_path( 'mentions-legales' );
	return $page ? get_permalink( $page ) : ( get_privacy_policy_url() ?: '#' );
}

/* Classe « cz-legal » sur les pages légales (mise en page de lecture) */
function cz_is_legal_page() {
	if ( ! is_page() ) {
		return false;
	}
	$id     = get_queried_object_id();
	$terms  = function_exists( 'wc_terms_and_conditions_page_id' ) ? (int) wc_terms_and_conditions_page_id() : 0;
	$slug   = get_post_field( 'post_name', $id );
	return $id === $terms || $id === (int) get_option( 'wp_page_for_privacy_policy' ) || in_array( $slug, array( 'mentions-legales', 'conditions-generales-de-vente' ), true );
}

add_filter( 'body_class', function ( $classes ) {
	if ( cz_is_legal_page() ) {
		$classes[] = 'cz-legal';
	}
	return $classes;
} );

/* Pas de fil d'Ariane Storefront en double : « Accueil / Mentions légales » suffit */
add_action( 'storefront_page_after', function () {
	if ( cz_is_legal_page() ) {
		echo '<p class="cz-legal-back"><a href="' . esc_url( home_url( '/' ) ) . '">← Retour à l’accueil</a></p>';
	}
} );

/* Case CGV : le lien ouvre la page des CGV dans un nouvel onglet
   (au lieu d'afficher tout le texte dans le formulaire) */
add_action( 'wp', function () {
	remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
} );

/* Message si la case n'est pas cochée */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Please read and accept the terms and conditions to proceed with your order.' === $text ) {
		return 'Veuillez accepter les conditions générales de vente pour valider votre commande.';
	}
	return $translation;
}, 20, 3 );

/* Preuve d'acceptation : date et heure enregistrées dans la commande */
add_action( 'woocommerce_checkout_create_order', function ( $order ) {
	if ( ! empty( $_POST['terms'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$order->update_meta_data( '_cz_terms_accepted', current_time( 'mysql' ) );
	}
} );

add_action( 'woocommerce_admin_order_data_after_billing_address', function ( $order ) {
	$date = $order->get_meta( '_cz_terms_accepted' );
	if ( $date ) {
		echo '<p><strong>CGV acceptées :</strong><br>le ' . esc_html( date_i18n( 'd/m/Y à H:i', strtotime( $date ) ) ) . '</p>';
	}
} );
