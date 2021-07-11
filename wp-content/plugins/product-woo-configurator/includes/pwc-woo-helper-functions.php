<?php
/**
 * PWC WooCommerce Helper Functions
 *
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [pwc_apply_currency_position Get product price and append currency symbol ]
 * @param  [string] $price
 * @param  [string] $cur  
 * @return [string]
 */

if( ! function_exists( 'pwc_apply_currency_position' ) ) {

	function pwc_apply_currency_position( $price, $cur = '$' ) {

		$currency_pos = get_option( 'woocommerce_currency_pos' );

		if( function_exists( 'get_woocommerce_currency_symbol' ) ) {

			if( 'left' == $currency_pos ) {
				return get_woocommerce_currency_symbol() . $price;
			}
			elseif ( 'right' == $currency_pos ) {
				return $price . get_woocommerce_currency_symbol();
			}
			elseif ( 'left_space' == $currency_pos ) {
				return get_woocommerce_currency_symbol() . ' ' . $price;
			}
			elseif ( 'right_space' == $currency_pos ) {
				return $price . ' ' . get_woocommerce_currency_symbol();
			} 
			else {
				return get_woocommerce_currency_symbol() . $price;
			}
		
		}
		else {
			return $cur . $price;
		}	

	}

}