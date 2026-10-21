<?php
/**
 * Profil d'un membre de l'équipe – Cluizel Paris
 * Gabarit d'un contenu cz_equipe (/equipe/prenom-nom/).
 *
 * @package storefront-child
 */

get_header();

while ( have_posts() ) :
	the_post();

	$cz_id       = get_the_ID();
	$cz_poste    = (string) cz_member_field( 'cz_poste' );
	$cz_depuis   = (string) cz_member_field( 'cz_depuis' );
	$cz_special  = (string) cz_member_field( 'cz_specialite' );
	$cz_citation = (string) cz_member_field( 'cz_citation' );
	$cz_prefere  = (int) cz_member_field( 'cz_prefere' );
	$cz_product  = $cz_prefere && function_exists( 'wc_get_product' ) ? wc_get_product( $cz_prefere ) : null;
	?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main cz-member">

			<article class="cz-member-profile">
				<div class="cz-member-profile-photo">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) );
					} else {
						echo '<span class="cz-member-initials" aria-hidden="true">' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
					}
					?>
				</div>

				<div class="cz-member-profile-text">
					<?php if ( $cz_poste ) : ?>
						<p class="cz-member-profile-role"><?php echo esc_html( $cz_poste ); ?></p>
					<?php endif; ?>
					<h1 class="cz-member-profile-name"><?php the_title(); ?></h1>

					<?php if ( $cz_citation ) : ?>
						<blockquote class="cz-member-quote"><p>« <?php echo esc_html( $cz_citation ); ?> »</p></blockquote>
					<?php endif; ?>

					<?php if ( $cz_depuis || $cz_special ) : ?>
						<dl class="cz-member-facts">
							<?php if ( $cz_depuis ) : ?>
								<div><dt>Chez Cluizel depuis</dt><dd><?php echo esc_html( $cz_depuis ); ?></dd></div>
							<?php endif; ?>
							<?php if ( $cz_special ) : ?>
								<div><dt>Spécialité</dt><dd><?php echo esc_html( $cz_special ); ?></dd></div>
							<?php endif; ?>
						</dl>
					<?php endif; ?>

					<div class="cz-member-bio">
						<?php the_content(); ?>
					</div>

					<?php if ( $cz_product && $cz_product->is_visible() ) : ?>
						<a class="cz-member-fav" href="<?php echo esc_url( $cz_product->get_permalink() ); ?>">
							<span class="cz-member-fav-thumb"><?php echo $cz_product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							<span class="cz-member-fav-text">
								<span class="cz-member-fav-label">Son chocolat préféré</span>
								<span class="cz-member-fav-name"><?php echo esc_html( $cz_product->get_name() ); ?></span>
								<span class="cz-member-fav-price"><?php echo wp_kses_post( $cz_product->get_price_html() ); ?></span>
							</span>
						</a>
					<?php endif; ?>

					<a class="cz-btn" href="<?php echo esc_url( get_post_type_archive_link( 'cz_equipe' ) ); ?>">Voir toute l’équipe</a>
				</div>
			</article>

			<?php
			$cz_others = get_posts(
				array(
					'post_type'      => 'cz_equipe',
					'posts_per_page' => 3,
					'post__not_in'   => array( $cz_id ),
					'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
					'fields'         => 'ids',
				)
			);
			if ( $cz_others ) :
				?>
				<section class="cz-member-others" aria-labelledby="cz-others-title">
					<h2 id="cz-others-title" class="cz-member-others-title">Les autres membres de l’équipe</h2>
					<ul class="cz-team-grid">
						<?php
						foreach ( $cz_others as $cz_other ) {
							cz_member_card( $cz_other );
						}
						?>
					</ul>
				</section>
			<?php endif; ?>

		</main>
	</div>

	<?php
endwhile;

get_footer();
