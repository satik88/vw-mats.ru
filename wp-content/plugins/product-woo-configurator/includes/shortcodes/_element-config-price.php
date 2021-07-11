<?php
/**
 * Configurator Price
 *
 *
 * @class			PWCSE_Config_Price
 * @parent class	PWCSE
 * @version			1.0
 * @author			Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined( 'PWC_VERSION' ) ) {
	return;
}	

/**
 * PWCSE_Config_Price
 */

if( ! class_exists( 'PWCSE_Config_Price' ) ) {

	class PWCSE_Config_Price extends PWCSE {

		public function __construct() {
			add_shortcode( 'pwc_config_price', array( $this, 'pwc_config_price' ) );
		}

		public function pwc_config_price( $atts = '', $content = '', $code ) {
			
			extract( shortcode_atts( array(
				'id'               => '0',
				'extra_class'      => '',
				'total_price_text' => esc_html__( 'Total Price', 'product-woo-configurator' ),
				'base_price_text'  => esc_html__( 'Base Price', 'product-woo-configurator' )
			), $atts ) );
			
			// Empty assignment
			$output = '';

			if( ! $id ) {
				return;
			}

			$config_post = get_post_type( $id );
					
			// check amz_configurator post type
			if( $id == '0' || $config_post != 'amz_configurator' ) {
				return;
			}

			$product_id = get_post_meta( $id, '_pwc_product_id', true );

			if( ! $product_id || $product_id == '0' || ! get_post_type( $product_id ) == 'product' ) {
				return;
			}

			$this->id = $id;
			$this->product_id = $product_id;

			parent::iniatialize_shortcode( $id );

			if( is_array( $this->cs ) && count(  $this->cs ) > 0 ) {
				$output .= parent::total_price_html( $atts, false ); // shortcode atts, echo
			}

			return $output;
			
		}

	}

}

new PWCSE_Config_Price();