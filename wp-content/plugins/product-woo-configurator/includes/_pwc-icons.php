<?php
/**
 * PWC Icons
 *
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [pwc_icons build icons classes as array]
 * 
 * @param  string $class
 * @return string
 */
	
if( ! function_exists( 'pwc_icons' ) ) {

	function pwc_icons( $class = '' ) {

		// Frontend
		$icon['pwcf-pencil']         = 'pwcf-pencil';
		$icon['pwcf-trash']          = 'pwcf-trash';
		$icon['pwcf-reset']          = 'pwcf-reset';
		$icon['pwcf-close']          = 'pwcf-close';
		$icon['pwcf-inspiration']    = 'pwcf-inspiration';
		$icon['pwcf-screenshot']     = 'pwcf-screenshot';
		$icon['pwcf-prev-arrow']     = 'pwcf-prev-arrow';
		$icon['pwcf-next-arrow']     = 'pwcf-next-arrow';
		$icon['pwcf-cart-icon']      = 'pwcf-cart-icon';
		$icon['pwcf-dribbble']       = 'pwcf-dribbble';
		$icon['pwcf-eye']            = 'pwcf-eye';
		$icon['pwcf-facebook']       = 'pwcf-facebook';
		$icon['pwcf-copy']           = 'pwcf-copy';
		$icon['pwcf-flickr']         = 'pwcf-flickr';
		$icon['pwcf-google-plus']    = 'pwcf-google-plus';
		$icon['pwcf-instagram']      = 'pwcf-instagram';
		$icon['pwcf-linkedin']       = 'pwcf-linkedin';
		$icon['pwcf-pinterest']      = 'pwcf-pinterest';
		$icon['pwcf-refresh']        = 'pwcf-refresh';
		$icon['pwcf-tumblr']         = 'pwcf-tumblr';
		$icon['pwcf-twitter']        = 'pwcf-twitter';
		$icon['pwcf-cancel']         = 'pwcf-cancel';
		$icon['pwcf-left-open']      = 'pwcf-left-open';
		$icon['pwcf-left-open-big']  = 'pwcf-left-open-big';
		$icon['pwcf-right-open']     = 'pwcf-right-open';
		$icon['pwcf-right-open-big'] = 'pwcf-right-open-big';
		$icon['pwcf-up-open-big']    = 'pwcf-up-open-big';

		// Backend
		$icon['pwc-angle-right']      = 'pwc-angle-right';
		$icon['pwc-angle-left']       = 'pwc-angle-left';
		$icon['pwc-angle-down']       = 'pwc-angle-down';
		$icon['pwc-angle-up']         = 'pwc-angle-up';
		$icon['pwc-folder']           = 'pwc-folder';
		$icon['pwc-folder-open']      = 'pwc-folder-open';
		$icon['pwc-refresh']          = 'pwc-refresh';
		$icon['pwc-sort-desc']        = 'pwc-sort-desc';
		$icon['pwc-sort-asc']         = 'pwc-sort-asc';
		$icon['pwc-eye-disabled']     = 'pwc-eye-disabled';
		$icon['pwc-eye']              = 'pwc-eye';
		$icon['pwc-minus']            = 'pwc-minus';
		$icon['pwc-plus']             = 'pwc-plus';
		$icon['pwc-locked-simple']    = 'pwc-locked-simple';
		$icon['pwc-lock-simple-open'] = 'pwc-lock-simple-open';
		$icon['pwc-cross']            = 'pwc-cross';
		$icon['pwc-tick']             = 'pwc-tick';
		$icon['pwc-arrow-top']        = 'pwc-arrow-top';
		$icon['pwc-arrow-bottom']     = 'pwc-arrow-bottom';
		$icon['pwc-arrow-left']       = 'pwc-arrow-left';
		$icon['pwc-arrow-right']      = 'pwc-arrow-right';
		$icon['pwc-media']            = 'pwc-media';
		$icon['pwc-bucket']           = 'pwc-bucket';
		$icon['pwc-layer']            = 'pwc-layer';
		$icon['pwc-pencil']           = 'pwc-pencil';
		$icon['pwc-fullscreen']       = 'pwc-fullscreen';
		$icon['pwc-delete']           = 'pwc-delete';
		$icon['pwc-pen']              = 'pwc-pen';
		$icon['pwc-setting-simple']   = 'pwc-setting-simple';
		$icon['pwc-reload']           = 'pwc-reload';
		$icon['pwc-trashcan']         = 'pwc-trashcan';
		$icon['pwc-square-path']      = 'pwc-square-path';
		$icon['pwc-align-bottom']     = 'pwc-align-bottom';
		$icon['pwc-align-middle']     = 'pwc-align-middle';
		$icon['pwc-align-top']        = 'pwc-align-top';
		$icon['pwc-align-center']     = 'pwc-align-center';
		$icon['pwc-align-left']       = 'pwc-align-left';
		$icon['pwc-align-right']      = 'pwc-align-right';
		$icon['pwc-reload-simple']    = 'pwc-reload-simple';
		$icon['pwc-cross-simple']     = 'pwc-cross-simple';
		$icon['pwc-lock']             = 'pwc-lock';
		$icon['pwc-unlock']           = 'pwc-unlock';
		$icon['pwc-sort']             = 'pwc-sort';

		$icon = apply_filters( 'pwc_icons_array', $icon );

		if( isset( $icon[$class] ) ) {
			return $icon[$class];
		}
		
	}
	
}

add_filter( 'pwc_icons', 'pwc_icons', 10, 1 );