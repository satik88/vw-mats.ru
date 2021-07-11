<?php
/**
 * Configurator Inspiration / Screenshot / Reset
 *
 *
 * @class			PWCSE_Config_Inspiration
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
 * PWCSE_Config_Inspiration
 */

if( ! class_exists( 'PWCSE_Config_Inspiration' ) ) {

	class PWCSE_Config_Inspiration extends PWCSE {

		public function __construct() {
			add_shortcode( 'pwc_config_inspiration', array( $this, 'pwc_config_inspiration' ) );
		}

		public function pwc_config_inspiration( $atts = '', $content = '', $code ) {
			
			extract( shortcode_atts( array(
				'id'               => '0',
				'extra_class'      => ''
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
				$output .= parent::product_inspiration( $atts, false ); // shortcode atts, echo
			}

			return $output;
			
		}

	}

}

new PWCSE_Config_Inspiration();