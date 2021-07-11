<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_cart_form' ) ) {

	class product_config_cart_form extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_cart_form';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Cart Form', 'product-woo-configurator' ),
					'description' => esc_html__( 'Display a configurator cart form', 'product-woo-configurator' ),
					'icon' => 'sl-paper-plane',
					'category' => esc_html__( 'WP Configurator', 'product-woo-configurator' ),
					'params' => array(

						// General
						'general' => array(
							array(
								'label'       => esc_html__( 'Choose Configurator', 'product-woo-configurator' ),
								'admin_label' => true,
								'description' => esc_html__( 'Choose Configurtor you want to display', 'product-woo-configurator' ),
								'name'        => 'id',
								'type'        => 'select',
								'value'       => '',
								'options'	  => pwc_configurator_posts()
							),

							array(
								'label'       => esc_html__( 'Overlap Button', 'product-woo-configurator' ),
								'admin_label' => true,
								'description' => esc_html__( 'Input overlap to the button', 'product-woo-configurator' ),
								'name'        => 'overlap',
								'type'        => 'select',
								'value'       => 'overlap',
								'options'	  => array(
									'overlap'    => esc_html__( 'Yes', 'product-woo-configurator' ),
									'no-overlap' => esc_html__( 'No', 'product-woo-configurator' )
								)
							),
							array(
								'label'       => esc_html__( 'Extra class name', 'product-woo-configurator' ),
								'name'        => 'extra_class',
								'type'        => 'text',
								'description' => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'product-woo-configurator' )
							)
						),

						// Styling
						'styling' => array(
							array(
								'name'    => 'css_custom',
								'type'    => 'css',
								'options'		=> array(
									array(
										"screens" => "any,1024,999,767,479",
										'Input' => array(
											array( 'property' => 'color', 'label' => 'Text Color', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'font-family', 'label' => 'Font Family', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'font-size', 'label' => 'Text Size', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'line-height', 'label' => 'Line Height', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'border', 'label' => 'Border', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'background', 'label' => 'Background', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'margin', 'label' => 'Margin', 'selector' => '.config-cart-form .quantity .input-text' ),
											array( 'property' => 'padding', 'label' => 'Padding', 'selector' => '.config-cart-form .quantity .input-text' )
										),
										'Cart Button' => array(
											array( 'property' => 'color', 'label' => 'Text Color', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'font-family', 'label' => 'Font Family', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'font-size', 'label' => 'Text Size', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'line-height', 'label' => 'Line Height', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'border', 'label' => 'Border', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'background', 'label' => 'Background', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'margin', 'label' => 'Margin', 'selector' => '.single_add_to_cart_button.button' ),
											array( 'property' => 'padding', 'label' => 'Padding', 'selector' => '.single_add_to_cart_button.button' )
										)
									)
								)
							)
						)
						
					)
				)
			);
			$this->map( $args );

		}

	}

}

new product_config_cart_form();

