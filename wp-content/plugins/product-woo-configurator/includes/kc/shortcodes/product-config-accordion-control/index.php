<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_accordion_control' ) ) {

	class product_config_accordion_control extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_accordion_control';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Accordion Control', 'product-woo-configurator' ),
					'description' => esc_html__( 'Display a configurator accordion style control', 'product-woo-configurator' ),
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

						// Styling
						'styling' => array(
							array(
								'name'    => 'css_custom',
								'type'    => 'css',
								'options'		=> array(
									array(
										"screens" => "any,1024,999,767,479",
										'General' => array(
											array('property' => 'text-align', 'label' => 'Alignment' ),
											array('property' => 'display', 'label' => 'Display' ),
											array('property' => 'margin', 'label' => 'Margin' ),
											array('property' => 'padding', 'label' => 'Padding' ),
										),
										'Control Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec .pwc-layer-title' )
										),
										'Hover Title' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.pwc-icon-hover-inner' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.pwc-icon-hover-inner' )
										),
										'Hover Price' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'font-family', 'label' => 'Font Family', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.pwc-icon-hover-inner span.config-hover-price' )
										),
										'Accordion List' => array(
											array('property' => 'border', 'label' => 'Border', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.pwc-skin-accordion-controls .pwc-controls-list-sec' )
										),
										'Control List' => array(
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.pwc-controls-img-list li' ),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.pwc-controls-img-list li' ),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.pwc-controls-img-list li' )
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

new product_config_accordion_control();
