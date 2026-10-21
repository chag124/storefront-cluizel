<?php
/**
 * Avis clients – Cluizel Paris
 * Surcharge de woocommerce/templates/single-product-reviews.php.
 * À gauche : note moyenne, répartition des notes et bouton « Laisser un avis ».
 * À droite : la liste des avis (cartes beiges) puis le formulaire, réservé
 * aux clients connectés qui ont acheté le produit.
 *
 * @package storefront-child
 * @version 11.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$cz_count   = (int) $product->get_review_count();
$cz_average = (float) $product->get_average_rating();
$cz_counts  = $product->get_rating_counts();
$cz_can     = cz_customer_can_review( $product );
?>
<section id="reviews" class="woocommerce-Reviews cz-reviews" aria-labelledby="cz-reviews-title">
	<h2 id="cz-reviews-title" class="cz-reviews-title">Avis clients</h2>

	<div class="cz-reviews-grid">

		<aside class="cz-reviews-summary">
			<?php if ( $cz_count ) : ?>
				<p class="cz-reviews-average">
					<span class="cz-reviews-score"><?php echo esc_html( number_format_i18n( $cz_average, 1 ) ); ?></span><span class="cz-reviews-outof">/5</span>
				</p>
				<?php echo cz_stars_html( $cz_average ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<p class="cz-reviews-count"><?php echo esc_html( $cz_count . ' ' . ( $cz_count > 1 ? 'avis' : 'avis' ) ); ?></p>

				<ul class="cz-reviews-bars">
					<?php
					for ( $cz_star = 5; $cz_star >= 1; $cz_star-- ) :
						$cz_n   = isset( $cz_counts[ $cz_star ] ) ? (int) $cz_counts[ $cz_star ] : 0;
						$cz_pct = $cz_count ? round( $cz_n / $cz_count * 100 ) : 0;
						?>
						<li>
							<span class="cz-reviews-bar-label"><?php echo (int) $cz_star; ?> ★</span>
							<span class="cz-reviews-bar" aria-hidden="true"><span style="width: <?php echo (int) $cz_pct; ?>%"></span></span>
							<span class="cz-reviews-bar-count"><span class="screen-reader-text"><?php echo (int) $cz_star; ?> étoiles : </span><?php echo (int) $cz_n; ?></span>
						</li>
					<?php endfor; ?>
				</ul>
			<?php else : ?>
				<p class="cz-reviews-empty-title">Aucun avis pour le moment</p>
			<?php endif; ?>

			<?php if ( $cz_can ) : ?>
				<a class="cz-btn" href="#review_form_wrapper">Laisser un avis</a>
			<?php elseif ( ! is_user_logged_in() ) : ?>
				<p class="cz-reviews-note">Vous avez acheté ce produit ? <a href="<?php echo esc_url( add_query_arg( 'redirect_to', rawurlencode( get_permalink() . '#reviews' ), wc_get_page_permalink( 'myaccount' ) ) ); ?>">Connectez-vous</a> pour laisser un avis.</p>
			<?php else : ?>
				<p class="cz-reviews-note">Seuls les clients ayant acheté ce produit peuvent laisser un avis.</p>
			<?php endif; ?>
		</aside>

		<div class="cz-reviews-main">
			<?php if ( have_comments() ) : ?>
				<ol class="commentlist cz-reviews-list">
					<?php
					wp_list_comments(
						array(
							'callback' => 'cz_review_item',
							'style'    => 'ol',
						)
					);
					?>
				</ol>

				<?php
				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
					echo '<nav class="woocommerce-pagination cz-reviews-pagination" aria-label="Pages des avis">';
					paginate_comments_links(
						array(
							'prev_text' => '← Précédents',
							'next_text' => 'Suivants →',
							'type'      => 'list',
						)
					);
					echo '</nav>';
				endif;
				?>
			<?php else : ?>
				<p class="woocommerce-noreviews">Soyez le premier à donner votre avis sur « <?php the_title(); ?> ».</p>
			<?php endif; ?>

			<?php if ( $cz_can ) : ?>
				<div id="review_form_wrapper" class="cz-review-form">
					<div id="review_form">
						<?php
						$cz_stars = '<fieldset class="cz-rating-input"><legend>Votre note <span class="screen-reader-text">(obligatoire)</span></legend><div class="cz-rating-stars">';
						for ( $cz_i = 5; $cz_i >= 1; $cz_i-- ) {
							$cz_stars .= sprintf(
								'<input type="radio" id="cz-rating-%1$d" name="rating" value="%1$d" required><label for="cz-rating-%1$d" title="%1$d sur 5"><span class="screen-reader-text">%1$d étoile%2$s sur 5</span>★</label>',
								$cz_i,
								$cz_i > 1 ? 's' : ''
							);
						}
						$cz_stars .= '</div></fieldset>';

						comment_form(
							array(
								'title_reply'          => have_comments() ? 'Laisser un avis' : 'Soyez le premier à donner votre avis',
								'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title cz-review-form-title">',
								'title_reply_after'    => '</h3>',
								'title_reply_to'       => 'Répondre à %s',
								'comment_notes_before' => '',
								'comment_notes_after'  => '',
								'logged_in_as'         => '<p class="cz-review-form-as">Vous publiez en tant que <strong>' . esc_html( wp_get_current_user()->first_name ? wp_get_current_user()->first_name : wp_get_current_user()->display_name ) . '</strong>. Votre avis sera visible après validation.</p>',
								'fields'               => array(),
								'comment_field'        => $cz_stars . '<p class="comment-form-comment"><label for="comment">Votre avis</label><textarea id="comment" name="comment" rows="6" required placeholder="Goût, texture, présentation… Qu’en avez-vous pensé ?"></textarea></p>',
								'label_submit'         => 'Publier mon avis',
								'class_submit'         => 'submit cz-btn',
								'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
							)
						);
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>