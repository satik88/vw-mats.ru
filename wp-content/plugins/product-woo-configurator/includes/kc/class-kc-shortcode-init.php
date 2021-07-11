<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'pwc_shortcodes_init' ) ) {

	class pwc_shortcodes_init {

		private $value;
		
		public function map( $args ) {
			
			global $kc;
			if ( empty( $args ) || !is_array( $args ) )
				return;
			
			$kc->add_map( $args );
			
		}
		
		public function add_shortcode( $name, $callback ) {
			if ( is_callable( $callback ) ) {
				add_shortcode ( $name, $callback );
			}
		}

		public function get_array( $name ) {
			switch ( $name ) {

				case 'order':
					$value = array(
						'desc'	=> esc_html__( 'Descending Order', 'product-woo-configurator' ),
						'asc'	=> esc_html__( 'Ascending Order', 'product-woo-configurator' )
					);
				break;

				case 'orderby':

					$value = array(
						'date'       => esc_html__( 'Date', 'product-woo-configurator' ),
						'modified'   => esc_html__( 'Date Modified', 'product-woo-configurator' ),
						'rand'       => esc_html__( 'Rand', 'product-woo-configurator' ),
						'ID'         => esc_html__( 'ID', 'product-woo-configurator' ),
						'title'      => esc_html__( 'Title', 'product-woo-configurator' ),
						'author'     => esc_html__( 'Author', 'product-woo-configurator' ),
						'name'       => esc_html__( 'Name', 'product-woo-configurator' ),
						'parent'     => esc_html__( 'Parent', 'product-woo-configurator' ),
						'menu_order' => esc_html__( 'Menu Order', 'product-woo-configurator' ),
						'none'       => esc_html__( 'None', 'product-woo-configurator' )
					);

				break;

				case 'menu_list':

					$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
					$menu_list = array();
					$menu_list[] = esc_attr__( 'Default', 'product-woo-configurator' );
					if( !empty( $menus ) ) {
						foreach ( $menus as $key => $slug ) {
							$menu_list[$slug->slug] = $slug->name;
						}
					}					

					$value = $menu_list;

				break;
				
				default:
				break;
			}

			return $value;
		}

	}
}

new pwc_shortcodes_init();
