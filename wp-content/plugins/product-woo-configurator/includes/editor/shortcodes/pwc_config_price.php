<?php 

$pix_options = array(

	array(
		'name'    => esc_html__( 'Choose Configurator', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Choose Configurtor you want to display', 'product-woo-configurator' ),
		'id'      => 'id',
		'std'     => '',
		'options' => pwc_configurator_posts(),
		'type'    => 'select'
	),

	array(
		'name' 	=> 	esc_html__( 'Total Price Text', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'Type the total price text', 'product-woo-configurator' ),
		'id'	=>	'total_price_text',
		'std'	=>	esc_html__( 'Total Price', 'product-woo-configurator' ),
		'type'	=>	'text'
	),

	array(
		'name' 	=> 	esc_html__( 'Base Price Text', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'Type the base price text', 'product-woo-configurator' ),
		'id'	=>	'base_price_text',
		'std'	=>	esc_html__( 'Base Price', 'product-woo-configurator' ),
		'type'	=>	'text'
	),

	array(
		'name' 	=> 	esc_html__( 'Extra class name', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'product-woo-configurator' ),
		'id'	=>	'extra_class',
		'std'	=>	'',
		'type'	=>	'text'
	),

);