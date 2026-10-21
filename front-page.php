<?php
/**
 * Page d'accueil – Cluizel Paris (maquette « Accueil »)
 * WordPress utilise ce gabarit pour la page d'accueil statique
 * (Réglages › Lecture). Sections : bandeau d'accueil, nouveautés
 * (3 derniers produits, itération 16), nos univers, comment ça marche.
 *
 * @package storefront-child
 */

get_header();

$cz_front_id = (int) get_option( 'page_on_front' );
$cz_hero_img = $cz_front_id ? (int) get_post_thumbnail_id( $cz_front_id ) : 0;
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main cz-home">

		<!-- Bandeau d'accueil -->
		<section class="cz-hero">
			<div class="cz-hero-text">
				<h1 class="cz-hero-title"><?php echo esc_html( cz_home_text( 'cz_hero_titre' ) ); ?></h1>
				<p class="cz-hero-lead"><?php echo esc_html( cz_home_text( 'cz_hero_texte' ) ); ?></p>
				<a class="cz-btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo esc_html( cz_home_text( 'cz_hero_bouton' ) ); ?></a>
			</div>
			<div class="cz-hero-media">
				<?php
				if ( $cz_hero_img ) {
					echo wp_get_attachment_image( $cz_hero_img, 'large', false, array( 'alt' => '', 'fetchpriority' => 'high' ) );
				}
				?>
			</div>
		</section>

		<!-- Nouveautés : les 3 derniers produits ajoutés (itération 16) -->
		<?php echo do_shortcode( '[cz_nouveautes]' ); ?>

		<!-- Nos univers : les catégories de la carte -->
		<?php $cz_univers = cz_catalogue_categories(); ?>
		<?php if ( $cz_univers ) : ?>
			<section class="cz-univers" aria-labelledby="cz-univers-title">
				<h2 id="cz-univers-title" class="cz-home-title">Nos univers</h2>
				<ul class="cz-univers-grid">
					<?php foreach ( $cz_univers as $cz_cat ) : ?>
						<?php $cz_img = cz_category_image_id( $cz_cat ); ?>
						<li>
							<a class="cz-univers-card" href="<?php echo esc_url( get_term_link( $cz_cat ) ); ?>">
								<?php
								if ( $cz_img ) {
									echo wp_get_attachment_image( $cz_img, 'large', false, array( 'alt' => '', 'loading' => 'lazy' ) );
								}
								?>
								<span class="cz-univers-name"><?php echo esc_html( $cz_cat->name ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<!-- Comment ça marche ? -->
		<section class="cz-steps-home" aria-labelledby="cz-steps-title">
			<h2 id="cz-steps-title" class="cz-steps-home-title">Comment ça marche ?</h2>
			<ol class="cz-steps-home-list">
				<?php for ( $cz_i = 1; $cz_i <= 3; $cz_i++ ) : ?>
					<li>
						<span class="cz-steps-home-num" aria-hidden="true"><?php echo (int) $cz_i; ?></span>
						<h3 class="cz-steps-home-name"><?php echo esc_html( cz_home_text( "cz_etape{$cz_i}_titre" ) ); ?></h3>
						<p><?php echo esc_html( cz_home_text( "cz_etape{$cz_i}_texte" ) ); ?></p>
					</li>
				<?php endfor; ?>
			</ol>
		</section>

	</main>
</div>

<?php
get_footer();
