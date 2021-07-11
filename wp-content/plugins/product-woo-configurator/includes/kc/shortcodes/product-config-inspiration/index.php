<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_inspiration' ) ) {

	class product_config_inspiration extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_inspiration';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Inspiration/Screenshot/Reset', 'product-woo-configurator' ),
					'description' => esc_html__( 'Display a configurator inspiration, screenshot and reset', 'product-woo-configurator' ),
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
								'label'       => esc_html__( 'Choose Options', 'product-woo-configurator' ),
								'description' => esc_html__( 'Choose the options', 'product-woo-configurator' ),
								'name'        => 'options',
								'type'        => 'multiple',
								'value'       => 'inspiration,screenshot,reset',
								'options'	  => array(
									'inspiration' => esc_html__( 'Inspiration', 'product-woo-configurator' ),
									'screenshot'  => esc_html__( 'Screenshot', 'product-woo-configurator' ),
									'reset'       => esc_html__( 'Reset', 'product-woo-configurator' )
								)
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
								'description' => esc_html__( 'Slider auto height', 'product-woo-configurator' ),
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
										'Trigger Icons' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.insp-screenshot a' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.insp-screenshot a' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.insp-screenshot a' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.insp-screenshot a' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.insp-screenshot a' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.insp-screenshot a' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.insp-screenshot a' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.insp-screenshot a' ),
										),
										'Container' => array(
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-wrap.popup' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-wrap.popup' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-wrap.popup' ),
										),
										'Ins Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-lists h3' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-lists h3' )
										),
										'Ins Add New Button' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' )
										),
										'Popup Close Button' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-lists h3 .add-new-inspiration-form' )
										),
										'Tab Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-lists .lists-scroll li' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-lists .lists-scroll li' )
										),
										'Tab Active Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-lists .lists-scroll li.active' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-lists .lists-scroll li.active' )
										),
										'Slider Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.ins-list p.title' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.ins-list p.title' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.ins-list p.title' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.ins-list p.title' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.ins-list p.title' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.ins-list p.title' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.ins-list p.title' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.ins-list p.title' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.ins-list p.title' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.ins-list p.title' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.ins-list p.title' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.ins-list p.title' )
										),
										'Slider Desc' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.ins-list .desc' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.ins-list .desc' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.ins-list .desc' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.ins-list .desc' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.ins-list .desc' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.ins-list .desc' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.ins-list .desc' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.ins-list .desc' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.ins-list .desc' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.ins-list .desc' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.ins-list .desc' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.ins-list .desc' )
										),
										'Slider Button' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.ins-list .btn.btn-solid.btn-primary' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.ins-list .btn.btn-solid.btn-primary' )
										),
										'E/U/D Icons' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.ins-icons i' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.ins-icons i' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.ins-icons i' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.ins-icons i' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.ins-icons i' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.ins-icons i' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.ins-icons i' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.ins-icons i' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.ins-icons i' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.ins-icons i' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.ins-icons i' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.ins-icons i' )
										),
										'Form Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.add-new-inspiration .title' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.add-new-inspiration .title' )
										),
										'Input' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.inspiration-form .ins-field-group input, .inspiration-form .ins-field-group .existing-group' )
										),
										'Create Button' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.save-inspiration.btn' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.save-inspiration.btn' )
										),
										'Cancel Button' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'background', 'label' => 'Background', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'border', 'label' => 'Border', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'border-radius', 'label' => 'Border Radius', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.cancel-inspiration-form.btn' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.cancel-inspiration-form.btn' )
										),
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

new product_config_inspiration();

