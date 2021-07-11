<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Init KC */
add_action('init', 'pwc_kc_int', 99 );

if( ! function_exists( 'pwc_kc_int' ) ) {
	function pwc_kc_int() {

		// Iniatialize
		require_once( PWC_INCLUDE_DIR . 'kc/class-kc-shortcode-init.php' );

		foreach ( glob( PWC_INCLUDE_DIR . 'kc/shortcodes/*/index.php') as $filename ) {
			require_once( $filename );
		}

	}
}