<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_share' ) ) {

	class product_config_share extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config_share';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Share', 'product-woo-configurator' ),
					'description' => esc_html__( 'Display a configurator share', 'product-woo-configurator' ),
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
								'label'       => esc_html__( 'Choose Social Options', 'product-woo-configurator' ),
								'description' => esc_html__( 'Choose what are the social icons you want to display', 'product-woo-configurator' ),
								'name'        => 'social_share',
								'type'        => 'multiple',
								'value'       => 'facebook,twitter,google-plus,linkedin,pinterest,copy_to_clipboard',
								'options'	  => array(
									'facebook'          => esc_html__( 'Facebook', 'product-woo-configurator' ),
									'twitter'           => esc_html__( 'Twitter', 'product-woo-configurator' ),
									'google_plus'       => esc_html__( 'Google Plus', 'product-woo-configurator' ),
									'linkedin'          => esc_html__( 'Linkedin', 'product-woo-configurator' ),
									'pinterest'         => esc_html__( 'Pinterest', 'product-woo-configurator' ),
									'copy_to_clipboard' => esc_html__( 'Copy to Clipboard', 'product-woo-configurator' )
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
										'General' => array(
											array('property' => 'text-align', 'label' => 'Alignment'),
											array('property' => 'display', 'label' => 'Display'),
											array('property' => 'margin', 'label' => 'Margin'),
											array('property' => 'padding', 'label' => 'Padding'),
										),
										'Share Icon' => array(
											array('property' => 'color', 'label' => 'Text Color', 'selector' => '.product-share a'),
											array('property' => 'font-size', 'label' => 'Text Size', 'selector' => '.product-share a'),
											array('property' => 'line-height', 'label' => 'Line Height', 'selector' => '.product-share a'),
											array('property' => 'font-weight', 'label' => 'Font Weight', 'selector' => '.product-share a'),
											array('property' => 'text-transform', 'label' => 'Text Transform', 'selector' => '.product-share a'),
											array('property' => 'letter-spacing', 'label' => 'Letter Spacing', 'selector' => '.product-share a'),
											array('property' => 'margin', 'label' => 'Margin', 'selector' => '.product-share a'),
											array('property' => 'padding', 'label' => 'Padding', 'selector' => '.product-share a')
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

new product_config_share();

