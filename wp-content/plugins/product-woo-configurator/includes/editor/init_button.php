<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'pwc_shortcodes_rich_editor_button_init' ) ) {

	class pwc_shortcodes_rich_editor_button_init {

		private $pluginname = 'pwc_shortcodes_button';
		
		public function __construct() {
			
			add_action( 'init', array( $this, 'shortcodes_button' ) );
			
		}

		public function shortcodes_button() {
			
			// Only add hooks when the current user has permissions AND is in Rich Text editor mode
			if ( current_user_can('edit_posts') && current_user_can('edit_pages') && get_user_option('rich_editing') == 'true' ) {

				//Loading necessary Files
				add_action( 'admin_enqueue_scripts'	, array( $this, 'admin_enqueue_scripts' ) );

				add_filter( 'mce_external_plugins', array( $this, 'shortcodes_tinymce_plugin' ) );
				add_filter( 'mce_buttons', array( $this, 'register_button' ) );
				add_filter( 'admin_print_scripts' , array( $this, 'tinymce_create_menu' ), 99 );

				add_action( 'wp_ajax_pix_sc_options' , array( $this, 'shortcode_options' ) );

			}
			
		}

		public function admin_enqueue_scripts( $hook ) {
			if( 'post.php' == $hook || 'post-new.php' == $hook ) {
				wp_enqueue_style( 'pwc_tinymce', PWC_PLUGIN_URL .'/includes/editor/tinymce/css/style.css', array(), '1.0', 'all' );
				wp_enqueue_script( 'pwc_tinymce', PWC_PLUGIN_URL .'/includes/editor/tinymce/js/dialog.js', array( 'jquery' ), '1.0' );
			}
		}

		// Load the TinyMCE plugin
		public function shortcodes_tinymce_plugin( $plugin_array ) {

			$plugin_array[$this->pluginname] = PWC_PLUGIN_URL . '/includes/editor/tinymce/editor_plugin.js';
			return $plugin_array;

		}

		public function register_button( $buttons ) {
			array_push( $buttons, 'separator', $this->pluginname );
			return $buttons;
		}

		function tinymce_create_menu() {

			// Template
			$dialog = 	
			'<div id="pix-dialog" class="pix-dialog">
				<div class="pix-content">
					<a class="close" href="#" title="Close"><span class="media-modal-icon"></span></a>
					<div class="title">
						<h2>'. esc_html__( 'Demo Title', 'product-woo-configurator' ) .'</h2>
					</div>
					<div class="options"></div>
					<div class="footer">
						<a href="#" class="button media-button button-primary button-large media-button-insert">'. esc_html__( 'Insert Shortcode', 'product-woo-configurator' ) .'</a>
					</div>
				<div>
			</div>';

			// Menu array
			$menu = array();

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Accordion Control', 'product-woo-configurator' ),
				'name'       => 'pwc_config_accordion_control',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Cart Form', 'product-woo-configurator' ),
				'name'       => 'pwc_config_cart_form',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Controls', 'product-woo-configurator' ),
				'name'       => 'pwc_config_controls',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Inspiration', 'product-woo-configurator' ),
				'name'       => 'pwc_config_inspiration',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Preview', 'product-woo-configurator' ),
				'name'       => 'pwc_config_preview',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Price', 'product-woo-configurator' ),
				'name'       => 'pwc_config_price',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator Share', 'product-woo-configurator' ),
				'name'       => 'pwc_config_share',
				'insertType' => 'popup'
			);

			$menu[] = array( 	
				'title'      => esc_html__( 'Configurator', 'product-woo-configurator' ),
				'name'       => 'pwc_config',
				'insertType' => 'popup'
			);
			
			echo "<script type='text/javascript'>\n";
			echo "var pwc_menu_globals = {}, configurator_dialog_globals = {};\n";
			echo "configurator_dialog_globals = " . json_encode( $dialog ) . ";\n";
			echo "pwc_menu_globals = " . json_encode( $menu ) . ";\n";
			echo "\n</script>";

		}

		public function shortcode_options() {

			$pix_options = esc_html__( 'Options Not Set', 'product-woo-configurator' );

			$shortcode = isset( $_REQUEST['pix_sc'] ) ? $_REQUEST['pix_sc'] : esc_html__( 'Not set', 'product-woo-configurator' );

			require_once( PWC_INCLUDE_DIR .'/editor/shortcodes/' . $shortcode .'.php');

			echo json_encode( $pix_options );

			die();

		}

	}
}

new pwc_shortcodes_rich_editor_button_init();