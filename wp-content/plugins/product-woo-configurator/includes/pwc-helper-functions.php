<?php
/**
 * PWC Helper Functions
 *
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [pwc_featured_thumbnail Get feature thumbnail]
 * @version  1.0
 * @param  [string/int] $width
 * @param  [string/int] $height
 * @param  [bool] $only_src
 * @param  [bool] $show_placeholder
 * @return [string] Returns image Url/Image Tag
 */

if( ! function_exists( 'pwc_featured_thumbnail' ) ) {

	function pwc_featured_thumbnail( $width = 'full', $height = 'full', $only_src = true, $show_placeholder = true ) {

	    $output = $image_thumb_url = $img_url = '';

	    if( has_post_thumbnail() ){

	        $image_id = get_post_thumbnail_id();

	        $image_thumb_url = wp_get_attachment_image_src( $image_id, 'full' );
	    }

	    if( ! is_int( $width ) ) {
	        $width = 'full';
	    } 

	    if( !is_int( $height ) ) {
	        $height = 'full';
	    }

	    if( ! empty( $image_thumb_url ) ) {

	        $img = pwc_aq_resize( $image_thumb_url[0], $width , $height, true, true );

	        // if that image not met the mentioned width/height loads full size image url
	        $img_url = ( $img ) ? $img : $image_thumb_url[0];

	        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

	    }
	    else if( empty( $image_thumb_url ) && $show_placeholder ) {

	    	$protocol = is_ssl() ? 'https' : 'http';

	        $default_placeholder = '';

	        $img_url = empty( $default_placeholder ) ? $protocol.'://placehold.it/'.$width.'x'.$height : $default_placeholder;	

	        $alt = esc_attr__( 'Placeholder', 'product-woo-configurator' );
	    }

	    if( $only_src ) {
            $output = $img_url;
        }
        else {
            $output = '<img src="'.esc_url( $img_url ) .'" alt="'. esc_attr( $alt ) .'">';
        }

	    return $output;

	}
}

/**
 * [pwc_get_image_by_id Returns image Url/Image Tag by attachemnt ID]
 * @version  1.0
 * @param  [string/int] $width
 * @param  [string/int] $height
 * @param  [bool] $only_src
 * @param  [bool] $show_placeholder
 * @return [string] 
 */

if( ! function_exists( 'pwc_get_image_by_id' ) ) {

	function pwc_get_image_by_id( $width, $height, $image_id = '', $only_src = true, $placeholder = false ) {

		if( empty( $image_id ) ) {
			return;
		}

		// Empty assignment
		$output = $image_thumb_url = $img_url = $alt = '';

		// Full image URL
	    $image_thumb_url = wp_get_attachment_image_src( $image_id, 'full' );

	    if( ! is_int( $width ) ) {
	        $width = 'full';
	    }

	    if( ! is_int( $height ) ) {
	        $height = 'full';
	    }

	    if( ! empty( $image_thumb_url ) ) {

	        $img = pwc_aq_resize( $image_thumb_url[0], $width , $height, true, true );

	        // if that image not met the mentioned width/height loads full size image url
	        $img_url = ( $img ) ? $img : $image_thumb_url[0];

	        $alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

	    }
	    else if( empty( $image_thumb_url ) && $placeholder ) {

	    	$protocol = is_ssl() ? 'https' : 'http';

	        $default_placeholder = '';

	        $img_url = empty( $default_placeholder ) ? $protocol.'://placehold.it/'.$width.'x'.$height : $default_placeholder;	

	        $alt = esc_attr__( 'Placeholder', 'product-woo-configurator' );

	    }

	    if( $only_src ) {
            $output = $img_url;
        }
        else {
            $output = '<img src="'.esc_url( $img_url ) .'" alt="'. esc_attr( $alt ) .'">';
        }

	    return $output;                 

	}
}

/**
 * [pwc_get_meta_value Returns metabox value]
 * @param  [string] $id
 * @param  [type] $meta_key
 * @param  string $meta_default
 * @return [string/int/bool/array]
 */

if( ! function_exists( 'pwc_get_meta_value' ) ) {
	function pwc_get_meta_value( $id = '', $meta_key = '', $meta_default = '' ) {

		$value = ( null != get_post_meta( $id, $meta_key, true ) ) ? get_post_meta( $id, $meta_key, true ) : $meta_default;

		return $value;
	}
}

/**
 * [pwc_random_string Returns random string]
 * @param  [int] $length
 * @return [string]
 */

if( ! function_exists( 'pwc_random_string' ) ) {

	function pwc_random_string( $length = 4 ) {

		// Empty assignment
		$random_string = '';

	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $characters_length = strlen( $characters );
	    
	    for ( $i = 0; $i < $length; $i++ ) {
	        $random_string .= $characters[ rand( 0, $characters_length - 1 ) ];
	    }

	    return $random_string;

	}

}

/**
 * [pwc_user_role Returns User role. Eg: Administrator, Subscriber]
 * @param  int $user_id
 * @return [string]
 */

if( ! function_exists( 'pwc_user_role' ) ) {
	function pwc_user_role( $user_id = '' ) {

	    $user_id = ( isset( $user_id ) && !empty( $user_id ) ) ? $user_id : get_current_user_id(); 

	    $user_info = get_userdata( $user_id );
	    $role = ( $user_info && $user_info->roles ) ? $user_info->roles[0] : '';

	    return $role;
	}
}

/**
 * [pwc_array_filter_recursive]
 * @param  [array] $array
 * @param  [string] $callback
 * @return [array]
 */

