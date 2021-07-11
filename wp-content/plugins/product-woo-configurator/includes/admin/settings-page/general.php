<?php

	$field = array();

	$field[] = array(
		'title'        => esc_html__( 'Configurator', 'panorama' ),
		'type'         => 'title'
	);

	$field[] = array(
		'title'   => esc_html__( 'Icon Type', 'panorama' ),
		'desc'    => esc_html__( 'Choose icon type here.', 'panorama' ),
		'id'      => 'pwc_icon_type',
		'std'     => 'round',
		'type'    => 'select',
		'options' => array(
			'round'  => esc_html__( 'Round', 'product-woo-configurator' ),
			'square' => esc_html__( 'Square', 'product-woo-configurator' )
		)
	);

	$field[] = array(
		'title'   => esc_html__( 'Icon Width', 'panorama' ),
		'desc'    => esc_html__( 'Enter icon width here.', 'panorama' ),
		'id'      => 'pwc_icon_width',
		'std'     => '20',
		'type'    => 'text'
	);

	$field[] = array(
		'title'   => esc_html__( 'Icon Height', 'panorama' ),
		'desc'    => esc_html__( 'Enter icon height here.', 'panorama' ),
		'id'      => 'pwc_icon_height',
		'std'     => '20',
		'type'    => 'text'
	);

?>

<div class="wrap">
	<?php $settings = new pwc_option_fields( $field ); ?>
</div>
	
