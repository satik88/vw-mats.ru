<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$addons_lists = apply_filters( 'pwc_addons_lists', array() );
$addon        = apply_filters( 'pwc_addon', '' );

$subtab       = isset( $_GET['subtab'] ) ? $_GET['subtab'] : key( $addons_lists );

if( ! empty( $addons_lists ) ) {

	// Sub tab menu
	echo '<ul class="sub-tab">';

		foreach( $addons_lists as $key => $addon ) {
			echo '<li><a href="?post_type=amz_configurator&page=pwc-settings&tab=addon&subtab='. $key .'">'. esc_html( $addon ) .'</a></li>';

			
		}

	echo '</ul>'; // .sub-tab
}

if( isset( $subtab ) && ! empty( $subtab ) ) {

	$fields = apply_filters( 'pwc_addons_'. $subtab .'_settings_fields', array() );

	if( ! empty( $fields ) ) {
		$settings = new pwc_option_fields( $fields );
	}

	do_action( 'pwc_addons_'. $subtab .'_license' );

}