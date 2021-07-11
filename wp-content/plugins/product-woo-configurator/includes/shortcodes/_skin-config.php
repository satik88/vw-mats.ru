<?php
/**
 * Configurator Skin Style1
 *
 *
 * @class			PWCSE_Config_Skin
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
 * PWCSE_Config_Skin
 */

if( ! class_exists( 'PWCSE_Config_Skin' ) ) {

	class PWCSE_Config_Skin extends PWCSE {

		public function __construct() {
			add_shortcode( 'pwc_config', array( $this, 'pwc_config' ) );
		}

		public function pwc_config( $atts = '', $content = '', $code ) {
			
			extract( shortcode_atts( array(
				'id'          => '0',
				'style'       => 'style1',
				'extra_class' => ''
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

				$styles = array_keys( pwc_get_styles() );

				if( ! in_array( $style, $styles ) ) {
					$style = 'style1';
				}

				if( 'style1' == $style ) {
					
					$output .= '<div id="configurator-wrap-' . esc_attr( $this->id ) . '" class="single-product-wrap configurator-wrap style1">';

						$output .= parent::get_preview_html( '', false );

						$output .= '<div class="summary entry-summary">';
							$output .= parent::get_controls_html( '', false );
							$output .= parent::total_price_html();
							$output .= parent::product_share( '', false );
							$output .= parent::add_to_cart_from( '', false );
						$output .= '</div>'; // .summary

						$output .= parent::product_inspiration( '', false );

					$output .= '</div>'; // .single-product-wrap

				}
				else if( 'style2' == $style ) {
					
					$output .= '<div id="configurator-wrap-' . esc_attr( $this->id ) . '" data-cid="' . esc_attr( $this->id ) . '" class="single-product-wrap style2 configurator-wrap">';

						$output .= '<div class="summary entry-summary">';
							$output .= parent::get_controls_html( '', false );
							$output .= parent::get_preview_html( '', false );
							$output .= parent::total_price_html();
							$output .= parent::product_share( '', false );
							$output .= parent::add_to_cart_from( '', false );
						$output .= '</div>'; // .summary

						$output .= parent::product_inspiration( '', false );

					$output .= '</div>'; // .single-product-wrap

				}
				else if( 'accordion' == $style ) {
					
					$output .= '<div id="configurator-wrap-' . esc_attr( $this->id ) . '" class="single-product-wrap configurator-wrap ' . $style . '">';

						$output .= parent::get_preview_html();
						$output .= parent::product_inspiration( '', false );

						$output .= '<div class="summary entry-summary">';
							$output .= parent::get_accordion_controls_html();
							$output .= parent::total_price_html();
							$output .= parent::product_share( '', false );
							$output .= parent::add_to_cart_from();
						$output .= '</div>'; // .summary

					$output .= '</div>'; // .single-product-wrap

				}
				else {
					$output .= '<div id="configurator-wrap-' . esc_attr( $this->id ) . '" class="single-product-wrap configurator-wrap '. esc_attr( $style ) .'">';
						$output .= apply_filters( 'pwc_skin_'. $style, '', $this->cs ); // second value should to string which is the data we are going to change using add_filter.
					$output .= '</div>';

				}				
				
			}

			return $output;
			
		}

	}

}

new PWCSE_Config_Skin();