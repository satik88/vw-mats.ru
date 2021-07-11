<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_price' ) ) {

	class product_config_price extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_price';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Price', 'product-woo-configurator' ),
					'description' => esc_html__('Display a product price', 'product-woo-configurator'),
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
								'options'     => pwc_configurator_posts()
							),
							array(
								'label'       => esc_html__( 'Total Price Text', 'product-woo-configurator' ),
								'name'        => 'total_price_text',
								'type'        => 'text',
								'value'       => esc_html__( 'Total Price', 'product-woo-configurator' ),
								'description' => esc_html__( 'Type the total price text', 'product-woo-configurator' )
							),
							array(
								'label'       => esc_html__( 'Base Price Text', 'product-woo-configurator' ),
								'name'        => 'base_price_text',
								'type'        => 'text',
								'value'       => esc_html__( 'Base Price', 'product-woo-configurator' ),
								'description' => esc_html__( 'Type the base price text', 'product-woo-configurator' )
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
										'General' => array(
											array('property' => 'text-align', 'label' => 'Alignment'),
											array('property' => 'display', 'label' => 'Display'),
											array('property' => 'margin', 'label' => 'Margin'),
											array('property' => 'padding', 'label' => 'Padding'),
										),
										'Total Price Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.single-product-price .total-text'),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.single-product-price .total-text'),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.single-product-price .total-text'),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.single-product-price .total-text'),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.single-product-price .total-text'),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.single-product-price .total-text'),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.single-product-price .total-text')
										),
										'Price' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.single-product-price .calculation.price'),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.single-product-price .calculation.price')
										),
										'Price List' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.single-product-price .total-price'),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.single-product-price .total-price'),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.single-product-price .total-price'),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.single-product-price .total-price'),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.single-product-price .total-price'),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.single-product-price .total-price'),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.single-product-price .total-price')
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

new product_config_price();

