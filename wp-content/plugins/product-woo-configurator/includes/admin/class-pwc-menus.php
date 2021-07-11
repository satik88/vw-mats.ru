<?php
/**
 * Menu
 *
 * Add Menus
 *
 * @class     PWC_Plugin_menu
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Plugin_menu Class.
 */

if( ! class_exists( 'PWC_Plugin_menu' ) ) {

	class PWC_Plugin_menu {

		/**
		 * Hook in methods.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_menus' ) );
		}

		/**
		 * Register plugin menu
		 */
		public function register_menus() {

			add_submenu_page( 
				'edit.php?post_type=amz_configurator', 
				esc_html__( 'Settings', 'product-woo-configurator' ), 
				esc_html__( 'Settings', 'product-woo-configurator' ), 
				'administrator', 
				'pwc-settings', 
				array( $this, 'manage_settings_page' )
			);

		}

		/**
		 * Callback function for Settings
		 */
		public function manage_settings_page() {
			?>
				<div class="pwc-settings-wrap">

					<h2 class="title"><?php esc_html_e( 'Welcome to WP Configurator!', 'product-woo-configurator' ); ?></h2>
					<p class="sub-title"><?php _e( 'Thank you for using WooCommerce Configurator. If you need help or have any suggestions, please contact us.', 'product-woo-configurator' ); ?></p>

					<h2 class="nav-tab-wrapper">
						<?php
							$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
						?>
						<a href="?post_type=amz_configurator&page=pwc-settings&tab=general" class="nav-tab <?php echo 'general' == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General', 'product-woo-configurator' ); ?></a>
						<a href="?post_type=amz_configurator&page=pwc-settings&tab=license" class="nav-tab <?php echo 'license' == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'License', 'product-woo-configurator' ); ?></a>
						<a href="?post_type=amz_configurator&page=pwc-settings&tab=css" class="nav-tab <?php echo 'css' == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Custom CSS', 'product-woo-configurator' ); ?></a>
					    <a href="?post_type=amz_configurator&page=pwc-settings&tab=addon" class="nav-tab <?php echo 'addon' == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Addons', 'product-woo-configurator' ); ?></a>
					</h2> <!-- .nav-tab-wrapper -->
					
					<?php
						if( 'general' == $tab ) :

							require_once( PWC_INCLUDE_DIR .'admin/settings-page/general.php' );

						elseif( 'license' == $tab ) :

							require_once( PWC_INCLUDE_DIR .'admin/settings-page/license.php' );

						elseif( 'css' == $tab ) :

							require_once( PWC_INCLUDE_DIR .'admin/settings-page/options-css.php' );

						elseif( 'addon' == $tab ) :

							require_once( PWC_INCLUDE_DIR .'admin/settings-page/options-addon.php' );

						endif;
					?>

				</div> <!-- .pwc-settings-wrap -->

			<?php
		}
	}

}

new PWC_Plugin_menu();