<?php
/**
 * Page « Notre équipe » : liste de tous les membres – Cluizel Paris
 * Gabarit de l'archive du type de contenu cz_equipe (/equipe/).
 *
 * @package storefront-child
 */

get_header();

$cz_team = cz_team_settings();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main cz-team">

		<header class="cz-team-head">
			<h1 class="cz-page-title"><?php echo esc_html( $cz_team['title'] ); ?></h1>
			<?php if ( $cz_team['intro'] ) : ?>
				<p class="cz-team-intro"><?php echo esc_html( $cz_team['intro'] ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="cz-team-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					cz_member_card( get_the_ID() );
				endwhile;
				?>
			</ul>
		<?php else : ?>
			<p>L’équipe sera bientôt présentée ici.</p>
		<?php endif; ?>

	</main>
</div>

<?php
get_footer();
