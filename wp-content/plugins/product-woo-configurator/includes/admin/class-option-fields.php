<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists( 'pwc_option_fields' ) ) {

	class pwc_option_fields {

		private $fields;
		private $fields_html = '';

		public function __construct( $fields ){

			$this->fields = $fields;

			$this->pwc_save_formfields( $fields );
			$this->pwc_formfields( $fields );

		}

		public function pwc_formfields( $fields ) {

			$this->fields_html .= '<form method="POST">';

				$this->fields_html .= '<div class="amz-options-wrap">';

					foreach( $fields as $key => $field ) {

						// Default
						$field_type   = isset( $field['type'] ) ? $field['type'] : '';
						$field_id     = isset( $field['id'] ) ? $field['id'] : '';
						$title        = isset( $field['title'] ) ? $field['title'] : '';
						$desc         = isset( $field['desc'] ) ? $field['desc'] : '';
						$placeholder  = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
						$std          = isset( $field['std'] ) ? $field['std'] : '';
						$mode         = isset( $field['mode'] ) ? $field['mode'] : '';
						$options      = isset( $field['options'] ) ? $field['options'] : '';
						$multi_select = isset( $field['multi_select'] ) ? $field['multi_select'] : 'false';

						if( isset( $field['id'] ) ) {

							$value = get_option( $field_id, $std );

							$this->fields_html .= '<div class="amz-options">';

								// Left Side Content
								$this->fields_html .= '<div class="amz-pull-left">';

									$this->fields_html .= '<label for="' . esc_attr( $field_id ) . '" class="amz-sub-title">' . ucwords( esc_html( $title ) ) . '</label>';

									if( isset( $desc ) && ! empty( $desc ) ) {
										$this->fields_html .= '<p class="description">'. $desc .'</p>';
									}

								$this->fields_html .= '</div>'; // .amz-pull-left

								// Right Side Content
								$this->fields_html .= '<div class="amz-pull-right">';

									switch( $field_type ) {
										case 'text':
										case 'number':
										case 'email':
										case 'tel':
										case 'url':
											$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'" type="'. esc_attr( $field_type ) .'" placeholder="'. esc_attr( $placeholder ) .'" value="'. esc_attr( $value ) .'" class="textfield '. esc_attr( $mode ) .'">';
										break;

										case 'textarea':
											$this->fields_html .= '<textarea name="'. esc_attr( $field_id ) .'" placeholder="'. esc_attr( $placeholder ) .'" class="textarea '. esc_attr( $mode ) .'">'. esc_html( $value ) .'</textarea>';
										break;

										case 'select':

											if( ! empty( $options ) ) {
												$this->fields_html .= '<select name="'. esc_attr( $field_id ) .'">';
													foreach( $options as $key => $opt ) {
														$this->fields_html .= '<option value="'. esc_attr( $key ) .'" id="'. esc_attr( $key ) .'" '. ( ( $value == $key ) ? ' selected="selected"' : '' ) .'>'. esc_html( $opt ) .'</option>';
													}
												$this->fields_html .= '</select>';
											}
											
										break;

										case 'checkbox':

											if( ! empty( $options ) ) {
												$this->fields_html .= '<div class="amz-options-wrap">';
													foreach( $options as $key => $opt ) {
														$this->fields_html .= '<div class="amz-options-inner">';
															$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'[]" type="checkbox" value="'. esc_attr( $key ) .'" id="'. esc_attr( $key ) .'" '. ( in_array( $key, $value ) ? 'checked' : '' ) .'>';
															$this->fields_html .= '<label for="'. esc_attr( $key ) .'">'. esc_html( $opt ) .'</label>';
														$this->fields_html .= '</div>'; // .amz-options-inner
													}
												$this->fields_html .= '</div>'; // amz-options-wrap
											}								
											
										break;

										case 'radio':

											if( ! empty( $options ) ) {
												$this->fields_html .= '<div class="amz-options-wrap">';
													foreach( $options as $key => $opt ) {
														$this->fields_html .= '<div class="amz-options-inner">';
															$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'" type="radio" value="'. esc_attr( $key ) .'" id="'. esc_attr( $key ) .'" '. checked( $value, $key, false ) .'>';
															$this->fields_html .= '<label for="'. esc_attr( $key ) .'">'. esc_html( $opt ) .'</label>';
														$this->fields_html .= '</div>'; // .amz-options-inner
													}
												$this->fields_html .= '</div>'; // amz-options-wrap
											}								
											
										break;

										case 'switch':

											if( ! empty( $options ) ) {
												$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'" type="hidden" value="'. esc_attr( $value ) .'" id="'. esc_attr( $field_id ) .'">';
												$this->fields_html .= '<div class="amz-options-wrap">';
													foreach( $options as $key => $opt ) {

														$active = ( $key == $value ) ? 'active' : '';

														$this->fields_html .= '<span data-value="'. esc_attr( $key ) .'" class="amz-switch '. esc_attr( $active ) .'">'. esc_html( $opt ) .'</span>';
													}
												$this->fields_html .= '</div>'; // amz-options-wrap
											}								
											
										break;

										case 'colorpicker':
											$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'" type="text" placeholder="'. esc_attr( $placeholder ) .'" value="'. esc_attr( $value ) .'" class="amz-colorpicker">';
										break;

										case 'media_manager':

											if( ! empty( $options ) ) {

												$value = stripslashes( $value );

												$this->fields_html .= '<div class="pix_image_select pix-container ">';
													$this->fields_html .= '<input type="hidden" class="pix-saved-val" name="' . esc_attr( $field_id ) . '" value="' . esc_attr( $value ) . '">';
													$this->fields_html .= '<a href="#" class="select-files" data-title="'. esc_attr__( 'Select File', 'panorama' ) .'"  data-file-type="' . esc_attr( $options ) . '" data-multi-select="'. esc_attr( $multi_select ) .'" data-insert="true">'. esc_html( $title ) .'</a>';
												$this->fields_html .= '</div>';

											}								
											
										break;

										case 'edd_license':
											$license = get_option( 'pwc_license_key' );
											$status  = get_option( 'pwc_license_status' );

											$this->fields_html .= '<input name="'. esc_attr( $field_id ) .'" type="text" placeholder="'. esc_attr( $placeholder ) .'" value="'. esc_attr( $value ) .'" class="textfield large">';

											if( false !== $license ) {
												// _e('Activate License', 'product-woo-configurator');

												if( $status !== false && $status == 'valid' ) { 

													$this->fields_html .= '<span style="color:green;">' . __('active', 'product-woo-configurator') .'</span>';

													wp_nonce_field( 'pwc_license_nonce', 'pwc_license_nonce' );

													$this->fields_html .= '<input type="submit" class="button-secondary" name="edd_license_deactivate" value="'. __('Deactivate License', 'product-woo-configurator') .'"/>';
												 } else {
													wp_nonce_field( 'pwc_license_nonce', 'pwc_license_nonce' );

													$this->fields_html .= '<input type="submit" class="button-secondary" name="edd_license_activate" value="'. __('Activate License', 'product-woo-configurator') .'"/>';

												}

											}
											
										break;
										
										default:
										break;
									}

								$this->fields_html .= '</div>'; // .amz-pull-right

							$this->fields_html .= '</div>'; // .amz-options
						}

						elseif( 'title' == $field_type && ! empty( $title ) ) {
							$this->fields_html .= '<h3 class="title">'. esc_html( $title ) .'</h3>';
						}				
					}

				$this->fields_html .= '</div>'; // .amz-options-wrap

				$this->fields_html .= '<input name="amz_update_settings" type="submit" value="'. esc_attr__( 'Save settings', 'product-woo-configurator' ).'" class="button-primary submit-btn"/>';

			$this->fields_html .= '</form>';

			echo $this->fields_html;
		}

		public function pwc_save_formfields( $fields ) {

			if( isset( $_POST['amz_update_settings'] ) ) {
			    foreach( $fields as $key => $field ) {

					if( isset( $field['id'] ) ) {
						// Default
						$field_id = $field['id'];
						$std = $field['std'];

						$value = isset( $_POST[$field_id] ) ? $_POST[$field_id] : $std;

						update_option( $field_id, $value, true );
					}				
				}
			}
		}

	}
}