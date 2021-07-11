<?php
/*
	Plugin Name: WP Configurator for WooCommerce
	Plugin URI: https://luminesthemes.com/items/configurator-plugin/
	Description: WooCommerce configurator plugin.
	Version: 1.4.6.5
	Author: Luminesthemes
	Author URI: http://luminesthemes.com
	Text Domain: product-woo-configurator
	Domain Path: /languages/
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( defined( 'PWC_VERSION' ) ) {
	return;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {

	/*
	 * Iniatialize class
	 */
	if ( ! class_exists( 'PWC' ) ) {

		class PWC {

			/**
			 * The single instance of the class.
			 */
			protected static $_instance = null;

			/**
			 * Main Helper Instance.
			 *
			 * Ensures only one instance of Helper is loaded or can be loaded.
			 *
			 * @static
			 * @return PWC - Main instance.
			 */
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}
				return self::$_instance;
			}

			public function __construct() {

				$this->define_constants();
				$this->includes();

				add_action( 'init', array( $this, 'init' ) );
				add_action( 'plugins_loaded', array( $this, 'pwc_textdomain' ) ); // call plugin text-domain

				do_action( 'pwc_loaded' );

			}

			/**
			 * Init hook
			 */
			public function init() {

				add_action( 'admin_notices', array( $this, 'licence_activation' ) );

			}

			/**
			 * Notice
			 */
			public function licence_activation() {

				if ( is_multisite() && ( is_plugin_active_for_network( 'product-woo-configurator/product-woo-configurator.php' ) || is_network_only_plugin( 'product-woo-configurator/product-woo-configurator.php' ) ) ) {
					$redirect = network_admin_url( 'edit.php?post_type=amz_configurator&page=pwc-settings&tab=license' );
				} else {
					$redirect = admin_url( 'edit.php?post_type=amz_configurator&page=pwc-settings&tab=license' );
				}

				$status = get_option( 'pwc_license_status' );

				if ( $status == false || $status != 'valid' ) {
					echo '<div class="notice notice-warning"><p>' . sprintf( __( 'Hola! Would you like to receive automatic updates and unlock premium support? Please <a href="%s">activate your copy</a> of WP Configurator.', 'product-woo-configurator' ), wp_nonce_url( $redirect ) ) . '</p>' . '</div>';
				}

			}

			/**
			 * Define Constants.
			 */
			private function define_constants() {

				define( 'PWC_VERSION', '1.4.6.3' );

				define( 'PWC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
				define( 'PWC_INCLUDE_DIR', PWC_PLUGIN_DIR . 'includes/' );
				define( 'PWC_TEMPLATES_DIR', PWC_PLUGIN_DIR . 'templates/' );
				define( 'PWC_CLASS_DIR', PWC_INCLUDE_DIR . 'class/' );
				define( 'PWC_SHORTCODES_DIR', PWC_INCLUDE_DIR . 'shortcodes/' );

				define( 'PWC_PLUGIN_URL', plugins_url( '', __FILE__ ) );
				define( 'PWC_ASSETS_URL', PWC_PLUGIN_URL . '/assets/' );

				define( 'PWC_PLUGIN_FILE', __FILE__ );
				define( 'PWC_TEMPLATE_PATH', $this->template_path() );

			}

			/**
			 * Include required core files used in admin and on the frontend.
			 */
			public function includes() {

				// Include useful 3rd party plugins
				require PWC_INCLUDE_DIR . 'helper-plugin/aq_resizer.php';

				// Admin
				require PWC_INCLUDE_DIR . 'admin/class-pwc-configuration-settings.php';
				require PWC_INCLUDE_DIR . 'admin/class-pwc-admin-config.php';
				require PWC_INCLUDE_DIR . 'admin/updater/pwc-update-handler.php';
				require PWC_INCLUDE_DIR . 'admin/class-pwc-post-types.php'; // Register configurator post type
				require PWC_INCLUDE_DIR . 'admin/class-pwc-metabox.php'; // Register configurator meta box
				require PWC_INCLUDE_DIR . 'admin/class-pwc-additional-fields.php'; // Helps to create additional fields in configurator metabox
				require PWC_INCLUDE_DIR . 'admin/class-pwc-menus.php'; // Additional Menu
				require PWC_INCLUDE_DIR . 'admin/class-option-fields.php'; // Helps to create options

				require PWC_INCLUDE_DIR . 'editor/init_button.php'; // Tiny MCE shortcode button

				// Configurator Shortcode
				require PWC_SHORTCODES_DIR . '__class-pwc-config-elements.php'; // contains all config elements shortcode functions
				require PWC_SHORTCODES_DIR . '__include-config-elements.php'; // include all shortcodes

				// Frontend
				require PWC_INCLUDE_DIR . 'frontend/class-pwc-frontend-config.php';

				// Helper
				require PWC_INCLUDE_DIR . 'pwc-helper-functions.php';
				require PWC_INCLUDE_DIR . 'pwc-woo-helper-functions.php';
				require PWC_INCLUDE_DIR . 'pwc-woo-actions-filters.php';

				// Icons array based on filter
				require PWC_INCLUDE_DIR . '_pwc-icons.php';

				// init KC
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				if ( is_plugin_active( 'kingcomposer/kingcomposer.php' ) || is_plugin_active_for_network( 'kingcomposer/kingcomposer.php' ) ) {
					require PWC_INCLUDE_DIR . 'kc/kc.php';
				}

			}

			/**
			 * Get the plugin path.
			 *
			 * @return string
			 */
			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			public function pwc_textdomain() {
				load_plugin_textdomain( 'product-woo-configurator', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			}

			/**
			 * Adding custom template for plugin. Redirect to our page template if slug is "config" (Later add option in setting to ask page id. then get slug from that page id)
			 */
			public function page_template( $page_template ) {

				if ( is_page( 'config' ) ) {
					$page_template = pwc_locate_template( 'product-config.php' );
				} elseif ( is_singular( 'configurator' ) ) {
					$page_template = pwc_locate_template( 'single-configurator.php' );
				}
				return $page_template;
			}

			/**
			 * Get the template path.
			 *
			 * @return string
			 */
			public function template_path() {
				return apply_filters( 'configurator_template_path', 'configurator/' );
			}

		}

	}

	/**
	 * Main instance of PWC.
	 *
	 * Returns the main instance of PWC to prevent the need to use globals.
	 */
	function PWC() {
		return PWC::instance();
	}

	// Global for backwards compatibility.
	$pwc = PWC();

} else {

	add_action( 'admin_notices', 'pwc_requirements_notice' );

	/**
	 * Plugin requirement notice.
	 *
	 * @return void
	 */
	function pwc_requirements_notice() {
		echo '<div class="updated"><p>' . __( 'WP Configurator requires <strong>WooCommerce</strong> to be installed and activated on your site.', 'product-woo-configurator' ) . '</p></div>';
	}

	// Deactivate the plugin.
	deactivate_plugins( __FILE__ );
}
