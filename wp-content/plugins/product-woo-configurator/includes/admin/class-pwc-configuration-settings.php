<?php
/**
 * Metabox configuration
 *
 * Configuration
 *
 * @class     PWC_Metabox_configuration
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Metabox_configuration Class.
 */
class PWC_Metabox_configuration {

	public $views;
	public $post_obj = '';
	public $post_id = 0;

	/**
	 * Hook in methods.
	 */
	public function __construct( $post ) {
		$this->post_obj = $post;
		$this->metabox_content( $post );
	}

	/**
	 * Configuration settings metabox
	 */
	public function metabox_content( $post ){

		wp_nonce_field( 'pwc_configuration', 'pwc_configuration_save' );

		$this->views = pwc_get_meta_value( $post->ID, '_pwc_views', array( 'front'=> esc_html__( 'Front', 'product-woo-configurator' ) ) );
		
		$first_view = key( $this->views );

		echo '<div id="pwc-settings-panel" data-views="'. esc_attr( json_encode( $this->views ) ) .'">';

			echo '<div id="pwc-image-options">';					

				echo '<div class="choose-image">';

					echo $this->print_base_fields( $this->get_base_fields() );

				echo '</div>';

				echo '<div id="preview-settings" class="layers-container">';

					echo '<div class="layers-con">';

						echo '<h2 class="title">'. esc_html__( 'Selected Layers', 'product-woo-configurator' ) .'</h2>';

						echo '<div class="selected-layers">	
							<div class="icon-bg"><i class="'. apply_filters( 'pwc_icons', 'pwc-sort' ) .'"></i></div>
							<div class="icon-bg layer-text" id="active-layer-name">'. esc_html__( 'No Layer Selected', 'product-woo-configurator' ) .'</div>
							<div class="icon-bg add-edit-tooltip" id="pwc-preview-add-edit-image"><i class="'. apply_filters( 'pwc_icons', 'pwc-pencil' ) .'"></i><span class="tool-tips">'. esc_html__( 'Add / Edit' , 'product-woo-configurator' ).'</span></div>
							<div id="pwc-preview-remove-image" class="icon-bg delete-tooltip not-active pwc-check-active"><i class="'. apply_filters( 'pwc_icons', 'pwc-trashcan' ) .'"></i><span class="tool-tips">'. esc_html__( 'Remove Image' , 'product-woo-configurator' ).'</span></div>
							<div id="pwc-preview-lock-unlock" class="icon-bg not-active pwc-check-active"><i class="'. apply_filters( 'pwc_icons', 'pwc-locked-simple' ) .'"></i><span class="tool-tips">'. esc_html__( 'Lock / Unlock' , 'product-woo-configurator' ).'</span></div>
						</div>'; // .selected-layers

						echo '<div class="choose-img">';

							if( isset( $this->views ) && !empty( $this->views ) ) {
								echo '<select id="select-image-view">';

									foreach( $this->views as $view_key => $view ) {
										echo '<option value="'. esc_attr( $view_key ) .'">'. esc_html( $view ) .'</option>';
									}

								echo '</select>';
							}

							echo '<div id="add-remove-hotspot" class="add-hotspot pwc-check-hotspot-active" data-hotspot="add">
								<div class="icon-bg"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i><span class="tool-tips">'. esc_html__( 'Add / Remove Hotspot', 'product-woo-configurator' ) .'</span></div>
							</div>';
						echo '</div>'; // .choose-img

						echo '<div id="transform-groups" class="not-active pwc-check-active">
	
							<div id="size-groups">
								<div class="position-size">
									<label> x
										<input autocomplete="false" class="show-pos-x transform-input" id="transform-x" data-con="'. esc_attr( $first_view ) .'" data-input="pos-x" type="text">
									</label>

									<label> Y
										<input autocomplete="false" class="show-pos-y transform-input" id="transform-y" data-con="'. esc_attr( $first_view ) .'" data-input="pos-y" type="text">
									</label>

									<label class="z-index-field-con"> Z
										<input autocomplete="false" class="show-z-index transform-input" id="z-index" data-con="'. esc_attr( $first_view ) .'" data-input="z-index" type="text">
									</label>
								</div>

								<div class="size-position">
									<label> W
										<input autocomplete="false" class="show-width transform-input" id="transform-w" data-con="'. esc_attr( $first_view ) .'" data-input="width" type="text">
									</label>

									<label> H
										<input autocomplete="false" class="show-height transform-input" id="transform-h" data-con="'. esc_attr( $first_view ) .'" data-input="height" type="text">
									</label>
								</div>
							</div>

							<div id="align-groups">
								<div class="align-left-right">
									<input class="show-align-h transform-input" data-con="view-1" data-input="align-h" type="hidden">
									<div data-value="left" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-left' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Left' , 'product-woo-configurator' ).'</span></div>
									<div data-value="center" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-center' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Center' , 'product-woo-configurator' ).'</span></div>
									<div data-value="right" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-right' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Right' , 'product-woo-configurator' ).'</span></div> 
								</div>

								<div class="align-top-bottom">
									<input class="show-align-v transform-input" data-con="view-1" data-input="align-v" type="hidden">
									<div data-value="top" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-top' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Top' , 'product-woo-configurator' ).'</span></div>
									<div data-value="middle" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-middle' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Middle' , 'product-woo-configurator' ).'</span></div>
									<div data-value="bottom" class="icon-bg"><span class="'. apply_filters( 'pwc_icons', 'pwc-align-bottom' ) .'"></span><span class="tool-tips">'. esc_html__( 'Align Bottom' , 'product-woo-configurator' ).'</span></div> 
								</div>
							</div>

						</div>'; // #transform-groups

					echo '</div>'; // .layers-con

				echo '</div>'; // #preview-settings

			echo '</div>'; // #pwc-image-options

			$configuration_settings = pwc_get_meta_value( $post->ID, 'components', array() );

			echo '<div id="pwc-preview">';

				if( isset( $this->views ) && !empty( $this->views ) ) {

					$i = 0;
					foreach ( $this->views as $view_key => $view ) {

						$active = ( 0 == $i ) ? 'active' : '';

						/* Preview images */
						echo '<div id="pwc-'. esc_attr( $view_key ) .'" class="pwc-preview-inner loading '. esc_attr( $active ) .'">';

							foreach ( $configuration_settings as $key => $value ) {

								if( isset( $value[$view_key]['image'] ) && !empty( $value[$view_key]['image'] ) ) {

									$src = pwc_get_image_by_id( 'full', 'full', $value[$view_key]['image'], 1, 0 );
									$width   = $value[$view_key]['width'];
									$height  = $value[$view_key]['height'];
									$pos_x   = $value[$view_key]['pos_x'];
									$pos_y   = $value[$view_key]['pos_y'];
									$align_h = $value[$view_key]['align_h'];
									$align_v = $value[$view_key]['align_v'];

									$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '" data-width="' . esc_attr( $width ) . '" data-height="' . esc_attr( $height ) . '"';

									echo '<div data-preview-uid="'. esc_attr( $value['uid'] ) .'" id="pwc-'. esc_attr( $view_key ) .'-'. esc_attr( $value['uid'] ) .'"  class="pwc-preview-imgcon"'. $data .'>
											<img src="'. esc_url( $src ).'" alt="" width="'. esc_attr( $width ) .'" height="'. esc_attr( $height ) .'">
										</div>';

								}

								// hotspot
								if( isset( $value[$view_key]['hs_enable'] ) && $value[$view_key]['hs_enable'] == 'true' ) {

									$pos_x   = $value[$view_key]['hs_pos_x'];
									$pos_y   = $value[$view_key]['hs_pos_y'];
									$align_h = $value[$view_key]['hs_align_h'];
									$align_v = $value[$view_key]['hs_align_v'];

									$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '"';

									echo '<div class="pwc-hotspot" id="hotspot-'. esc_attr( $view_key ) .'-'. esc_attr( $value['uid'] ) .'" data-hotspot-uid="'. esc_attr( $value['uid'] ) .'"'. $data .'><span></span></div>';
								}

								if( isset( $value['values'] ) && !empty( $value['values'] ) ) {
									foreach ( $value['values'] as $sub_key => $val ) {

										if( isset( $val[$view_key]['image'] ) && !empty( $val[$view_key]['image'] ) ) {

											$sub_src = pwc_get_image_by_id( 'full', 'full', $val[$view_key]['image'], 1, 0 );
											$sub_width  = $val[$view_key]['width'];
											$sub_height = $val[$view_key]['height'];
											$pos_x      = $val[$view_key]['pos_x'];
											$pos_y      = $val[$view_key]['pos_y'];
											$align_h    = $val[$view_key]['align_h'];
											$align_v    = $val[$view_key]['align_v'];

											$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '" data-width="' . esc_attr( $sub_width ) . '" data-height="' . esc_attr( $sub_height ) . '"';

											echo '<div data-preview-uid="'. esc_attr( $val['uid'] ) .'" id="pwc-'. esc_attr( $view_key ) .'-'. esc_attr( $val['uid'] ) .'"  class="pwc-preview-imgcon"'. $data .'>
													<img src="'. esc_url( $sub_src ).'" alt="" width="'. esc_attr( $sub_width ) .'" height="'. esc_attr( $sub_height ) .'">
												</div>';
											
										}

										if( isset( $val['values'] ) && !empty( $val['values'] ) ) {
											echo $this->get_sub_layer_image( $view_key, $val['values'] );
										}

										// hotspot
										if( isset( $val[$view_key]['hs_enable'] ) && $val[$view_key]['hs_enable'] == 'true' ) {

											$pos_x   = $val[$view_key]['hs_pos_x'];
											$pos_y   = $val[$view_key]['hs_pos_y'];
											$align_h = $val[$view_key]['hs_align_h'];
											$align_v = $val[$view_key]['hs_align_v'];

											$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '"';

											echo '<div class="pwc-hotspot" id="hotspot-'. esc_attr( $val['uid'] ) .'" data-hotspot-uid="'. esc_attr( $val['uid'] ) .'"'. $data .'><span></span></div>';
										}
									}
								}

							}

						echo '</div>'; // .pwc-preview-inner
					$i++; }
				}		

			echo '</div>'; // #pwc-preview

			echo '<div id="pwc-settings-wrap" class="clearfix">';		

				echo '<div id="pwc-settings-inner">';
					echo '<h2 class="table-title">'. esc_html__( 'Controls', 'product-woo-configurator' ) .'</h2>';

					echo '<div class="pwc-layer-control-top">';				

						echo '<div class="pwc-layer-controls">';

							echo '<span class="pwc-control-inner layer-on-off">';
								echo '<input type="checkbox">';
							echo '</span>'; // .layer-on-off

							echo '<span class="pwc-control-inner layer-show-hide">';
								echo '<span class="'. apply_filters( 'pwc_icons', 'pwc-eye-disabled' ) .'"></span>';
							echo '</span>'; // .layer-show-hide

							echo '<span class="pwc-control-inner layer-lock-unlock">';
								echo '<span class="'. apply_filters( 'pwc_icons', 'pwc-lock' ) .'"></span>';
							echo '</span>'; // .layer-lock-unlock

						echo '</div>'; // .pwc-layer-controls

						echo '<div class="pwc-layer-name">';

							echo '<span class="pwc-control-layer-name">';
								echo '<span>'. esc_html__( 'Layers', 'product-woo-configurator' ) .'</span>';
							echo '</span>'; // .pwc-control-layer-name

						echo '</div>'; // .pwc-layer-name

						echo '<div class="pwc-layer-add-remove">';

							echo '<span class="pwc-layer-add">';
								echo '<span class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></span>';
							echo '</span>'; // .pwc-layer-add

							echo '<span class="pwc-layer-remove">';
								echo '<span class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></span>';
							echo '</span>'; // .pwc-layer-remove

						echo '</div>'; // .pwc-layer-add-remove

					echo '</div>';

					// Layers
					echo '<div id="pwc-settings">';
						if( !empty( $configuration_settings ) ) {

							foreach ( $configuration_settings as $key => $value ) {
								
								echo '<div class="pwc-settings-group">';

									// Print required hidden input fields
									echo $this->configurator_build_form_fields( $this->views, $key, $value );

									// Collapse value
									$collapse = isset( $value['collapse'] ) && ( 'true' == $value['collapse'] ) ? $value['collapse'] : 'false';

									if( 'true' == $collapse ) {
										$collapse_class = 'collapse';
										$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-desc' );
									}
									else {
										$collapse_class = '';
										$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-asc' );
									}

									echo '<div class="pwc-settings-sub-group-wrapper '. esc_attr( $collapse_class ) .'">';

										if( isset( $value['values'] ) && !empty( $value['values'] ) ) {
											foreach ( $value['values'] as $sub_key => $val ) {
												echo $this->configurator_build_sub_form_fields( $this->views, $key, $sub_key, $val );
											}
										}
									echo '</div>';

								echo '</div>'; // .pwc-settings-group

							}
						}
					echo '</div>'; // #pwc-settings

					echo '<a href="#" id="pwc-options" class="add-btn"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i>'. esc_html__( 'Add', 'product-woo-configurator' ) .'</a>';
					echo '<a href="#" id="pwc-delete-layers" class="delete-btn"><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i>'. esc_html__( 'Delete', 'product-woo-configurator' ) .'</a>';

					echo '<a href="#" id="pwc-save-btn" class="button button-primary button-large">'. esc_html__( 'Save', 'product-woo-configurator' ) .'</a>';

				echo '</div>'; // #pwc-settings-inner

				$this->print_layer_options();

				

			echo '</div>'; // #pwc-settings-wrap

			$this->print_repeatable_content();

		echo '</div>';	
	}

	public function get_sub_fields( $collapse, $parent, $value ){

		if( !empty( $value ) ) {

			if( 'true' == $collapse ) {
				$collapse_class = 'collapse';
				$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-desc' );
			} else {
				$collapse_class = '';
				$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-asc' );
			}
			echo '<div class="pwc-settings-sub-value-group-wrapper '. esc_attr( $collapse_class ) .'">';
				foreach ( $value as $key => $subfield ) {

					echo '<div class="pwc-settings-sub-value-group">';

						$subfield_uid = isset( $subfield['uid'] ) ? $subfield['uid'] : '';

						echo '<div class="pwc-sub-group-field" data-uid="'. esc_attr( $subfield_uid ) .'">';

							echo '<div class="pwc-layer-controls">';

								echo '<span class="pwc-control-inner layer-on-off">';
									echo '<input type="checkbox">';
								echo '</span>'; // .layer-checkbox

								echo '<span class="pwc-control-inner layer-show-hide">';
									echo '<span></span>';
								echo '</span>'; // .layer-zoom

								echo '<span class="pwc-control-inner layer-lock-unlock">';
									echo '<span></span>';
								echo '</span>'; // .layer-lock

							echo '</div>'; // .pwc-layer-controls

							echo '<div class="pwc-form-field pwc-form-name">';

							echo '<input data-index="[value]" data-key="[uid]" name="'.$parent.'['.$key.'][uid]" type="hidden" class="component-input textfield" value="'. esc_attr( $subfield_uid ) .'">';

							$subfield_name = isset( $subfield['name'] ) ? $subfield['name'] : '';
							echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-square-path' ) .'"></span><input data-index="[value]" data-key="[name]" name="'.$parent.'['.$key.'][name]" type="text" class="component-input textfield" value="'. esc_attr( $subfield_name ) .'"></div>';

							$subfield_icon = isset( $subfield['icon'] ) ? $subfield['icon'] : '';
							$subfield_icon_src = !empty( $subfield_icon ) ? pwc_get_image_by_id( 'full', 'full', $subfield_icon, 1, 0 ) : '';
							echo '<div class="pwc-form-field icon-con" data-src="'. esc_attr( $subfield_icon_src ) .'">
								<input data-index="[value]" data-key="[icon]" name="'.$parent.'['.$key.'][icon]" type="hidden" class="field-icon component-input" value="'. esc_attr( $subfield_icon ) .'">
							</div>';

							if( isset( $this->views ) && ! empty( $this->views ) ) {

								foreach ( $this->views as $view_key => $view ) {

									$subfield_pos_x = isset( $subfield[$view_key]['pos_x'] ) ? $subfield[$view_key]['pos_x'] : '';
									$subfield_pos_y = isset( $subfield[$view_key]['pos_y'] ) ? $subfield[$view_key]['pos_y'] : '';
									$subfield_z_index = isset( $subfield[$view_key]['z_index'] ) ? $subfield[$view_key]['z_index'] : '';
									$subfield_align_h = isset( $subfield[$view_key]['align_h'] ) ? $subfield[$view_key]['align_h'] : '';
									$subfield_align_v = isset( $subfield[$view_key]['align_v'] ) ? $subfield[$view_key]['align_v'] : '';
									$subfield_hs_pos_x = isset( $subfield[$view_key]['hs_pos_x'] ) ? $subfield[$view_key]['hs_pos_x'] : '';
									$subfield_hs_pos_y = isset( $subfield[$view_key]['hs_pos_y'] ) ? $subfield[$view_key]['hs_pos_y'] : '';
									$subfield_hs_enable = isset( $subfield[$view_key]['hs_enable'] ) ? $subfield[$view_key]['hs_enable'] : '';
									$subfield_hs_align_h = isset( $subfield[$view_key]['hs_align_h'] ) ? $subfield[$view_key]['hs_align_h'] : '';
									$subfield_hs_align_v = isset( $subfield[$view_key]['hs_align_v'] ) ? $subfield[$view_key]['hs_align_v'] : '';
									$subfield_width = isset( $subfield[$view_key]['width'] ) ? $subfield[$view_key]['width'] : '';
									$subfield_height = isset( $subfield[$view_key]['height'] ) ? $subfield[$view_key]['height'] : '';
									$subfield_image = isset( $subfield[$view_key]['image'] ) ? $subfield[$view_key]['image'] : '';

									$subfield_image_src = !empty( $subfield_image ) ? pwc_get_image_by_id( 'full', 'full', $subfield_image, 1, 0 ) : '';

									echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con" data-src="'. esc_attr( $subfield_image_src ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][pos_x]" name="'.$parent.'['.$key.']['.$view_key.'][pos_x]" type="hidden" class="field-pos-x component-input" value="'. esc_attr( $subfield_pos_x ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][pos_y]" name="'.$parent.'['.$key.']['.$view_key.'][pos_y]" type="hidden" class="field-pos-y component-input" value="'. esc_attr( $subfield_pos_y ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][z_index]" name="'.$parent.'['.$key.']['.$view_key.'][z_index]" type="hidden" class="field-z-index component-input" value="'. esc_attr( $subfield_z_index ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][align_h]" name="'.$parent.'['.$key.']['.$view_key.'][align_h]" type="hidden" class="field-align-h component-input" value="'. esc_attr( $subfield_align_h ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][align_v]" name="'.$parent.'['.$key.']['.$view_key.'][align_v]" type="hidden" class="field-align-v component-input" value="'. esc_attr( $subfield_align_v ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][hs_pos_x]" name="'.$parent.'['.$key.']['.$view_key.'][hs_pos_x]" type="hidden" class="field-hs-pos-x component-input" value="'. esc_attr( $subfield_hs_pos_x ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][hs_pos_y]" name="'.$parent.'['.$key.']['.$view_key.'][hs_pos_y]" type="hidden" class="field-hs-pos-y component-input" value="'. esc_attr( $subfield_hs_pos_y ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][hs_enable]" name="'.$parent.'['.$key.']['.$view_key.'][hs_enable]" type="hidden" class="field-hs-enable component-input" value="'. esc_attr( $subfield_hs_enable ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][hs_align_h]" name="'.$parent.'['.$key.']['.$view_key.'][hs_align_h]" type="hidden" class="field-hs-align-h component-input" value="'. esc_attr( $subfield_hs_align_h ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][hs_align_v]" name="'.$parent.'['.$key.']['.$view_key.'][hs_align_v]" type="hidden" class="field-hs-align-v component-input" value="'. esc_attr( $subfield_hs_align_v ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][width]" name="'.$parent.'['.$key.']['.$view_key.'][width]" type="hidden" class="field-width component-input" value="'. esc_attr( $subfield_width ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][height]" name="'.$parent.'['.$key.']['.$view_key.'][height]" type="hidden" class="field-height component-input" value="'. esc_attr( $subfield_height ) .'">

										<input data-index="[value]" data-key="['.$view_key.'][image]" name="'.$parent.'['.$key.']['.$view_key.'][image]" type="hidden" class="field-image component-input" value="'. esc_attr( $subfield_image ) .'">';
										
										$args = array( 'view' => $view_key,  'parent' => $parent, 'key' => $key, 'value' => $subfield[$view_key] );
										do_action( 'pwc_add_child_view_fields', $args );

									echo '</div>';
								}

							}

							$args = array( 'parent' => $parent, 'key' => $key, 'value' => $subfield );
							do_action( 'pwc_add_child_fields', $args );

							$subfield_price = isset( $subfield['price'] ) ? $subfield['price'] : '';
							echo '<div class="pwc-form-field price-con">
								<input data-index="[value]" data-key="[price]" name="'.$parent.'['.$key.'][price]" type="hidden" class="field-price component-input" value="'. esc_attr( $subfield_price ) .'">
							</div>';

							$subfield_hide_control = isset( $subfield['hide_control'] ) ? $subfield['hide_control'] : '';
							echo '<div class="pwc-form-field hide_control-con">
								<input data-index="[value]" data-key="[hide_control]" name="'.$parent.'['.$key.'][hide_control]" type="hidden" class="field-hide_control component-input" value="'. esc_attr( $subfield_hide_control ) .'">
							</div>';

							$subfield_active = isset( $subfield['active'] ) ? $subfield['active'] : '';
							echo '<div class="pwc-form-field active-con">
								<input data-index="[value]" data-key="[active]" name="'.$parent.'['.$key.'][active]" type="hidden" class="field-active component-input" value="'. esc_attr( $subfield_active ) .'">
							</div>';

							$subfield_description = isset( $subfield['description'] ) ? $subfield['description'] : '';
							echo '<div class="pwc-form-field description-con">
								<input data-index="[value]" data-key="[description]" name="'.$parent.'['.$key.'][description]" type="hidden" class="field-description component-input" value="'. esc_attr( $subfield_description ) .'">
							</div>';

							$subfield_label = isset( $subfield['label'] ) ? $subfield['label'] : '';
							echo '<div class="pwc-form-field label-con">
								<input data-index="[value]" data-key="[label]" name="'.$parent.'['.$key.'][label]" type="hidden" class="field-label component-input" value="'. esc_attr( $subfield_label ) .'">
							</div>';

							$subfield_multiple = isset( $subfield['multiple'] ) ? $subfield['multiple'] : '';
							echo '<div class="pwc-form-field multiple-con">
								<input data-index="[value]" data-key="[multiple]" name="'.$parent.'['.$key.'][multiple]" type="hidden" class="field-multiple component-input" value="'. esc_attr( $subfield_multiple ) .'">
							</div>';

							$subfield_required = isset( $subfield['required'] ) ? $subfield['required'] : '';
							echo '<div class="pwc-form-field required-con">
								<input data-index="[value]" data-key="[required]" name="'.$parent.'['.$key.'][required]" type="hidden" class="field-required component-input" value="'. esc_attr( $subfield_required ) .'">
							</div>';

							echo '</div>';

							echo '<div class="pwc-form-field pwc-form-add-remove">';

								echo '<div class="pwc-form-field trash-icon">
									<span data-remove="'.$parent.'['.$key.']"><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
								</div>';

								// Collapse value
								$collapse = isset( $subfield['collapse'] ) && ( 'true' == $subfield['collapse'] ) ? $subfield['collapse'] : 'false';

								echo '<div class="pwc-form-field angle-down-icon">
									<input type="hidden" data-index="[value]" data-key="[collapse]" name="'.$parent.'['.$key.'][collapse]" class="component-input" value="'. esc_attr( $collapse ) .'">
									<span class="collapse-sub-values" data-collapse="'. esc_attr( $collapse ) .'"><i class="'. esc_attr( $collapse_icon_class ) .'"></i></span>
								</div>';

								echo '<div class="pwc-form-field add-icon"><a href="#" data-parent-index="'.$parent.'['.$key.'][values]" class="pwc-sub-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

							echo '</div>';

						echo '</div>';

						if( !empty( $subfield['values'] ) ) {
							echo $this->get_sub_fields( $collapse, $parent.'['.$key.'][values]', $subfield['values'] );
						}

					echo '</div>';
				}
			echo '</div>';
		}
	}

	public function get_sub_layer_image( $screen, $value ){
		if( !empty( $value ) ) {
			foreach ( $value as $key => $subfield ) {
				if( isset( $subfield[$screen]['image'] ) && !empty( $subfield[$screen]['image'] ) ) {

					$sub_src = pwc_get_image_by_id( 'full', 'full', $subfield[$screen]['image'], 1, 0 );
					$sub_width  = $subfield[$screen]['width'];
					$sub_height = $subfield[$screen]['height'];
					$pos_x      = $subfield[$screen]['pos_x'];
					$pos_y      = $subfield[$screen]['pos_y'];
					$align_h    = $subfield[$screen]['align_h'];
					$align_v    = $subfield[$screen]['align_v'];

					$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '" data-width="' . esc_attr( $sub_width ) . '" data-height="' . esc_attr( $sub_height ) . '"';

					echo '<div data-preview-uid="'. esc_attr( $subfield['uid'] ) .'" id="pwc-'. esc_attr( $screen ) .'-'. esc_attr( $subfield['uid'] ) .'" class="pwc-preview-imgcon"'. $data .'>
						<img src="'. esc_url( $sub_src ).'" alt="" width="'. esc_attr( $sub_width ) .'" height="'. esc_attr( $sub_height ) .'">
					</div>';					
					
				}

				if( isset( $subfield['values'] ) && !empty( $subfield['values'] ) ) {
					echo $this->get_sub_layer_image( $screen, $subfield['values'] );
				}

				// hotspot
				if( isset( $subfield[$screen]['hs_enable'] ) && $subfield[$screen]['hs_enable'] == 'true' ) {

					$pos_x   = $subfield[$screen]['hs_pos_x'];
					$pos_y   = $subfield[$screen]['hs_pos_y'];
					$align_h = $subfield[$screen]['hs_align_h'];
					$align_v = $subfield[$screen]['hs_align_v'];

					$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '"';

					echo '<div class="pwc-hotspot" id="hotspot-'. esc_attr( $subfield['uid'] ) .'" data-hotspot-uid="'. esc_attr( $subfield['uid'] ) .'"'. $data .'><span></span></div>';
				}
			}
		}
	}

	public function configurator_build_form_fields( $views, $key, $value ){

		$uid = isset( $value['uid'] ) ? $value['uid'] : '';

		echo '<div class="pwc-group-field" data-uid="'. esc_attr( $uid ) .'">';

			echo '<div class="pwc-layer-controls">';

				echo '<span class="pwc-control-inner layer-on-off">';
					echo '<input type="checkbox">';
				echo '</span>'; // .layer-checkbox

				echo '<span class="pwc-control-inner layer-show-hide">';
					echo '<span></span>';
				echo '</span>'; // .layer-zoom

				echo '<span class="pwc-control-inner layer-lock-unlock">';
					echo '<span></span>';
				echo '</span>'; // .layer-lock

			echo '</div>'; // .pwc-layer-controls

			echo '<div class="pwc-form-field pwc-form-name">';

				echo '<input type="hidden" data-index="components" data-key="[uid]" name="components['.$key.'][uid]" class="field-uid component-input" value="'. esc_attr( $uid ) .'">';

				$name = isset( $value['name'] ) ? $value['name'] : esc_html__( 'Group', 'product-woo-configurator' );
				echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-folder' ) .'"></span><input type="text" data-index="components" data-key="[name]" name="components['.$key.'][name]" class="component-input textfield" value="'. esc_attr( $name ) .'"></div>';

				$icon = isset( $value['icon'] ) ? $value['icon'] : '';
				$icon_src = !empty( $icon ) ? pwc_get_image_by_id( 'full', 'full', $icon, 1, 0 ) : '';

				echo '<div class="pwc-form-field icon-con" data-src="'. esc_attr( $icon_src ) .'">
					<input type="hidden" data-index="components" data-key="[icon]" name="components['.$key.'][icon]" class="field-icon component-input" value="'. esc_attr( $icon ) .'">
				</div>';

				if( isset( $this->views ) && !empty( $this->views ) ) {
					foreach( $this->views as $view_key => $view ) {

						$pos_x = isset( $value[$view_key]['pos_x'] ) ? $value[$view_key]['pos_x'] : '';
						$pos_y = isset( $value[$view_key]['pos_y'] ) ? $value[$view_key]['pos_y'] : '';
						$z_index = isset( $value[$view_key]['z_index'] ) ? $value[$view_key]['z_index'] : '';
						$align_h = isset( $value[$view_key]['align_h'] ) ? $value[$view_key]['align_h'] : '';
						$align_v = isset( $value[$view_key]['align_v'] ) ? $value[$view_key]['align_v'] : '';
						$hs_pos_x = isset( $value[$view_key]['hs_pos_x'] ) ? $value[$view_key]['hs_pos_x'] : '';
						$hs_pos_y = isset( $value[$view_key]['hs_pos_y'] ) ? $value[$view_key]['hs_pos_y'] : '';
						$hs_enable = isset( $value[$view_key]['hs_enable'] ) ? $value[$view_key]['hs_enable'] : '';
						$hs_align_h = isset( $value[$view_key]['hs_align_h'] ) ? $value[$view_key]['hs_align_h'] : '';
						$hs_align_v = isset( $value[$view_key]['hs_align_v'] ) ? $value[$view_key]['hs_align_v'] : '';
						$width = isset( $value[$view_key]['width'] ) ? $value[$view_key]['width'] : '';
						$height = isset( $value[$view_key]['height'] ) ? $value[$view_key]['height'] : '';
						$image = isset( $value[$view_key]['image'] ) ? $value[$view_key]['image'] : '';


						$image_src = !empty( $image ) ? pwc_get_image_by_id( 'full', 'full', $image, 1, 0 ) : '';
						echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con" data-src="'. esc_attr( $image_src ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][pos_x]" name="components['.$key.']['.$view_key.'][pos_x]" class="field-pos-x component-input" value="'. esc_attr( $pos_x ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][pos_y]" name="components['.$key.']['.$view_key.'][pos_y]" class="field-pos-y component-input" value="'. esc_attr( $pos_y ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][z_index]" name="components['.$key.']['.$view_key.'][z_index]" class="field-z-index component-input" value="'. esc_attr( $z_index ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][align_h]" name="components['.$key.']['.$view_key.'][align_h]" class="field-align-h component-input" value="'. esc_attr( $align_h ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][align_v]" name="components['.$key.']['.$view_key.'][align_v]" class="field-align-v component-input" value="'. esc_attr( $align_v ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_pos_x]" name="components['.$key.']['.$view_key.'][hs_pos_x]" class="field-hs-pos-x component-input" value="'. esc_attr( $hs_pos_x ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_pos_y]" name="components['.$key.']['.$view_key.'][hs_pos_y]" class="field-hs-pos-y component-input" value="'. esc_attr( $hs_pos_y ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_enable]" name="components['.$key.']['.$view_key.'][hs_enable]" class="field-hs-enable component-input" value="'. esc_attr( $hs_enable ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_align_h]" name="components['.$key.']['.$view_key.'][hs_align_h]" class="field-hs-align-h component-input" value="'. esc_attr( $hs_align_h ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_align_v]" name="components['.$key.']['.$view_key.'][hs_align_v]" class="field-hs-align-v component-input" value="'. esc_attr( $hs_align_v ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][width]" name="components['.$key.']['.$view_key.'][width]" class="field-width component-input" value="'. esc_attr( $width ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][height]" name="components['.$key.']['.$view_key.'][height]" class="field-height component-input" value="'. esc_attr( $height ) .'">

							<input type="hidden" data-index="components" data-key="['.$view_key.'][image]" name="components['.$key.']['.$view_key.'][image]" class="field-image component-input" value="'. esc_attr( $image ) .'">';

							
							$value_view_key = isset( $value[$view_key] ) ? $value[$view_key] : array();

							$args = array( 'view' => $view_key, 'key' => $key, 'value' => $value_view_key );
							do_action( 'pwc_add_group_view_fields', $args );

						echo '</div>';
					}
				}

				$args = array( 'key' => $key, 'value' => $value );
				do_action( 'pwc_add_group_fields', $args );

				$price = isset( $value['price'] ) ? $value['price'] : '';
				echo '<div class="pwc-form-field price-con">
					<input type="hidden" data-index="components" data-key="[price]" name="components['.$key.'][price]" class="field-price component-input" value="'. esc_attr( $price ) .'">
				</div>';

				$hide_control = isset( $value['hide_control'] ) ? $value['hide_control'] : '';
				echo '<div class="pwc-form-field hide_control-con">
					<input type="hidden" data-index="components" data-key="[hide_control]" name="components['.$key.'][hide_control]" class="field-hide_control component-input" value="'. esc_attr( $hide_control ) .'">
				</div>';

				$active = isset( $value['active'] ) ? $value['active'] : '';
				echo '<div class="pwc-form-field active-con">
					<input type="hidden" data-index="components" data-key="[active]" name="components['.$key.'][active]" class="field-active component-input" value="'. esc_attr( $active ) .'">
				</div>';

				$description = isset( $value['description'] ) ? $value['description'] : '';
				echo '<div class="pwc-form-field description-con">
					<input type="hidden" data-index="components" data-key="[description]" name="components['.$key.'][description]" class="field-description component-input" value="'. esc_attr( $description ) .'">
				</div>';

				$label = isset( $value['label'] ) ? $value['label'] : '';
				echo '<div class="pwc-form-field label-con">
					<input type="hidden" data-index="components" data-key="[label]" name="components['.$key.'][label]" class="field-label component-input" value="'. esc_attr( $label ) .'">
				</div>';

				$multiple = isset( $value['multiple'] ) ? $value['multiple'] : '';
				echo '<div class="pwc-form-field multiple-con">
					<input type="hidden" data-index="components" data-key="[multiple]" name="components['.$key.'][multiple]" class="field-multiple component-input" value="'. esc_attr( $multiple ) .'">
				</div>';

				$required = isset( $value['required'] ) ? $value['required'] : '';
				echo '<div class="pwc-form-field required-con">
					<input type="hidden" data-index="components" data-key="[required]" name="components['.$key.'][required]" class="field-required component-input" value="'. esc_attr( $required ) .'">
				</div>';

			echo '</div>';

			echo '<div class="pwc-form-field pwc-form-add-remove">';

				echo '<div class="pwc-form-field trash-icon">
					<span data-remove="components['.$key.']"><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
				</div>';

				// Collapse value
				$collapse = isset( $value['collapse'] ) && ( 'true' == $value['collapse'] ) ? $value['collapse'] : 'false';

				if( 'true' == $collapse ) {
					$collapse_class = 'collapse';
					$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-desc' );
				}
				else {
					$collapse_class = '';
					$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-asc' );
				}

				echo '<div class="pwc-form-field angle-down-icon">
					<input type="hidden" data-index="components" data-key="[collapse]" name="components['.$key.'][collapse]" class="component-input" value="'. esc_attr( $collapse ) .'">
					<span class="collapse-values" data-collapse="'. esc_attr( $collapse ) .'"><i class="'. esc_attr( $collapse_icon_class ) .'"></i></span>
				</div>';

				echo '<div class="pwc-form-field add-icon"><a data-index="components" data-parent-index="components['.$key.'][values]" href="#" class="pwc-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

			echo '</div>';

		echo '</div>'; // .pwc-group-field
	}

	public function configurator_build_sub_form_fields( $views, $key, $sub_key, $val ) {

		// Collapse value
		$collapse = isset( $val['collapse'] ) && ( 'true' == $val['collapse'] ) ? $val['collapse'] : 'false';

		if( 'true' == $collapse ) {
			$collapse_class = 'collapse';
			$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-desc' );
		}
		else {
			$collapse_class = '';
			$collapse_icon_class = apply_filters( 'pwc_icons', 'pwc-sort-asc' );
		}

		echo '<div class="pwc-settings-sub-group">';

			$sub_uid = isset( $val['uid'] ) ? $val['uid'] : '';

			echo '<div class="pwc-sub-group-field" data-uid="'. esc_attr( $sub_uid ) .'">';

				echo '<div class="pwc-layer-controls">';

					echo '<span class="pwc-control-inner layer-on-off">';
						echo '<input type="checkbox">';
					echo '</span>'; // .layer-checkbox

					echo '<span class="pwc-control-inner layer-show-hide">';
						echo '<span></span>';
					echo '</span>'; // .layer-zoom

					echo '<span class="pwc-control-inner layer-lock-unlock">';
						echo '<span></span>';
					echo '</span>'; // .layer-lock

				echo '</div>'; // .pwc-layer-controls

				echo '<div class="pwc-form-field pwc-form-name">';

				echo '<input type="hidden" data-index="[value]" data-key="[name]" name="components['.$key.'][values]['.$sub_key.'][uid]" class="component-input textfield" value="'. esc_attr( $sub_uid ) .'">';

				$sub_name = isset( $val['name'] ) ? $val['name'] : esc_html__( 'New Parent', 'product-woo-configurator' );
				echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-refresh' ) .'"></span><input type="text" data-index="[value]" data-key="[name]" name="components['.$key.'][values]['.$sub_key.'][name]" class="component-input textfield" value="'. esc_attr( $sub_name ) .'"></div>';

				$sub_icon = isset( $val['icon'] ) ? $val['icon'] : '';
				$sub_icon_src = !empty( $sub_icon ) ? pwc_get_image_by_id( 'full', 'full', $sub_icon, 1, 0 ) : '';
				echo '<div class="pwc-form-field icon-con" data-src="'. esc_attr( $sub_icon_src ) .'">
					<input type="hidden" data-index="[value]" data-key="[icon]" name="components['.$key.'][values]['.$sub_key.'][icon]" class="field-icon component-input" value="'. esc_attr( $sub_icon ) .'">
					
				</div>';

				if( isset( $this->views ) && !empty( $this->views ) ) {
					foreach( $this->views as $view_key => $view ) {

						$pos_x = isset( $val[$view_key]['pos_x'] ) ? $val[$view_key]['pos_x'] : '';
						$pos_y = isset( $val[$view_key]['pos_y'] ) ? $val[$view_key]['pos_y'] : '';
						$z_index = isset( $val[$view_key]['z_index'] ) ? $val[$view_key]['z_index'] : '';
						$align_h = isset( $val[$view_key]['align_h'] ) ? $val[$view_key]['align_h'] : '';
						$align_v = isset( $val[$view_key]['align_v'] ) ? $val[$view_key]['align_v'] : '';
						$hs_pos_x = isset( $val[$view_key]['hs_pos_x'] ) ? $val[$view_key]['hs_pos_x'] : '';
						$hs_pos_y = isset( $val[$view_key]['hs_pos_y'] ) ? $val[$view_key]['hs_pos_y'] : '';
						$hs_enable = isset( $val[$view_key]['hs_enable'] ) ? $val[$view_key]['hs_enable'] : '';
						$hs_align_h = isset( $val[$view_key]['hs_align_h'] ) ? $val[$view_key]['hs_align_h'] : '';
						$hs_align_v = isset( $val[$view_key]['hs_align_v'] ) ? $val[$view_key]['hs_align_v'] : '';
						$width = isset( $val[$view_key]['width'] ) ? $val[$view_key]['width'] : '';
						$height = isset( $val[$view_key]['height'] ) ? $val[$view_key]['height'] : '';
						$image = isset( $val[$view_key]['image'] ) ? $val[$view_key]['image'] : '';

						$image_src = !empty( $image ) ? pwc_get_image_by_id( 'full', 'full', $image, 1, 0 ) : '';
						echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con" data-src="'. esc_attr( $image_src ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][pos_x]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][pos_x]" class="field-pos-x component-input" value="'. esc_attr( $pos_x ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][pos_y]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][pos_y]" class="field-pos-y component-input" value="'. esc_attr( $pos_y ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][z_index]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][z_index]" class="field-z-index component-input" value="'. esc_attr( $z_index ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][align_h]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][align_h]" class="field-align-h component-input" value="'. esc_attr( $align_h ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][align_v]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][align_v]" class="field-align-v component-input" value="'. esc_attr( $align_v ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][hs_pos_x]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][hs_pos_x]" class="field-hs-pos-x component-input" value="'. esc_attr( $hs_pos_x ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][hs_pos_y]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][hs_pos_y]" class="field-hs-pos-y component-input" value="'. esc_attr( $hs_pos_y ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][hs_enable]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][hs_enable]" class="field-hs-enable component-input" value="'. esc_attr( $hs_enable ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][hs_align_h]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][hs_align_h]" class="field-hs-align-h component-input" value="'. esc_attr( $hs_align_h ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][hs_align_v]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][hs_align_v]" class="field-align-v component-input" value="'. esc_attr( $hs_align_v ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][width]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][width]" class="field-width component-input" value="'. esc_attr( $width ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][height]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][height]" class="field-height component-input" value="'. esc_attr( $height ) .'">

							<input type="hidden" data-index="[value]" data-key="['.$view_key.'][image]" name="components['.$key.'][values]['.$sub_key.']['.$view_key.'][image]" class="field-image component-input" value="'. esc_attr( $image ) .'">';

							$val_view_key = isset( $val[$view_key] ) ? $val[$view_key] : array();

							$args = array( 'view' => $view_key, 'key' => $key, 'sub_key' => $sub_key, 'value' => $val_view_key );
							
							do_action( 'pwc_add_parent_view_fields', $args );

						echo '</div>';
					}
				}

				$args = array( 'key' => $key, 'sub_key' => $sub_key, 'value' => $val );
				do_action( 'pwc_add_parent_fields', $args );

				$sub_price = isset( $val['price'] ) ? $val['price'] : '';
				echo '<div class="pwc-form-field price-con">
					<input type="hidden" data-index="[value]" data-key="[price]" name="components['.$key.'][values]['.$sub_key.'][price]" class="field-price component-input" value="'. esc_attr( $sub_price ) .'">
				</div>';

				$sub_hide_control = isset( $val['hide_control'] ) ? $val['hide_control'] : '';
				echo '<div class="pwc-form-field hide_control-con">
					<input type="hidden" data-index="[value]" data-key="[hide_control]" name="components['.$key.'][values]['.$sub_key.'][hide_control]" class="field-hide_control component-input" value="'. esc_attr( $sub_hide_control ) .'">					
				</div>';

				$sub_active = isset( $val['active'] ) ? $val['active'] : '';
				echo '<div class="pwc-form-field active-con">
					<input type="hidden" data-index="[value]" data-key="[active]" name="components['.$key.'][values]['.$sub_key.'][active]" class="field-active component-input" value="'. esc_attr( $sub_active ) .'">
					
				</div>';

				$sub_description = isset( $val['description'] ) ? $val['description'] : '';
				echo '<div class="pwc-form-field description-con">
					<input type="hidden" data-index="[value]" data-key="[description]" name="components['.$key.'][values]['.$sub_key.'][description]" class="field-description component-input" value="'. esc_attr( $sub_description ) .'">
					
				</div>';

				$sub_label = isset( $val['label'] ) ? $val['label'] : '';
				echo '<div class="pwc-form-field label-con">
					<input type="hidden" data-index="[value]" data-key="[label]" name="components['.$key.'][values]['.$sub_key.'][label]" class="field-label component-input" value="'. esc_attr( $sub_label ) .'">
					
				</div>';

				$sub_multiple = isset( $val['multiple'] ) ? $val['multiple'] : '';
				echo '<div class="pwc-form-field multiple-con">
					<input type="hidden" data-index="[value]" data-key="[multiple]" name="components['.$key.'][values]['.$sub_key.'][multiple]" class="field-multiple component-input" value="'. esc_attr( $sub_multiple ) .'">
				</div>';

				$sub_required = isset( $val['required'] ) ? $val['required'] : '';
				echo '<div class="pwc-form-field required-con">
					<input type="hidden" data-index="[value]" data-key="[required]" name="components['.$key.'][values]['.$sub_key.'][required]" class="field-required component-input" value="'. esc_attr( $sub_required ) .'">
				</div>';

				echo '</div>';

				echo '<div class="pwc-form-field pwc-form-add-remove">';

					echo '<div class="pwc-form-field trash-icon remove-sub-group">
						<span data-remove="components['.$key.'][values]['.$sub_key.']"><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
					</div>';

					echo '<div class="pwc-form-field angle-down-icon">
						<input type="hidden" data-index="[value]" data-key="[collapse]" name="components['.$key.'][values]['.$sub_key.'][collapse]" class="component-input" value="'. esc_attr( $collapse ) .'">
						<span class="collapse-sub-values" data-collapse="'. esc_attr( $collapse ) .'"><i class="'. esc_attr( $collapse_icon_class ) .'"></i></span>
					</div>';

					echo '<div class="pwc-form-field add-icon"><a href="#" data-parent-index="components['.$key.'][values]['.$sub_key.'][values]" class="pwc-sub-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

				echo '</div>';

			echo '</div>';

			if( !empty( $val['values'] ) ) {
				echo $this->get_sub_fields( $collapse, 'components['.$key.'][values]['.$sub_key.'][values]', $val['values'] );
			}

		echo '</div>';
	}


	/**
	 * Print Base fields
	 */
	public function print_base_fields() {

		$field_html = '';

		$base_fields = $this->get_base_fields();

		foreach( $base_fields as $key => $field ) {

			$title = isset( $field['title'] ) ? $field['title'] : '';

			$field_html .= '<div class="choose-set">';
				$field_html .= '<h3 class="title">'. esc_html( $title ) .'</h3>';
				$field_html .= $this->base_meta_field( $field );
			$field_html .= '</div>';
		}

		return $field_html;
	}

	/**
	 * Print meta field input
	 */
	public function base_meta_field( $field ) {

		$input = '';

		$id = isset( $field['id'] ) ? $field['id'] : '';
		$type = isset( $field['type'] ) ? $field['type'] : '';
		$options = isset( $field['options'] ) ? $field['options'] : array();
		$value = pwc_get_meta_value( $this->get_id(), $id, '' );

		if( 'text' == $type ) {
			$input = '<input type="text" name="'. esc_attr( $id ) .'" value="'. esc_attr( $value ) .'">';
		}
		elseif( 'textarea' == $type ) {
			$input = '<textarea name="'. esc_attr( $id ) .'" rows="2">'. esc_html( $value ) .'</textarea>';
		}
		elseif( 'select' == $type ) {
			$input .= '<select name="'. esc_attr( $id ) .'">';
				foreach( $options as $key => $option ) {

					$input .= '<option value="'. esc_attr( $key ) .'" '. selected( $value, $key, false ) .'>'. esc_html( $option ) .'</option>';

				}
			$input .= '</select>';
		}
		elseif( 'multitext' == $type ) {
			$input .= '<input type="text" name="'. esc_attr( $id ) .'" value="">';
			$input .= '<a href="#" id="pwc-add-view" class="button button-primary button-large">'. esc_html__( 'Add', 'product-woo-configurator' ) .'</a>';

			$count = ( isset( $value ) && ! empty( $value ) ) ? count( $value ) : 0;
			if( ! empty( $count ) && 1 <= $count ) {
				$input .= '<ul class="view-list" data-config-id="'. esc_attr( $this->get_id() ) .'">';

					if( $value ) {
						foreach( $value as $key => $text ) {
							$input .= '<li>'. esc_html( $text ) .'<a href="#" data-index="'. esc_attr( $key ) .'" class="delete-view">'. esc_html__( 'Delete', 'product-woo-configurator' ) .'</a></li>';
						}
					}
					
				$input .= '</ul>';
			}
		}

		return $input;
	}


	/**
	 * Get Base fields array
	 */
	public function get_base_fields() {

		$style = $this->get_config_style();

		$field = array();

		$args = array( 
			'post_type' => 'product',
			'posts_per_page' => -1
		);
		$q = new WP_Query( $args );

		$product_ids = array();

		$product_ids[0] = esc_html__( 'Select Product', 'product-woo-configurator' );
		while ( $q->have_posts() ) : $q->the_post();

			$id = get_the_ID();
			$title = get_the_title();

			$product_ids[$id] = $title;

		endwhile; wp_reset_postdata();

		$field[] = array(
			'id'      => '_pwc_product_id',
			'title'   => esc_html__( 'Choose Product', 'product-woo-configurator' ),
			'type'    => 'select',
			'options' => $product_ids
		);

		$field[] = array(
			'id'    => '_pwc_base_price',
			'title' => esc_html__( 'Base Price', 'product-woo-configurator' ),
			'type'  => 'text'
		);

		$field[] = array(
			'id'    => '_pwc_description',
			'title' => esc_html__( 'Enter Short Description', 'product-woo-configurator' ),
			'type'  => 'textarea'
		);

		$field[] = array(
			'id'      => '_pwc_views',
			'title'   => esc_html__( 'Add View', 'product-woo-configurator' ),
			'type'    => 'multitext',
			'default' => array( 
				'front'=> esc_html__( 'Front', 'product-woo-configurator' )
			)
		);

		$fields = apply_filters( 'pwc_base_fields', $field, $style );

		return $fields;
	}


	/**
	 * Get Config fields array
	 */
	public function get_config_fields() {

		$base = array();
		$extras = array();
		$option_set = array();

		// Base fields
		$base[] = array(
			'id'    => 'description',
			'title' => esc_html__( 'Description', 'product-woo-configurator' ),
			'type'  => 'textarea'
		);

		$option_set['base'] = apply_filters( 'pwc_base_config_fields', $base );

		// Extras fields
		$extras[] = array(
			'id'    => 'price',
			'title' => esc_html__( 'Price', 'product-woo-configurator' ),
			'type'  => 'text'
		);

		$option_set['extras'] = apply_filters( 'pwc_extras_config_fields', $extras );

		// Third party plugin can include field with this hook
		$option_set = apply_filters( 'pwc_config_fields', $option_set );

		return $option_set;
	}

	/**
	 * Get post id
	 */
	public function get_id() {
		return ( null != $this->post_obj->ID ) ? $this->post_obj->ID : 0;
	}

	/**
	 * Get configurator style
	 */
	public function get_config_style() {

		$product_id = pwc_get_meta_value( $this->get_id(), '_pwc_product_id', true );

		$style = pwc_get_meta_value( $product_id, '_configurator_style', 'style1' );

		return $style;
	}

	/**
	 * Get views
	 */
	public function get_views() {
		$views = pwc_get_meta_value( $this->get_id(), '_pwc_views', array( 'front'=> esc_html__( 'Front', 'product-woo-configurator' ) ) );

		return $views;
	}

	/**
	 * First View
	 */
	public function first_view() {

		$first_view = ( null != $this->get_views() ) ? key( $this->get_views() ) : 0;

		return $first_view;
	}

	/**
	 * Print repeatable html temple
	 */
	public function print_repeatable_content() {

		// For repeated fields
		echo '<div id="pwc-repeatable-field-hidden-structure">';

			// Main Group
			echo '<div id="pwc-settings-field">';

				echo '<div class="pwc-settings-group">';

					echo '<div class="pwc-group-field">';

						echo '<div class="pwc-layer-controls">';

							echo '<span class="pwc-control-inner layer-on-off">';
								echo '<input type="checkbox">';
							echo '</span>'; // .layer-checkbox

							echo '<span class="pwc-control-inner layer-show-hide">';
								echo '<span></span>';
							echo '</span>'; // .layer-zoom

							echo '<span class="pwc-control-inner layer-lock-unlock">';
								echo '<span></span>';
							echo '</span>'; // .layer-lock

						echo '</div>'; // .pwc-layer-controls

						echo '<div class="pwc-form-field pwc-form-name">';

						echo '<input type="hidden" data-index="components" data-key="[uid]" class="set-uid component-input">';

						echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-folder' ) .'"></span><input type="text" data-index="components" data-key="[name]" class="component-input textfield" value="'. esc_attr__( 'Group', 'product-woo-configurator' ) .'"></div>';

						echo '<div class="pwc-form-field icon-con">
							<input type="hidden" data-index="components" data-key="[icon]" class="field-icon component-input" value="">
						</div>';

						if( isset( $this->views ) && ! empty( $this->views ) ) {
							foreach ( $this->views as $view_key => $view ) {

								echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con">
							
									<input type="hidden" data-index="components" data-key="['.$view_key.'][pos_x]" class="field-pos-x component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][pos_y]" class="field-pos-y component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][z_index]" class="field-z-index component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][align_h]" class="field-align-h component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][align_v]" class="field-align-v component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_pos_x]" class="field-hs-pos-x component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_pos_y]" class="field-hs-pos-y component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_enable]" class="field-hs-enable component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_align_h]" class="field-hs-align-h component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][hs_align_v]" class="field-hs-align-v component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][width]" class="field-width component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][height]" class="field-height component-input" value="">

									<input type="hidden" data-index="components" data-key="['.$view_key.'][image]" class="field-image component-input" value="">';
									
									do_action( 'pwc_add_group_view_fields_js', $view_key );

								echo '</div>';
							}
						}

						do_action( 'pwc_add_group_fields_js' );

						echo '<div class="pwc-form-field price-con">
							<input type="hidden" data-index="components" data-key="[price]" class="field-price component-input" value="">
						</div>';

						echo '<div class="pwc-form-field hide_control-con">
							<input type="hidden" data-index="components" data-key="[hide_control]" class="field-hide_control component-input" value="">
						</div>';

						echo '<div class="pwc-form-field active-con">
							<input type="hidden" data-index="components" data-key="[active]" class="field-active component-input" value="">
						</div>';

						echo '<div class="pwc-form-field description-con">
							<input type="hidden" data-index="components" data-key="[description]" class="field-description component-input" value="">
						</div>';

						echo '<div class="pwc-form-field label-con">
							<input type="hidden" data-index="components" data-key="[label]" class="field-label component-input" value="">
						</div>';

						echo '<div class="pwc-form-field multiple-con">
							<input type="hidden" data-index="components" data-key="[multiple]" class="field-multiple component-input" value="">
						</div>';

						echo '<div class="pwc-form-field required-con">
							<input type="hidden" data-index="components" data-key="[required]" class="field-required component-input" value="">
						</div>';

						echo '</div>';

						echo '<div class="pwc-form-field pwc-form-add-remove">';

							echo '<div class="pwc-form-field trash-icon">
								<span><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field angle-down-icon">
								<input type="hidden" data-index="components" data-key="[collapse]" class="component-input" value="">
								<span class="collapse-values" data-collapse="true"><i class="'. apply_filters( 'pwc_icons', 'pwc-sort-desc' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field add-icon"><a data-index="components" href="#" class="pwc-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

						echo '</div>';

					echo '</div>';

					echo '<div class="pwc-settings-sub-group-wrapper"></div>';

				echo '</div>'; // .pwc-settings-group

			echo '</div>'; // .pwc-settings-field

			// Value Group
			echo '<div class="pwc-settings-sub-field">';

				echo '<div class="pwc-settings-sub-group">';

					echo '<div class="pwc-sub-group-field">';

						echo '<div class="pwc-layer-controls">';

							echo '<span class="pwc-control-inner layer-on-off">';
								echo '<input type="checkbox">';
							echo '</span>'; // .layer-checkbox

							echo '<span class="pwc-control-inner layer-show-hide">';
								echo '<span></span>';
							echo '</span>'; // .layer-zoom

							echo '<span class="pwc-control-inner layer-lock-unlock">';
								echo '<span></span>';
							echo '</span>'; // .layer-lock

						echo '</div>'; // .pwc-layer-controls

						echo '<div class="pwc-form-field pwc-form-name">';

						echo '<input type="hidden" data-index="[value]" data-key="[uid]" class="set-uid component-input">';

						echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-refresh' ) .'"></span><input type="text" data-index="[value]" data-key="[name]" class="component-input textfield" value="'. esc_attr__( 'New Parent', 'product-woo-configurator' ) .'"></div>';

						echo '<div class="pwc-form-field icon-con">
							<input type="hidden" data-index="[value]" data-key="[icon]" class="field-icon component-input" value="">
						</div>';

						if( isset( $this->views ) && ! empty( $this->views ) ) {

							foreach ( $this->views as $view_key => $view ) {

								echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][pos_x]" class="field-pos-x component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][pos_y]" class="field-pos-y component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][z_index]" class="field-z-index component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][align_h]" class="field-align-h component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][align_v]" class="field-align-v component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][hs_pos_x]" class="field-hs-pos-x component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][hs_pos_y]" class="field-hs-pos-y component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][hs_enable]" class="field-hs-enable component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][hs_align_h]" class="field-hs-align-h component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][hs_align_v]" class="field-hs-align-v component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][width]" class="field-width component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][height]" class="field-height component-input" value="">

									<input type="hidden" data-index="[value]" data-key="['. $view_key .'][image]" class="field-image component-input" value="">';

									do_action( 'pwc_add_sub_view_fields_js', $view_key );
									
								echo '</div>';

							}

						}

						do_action( 'pwc_add_sub_fields_js' );

						echo '<div class="pwc-form-field price-con">
							<input type="hidden" data-index="[value]" data-key="[price]" class="field-price component-input" value="">
						</div>';

						echo '<div class="pwc-form-field hide_control-con">
							<input type="hidden" data-index="[value]" data-key="[hide_control]" class="field-hide_control component-input" value="">
						</div>';

						echo '<div class="pwc-form-field active-con">
							<input type="hidden" data-index="[value]" data-key="[active]" class="field-active component-input" value="">
						</div>';

						echo '<div class="pwc-form-field description-con">
							<input type="hidden" data-index="[value]" data-key="[description]" class="field-description component-input" value="">
						</div>';

						echo '<div class="pwc-form-field label-con">
							<input type="hidden" data-index="[value]" data-key="[label]" class="field-label component-input" value="">
						</div>';

						echo '<div class="pwc-form-field multiple-con">
							<input type="hidden" data-index="[value]" data-key="[multiple]" class="field-multiple component-input" value="">
						</div>';

						echo '<div class="pwc-form-field required-con">
							<input type="hidden" data-index="[value]" data-key="[required]" class="field-required component-input" value="">
						</div>';

						echo '</div>';

						echo '<div class="pwc-form-field pwc-form-add-remove">';

							echo '<div class="pwc-form-field trash-icon">
								<span><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field angle-down-icon">
								<input type="hidden" data-index="[value]" data-key="[collapse]" class="component-input" value="">
								<span class="collapse-sub-values" data-collapse="true"><i class="'. apply_filters( 'pwc_icons', 'pwc-sort-desc' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field add-icon"><a href="#" class="pwc-sub-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

						echo '</div>';

					echo '</div>';

				echo '</div>';

			echo '</div>';


			// Sub value Group
			echo '<div class="pwc-settings-sub-value-field">';

				echo '<div class="pwc-settings-sub-value-group">';

					echo '<div class="pwc-sub-group-field">';

						echo '<div class="pwc-layer-controls">';

							echo '<span class="pwc-control-inner layer-on-off">';
								echo '<input type="checkbox">';
							echo '</span>'; // .layer-checkbox

							echo '<span class="pwc-control-inner layer-show-hide">';
								echo '<span></span>';
							echo '</span>'; // .layer-zoom

							echo '<span class="pwc-control-inner layer-lock-unlock">';
								echo '<span></span>';
							echo '</span>'; // .layer-lock

						echo '</div>'; // .pwc-layer-controls

						echo '<div class="pwc-form-field pwc-form-name">';

						echo '<input data-index="[value]" data-key="[uid]" type="hidden" class="set-uid component-input">';

						echo '<div class="pwc-form-field pwc-form-name-inner"><span class="pwc-name-icon '. apply_filters( 'pwc_icons', 'pwc-square-path' ) .'"></span><input data-index="[value]" data-key="[name]" type="text" class="component-input textfield" value="'. esc_attr__( 'New child', 'product-woo-configurator' ) .'"></div>';

						echo '<div class="pwc-form-field icon-con">
							<input data-index="[value]" data-key="[icon]" type="hidden" class="field-icon component-input" value="">
						</div>';

						if( isset( $this->views ) && ! empty( $this->views ) ) {

							foreach ( $this->views as $view_key => $view ) {

								echo '<div class="pwc-form-field '. esc_attr( $view_key ) .'-con">

									<input data-index="[value]" data-key="['.$view_key.'][pos_x]" type="hidden" class="field-pos-x component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][pos_y]" type="hidden" class="field-pos-y component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][z_index]" type="hidden" class="field-z-index component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][align_h]" type="hidden" class="field-align-h component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][align_v]" type="hidden" class="field-align-v component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][hs_pos_x]" type="hidden" class="field-hs-pos-x component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][hs_pos_y]" type="hidden" class="field-hs-pos-y component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][hs_enable]" type="hidden" class="field-hs-enable component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][hs_align_h]" type="hidden" class="field-hs-align-h component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][hs_align_v]" type="hidden" class="field-hs-align-v component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][width]" type="hidden" class="field-width component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][height]" type="hidden" class="field-height component-input" value="">

									<input data-index="[value]" data-key="['.$view_key.'][image]" type="hidden" class="field-image component-input" value="">';

									// add input field if view_field = true
									do_action( 'pwc_add_sub_view_fields_js', $view_key );

								echo '</div>';
							}

						}

						// add field via action
						do_action( 'pwc_add_sub_fields_js' );

						echo '<div class="pwc-form-field price-con">
							<input data-index="[value]" data-key="[price]" type="hidden" class="field-price component-input" value="">
						</div>';

						echo '<div class="pwc-form-field hide_control-con">
							<input data-index="[value]" data-key="[hide_control]" type="hidden" class="field-hide_control component-input" value="">
						</div>';

						echo '<div class="pwc-form-field active-con">
							<input data-index="[value]" data-key="[active]" type="hidden" class="field-active component-input" value="">
						</div>';

						echo '<div class="pwc-form-field description-con">
							<input data-index="[value]" data-key="[description]" type="hidden" class="field-description component-input" value="">
						</div>';

						echo '<div class="pwc-form-field label-con">
							<input data-index="[value]" data-key="[label]" type="hidden" class="field-label component-input" value="">
						</div>';

						echo '<div class="pwc-form-field multiple-con">
							<input data-index="[value]" data-key="[multiple]" type="hidden" class="field-multiple component-input" value="">
						</div>';

						echo '<div class="pwc-form-field required-con">
							<input data-index="[value]" data-key="[required]" type="hidden" class="field-required component-input" value="">
						</div>';

						echo '</div>';

						echo '<div class="pwc-form-field pwc-form-add-remove">';

							echo '<div class="pwc-form-field trash-icon">
								<span><i class="'. apply_filters( 'pwc_icons', 'pwc-delete' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field angle-down-icon">
								<input data-index="[value]" data-key="[collapse]" type="hidden" class="component-input" value="">
								<span class="collapse-sub-values" data-collapse="true"><i class="'. apply_filters( 'pwc_icons', 'pwc-sort-desc' ) .'"></i></span>
							</div>';

							echo '<div class="pwc-form-field add-icon"><a href="#" class="pwc-sub-values add-parent-index"><i class="'. apply_filters( 'pwc_icons', 'pwc-plus' ) .'"></i></a></div>';

						echo '</div>';

					echo '</div>';

				echo '</div>';

			echo '</div>';

		echo '</div>';
	}

	/**
	 * Print layer options html temple
	 */
	public function print_layer_options() {

		echo '<div id="pwc-layer-options">';
			echo '<div class="options-container">';
			echo '<h2 class="table-title">'. esc_html__( 'Options', 'product-woo-configurator' ) .'</h2>';
				echo '<div class="options-set-1">';

					echo '<div class="icon-set">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Choose Icon', 'product-woo-configurator' ) .'</h2>
							<span class="sub-title">'. esc_html__( 'Choose an Image', 'product-woo-configurator' ) .'</span>
						</div>

						<div class="choose-icon text-left">
							<div class="group-icon">
								<input type="hidden" data-con="icon" data-input="icon" class="pix-saved-val show-icon">
								<div class="selected-con"></div>
								<a href="#" class="select-image" data-show="icon" data-title="'. esc_attr__( 'Select File', 'product-woo-configurator' ) .'"  data-file-type="image"><img src="'. PWC_ASSETS_URL .'backend/img/select-img.png" alt=""></a>
							</div>	
							<p class="text-bottom">'. esc_html__( 'Icon', 'product-woo-configurator' ) .'</p>
						</div>
					</div>'; // .icon-set

					echo '<div class="image-set">';
						echo '<div class="title-set">
							<h2 class="title">'. esc_html__( 'Product Image', 'product-woo-configurator' ) .'</h2>
							<span class="sub-title">'. esc_html__( 'Choose product images all four sides', 'product-woo-configurator' ) .'</span>
						</div>';

						echo '<div id="product-image">';

							if( isset( $this->views ) && ! empty( $this->views ) ) {
								foreach ( $this->views as $view_key => $view ) {

									echo '<div class="choose-icon text-center">
										<div class="group-icon">
											<input data-con="'. esc_attr( $view_key ) .'" data-input="image" type="hidden" class="pix-saved-val show-'. esc_attr( $view_key ) .'-image">
											<div class="selected-con"></div>
											<a href="#" class="select-image" data-show="'. esc_attr( $view_key ) .'" data-img-view="'. esc_attr( $view_key ) .'" data-add-to-preview data-title="'. esc_attr__( 'Select File', 'product-woo-configurator' ) .'"  data-file-type="image"><img src="'. PWC_ASSETS_URL .'backend/img/select-img.png" alt=""></a>
										</div>	
										<p class="text-bottom">'. esc_html( $view ) .'</p>
									</div>';

								}
							}

						echo '</div>';
					echo '</div>'; // .image-set

					echo '<div class="description">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Description', 'product-woo-configurator' ) .'</h2>
							<span class="sub-title">'. esc_html__( 'Enter Short Description', 'product-woo-configurator' ) .'</span>
						</div>

						<textarea data-con="description" data-input="description" rows="6" class="show-description"></textarea>
					</div>'; // .description

					echo '<div class="label">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Label', 'product-woo-configurator' ) .'</h2>
							<span class="sub-title">'. esc_html__( 'Enter label', 'product-woo-configurator' ) .'</span>
						</div>

						<input data-con="label" data-input="label" type="text" value="" name="" class="show-label">
					</div>'; // .label

				echo '</div>'; // .options-set-1

				echo '<div class="options-set-2 options-set">';

					echo '<div class="group">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Price', 'product-woo-configurator' ) .'</h2>
						</div>
						<div class="price">
							<input data-con="price" data-input="price" type="text" value="" name="" class="show-price">
						</div>
					</div>';

					echo '<div class="group">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Hide Control', 'product-woo-configurator' ) .'</h2>
						</div>

						<div class="checkbox">
							<label>
							<input data-con="hide_control" data-input="hide_control" class="show-hide_control" type="checkbox">
								'. esc_html__( 'Hide this and child layers in controls', 'product-woo-configurator' ) .'
							</label>
						</div>
					</div>';

					echo '<div class="group">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Active', 'product-woo-configurator' ) .'</h2>
						</div>
						<div class="checkbox">
							<label>
							<input data-con="active" data-input="active" class="show-active" type="checkbox" name="">
								'. esc_html__( 'Active on load?', 'product-woo-configurator' ) .'
							</label>
						</div>
					</div>';						

					echo '<div class="group">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Multiple', 'product-woo-configurator' ) .'</h2>
						</div>
						<div class="checkbox">
							<label>
							<input data-con="multiple" data-input="multiple" class="show-multiple" type="checkbox" name="">
								'. esc_html__( 'Allow Multiple selection?', 'product-woo-configurator' ) .'
							</label>
						</div>
					</div>';

					echo '<div class="group">
						<div class="title-set">
							<h2 class="title">'. esc_html__( 'Required', 'pamapamar' ) .'</h2>
						</div>

						<div class="checkbox">
							<label>
							<input data-con="required" data-input="required" class="show-required" type="checkbox">
								'. esc_html__( 'Is this Required?', 'pamapamar' ) .'
							</label>
						</div>
					</div>';
				
				echo '</div>'; // .options-set-2
				
				echo '<div class="options-set">';
					do_action( 'pwc_add_meta_option' );
				echo '</div>';

			echo '</div>'; // .options-container

		echo '</div>'; // #pwc-layer-options
	}

}