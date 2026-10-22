<?php
/**
 * Mode de livraison « Livraison selon le poids » (itération 19)
 *
 * Le tarif dépend du poids total des articles du panier, réparti en
 * 3 tranches réglables dans WooCommerce › Réglages › Expédition › zone.
 *
 * @package storefront-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CZ_Shipping_Weight extends WC_Shipping_Method {

	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'cz_weight';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = 'Livraison selon le poids';
		$this->method_description = 'Tarif calculé selon le poids total de la commande, en 3 tranches. Pensez à renseigner le poids de chaque produit (onglet Expédition de la fiche produit).';
		$this->supports           = array( 'shipping-zones', 'instance-settings', 'instance-settings-modal' );
		$this->init();
	}

	public function init() {
		$this->instance_form_fields = array(
			'title'  => array(
				'title'   => 'Nom affiché au client',
				'type'    => 'text',
				'default' => 'Livraison à domicile',
			),
			'max_1'  => array(
				'title'       => 'Tranche 1 : jusqu’à (kg)',
				'type'        => 'decimal',
				'default'     => '0.5',
				'description' => 'De 0 kg jusqu’à ce poids inclus.',
				'desc_tip'    => true,
			),
			'cost_1' => array(
				'title'   => 'Tranche 1 : tarif (€ TTC)',
				'type'    => 'price',
				'default' => '4.90',
			),
			'max_2'  => array(
				'title'       => 'Tranche 2 : jusqu’à (kg)',
				'type'        => 'decimal',
				'default'     => '2',
				'description' => 'Au-delà de la tranche 1, jusqu’à ce poids inclus.',
				'desc_tip'    => true,
			),
			'cost_2' => array(
				'title'   => 'Tranche 2 : tarif (€ TTC)',
				'type'    => 'price',
				'default' => '7.90',
			),
			'max_3'  => array(
				'title'       => 'Tranche 3 : jusqu’à (kg)',
				'type'        => 'decimal',
				'default'     => '10',
				'description' => 'Poids maximum livrable. Au-delà, la livraison n’est pas proposée (retrait en boutique uniquement). Laisser vide pour ne pas fixer de limite.',
				'desc_tip'    => true,
			),
			'cost_3' => array(
				'title'   => 'Tranche 3 : tarif (€ TTC)',
				'type'    => 'price',
				'default' => '12.90',
			),
		);

		$this->title      = $this->get_option( 'title' );
		$this->tax_status = 'none';

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Les 3 tranches, dans l'ordre : [ 'min' => kg, 'max' => kg|null, 'cost' => € ].
	 */
	public function get_tiers() {
		$num   = fn( $key ) => (float) str_replace( ',', '.', (string) $this->get_option( $key ) );
		$max_1 = max( 0, $num( 'max_1' ) );
		$max_2 = max( $max_1, $num( 'max_2' ) );
		$max_3 = '' === trim( (string) $this->get_option( 'max_3' ) ) ? null : max( $max_2, $num( 'max_3' ) );

		return array(
			array( 'min' => 0, 'max' => $max_1, 'cost' => max( 0, $num( 'cost_1' ) ) ),
			array( 'min' => $max_1, 'max' => $max_2, 'cost' => max( 0, $num( 'cost_2' ) ) ),
			array( 'min' => $max_2, 'max' => $max_3, 'cost' => max( 0, $num( 'cost_3' ) ) ),
		);
	}

	/**
	 * Numéro (0, 1, 2) de la tranche correspondant au poids, ou null si trop lourd.
	 */
	public function get_tier_index( $weight ) {
		foreach ( $this->get_tiers() as $i => $tier ) {
			if ( null === $tier['max'] || $weight <= $tier['max'] + 0.0001 ) {
				return $i;
			}
		}
		return null;
	}

	public function calculate_shipping( $package = array() ) {
		$weight = cz_package_weight( $package['contents'] ?? array() );
		$index  = $this->get_tier_index( $weight );
		if ( null === $index ) {
			return; // Trop lourd : la livraison n'est pas proposée.
		}
		$tiers = $this->get_tiers();

		$this->add_rate(
			array(
				'id'        => $this->get_rate_id(),
				'label'     => $this->title,
				'cost'      => $tiers[ $index ]['cost'],
				'package'   => $package,
				'meta_data' => array(
					'Poids total' => cz_format_weight( $weight ),
					'Tranche'     => cz_tier_label( $tiers[ $index ] ),
				),
			)
		);
	}
}
