<?php
/**
 * The template for displaying product content in the single-product-configurator.php template
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$id = get_the_id();

if( get_the_content() ) {
	the_content();
	return;
}

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
 do_action( 'woocommerce_before_single_product' );

 if( post_password_required() ) {
 	echo get_the_password_form();
 	return;
 }

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

wc_print_notices();

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

$config_id = pwc_get_meta_value( $id, '_configurator_post_id', 'true' );

$pwc_desc = pwc_get_meta_value( $config_id, '_pwc_description', '' );

$style = pwc_get_meta_value( $id, '_configurator_style', 'style1' );
?>

<div id="product-<?php the_ID(); ?>" <?php post_class( 'single-product-wrap' ); ?>>

	<div class="single-product-content clearfix">
		
		<div class="single-product-titlewrap">

			<?php the_title( '<h2 class="single-product-title">', '</h2>', true ); ?>

			<?php
			if( ! empty( $pwc_desc ) ) :
				echo wp_kses_post( $pwc_desc );				
			endif;
			?>

		</div> <!-- .single-product-titlewrap -->

		<?php echo do_shortcode( '[pwc_config id="'. esc_attr( $config_id ) .'" product_id="'. esc_attr( $id ) .'" style="'. esc_attr( $style ) .'"]' ); ?>

	</div> <!-- .single-product-content -->

	<div class="single-product-upsells clearfix">

		<div class="container">

			<?php

			/**
			 * woocommerce_after_single_product_summary hook.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action( 'woocommerce_after_single_product_summary' );

			?>

		</div> <!-- .container -->

	</div> <!-- .single-product-upsells -->

</div> <!-- .single-product-wrap -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