if( ! function_exists( 'pwc_array_filter_recursive' ) ) {

	function pwc_array_filter_recursive( $array, $callback = null ) {

	    foreach( $array as $key => & $value ) {
	        if ( is_array( $value ) ) {
	            $value = pwc_array_filter_recursive( $value, $callback );
	        }
	        else {
	            if ( ! is_null( $callback ) ) {
	                if ( ! $callback( $value ) ) {
	                    unset( $array[$key] );
	                }
	            }
	            else {
	                if ( ! (bool) $value ) {
	                    unset( $array[$key] );
	                }
	            }
	        }
	    }

	    unset( $value );

	    return $array;
	}

}

/**
 * [pwc_get_styles Returns configurator styles]
 * @return [array]
 */

if( ! function_exists( 'pwc_get_styles' ) ) {

	function pwc_get_styles() {

		$style = array(
			'style1'    => esc_html__( 'Style 1', 'product-woo-configurator' ),
			'style2'    => esc_html__( 'Style 2', 'product-woo-configurator' ),
			'accordion' => esc_html__( 'Accordion Control', 'product-woo-configurator' )
		);

	    return apply_filters( 'pwc_config_styles', $style );
	}

}

/**
 * [pwc_configurator_posts Returns array list of configurator posts]
 * @return [array]
 */

if( ! function_exists( 'pwc_configurator_posts' ) ) {

	function pwc_configurator_posts() {	

		$option = array( 0 => esc_html__( 'Choose Configurator', 'product-woo-configurator' ) );
		
		$args = array(
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'post_type'           => 'amz_configurator',
			'order'               => 'DESC',
			'orderby'             => 'date',
			'posts_per_page'      => -1
		);

		$args = apply_filters( 'pwc_configurator_posts_options_args', $args );
		
		$posts_array = get_posts( $args );

		foreach ( $posts_array as $post ) {
			$option[$post->ID] = $post->post_title;
		}

		wp_reset_postdata();

		$option = apply_filters( 'pwc_configurator_posts_options', $option );

		return $option;
	}

}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *	yourtheme       /   $template_path  /   $template_name
 *	yourtheme       /   $template_name
 *	$default_path   /   $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */

if( ! function_exists( 'pwc_locate_template' ) ) {

	function pwc_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	    if ( ! $template_path ) {
	        $template_path = PWC()->template_path();
	    }

	    if ( ! $default_path ) {
	        $default_path = apply_filters( 'pwc_template_default_path', PWC()->plugin_path() . '/templates/' );
	    }

	    // Look within passed path within the theme - this is priority.
	    $template = locate_template(
	        array(
	            trailingslashit( $template_path ) . $template_name,
	            $template_name
	        )
	    );

	    // Get default template/
	    if ( ! $template ) {
	        $template = $default_path . $template_name;
	    }

	    // Return what we found.
	    return apply_filters( 'pwc_locate_template', $template, $template_name, $template_path );
	}

}

/**
 * [pwc_get_template_part Get template part (for templates).]
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */

if( ! function_exists( 'pwc_get_template_part' ) ) {

	function pwc_get_template_part( $slug, $name = '' ) {

	    $template = '';

	    // Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
	    if ( $name ) {
	        $template = locate_template( array( "{$slug}-{$name}.php", PWC()->template_path() . "{$slug}-{$name}.php" ) );
	    }

	    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
	    if ( ! $template ) {
	        $template = locate_template( array( "{$slug}.php", PWC()->template_path() . "{$slug}.php" ) );
	    }

	    // Get default slug-name.php
	    if ( ! $template && $name && file_exists( PWC()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
	        $template = PWC()->plugin_path() . "/templates/{$slug}-{$name}.php";
	    }

	    if ( ! $template && file_exists( PWC()->plugin_path() . "/templates/{$slug}.php" ) ) {
	        $template = PWC()->plugin_path() . "/templates/{$slug}.php";
	    }

	    // Allow 3rd party plugins to filter template file from their plugin.
	    $template = apply_filters( 'pwc_get_template_part', $template, $slug, $name );

	    if ( $template ) {
	        load_template( $template, false );
	    }

	}

}

/**
 * [pwc_remove_duplicate_string Separate string with commas and remove the duplicate string]
 * @param  string $string
 * @return string
 */

if( ! function_exists( 'pwc_remove_duplicate_string' ) ) {

	function pwc_remove_duplicate_string( $string = '' ) {

		$string = str_replace(' ', '', $string );

		$array = explode( ',', $string );
		$array = array_unique( array_filter( $array ) );

		$string = implode( ',', $array );

		return $string;
	}
	
}

/**
 * [pwc_get_users Returns users objects]
 * @param  string $role User role
 * @return string
 */

if( ! function_exists( 'pwc_get_users' ) ) {

	function pwc_get_users( $role = 'administrator' ) {

		$list = array();

		$args = array(
			'role' => $role
		); 

		$users = get_users( $args );

		if( isset( $users ) ) {
			foreach( $users as $user ) {
				$user_info = get_userdata( $user->ID );
				$list[$user->ID] = $user->user_email;
			}			
		}

		return $list;
	}
	
}

// TODO: text, text_small, checkbox, select
if( ! function_exists( 'pwc_add_additional_field' ) ) {
	/*
	 * add addtional field in configurator options
	 * array( $type, $id, $title, $desc, $options = array(), $view_control = false )
	 */
	function pwc_add_additional_field( $options ) {
		new PWC_Additional_Fields( $options );
	}

}
