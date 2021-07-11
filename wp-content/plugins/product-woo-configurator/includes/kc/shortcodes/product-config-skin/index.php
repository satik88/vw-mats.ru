<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'product_config_skin' ) ) {

	class product_config_skin extends pwc_shortcodes_init {

		public function __construct() {

			$sc_name = 'pwc_config';

			$sc_path = plugin_dir_path( __FILE__ );
			$sc_url = plugin_dir_url( __FILE__ );

			$args = array(
				$sc_name => array(
					'name' => esc_html__( 'Configurator Skin', 'product-woo-configurator' ),
					'description' => esc_html__('Display a configurator', 'product-woo-configurator'),
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
								'label'       => esc_html__( 'Style', 'product-woo-configurator' ), 
								'admin_label' => true,
								'description' => esc_html__( 'Choose style you want to display', 'product-woo-configurator' ),
								'name'        => 'style',
								'type'        => 'select',
								'value'       => '',
								'options'     => pwc_get_styles()
							),
							array(
								'label'       => esc_html__( 'Extra class name', 'product-woo-configurator' ),
								'name'        => 'extra_class',
								'type'        => 'text',
								'description' => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'product-woo-configurator' )
							)
						)
						
					)
				)
			);
			$this->map( $args );

		}

	}

}

new product_config_skin();

