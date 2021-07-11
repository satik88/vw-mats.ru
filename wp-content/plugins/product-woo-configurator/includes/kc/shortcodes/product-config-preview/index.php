<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_preview' ) ) {

	class product_config_preview extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_preview';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Preview', 'product-woo-configurator' ),
					'description' => esc_html__( 'Display a configurator preview', 'product-woo-configurator' ),
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
								'label'       => esc_html__( 'Extra class name', 'product-woo-configurator' ),
								'name'        => 'extra_class',
								'type'        => 'text',
								'description' => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'product-woo-configurator' )
							)
						),

						// Slider
						'Slider' => array(
							array(
								'label'       => esc_html__( 'Enable autoplay', 'product-woo-configurator' ),
								'description' => esc_html__( 'Do you want to enable autoplay', 'product-woo-configurator' ),
								'name'        => 'autoplay',
								'type'        => 'select',
								'value'       => 'false',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Slide Speed', 'product-woo-configurator' ),
								'description' => esc_html__( 'Enter the Value in milesecond (Ex: 5000)', 'product-woo-configurator' ),
								'name'        => 'slide_speed',
								'type'        => 'text',
								'value'       => '5000'
							),

							array(
								'label'       => esc_html__( 'Slide Margin', 'product-woo-configurator' ),
								'description' => esc_html__( 'Enter the integer value (Ex: 30)', 'product-woo-configurator' ),
								'name'        => 'slide_margin',
								'type'        => 'text',
								'value'       => '30'
							),

							array(
								'label'       => esc_html__( 'Stage Padding', 'product-woo-configurator' ),
								'description' => esc_html__( 'Enter the integer value (Ex: 30)', 'product-woo-configurator' ),
								'name'        => 'stage_padding',
								'type'        => 'text',
								'value'       => '50'
							),

							array(
								'label'       => esc_html__( 'Arrow', 'product-woo-configurator' ),
								'description' => esc_html__( 'Do you want to display arrows?', 'product-woo-configurator' ),
								'name'        => 'slide_arrow',
								'type'        => 'select',
								'value'       => 'false',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Pagination', 'product-woo-configurator' ),
								'description' => esc_html__( 'Do you want to display pagination dots?', 'product-woo-configurator' ),
								'name'        => 'slider_pagination',
								'type'        => 'select',
								'value'       => 'true',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Loop', 'product-woo-configurator' ),
								'description' => esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion.', 'product-woo-configurator' ),
								'name'        => 'loop',
								'type'        => 'select',
								'value'       => 'false',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Mouse Drag', 'product-woo-configurator' ),
								'description' => esc_html__( 'Do you want to change the slide using mouse drag', 'product-woo-configurator' ),
								'name'        => 'mouse_drag',
								'type'        => 'select',
								'value'       => 'true',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Touch Drag', 'product-woo-configurator' ),
								'description' => esc_html__( 'Do you want to change the slide using touch drag(in touch devices)', 'product-woo-configurator' ),
								'name'        => 'touch_drag',
								'type'        => 'select',
								'value'       => 'true',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Stop on Hover', 'product-woo-configurator' ),
								'description' => esc_html__( 'If the mouse pointer is placed on slider it will pause', 'product-woo-configurator' ),
								'name'        => 'stop_on_hover',
								'type'        => 'select',
								'value'       => 'true',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

							array(
								'label'       => esc_html__( 'Auto Height', 'product-woo-configurator' ),
								'description' => esc_html__( 'slider Auto height', 'product-woo-configurator' ),
								'name'        => 'auto_height',
								'type'        => 'select',
								'value'       => 'true',
								'options' => array(
									'true'       => esc_html__( 'Yes', 'product-woo-configurator' ),
									'false'   => esc_html__( 'No', 'product-woo-configurator' )
								)
							),

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
											array('property' => 'height', 'label' => 'Height')
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

new product_config_preview();

