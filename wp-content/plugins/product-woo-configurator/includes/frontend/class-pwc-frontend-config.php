<?php
/**
 * Frontend Config
 *
 * Frontend Settings
 *
 * @class     PWC_Frontend_config
 * @version   1.4
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Frontend_config Class.
 */
class PWC_Frontend_config {

	private $active_array       = '';
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

	/**
	 * Hook in methods.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );

	}



	/**
	 * Register css and scripts
	 */
	public function enqueue_scripts( $hook_suffix ) {

		// Load css
		wp_enqueue_style( 'pwc-frontend-css', PWC_ASSETS_URL . 'frontend/css/pwc-frontend.css', array(), PWC_VERSION );
		wp_enqueue_style( 'owl-carousel', PWC_ASSETS_URL . 'frontend/css/owl-carousel.css', array(), PWC_VERSION );
		wp_enqueue_style( 'pwc-icon-css', PWC_ASSETS_URL . 'frontend/css/pwc-front-icon.css', array(), PWC_VERSION );

		// Scripts
		wp_enqueue_script( 'owl-carousel', PWC_ASSETS_URL . 'frontend/js/owl-carousel.js', array( 'jquery', 'jquery-ui-sortable' ), PWC_VERSION, true );
		wp_enqueue_script( 'html2canvas', PWC_ASSETS_URL . 'frontend/js/html2canvas.js', array( 'owl-carousel' ), PWC_VERSION, true );
		wp_enqueue_script( 'pwc-clipboard', PWC_ASSETS_URL . 'frontend/js/clipboard.js', array( 'html2canvas' ), PWC_VERSION, true );
		wp_enqueue_script( 'jquery-base64', PWC_ASSETS_URL . 'frontend/js/jquery.base64.min.js', array( 'clipboard' ), PWC_VERSION, true );

		wp_enqueue_script( 'accounting', WC()->plugin_url() . '/assets/js/accounting/accounting.js', array( 'jquery' ), '0.4.2' );

		wp_enqueue_script( 'pwc-frontend-script-js', PWC_ASSETS_URL . 'frontend/js/pwc-frontend-script.js', array( 'jquery' ), PWC_VERSION, true );

		$currency_pos = 'left';
		$symbol       = '';
		if ( class_exists( 'Woocommerce' ) ) {
			$currency_pos = get_option( 'woocommerce_currency_pos' );
			$symbol       = get_woocommerce_currency_symbol();
		}

		wp_localize_script(
			'pwc-frontend-script-js',
			'pwc_plugin',
			array(
				'ajaxurl'           => esc_url( admin_url( 'admin-ajax.php' ) ),
				'currency_position' => $currency_pos,
				'currency_symbol'   => $symbol,
				'rtl'               => is_rtl() ? 'true' : 'false',
			)
		);

		$lib = array(
			'symbol'    => get_woocommerce_currency_symbol(),
			'format'    => str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ),
			'decimal'   => wc_get_price_decimal_separator(),
			'thousand'  => wc_get_price_thousand_separator(),
			'precision' => wc_get_price_decimals(),
		);

		wp_localize_script( 'pwc-frontend-script-js', 'lib', $lib );

	}



	/**
	 * WP Head hook
	 */
	public function wp_head() {
		$custom_css = get_option( 'pwc_custom_css' );

		echo '<style type="text/css">' . $custom_css . '</style>';
	}

}

function PWCFC() {
	// return new Configurator();
	return PWC_Frontend_config::instance();
}

$pwc_front_end = PWCFC();
