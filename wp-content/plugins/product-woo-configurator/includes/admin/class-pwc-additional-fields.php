<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class PWC_Additional_Fields {

	public function __construct( $options ) {	

		$this->options = $options;

		$this->add_additional_field( $this->options );

		// set field in repeater for already created layes
		add_action( 'pwc_add_meta_option', array( $this, 'add_meta_option_field' ) );

		// set Group field in repeater-hidden for js
		add_action( 'pwc_add_group_view_fields_js', array( $this, 'pwc_add_group_view_fields_js' ) );
		add_action( 'pwc_add_group_fields_js', array( $this, 'pwc_add_group_fields_js' ) );

		// set field in repeater-hidden for js (Parent and child)
		add_action( 'pwc_add_sub_view_fields_js', array( $this, 'pwc_add_sub_view_fields_js' ) );
		add_action( 'pwc_add_sub_fields_js', array( $this, 'pwc_add_sub_fields_js' ) );

		// Group layer fields (non js version, render on page loads)
		add_action( 'pwc_add_group_view_fields', array( $this, 'pwc_add_group_view_fields' ) );
		add_action( 'pwc_add_group_fields', array( $this, 'pwc_add_group_fields' ) );

		// Parent Layer
		add_action( 'pwc_add_parent_view_fields', array( $this, 'pwc_add_parent_view_fields' ) );
		add_action( 'pwc_add_parent_fields', array( $this, 'pwc_add_parent_fields' ) );

		// child Layer
		add_action( 'pwc_add_child_view_fields', array( $this, 'pwc_add_child_view_fields' ) );
		add_action( 'pwc_add_child_fields', array( $this, 'pwc_add_child_fields' ) );

	}

	public function add_meta_option_field() {
		echo $this->field_html;
	}

	private function replace_string_by_val( $search, $string, $id, $value ) {

		if( isset( $this->options[$id]['type'] ) && $this->options[$id]['type'] == 'image' ) {

			$src = ! empty( $value ) ? pwc_get_image_by_id( 'full', 'full', $value, 1, 0 ) : '';
			$string = str_replace( $search, $src, $string );

		}

		return $string;

	}

	// This is input field for view group control
	public function pwc_add_group_view_fields( $args ) {

		$extra_input = '';

		foreach ( $this->group_view_field_html as $id => $field ) {

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array('$view', '$key', '$value'), array( $args['view'], $args['key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

		}
		
		echo $extra_input;

	}

	// This is input field for view group control
	public function pwc_add_group_fields( $args ) {

		$extra_input = '';

		foreach ( $this->group_field_html as $id => $field ) {			

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array('$key', '$value'), array( $args['key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

			// if( isset( $this->options[$id]['type'] ) && $this->options[$id]['type'] == 'image' ) {

			// 	$src = ! empty( $value ) ? pwc_get_image_by_id( 'full', 'full', $value, 1, 0 ) : '';

			// 	$extra_input = str_replace( '$src', $src, $extra_input );

			// }

		}
		
		echo $extra_input;

	}

	// This is input field for view parent control
	public function pwc_add_parent_view_fields( $args ) {

		$extra_input = '';

		foreach ( $this->parent_view_field_html as $id => $field ) {

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array('$view', '$key', '$sub_key', '$value'), array( $args['view'], $args['key'], $args['sub_key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

		}
		
		echo $extra_input;

	}

	// This is input field for view parent control
	public function pwc_add_parent_fields( $args ) {

		$extra_input = '';

		foreach ( $this->parent_field_html as $id => $field ) {

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array('$key', '$sub_key', '$value'), array( $args['key'], $args['sub_key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

		}
		
		echo $extra_input;

	}

	// This is input field for view child control
	public function pwc_add_child_view_fields( $args ) {

		$extra_input = '';

		foreach ( $this->child_view_field_html as $id => $field ) {

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array( '$parent', '$view', '$key', '$value'), array( $args['parent'], $args['view'], $args['key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

		}
		
		echo $extra_input;

	}

	// This is input field for view child control
	public function pwc_add_child_fields( $args ) {

		$extra_input = '';

		foreach ( $this->child_field_html as $id => $field ) {

			$value = isset( $args['value'][$id] ) ? $args['value'][$id] : '';

			$extra_input .= str_replace( array('$parent', '$key', '$value'), array( $args['parent'], $args['key'], $value ), $field );

			// if image field add src
			$extra_input = $this->replace_string_by_val( '$src', $extra_input, $id, $value );

		}
		
		echo $extra_input;

	}

	// This is input field for view group control for js
	public function pwc_add_group_view_fields_js( $view_key ) {
		
		$extra_input = str_replace( '$view_key', $view_key, $this->group_view_field_html_js );
		echo $extra_input;
		
	}

	public function pwc_add_group_fields_js() {
		echo $this->group_non_view_field_html_js;
	}

	// This is input field for view sub control
	public function pwc_add_sub_view_fields_js( $view_key ) {
		
		$extra_input = str_replace( '$view_key', $view_key, $this->view_field_html_js );
		echo $extra_input;

	}

	public function pwc_add_sub_fields_js() {
		echo $this->non_view_field_html_js;
	}

	/*
	 * add addtional field in configurator options
	 * array( $type, $id, $title, $desc, $options = array(), $view_control = false )
	 */
	public function add_additional_field() {

		if( empty( $this->options ) ) {
			return;
		}

		$this->field_html = $this->view_field_html_js = $this->group_view_field_html_js = $this->non_view_field_html_js = $this->group_non_view_field_html_js = '';

		$this->group_view_field_html  = $this->group_field_html  = array();
		$this->parent_view_field_html = $this->parent_field_html = array();
		$this->child_view_field_html  = $this->child_field_html  = array();

		foreach ( $this->options as $key => $option ) {
			
			if( ! isset( $option['type'] ) ) {
				return '';
			}

			$option['id'] = $key;
			$id = $key;
			
			// get field html
			$this->field_html .= $this->pwc_build_field( $option );

			$view_control = isset( $option['view_control'] ) ? $option['view_control'] : false;

			if( $option['type'] == 'title' ) {
				continue;
			}

			if( $option['type'] == 'image' ) {
				$data = ' data-src="$src"';
			} else {
				$data = '';
			}

			if( $view_control ) {

				// for group fields
				$this->group_view_field_html[$key] = '<input type="hidden" data-index="components" data-key="[$view][' . esc_attr( $id ) . ']" data-viewfield="$view" name="components[$key][$view][' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value" ' . $data . '>';

				// for parent fields
				$this->parent_view_field_html[$key] = '<input type="hidden" data-index="[value]" data-key="[$view][' . esc_attr( $id ) . ']" data-viewfield="$view" name="components[$key][values][$sub_key][$view][' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value" ' . $data . '>';

				// for child fields
				$this->child_view_field_html[$key] = '<input data-index="[value]" data-key="[$view][' . esc_attr( $id ) . ']" data-viewfield="$view" name="$parent[$key][$view][' . esc_attr( $id ) . ']" type="hidden" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value" ' . $data . '>';

				/* For js */
				// build sub group view control
				$this->view_field_html_js .= '<input data-index="[value]" data-key="[$view_key][' . esc_attr( $id ) . ']" data-viewfield="$view" type="hidden" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="">';

				// build group view control
				$this->group_view_field_html_js .= '<input type="hidden" data-index="components" data-key="[$view_key][' . esc_attr( $id ) . ']" data-viewfield="$view" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="">';
						
			} else {

				// group field
				$this->group_field_html[$key] = '<div class="pwc-form-field ' . esc_attr( $id ) . '-con">
					<input type="hidden" data-index="components" data-key="[' . esc_attr( $id ) . ']" name="components[$key][' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value"  ' . $data . '>
				</div>';

				// parent field
				$this->parent_field_html[$key] = '<div class="pwc-form-field ' . esc_attr( $id ) . '-con">
					<input type="hidden" data-index="[value]" data-key="[' . esc_attr( $id ) . ']" name="components[$key][values][$sub_key][' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value" ' . $data . '>
				</div>';

				// child field
				$this->child_field_html[$key] = '<div class="pwc-form-field ' . esc_attr( $id ) . '-con">
					<input data-index="[value]" data-key="[' . esc_attr( $id ) . ']" name="$parent[$key][' . esc_attr( $id ) . ']" type="hidden" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="$value" ' . $data . '>
				</div>';

				// build sub group control for js
				$this->non_view_field_html_js .= '<div class="pwc-form-field ' . esc_attr( $id ) . '-con">
					<input type="hidden" data-index="[value]" data-key="[' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="">
				</div>';

				// build group control for js
				$this->group_non_view_field_html_js .= '<div class="pwc-form-field ' . esc_attr( $id ) . '-con">
					<input type="hidden" data-index="components" data-key="[' . esc_attr( $id ) . ']" data-id="' . esc_attr( $id ) . '" class="pwc-additional-fields field-' . esc_attr( $id ) . ' component-input" value="">
				</div>';
			}


		} // end of foreach

		
		
	}

	private function pwc_build_field( $option ) {
		
		$input = '';
		
		$id      = isset( $option['id'] ) 		? $option['id'] : '';
		$type    = isset( $option['type'] ) 	? $option['type'] : '';
		$title   = isset( $option['title'] ) 	? $option['title'] : '';
		$desc    = isset( $option['desc'] ) 	? $option['desc'] : '';

		switch ( $option['type'] ) {
			case 'title':
				$input = '<h2 class="table-title">' . esc_html( $title ) . '</h2>';
				break;

			case 'text':
			case 'text_small':
			case 'number':

				$class = ( $type == 'text_small' ) ? ' small-text-field' : '';

				$field_type = ( $type == 'text_small' ) ? 'text' : $type;

				$input = '<div class="group'. $class .'">
					<div class="title-set">
						<h2 class="title">'. esc_html( $title ) .'</h2>
					</div>
					<div class="text">
						<input data-con="'. esc_attr( $id ) .'" data-input="'. esc_attr( $id ) .'" type="' . esc_attr( $field_type ) . '" value="" name="" class="show-'. esc_attr( $id ) .' field-'. esc_attr( $id ) .'">
						<p class="sub-title">'. esc_html( $desc ) .'</p>
					</div>
				</div>';

				break;

			case 'textarea':

				$input = '<div class="description">
					<div class="title-set">
						<h2 class="title">'. esc_html( $title ) .'</h2>
					</div>

					<textarea data-con="'. esc_attr( $id ) .'" data-input="'. esc_attr( $id ) .'" rows="6" class="show-'. esc_attr( $id ) .' field-'. esc_attr( $id ) .'"></textarea>
					<p class="sub-title">'. esc_html( $desc ) .'</p>
				</div>';

				break;

			case 'checkbox':

				$input = '<div class="group">
					<div class="title-set">
						<h2 class="title">'. esc_html( $title ) .'</h2>
					</div>

					<div class="checkbox">
						<label>
						<input data-con="'. esc_attr( $id ) .'" data-input="'. esc_attr( $id ) .'" class="show-'. esc_attr( $id ) .' field-'. esc_attr( $id ) .'" type="checkbox">
							'. esc_html( $desc ) .'
						</label>
					</div>
				</div>';

				break;

			case 'select':
				
				$select = isset( $option['options'] ) ? $option['options'] : array();

				$input = '<div class="group">
					<div class="title-set">
						<h2 class="title">'. esc_html( $title ) .'</h2>
					</div>
					<div class="checkbox">';
						$input .= '<select  data-con="'. esc_attr( $id ) .'" data-input="'. esc_attr( $id ) .'" name="'. esc_attr( $id ) .'" class="show-'. esc_attr( $id ) .' field-'. esc_attr( $id ) .'">';

							foreach( $select as $key => $option ) {

								$input .= '<option value="'. esc_attr( $key ) .'">'. esc_html( $option ) .'</option>';

							}
						$input .= '</select>
						<p class="sub-title">'. esc_html( $desc ) .'</p>';

				$input .= '</div>
					</div>';
				
				break;
			
			case 'image'; 

				$input = '<div class="icon-set">
					<div class="title-set">
						<h2 class="title">'. esc_html( $title ) .'</h2>
						<span class="sub-title">'. esc_html( $desc ) .'</span>
					</div>

					<div class="choose-icon text-left">
						<div class="group-icon">
							<input type="hidden" data-con="'. esc_attr( $id ) .'" data-input="'. esc_attr( $id ) .'" class="pix-saved-val show-'. esc_attr( $id ) .' field-'. esc_attr( $id ) .'">
							<div class="selected-con"></div>
							<a href="#" class="select-image" data-show="'. esc_attr( $id ) .'" data-title="'. esc_attr__( 'Select Image', 'product-woo-configurator' ) .'" data-file-type="image"><img src="'. PWC_ASSETS_URL .'backend/img/select-img.png" alt=""><p class="text-bottom">Image</p></a>
						</div>	
						
					</div>
				</div>';

				break;

		} // end of wsitch

		return $input;
	}

}
