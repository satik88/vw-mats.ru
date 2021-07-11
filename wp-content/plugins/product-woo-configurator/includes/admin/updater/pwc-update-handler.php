<?php

class PWC_Update_handler {

	public function __construct(){

		$this->define_constants();
		$this->includes();

		$status = get_option( 'pwc_license_status' ); 

		if( 'valid' == $status ) {
			$this->setup_update();
		}		

		add_action('admin_init', array( $this,'pwc_register_option' ) );

		add_action('admin_init', array( $this,'pwc_activate_license' ) );

		add_action( 'admin_notices', array( $this, 'pwc_admin_notices' ) );

		add_action('admin_init', array( $this, 'pwc_deactivate_license' ) );

	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {

		// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
		define( 'PWC_STORE_URL', 'https://luminesthemes.com' );

		// the id of your product in EDD
		define( 'PWC_ITEM_ID', 2407 );

		define( 'PWC_PLUGIN_LICENSE_PAGE', 'edit.php?post_type=amz_configurator&page=pwc-settings&tab=license' ); //add plugin page here

	}

	private function includes() {

		if( ! class_exists( 'PWC_Plugin_Updater' ) ) {
			// load our custom updater
			include( dirname( __FILE__ ) . '/class-pwc-plugin-updater.php' );
		}

	}

	private function setup_update() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( 'pwc_license_key' ) );

		// setup the updater
		$pwc_updater = new PWC_Plugin_Updater( PWC_STORE_URL, PWC_PLUGIN_FILE, array( 
				'version' 	=> PWC_VERSION, 	// current version number
				'license' 	=> $license_key, 	// license key (used get_option above to retrieve from DB)
				'item_id'   => PWC_ITEM_ID, 	// id of this product in EDD
				'author' 	=> 'Luminesthemes', // author of this plugin
				'url'       => home_url()
			)
		);

	}

	public function pwc_register_option() {
		// creates our settings in the options table
		register_setting('pwc_license', 'pwc_license_key', array( $this,'pwc_sanitize_license') );
	}

	public function pwc_sanitize_save_license( $new ) {
		
		$old = get_option( 'pwc_license_key' );

		if( ! $old ) {			
			update_option( 'pwc_license_key', $new );
		}

		if( $old && $old != $new ) {
			delete_option( 'pwc_license_status' ); // new license has been entered, so must reactivate			
			update_option( 'pwc_license_key', $new );
		}
		// return $new;
	}

	public function pwc_sanitize_license( $new ) {
		$old = get_option( 'pwc_license_key' );
		if( $old && $old != $new ) {
			delete_option( 'pwc_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}

	public function pwc_activate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['pwc_license_activate'] ) ) {
			// run a quick security check
		 	if( ! check_admin_referer( 'pwc_nonce', 'pwc_nonce' ) )
				return; // get out if we didn't click the Activate button
			// retrieve the license from the database
			$license = trim( get_option( 'pwc_license_key' ) );

			if( ! $license && isset( $_POST['pwc_license_key'] ) ) {
				$license = trim( $_POST['pwc_license_key'] );
			}

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_id'    => PWC_ITEM_ID, // The ID of the item in EDD
				'url'        => home_url()
			);
			// Call the custom API.
			$response = wp_remote_post( PWC_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				$message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				if ( false === $license_data->success ) {
					switch( $license_data->error ) {
						case 'expired' :
							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;
						case 'revoked' :
							$message = __( 'Your license key has been disabled.' );
							break;
						case 'missing' :
							$message = __( 'Invalid license.' );
							break;
						case 'invalid' :
						case 'site_inactive' :
							$message = __( 'Your license is not active for this URL.' );
							break;
						case 'item_name_mismatch' :
							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), PWC_ITEM_NAME );
							break;
						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.' );
							break;
						default :
							$message = __( 'An error occurred, please try again.' );
							break;
					}
				}
			}
			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( PWC_PLUGIN_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
				wp_redirect( $redirect );
				exit();
			}
			// $license_data->license will be either "valid" or "invalid"
			update_option( 'pwc_license_status', $license_data->license );
			$this->pwc_sanitize_save_license( $license );
			wp_redirect( admin_url( PWC_PLUGIN_LICENSE_PAGE ) );
			exit();
		}
	}

	public function pwc_deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST['pwc_license_deactivate'] ) ) {

			// run a quick security check
		 	if( ! check_admin_referer( 'pwc_nonce', 'pwc_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( 'pwc_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => PWC_ITEM_ID, 
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_post( PWC_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( PWC_PLUGIN_LICENSE_PAGE );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( 'pwc_license_status' );
				delete_option( 'pwc_license_key' );
			}

			wp_redirect( admin_url( PWC_PLUGIN_LICENSE_PAGE ) );
			exit();

		}
	}

	/**
	 * This is a means of catching errors from the activation method above and displaying it to the customer
	 */
	public function pwc_admin_notices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
			switch( $_GET['sl_activation'] ) {
				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;
				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;
			}
		}
	}

}

new PWC_Update_handler();
