<?php
/**
 * Configurator Cart Form
 *
 *
 * @class			PWCSE_Config_Cart_Form
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
 * PWCSE_Config_Cart_Form
 */

if( ! class_exists( 'PWCSE_Config_Cart_Form' ) ) {

	class PWCSE_Config_Cart_Form extends PWCSE {

		public function __construct() {
			add_shortcode( 'pwc_config_cart_form', array( $this, 'pwc_config_cart_form' ) );
		}

		public function pwc_config_cart_form( $atts = '', $content = '', $code ) {
			
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
				$output .= parent::add_to_cart_from( $atts, false ); // shortcode atts, echo
			}

			return $output;
			
		}

	}

}

new PWCSE_Config_Cart_Form();