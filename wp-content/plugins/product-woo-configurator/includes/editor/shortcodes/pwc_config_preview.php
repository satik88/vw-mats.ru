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
		'name' 	=> 	esc_html__( 'Extra class name', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'product-woo-configurator' ),
		'id'	=>	'extra_class',
		'std'	=>	'',
		'type'	=>	'text'
	),

	array(
		'name'    => esc_html__( 'Enable autoplay', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Do you want to enable autoplay', 'product-woo-configurator' ),
		'id'      => 'autoplay',
		'std'     => 'false',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name' 	=> 	esc_html__( 'Slide Speed', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'Enter the Value in milesecond (Ex: 5000)', 'product-woo-configurator' ),
		'id'	=>	'slide_speed',
		'std'	=>	'5000',
		'type'	=>	'text'
	),

	array(
		'name' 	=> 	esc_html__( 'Slide Margin', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'Enter the integer value (Ex: 30)', 'product-woo-configurator' ),
		'id'	=>	'slide_margin',
		'std'	=>	'30',
		'type'	=>	'text'
	),

	array(
		'name' 	=> 	esc_html__( 'Stage Padding', 'product-woo-configurator' ),
		'desc' 	=> 	esc_html__( 'Enter the integer value (Ex: 30)', 'product-woo-configurator' ),
		'id'	=>	'stage_padding',
		'std'	=>	'50',
		'type'	=>	'text'
	),

	array(
		'name'    => esc_html__( 'Arrow', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Do you want to display arrows?', 'product-woo-configurator' ),
		'id'      => 'slide_arrow',
		'std'     => 'false',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Pagination', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Do you want to display pagination dots?', 'product-woo-configurator' ),
		'id'      => 'slider_pagination',
		'std'     => 'true',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Loop', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion.', 'product-woo-configurator' ),
		'id'      => 'loop',
		'std'     => 'false',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Mouse Drag', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Do you want to change the slide using mouse drag', 'product-woo-configurator' ),
		'id'      => 'mouse_drag',
		'std'     => 'true',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Touch Drag', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Do you want to change the slide using touch drag(in touch devices)', 'product-woo-configurator' ),
		'id'      => 'touch_drag',
		'std'     => 'true',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Stop on Hover', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'If the mouse pointer is placed on slider it will pause', 'product-woo-configurator' ),
		'id'      => 'stop_on_hover',
		'std'     => 'true',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	),

	array(
		'name'    => esc_html__( 'Auto Height', 'product-woo-configurator' ),
		'desc'    => esc_html__( 'Slider auto height', 'product-woo-configurator' ),
		'id'      => 'auto_height',
		'std'     => 'true',
		'options' => array(
			'true'  => esc_html__( 'Yes', 'product-woo-configurator' ),
			'false' => esc_html__( 'No', 'product-woo-configurator' )
		),
		'type'    => 'select'
	)

);