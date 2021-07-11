<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     PWC_Post_types
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Post_types Class.
 */

if( ! class_exists( 'PWC_Post_types' ) ) {

	class PWC_Post_types {

		/**
		 * Hook in methods.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_taxonomies' ) );
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_filter( 'page_row_actions', array( $this, 'remove_row_actions' ), 99, 1 );
			add_action( 'admin_bar_menu', array( $this, 'remove_view_node' ), 99, 1 );
			add_filter( 'get_sample_permalink_html', array( $this, 'sample_permalink_html' ), 10, 5 );
		}

		/**
		 * Register core taxonomies.
		 */
		public static function register_taxonomies() {
			if ( taxonomy_exists( 'amz_configurator_cat' ) ) {
				return;
			}

			$labels = array(
				'name'               => esc_html__( 'Categories', 'product-woo-configurator' ),
				'singular_name'      => esc_html__( 'Category', 'product-woo-configurator' )
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => false,
				'show_admin_column'     => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'configurator_cat' ),
			);

			register_taxonomy( 'amz_configurator_cat', 'product-woo-configurator', $args );
		}

		/**
		 * Register core post types.
		 */
		public static function register_post_types() {

			if ( post_type_exists('amz_configurator') ) {
				return;
			}

			$args = array(
				'labels' => array(
					'name'               => esc_html__( 'Configurators', 'product-woo-configurator' ),
					'singular_name'      => esc_html__( 'Configurator', 'product-woo-configurator' ),
					'add_new'            => esc_html__( 'New Configurator', 'product-woo-configurator' ),
					'add_new_item'       => esc_html__( 'Add New Configurator', 'product-woo-configurator' ),
					'edit_item'          => esc_html__( 'Edit Configurator', 'product-woo-configurator' ),
					'new_item'           => esc_html__( 'Add New Configurator', 'product-woo-configurator' ),
					'view_item'          => esc_html__( 'View Configurator', 'product-woo-configurator' ),
					'search_items'       => esc_html__( 'Search Configurator', 'product-woo-configurator' ),"",
					'not_found'          => esc_html__( 'No configurations found', 'product-woo-configurator' ),
					'all_items'          => esc_html__( 'All configurators', 'product-woo-configurator' ),
					'not_found_in_trash' => esc_html__( 'No configurators found in Trash', 'product-woo-configurator' ),
					'parent_item_colon'  => '',
					'menu_name'          =>  esc_html__( 'Configurator', 'product-woo-configurator' ),
				),
				'public' 	=> true,
				'query_var' => 'product-woo-configurator',
				'hierarchical' => true,
				'menu_icon' =>'dashicons-image-filter',
				'rewrite' 	=> array(
					'slug' => "configurator"
					),		 
				'supports' 	=> array('title')
			);
			
			register_post_type( 'amz_configurator', $args );
		}


		public function remove_row_actions( $actions ) {

			if( get_post_type() === 'amz_configurator' ) {
				unset( $actions['inline hide-if-no-js'] );
				unset( $actions['view'] );
			}

		    return $actions;

		}

		public function remove_view_node( $wp_admin_bar ) {
			if( get_post_type() === 'amz_configurator' ) {
				$wp_admin_bar->remove_node( 'view' );
			}
		}

		public function sample_permalink_html( $return, $post_id, $title, $new_slug, $post ) {
			if( get_post_type() === 'amz_configurator' ) {
		    	$return = '';
		    }		    

		    return $return;
		}
	}

}

new PWC_Post_types();