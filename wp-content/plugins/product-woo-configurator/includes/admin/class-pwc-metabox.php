<?php
/**
 * Metabox
 *
 * Registers metaboxes
 *
 * @class     PWC_Meta_boxes
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Meta_boxes Class.
 */
class PWC_Meta_boxes {

	/**
	 * Hook in methods.
	 */
	public function __construct() {

		/*
		 * Register Meta boxes
		*/
		add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );

		/*
		 * Save Meta boxes
		*/

		add_action( 'save_post', array( $this, 'save_configuration' ) );

		add_action('wp_ajax_pwc_save_inspiration', array( $this, 'save_inspiration' ) );
		add_action('wp_ajax_nopriv_pwc_save_inspiration', array( $this, 'save_inspiration' ) );

		add_action('wp_ajax_pwc_delete_view', array( $this, 'delete_view' ) );
		add_action('wp_ajax_nopriv_pwc_delete_view', array( $this, 'delete_view' ) );
	}

	/**
	 * Register core metaboxes.
	 */
	public function register_metaboxes() {

		// Configuration
		add_meta_box( 'pwc-configration-settings', esc_html__( 'Configuration Settings', 'configurator' ), array( $this, 'configuration_settings' ), 'amz_configurator', 'advanced', 'high' );
	}

	/**
	 * Callback function for Settings metabox
	 */
	public function configuration_settings( $post ) {
		return new PWC_Metabox_configuration( $post );
	}

	/**
	 * Save Meta box values
	 */
	public function save_configuration( $post_id ) {

		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		/* Verify the nonce before proceeding. */
		if ( ! isset( $_POST['pwc_configuration_save'] ) || 
			 !wp_verify_nonce( $_POST['pwc_configuration_save'], 'pwc_configuration' ) ||  
			 ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$components = isset( $_POST['components'] ) ? $_POST['components'] : '';

		if( !empty( $components ) ) {

			$components = apply_filters( 'pwc_config_before_save', $components );

			update_post_meta( $post_id, 'components', $components );

			do_action( 'pwc_before_save' );

		}
		else {
			delete_post_meta( $post_id, 'components' );
		}

		$metabox_config = new PWC_Metabox_configuration( $post_id );

		$base_fields = $metabox_config->get_base_fields();

		foreach( $base_fields as $key => $field ) {

			if( 'multitext' == $field['type'] ) {

				$old_multitext = get_post_meta( $post_id, '_pwc_views', true );
				$old_multitext = ! empty( $old_multitext ) ? $old_multitext : $field['default'];
				
				$save_field = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : array();
				if( !empty( $save_field ) ) {
					$values = array_merge( $old_multitext, array( $save_field ) );

					$new_value = array();
					foreach( $values as $key => $value ) {
						$new_value[trim(strtolower( str_replace( ' ', '-', $value ) ) ) ] = $value;
					}
					update_post_meta( $post_id, $field['id'], $new_value );
				}

			}
			else {
				$save_field = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : '';
				if( !empty( $save_field ) ) {
					update_post_meta( $post_id, $field['id'], $save_field );
				}
				else {
					delete_post_meta( $post_id, $field['id'] );
				}
			}
			
		}

	}

	/**
	 * Save Inspiration Meta box values via ajax
	 */
	public function save_inspiration() {

		// Get current user role
		$role = pwc_user_role();

		$type        = isset( $_POST['type'] ) ? $_POST['type'] : '';
		$values      = isset( $_POST['values'] ) ? $_POST['values'] : '';
		$id          = isset($values['configurator_id']) ? $values['configurator_id'] : '';
		$index       = isset($values['index']) ? $values['index'] : '';
		$group_index = isset($values['group-index']) ? $values['group-index'] : '';
		$group       = isset($values['group']) ? $values['group'] : '';
		$name        = isset($values['name']) ? $values['name'] : '';
		$desc        = isset($values['desc']) ? $values['desc'] : '';
		$image       = isset($values['image']) ? $values['image'] : '';
		$key         = isset($values['key']) ? $values['key'] : '';
		$price       = isset($values['price']) ? $values['price'] : '';

		echo '<div id="ajax-inspiration-data">';

			$old_val = pwc_get_meta_value( $id, '_pwc_inspiration', array() );

			// Empty assignment
			$values = $list = $all_group = $all_list = array();

			if( 'add-new' == $type ) {

				$list['name']  = $name;
				$list['desc']  = $desc;
				$list['image'] = $image;
				$list['key']   = $key;
				$list['price'] = $price;

				$old_group = isset( $old_val['group'] ) && ! empty( $old_val['group'] ) ? $old_val['group'] : array();
				$old_list_item = isset( $old_val['list'][$group] ) && ! empty( $old_val['list'][$group] ) ? $old_val['list'][$group] : array();
				$old_list = isset( $old_val['list'] ) && ! empty( $old_val['list'] ) ? $old_val['list'] : array();

				$all_list[$group] = array_merge( $old_list_item, array( $list ) );

				$all_group = array_merge( $old_group, array( $group ) );
				$all_list = array_filter( array_merge( $old_list, $all_list ) );

				$values['group'] = array_unique( $all_group );
				$values['list'] = $all_list;

				if( !empty( $name ) && !empty( $group ) ) {
					update_post_meta( $id, '_pwc_inspiration', $values );
					echo '<div id="ins-data" data-error="false">';
						echo '<span class="success">'. esc_html__( 'Created Successfully', 'configurator' ) .'</span>';
					echo '</div>';
				}
				else {
					echo '<div id="ins-data" data-error="true">';
						if( empty( $name ) ) {
							echo '<span class="name-error">'. esc_html__( 'Please type the inspiration name', 'configurator' ) .'</span>';
						}
						if( empty( $group ) ) {
							echo '<span class="group-error">'. esc_html__( 'Please type the group name', 'configurator' ) .'</span>';
						}
					echo '</div>';
				}

			}
			else if( 'reset' == $type ) {
				$old_list = isset( $old_val['list'][$group] ) && ! empty( $old_val['list'][$group] ) ? array_filter( $old_val['list'][$group] ) : array();

				$old_list[$index]['key']   = $key;
				$old_list[$index]['price'] = $price;

				$old_val['list'][$group] = array_values( $old_list );
				
				if( 0 == $index || !empty( $index ) ) {
					update_post_meta( $id, '_pwc_inspiration', $old_val );
					echo '<div id="ins-data" data-error="false">';
						echo '<span class="success">'. esc_html__( 'Overwrite Successfully', 'configurator' ) .'</span>';
					echo '</div>';
				}
				else {
					echo '<div id="ins-data" data-error="true">';
						echo '<span class="error">'. esc_html__( 'Please choose the item to replace', 'configurator' ) .'</span>';
					echo '</div>';
				}
			}
			else if( 'update' == $type ) {
				$old_list = isset( $old_val['list'][$group] ) && ! empty( $old_val['list'][$group] ) ? array_filter( $old_val['list'][$group] ) : array();

				$old_list[$index]['name']  = $name;
				$old_list[$index]['desc']  = $desc;
				$old_list[$index]['image'] = $image;

				$old_val['list'][$group] = array_values( $old_list );
				
				if( 0 == $index || !empty( $index ) ) {
					update_post_meta( $id, '_pwc_inspiration', $old_val );
					echo '<div id="ins-data" data-error="false">';
						echo '<span class="success">'. esc_html__( 'Update Successfully', 'configurator' ) .'</span>';
					echo '</div>';
				}
				else {
					echo '<div id="ins-data" data-error="true">';
						echo '<span class="error">'. esc_html__( 'Please choose the item to replace', 'configurator' ) .'</span>';
					echo '</div>';
				}
			}
			else if( 'delete' == $type ) {
				
				$old_list = isset( $old_val['list'][$group] ) && ! empty( $old_val['list'][$group] ) ? array_filter( $old_val['list'][$group] ) : array();

				unset( $old_list[$index]);

				$old_val['list'][$group] = array_values( $old_list );

				if( 0 == $index || !empty( $index ) ) {
					update_post_meta( $id, '_pwc_inspiration', $old_val );
					echo '<div id="ins-data" data-error="false">';
						echo '<span class="success">'. esc_html__( 'Deleted Successfully', 'configurator' ) .'</span>';
					echo '</div>';
				}
				else {
					echo '<div id="ins-data" data-error="true">';
						echo '<span class="error">'. esc_html__( 'Please choose the item to delete', 'configurator' ) .'</span>';
					echo '</div>';
				}
			}
			else if( 'delete-group' == $type ) {

				unset( $old_val['group'][$group_index]);
				$old_val['group'] = array_values( $old_val['group'] );
				
				$old_group = isset( $old_val['list'] ) && ! empty( $old_val['list'] ) ? $old_val['list'] : array();
				unset( $old_group[$group]);
				$old_val['list'] = array_values( $old_group );

				if( 0 == $group_index || !empty( $group_index ) ) {
					update_post_meta( $id, '_pwc_inspiration', $old_val );
					echo '<div id="ins-data" data-error="false">';
						echo '<span class="success">'. esc_html__( 'Group Deleted Successfully', 'configurator' ) .'</span>';
					echo '</div>';
				}
				else {
					echo '<div id="ins-data" data-error="true">';
						echo '<span class="error">'. esc_html__( 'Please choose the group to delete', 'configurator' ) .'</span>';
					echo '</div>';
				}
			}
			

			// Get inspiration list
			$inspiration = pwc_get_meta_value( $id, '_pwc_inspiration', array() );

			// Inspiration Tab
			echo '<div class="tab-wrapper lists-scroll">';

				if( !empty( $inspiration ) && isset( $inspiration['group'] ) ) {
					echo '<ul class="tab-menu">';
						$i=0;
						foreach( $inspiration['group'] as $key => $group_name ) {
							$active = ( 0 == $i ) ? ' class="active"' : '';
							echo '<li'. $active .' data-anchor="'. esc_attr( strtolower( str_replace(' ', '-', $group_name ) ) ) .'">'. esc_html( $group_name ) .'<span class="delete-inspiration-group delete-btn" data-type="delete-group" data-group="'. esc_attr( $group_name ) .'" data-group-index="'. esc_attr( $key ) .'"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-cancel' ) .'"></i></span></li>';
						$i++;}
					echo '</ul>';
				}

				echo '<div class="tab-content">';

					if( !empty( $inspiration ) && isset( $inspiration['list'] ) ) {
						$j=0;
						foreach( $inspiration['list'] as $group => $value ){

							$current = ( 0 == $j ) ? 'current' : '';

							echo '<div class="tab '. esc_attr( strtolower( str_replace(' ', '-', $group ) ) .' '.$current ) .'">';

								echo '<div class="owl-carousel-slider owl-carousel" data-items="3" data-loop="false" data-auto-height="false">';

									foreach( $value as $index => $list ) {

										$name  = isset( $list['name'] ) ? $list['name'] : '';
										$desc  = isset( $list['desc'] ) ? $list['desc'] : '';
										$image = isset( $list['image'] ) ? $list['image'] : '';
										$key   = isset( $list['key'] ) ? $list['key'] : '';

										$val          = array();
										$val['index'] = $index;
										$val['name']  = $name;
										$val['desc']  = $desc;
										$val['group'] = $group;
										$val['image'] = $image;
										$val['key']   = $key;

										if( !empty( $name ) ) {
											echo "<div class='ins-list' data-value='". json_encode( $val ) ."'>";

												if( ! empty( $image ) ) {
													echo '<img src="'. esc_url( $image ) .'" alt="">';
												}

												echo '<p class="title"><span>'. esc_html( $name ) .'</span></p>';
												if( !empty( $desc ) ) {
													echo '<p class="desc"><span>'. esc_html( $desc ) .'</span></p>';
												}

												echo '<a href="#" class="reset-components btn btn-md btn-solid btn-oval btn-gradient btn-uppercase">'. esc_html__( 'Select', 'product-woo-configurator' ) .'</a>';

												if( 'administrator' == $role ) {
													echo '<div class="ins-icons">';
														echo '<span class="update-form"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-pencil' ) .'"></i></span>';
														echo '<span data-type="reset" class="reset-inspiration"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-refresh' ) .'"></i></span>';
														echo '<span data-type="delete" class="delete-inspiration"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-trash' ) .'"></i></span>';
													echo '</div>';
												}
											echo '</div>';
										}
									}

								echo '</div>'; // .owl-carousel-slider

							echo '</div>'; // .tab-content
						$j++;}
					}

				echo '</div>'; // .tab-content-wrap

			echo '</div>'; // .lists-scroll

		echo '</div>'; // #ajax-inspiration-data

		die();
	}

	/**
	 * Delete View Meta box values via ajax
	 */
	public function delete_view() {

		// Get current user role
		$role = pwc_user_role();

		$index       = isset( $_POST['index'] ) ? $_POST['index'] : '';
		$configurator_id = isset( $_POST['configurator_id'] ) ? $_POST['configurator_id'] : '';

		$views = pwc_get_meta_value( $configurator_id, '_pwc_views' );

		$components = pwc_get_meta_value( $configurator_id, 'components' );

		if( array_key_exists( $index, $views ) ) {
			unset( $views[$index] );
			update_post_meta( $configurator_id, '_pwc_views', $views );

			$values = $this->delete_components( $components, $index );

			update_post_meta( $configurator_id, 'components', $values );
		}

		$components = pwc_get_meta_value( $configurator_id, 'components' );

		die();
	}

	/**
	 * Delete View Meta box values via ajax
	 */
	public function delete_components( $values, $index ) {

		$new_array = array();
		foreach( $values as $key => $value ) {

			unset($value[$index]);

			$new_array[$key] = $value;

			if( isset( $value['values'] ) ) {
				$new_array[$key]['values'] = $this->delete_sub_components( $value['values'], $index );
			}
		}

		return $new_array;
	}

	public function delete_sub_components( $values, $index ) {

		$sub_array = array();
		foreach( $values as $key => $value ) {

			unset($value[$index]);

			$sub_array[$key] = $value;

			if( isset( $value['values'] ) ) {
				$sub_array[$key]['values'] = $this->delete_sub_components( $value['values'], $index );
			}
		}

		return $sub_array;
	}
}

return new PWC_Meta_boxes();




	