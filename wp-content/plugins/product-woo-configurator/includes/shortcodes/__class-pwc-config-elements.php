<?php
/**
 * Configurator Shortcode Elements
 *
 *
 * @class     PWCSE
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined( 'PWC_VERSION' ) ) {
	return;
}

class PWCSE {

	public $cs = array();
	protected $active_item = '';
	protected $defaut_active_key = '';	
	protected $active_key = '';
	public $id;
	public $total_views = array();
	public $parent_groups = array();

	/**
	 * constructor
	 */
	public function __construct( $sc = false ) {

		$this->get_active_item();

	}

	public function get_active_item( $key = '' ) {

		$this->encoded_key = isset( $_GET['key'] ) ? $_GET['key'] : $key;
		$this->active_item = !empty( $this->encoded_key ) ? array_unique( explode( ',', base64_decode( $this->encoded_key ) ) ) : '';

		return $this->active_item;

	}

	/**
	 * [get_preview_html String html fragment of configurator preview]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function get_preview_html( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $slider_data = $values = array();
	
		$atts = apply_filters( 'pwc_config_preview_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_preview_classes', $additonal_class ); // classes should be as array

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		// Other values
		$slider_data['items']             = 'data-items="1"';
		$slider_data['center']            = 'data-center="true"';
		$slider_data['auto-height']       = 'data-auto-height="false"';
		$slider_data['autoplay']          = isset( $atts['autoplay'] ) ? 'data-autoplay="'. esc_attr( $atts['autoplay'] ) .'"' : '';
		$slider_data['slide_speed']       = isset( $atts['slide_speed'] ) ? 'data-autoplay-timeout="'. esc_attr( $atts['slide_speed'] ) .'"' : '';
		$slider_data['slide_margin']      = isset( $atts['slide_margin'] ) ? 'data-margin="'. esc_attr( $atts['slide_margin'] ) .'"' : '';
		$slider_data['stage_padding']     = isset( $atts['stage_padding'] ) ? 'data-stage-padding="'. esc_attr( $atts['stage_padding'] ) .'"' : '';
		$slider_data['slide_arrow']       = isset( $atts['slide_arrow'] ) ? 'data-nav="'. esc_attr( $atts['slide_arrow'] ) .'"' : '';
		$slider_data['slider_pagination'] = isset( $atts['slider_pagination'] ) ? 'data-dots="'. esc_attr( $atts['slider_pagination'] ) .'"' : '';
		$slider_data['loop']              = isset( $atts['loop'] ) ? 'data-loop="'. esc_attr( $atts['loop'] ) .'"' : '';
		$slider_data['mouse_drag']        = isset( $atts['mouse_drag'] ) ? 'data-mouse-drag="'. esc_attr( $atts['mouse_drag'] ) .'"' : '';
		$slider_data['touch_drag']        = isset( $atts['touch_drag'] ) ? 'data-mouse-drag="'. esc_attr( $atts['touch_drag'] ) .'"' : '';
		$slider_data['stop_on_hover']     = isset( $atts['stop_on_hover'] ) ? 'data-autoplay-hover-pause="'. esc_attr( $atts['stop_on_hover'] ) .'"' : '';

		$slider_data = apply_filters( 'pwc_config_preview_slider_data', $slider_data ); // slider data should be as array
		$slider_data = array_filter( $slider_data ); // remove empty array value

		$container_class = apply_filters( 'pwc_configurator_preview_container_class', array( 'configurator-view', 'pwc-configurator-view', 'owl-carousel' ) );

		$output .= '<div class="pwc-configurator-parent-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';
		
			$output .= '<div id="configurator-' . esc_attr( $this->id ) . '" class="pwc-configurator">';
				
				$output .= '<div id="configurator-view-' . esc_attr( $this->id ) . '" class="'. esc_attr( implode( ' ', $container_class ) ) .'" '. implode( ' ', $slider_data ) .'>';

					$output .= $this->get_preview_inner_html();

				$output .= '</div>'; // .configurator-view

				// Get default key
				$output .= $this->get_key_html();

			$output .= '</div>'; // .pwc-configurator

		$output .= '</div>'; // $el_classes

		// Values for filter
		$values['atts']        = $atts;
		$values['config-id']   = $this->id;
		$values['el_classes']  = $el_classes;

		$html = apply_filters( 'pwc_config_preview_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	/**
	 * [get_groups Returns parent group details]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function get_parent_groups() {

		if( is_array( $this->cs ) && count(  $this->cs ) > 0 ) {
			foreach( $this->cs as $key => $component ) {

				unset( $component['values'] );

				$this->parent_groups[$component['uid']] = $component;
				
			}
		}

		return apply_filters( 'pwc_parent_groups', $this->parent_groups );

	}

	/**
	 * [get_preview_inner_html String html fragment of configurator preview inner structure based on total views]
	 * @return string
	 */
	public function get_preview_inner_html() {

		// Empty assignment
		$output = '';

		$total_views = pwc_get_meta_value( $this->id, '_pwc_views', array( 'front'=> esc_html__( 'Front', 'product-woo-configurator' ) ) );

		$active_components = apply_filters( 'pwc_active_items', $this->get_active_array() );

		if( ! empty( $total_views ) ) {

			foreach ( $total_views as $preview_key => $screen ) {

				$output .= '<div id="pwc-'. esc_attr( $preview_key .'-' . $this->id ) .'" class="pwc-'. esc_attr( $preview_key ) .' pwc-preview-inner loading" data-type="'. esc_attr( $preview_key ) .'">';
					
					if( ! empty( $active_components ) ) {

						foreach ( $active_components as $key => $value ) {

							// Get image
							$output .= $this->get_image_html( $value, $preview_key, $key );

							// Get hotspot
							$output .= $this->get_hotspot_html( $value, $preview_key );

						}
					}

				$output .= '</div>'; // .pwc-preview-inner

			}

		}

		// Values for filter
		$values['total_views']        = $total_views;
		$values['active_components']   = $active_components;

		$html = apply_filters( 'pwc_config_preview_inner_html', $output, $values ); // html content, values

		return $html;

	}
	
	/**
	 * [get_image_html String html fragment of configurator preview image subsets]
	 * @param  array $value
	 * @param  string $screen
	 * @param  string $key
	 * @return string
	 */
	public function get_image_html( $value, $screen, $key ) {

		// Empty assignment
		$output = '';
		$data = array();

		if( isset( $value[$screen]['image'] ) && $value[$screen]['image'] ) {

			$src     = pwc_get_image_by_id( 'full', 'full', $value[$screen]['image'], 1, 0 );

			$width   = isset( $value[$screen]['width'] ) ? $value[$screen]['width'] : '';
			$height  = isset( $value[$screen]['height'] ) ? $value[$screen]['height'] : '';
			$pos_x   = isset( $value[$screen]['pos_x'] ) ? $value[$screen]['pos_x'] : '';
			$pos_y   = isset( $value[$screen]['pos_y'] ) ? $value[$screen]['pos_y'] : '';
			$align_h = isset( $value[$screen]['align_h'] ) ? $value[$screen]['align_h'] : '';
			$align_v = isset( $value[$screen]['align_v'] ) ? $value[$screen]['align_v'] : '';
			$z_index = isset( $value[$screen]['z_index'] ) ? $value[$screen]['z_index'] : '';

			// Image subset data
			if ( isset( $value['parent'] ) && isset( $value['parent']['parent_uid'] ) ) {
				$data['parent_uid'] = 'data-parent-uid="'. esc_attr( $value['parent']['parent_uid'] ) . '"';
			}

			if ( isset( $value['ancestor_uid'] ) ) {
				$data['ancestor_uid'] = 'data-ancestor-uid="'. esc_attr( $value['ancestor_uid'] ) . '"';
			}

			$data['uid']     = 'data-uid="'. esc_attr( $value['uid'] ) . '"';
			$data['width']   = 'data-width="'. esc_attr( $width ) . '"';
			$data['height']  = 'data-height="'. esc_attr( $height ) . '"';
			$data['pos_x']   = 'data-offset-x="'. esc_attr( $pos_x ) . '"';
			$data['pos_y']   = 'data-offset-y="'. esc_attr( $pos_y ) . '"';
			$data['align_h'] = 'data-align-h="'. esc_attr( $align_h ) . '"';
			$data['align_v'] = 'data-align-v="'. esc_attr( $align_v ) . '"';
			$data['z_index'] = 'data-z-index="'. esc_attr( $z_index ) . '"';
			
			$output .= '<div class="subset active" '. implode( ' ', $data ) .'>
				<img src="'. esc_url( $src ).'" alt="" width="'. esc_attr( $width ) .'" height="'. esc_attr( $height ) .'">
			</div>';

			$this->active_key .= $key .', ';
			$this->defaut_active_key .= $key .', ';

		}

		return $output;

	}

	/**
	 * [get_hotspot_html String html fragment of configurator preview image hotspot]
	 * @param  array $value
	 * @param  string $screen
	 * @return string
	 */
	public function get_hotspot_html( $value, $screen ) {

		// Empty assignment
		$output = '';

		// hotspot
		if( isset( $value[$screen]['hs_enable'] ) && $value[$screen]['hs_enable'] == 'true' ) {

			// Image subset hotspot data
			$pos_x   = isset( $value[$screen]['hs_pos_x'] ) ? $value[$screen]['hs_pos_x'] : '';
			$pos_y   = isset( $value[$screen]['hs_pos_y'] ) ? $value[$screen]['hs_pos_y'] : '';
			$align_h = isset( $value[$screen]['hs_align_h'] ) ? $value[$screen]['hs_align_h'] : '';
			$align_v = isset( $value[$screen]['hs_align_v'] ) ? $value[$screen]['hs_align_v'] : '';

			// Image subset data
			$data['hotspot_uid'] = 'data-hotspot-uid="'. esc_attr( $value['uid'] ) . '"';
			$data['pos_x']       = 'data-offset-x="'. esc_attr( $pos_x ) . '"';
			$data['pos_y']       = 'data-offset-y="'. esc_attr( $pos_y ) . '"';
			$data['align_h']     = 'data-align-h="'. esc_attr( $align_h ) . '"';
			$data['align_v']     = 'data-align-v="'. esc_attr( $align_v ) . '"';

			$output = '<div class="pwc-hotspot" id="hotspot-'. esc_attr( $screen .'-'. $value['uid'] ) .'" data-hotspot-uid="'. esc_attr( $value['uid'] ) .'" '. implode( ' ', $data ).'><span></span></div>';
		}

		return $output;

	}

	public function add_to_cart_from( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $btn_class = $values = array();
	
		$atts = apply_filters( 'pwc_config_cart_form_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_cart_form_classes', $additonal_class ); // classes should be as array

		$active_components = apply_filters( 'pwc_active_items', $this->get_active_array() );

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		// Other values
		$overlap = isset( $atts['overlap'] ) ? $atts['overlap'] : 'overlap';

		$btn_class[] = 'single_add_to_cart_button button alt single-product-cart-btn btn';
		$btn_class[] = isset( $atts['btn_style'] ) ? $atts['btn_style'] : 'btn-solid';
		$btn_class[] = isset( $atts['btn_text_style'] ) ? $atts['btn_text_style'] : 'btn-uppercase';
		$btn_class[] = isset( $atts['btn_size'] ) ? $atts['btn_size'] : 'btn-md';
		$btn_class[] = isset( $atts['btn_color'] ) ? $atts['btn_color'] : 'btn-primary';
		$btn_class[] = isset( $atts['btn_type'] ) ? $atts['btn_type'] : 'btn-oval';

		$product = wc_get_product( $this->product_id );

		if ( ! $product->is_purchasable() ) {
			return;
		}

		// Availability
		$availability      = $product->get_availability();
		$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

		ob_start();

		echo '<div class="config-cart-form-parent-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';

			echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );

			if ( $product->is_in_stock() ) : ?>

				<div class="config-cart-form config-cart-form-<?php echo esc_attr( $this->id ); ?>" data-config-id="<?php echo esc_attr( $this->id ); ?>">

					<form class="cart <?php echo esc_attr( $overlap ); ?>" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
					
						<?php do_action( 'pwc_config_before_add_to_cart_button' ); ?>

						<?php 
							if ( ! $product->is_sold_individually() ) {
								woocommerce_quantity_input( 
									array(
										'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
										'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
										'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
									),
									$product,
									true
								);
							}
					 	?>

						<button type="submit" class="<?php echo esc_attr( implode( ' ', $btn_class ) ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

						<?php echo $this->get_key_html(); ?>

						<input type="hidden" name="pwc-add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

						<?php do_action( 'pwc_config_after_add_to_cart_button' ); ?>

					</form>

				</div>

			<?php endif;

		echo '</div>'; // $el_classes

		// Values for filter
		$values['atts']       = $atts;
		$values['config-id']  = $this->id;
		$values['product-id'] = $this->product_id;
		$values['el_classes'] = $el_classes;
		$values['key_html']   = $this->get_key_html();		

		$output = ob_get_clean();

		$html = apply_filters( 'pwc_config_cart_form_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	/**
	 * [get_controls_html String html fragment of configurator controls]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function get_controls_html( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $values = array();
	
		$atts = apply_filters( 'pwc_config_controls_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_controls_classes', $additonal_class ); // classes should be as array

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		$output .= '<div class="pwc-controls-parent-wrap'. esc_attr( implode( ' ', $el_classes ) ) .'">';

			$output .= '<div class="pwc-controls-wrap" data-config-id="'. esc_attr( $this->id ) .'">';

				$output .= $this->build_controls_group_html( $this->cs );

			$output .= '</div>'; // .pwc-controls-wrap

		$output .= '</div>'; // $el_classes

		// Values for filter
		$values['atts']       = $atts;
		$values['config-id']  = $this->id;
		$values['el_classes'] = $el_classes;

		$html = apply_filters( 'pwc_config_controls_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	/**
	 * [total_price_html String html fragment of total price]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function total_price_html( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $values = array();
	
		$atts = apply_filters( 'pwc_config_price_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_price_classes', $additonal_class ); // classes should be as array

		$active_components = apply_filters( 'pwc_active_items', $this->get_active_array() );

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		// Other values
		$total_price_text = isset( $atts ) && isset( $atts['total_price_text'] ) ? $atts['total_price_text'] : esc_html__( 'Total Price', 'product-woo-configurator' );
		$base_price_text = isset( $atts ) && isset( $atts['base_price_text'] ) ? $atts['base_price_text'] : esc_html__( 'Base Price', 'product-woo-configurator' );

		// Base price
		$base_price = pwc_get_meta_value( $this->id, '_pwc_base_price', '0' );
		$build_price = wp_kses( wc_price( $base_price ), array() );

		$output .= '<div class="pwc-config-price-parent-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';

			$output .= '<div id="total-price-con" class="single-product-price pwc-config-price pwc-config-price-' . esc_attr( $this->id ) . ' pwc-config-price" data-config-id="'. esc_attr( $this->id ) .'">';	

				$output .= '<span class="total-text">'. esc_html( $total_price_text ) .'</span>';
				$output .= '<p class="calculation price">'. esc_html( $build_price ) .'</p>';

				$output .= do_action( 'pwc_after_total_price' );

				$output .= '<div class="total-price">';

					$output .= '<p>';

						$output .= '<span class="value" data-price="'. esc_attr( $base_price ) .'">'. esc_html( $base_price_text ) .' '. esc_html( $build_price ) .'</span>';

						if( !empty( $active_components ) ) {

							foreach ( $active_components as $key => $value ) {

								$multiple = isset( $value['parent']['multiple'] ) ? $value['parent']['multiple'] : 'false';
								$hide_control = isset( $value['hide_control'] ) ? $value['hide_control'] : 'false';

								if( 'false' == $multiple && isset( $value['parent'] ) && isset( $value['parent']['parent_uid'] ) ) {							
									$uid = $value['parent']['parent_uid'];								
								}
								else {
									$uid = $value['uid'];
								}

								$price = empty( $value['price'] ) ? '0' : $value['price'];

								$parent_name = ( isset( $value['parent'] ) && isset( $value['parent']['parent_uid'] ) ) ? $value['parent']['parent_name'] : '';

								if( 'false' == $hide_control ) {
									$output .= '<span id="price-list-'. esc_attr( $uid ) .'" class="value" data-price="'. esc_attr( $price ) .'"> <span class="sign">+</span> '. esc_html( $parent_name ) .' '. esc_html( pwc_apply_currency_position( $price ) ) .'</span>';
								}
							}

						}

					$output .= '</p>';

				$output .= '</div>'; // .total-price

			$output .= '</div>'; // #total-price-con

		$output .= '</div>'; // $el_classes


		// Values for filter
		$values['atts']              = $atts;
		$values['config-id']         = $this->id;
		$values['el_classes']        = $el_classes;
		$values['base_price']        = $base_price;
		$values['active_components'] = $active_components;

		$html = apply_filters( 'pwc_config_price_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	/**
	 * [product_share String html fragment of product share]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function product_share( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $values = array();
	
		$atts = apply_filters( 'pwc_config_share_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_share_classes', $additonal_class ); // classes should be as array

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		// Other values
		$social_share = isset( $atts ) && isset( $atts['social_share'] ) ? explode( ',', $atts['social_share'] ) : array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'copy_to_clipboard' );

		$output .= '<div class="product-share-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';
			
			$output .= '<div class="product-share" data-config-id="'. esc_attr( $this->id ) .'">';

				if( in_array( 'facebook', $social_share ) ) {
					$output .= '<a href="https://www.facebook.com/sharer/sharer.php?u=" target="_blank" class="facebook '. apply_filters( 'pwc_icons', 'pwcf-facebook' ) .'" title="'. esc_attr__( 'Facebook', 'product-woo-configurator' ) .'"></a>';
				}
				if( in_array( 'twitter', $social_share ) ) {
					$output .= '<a href="https://twitter.com/home?status=" target="_blank" class="twitter '. apply_filters( 'pwc_icons', 'pwcf-twitter' ) .'" title="'. esc_attr__( 'Twitter', 'product-woo-configurator' ) .'"></a>';
				}
				if( in_array( 'linkedin', $social_share ) ) {
					$output .= '<a href="https://www.linkedin.com/cws/share?url=" target="_blank" class="linkedin '. apply_filters( 'pwc_icons', 'pwcf-linkedin' ) .'" title="'. esc_attr__( 'Linkedin', 'product-woo-configurator' ) .'"></a>';
				}
				if( in_array( 'pinterest', $social_share ) ) {
					$output .= '<a href="https://pinterest.com/pin/create/button/?url=" target="_blank" class="pinterest '. apply_filters( 'pwc_icons', 'pwcf-pinterest' ) .'" title="'. esc_attr__( 'Pinterest', 'product-woo-configurator' ) .'"></a>';
				}
				if( in_array( 'copy_to_clipboard', $social_share ) ) {
					$output .= '<a href="#" class="copy copy-link '. apply_filters( 'pwc_icons', 'pwcf-copy' ) .'" title="'. esc_attr__( 'Copy to Clipboard', 'product-woo-configurator' ) .'"></a>';
				}				

			$output .= '</div>'; // .product-share

		$output .= '</div>'; // $el_classes

		// Values for filter
		$values['atts']       = $atts;
		$values['config-id']  = $this->id;
		$values['el_classes'] = $el_classes;

		$html = apply_filters( 'pwc_config_share_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * [product_inspiration String html fragment of configurator inspiration/screenshot/reset]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return return
	 */
	public function product_inspiration( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $slider_data = $values = array();
	
		$atts = apply_filters( 'pwc_config_inspiration_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_inspiration_classes', $additonal_class ); // classes should be as array

		$active_components = apply_filters( 'pwc_active_items', $this->get_active_array() );

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		// Other values
		$slider_data['items']             = 'data-items="3"';
		$slider_data['center']            = 'data-center="false"';
		$slider_data['auto-height']       = 'data-auto-height="false"';
		$slider_data['loop']              = 'data-loop="false"';
		$slider_data['autoplay']          = isset( $atts['autoplay'] ) ? 'data-autoplay="'. esc_attr( $atts['autoplay'] ) .'"' : '';
		$slider_data['slide_speed']       = isset( $atts['slide_speed'] ) ? 'data-autoplay-timeout="'. esc_attr( $atts['slide_speed'] ) .'"' : '';
		$slider_data['slide_margin']      = isset( $atts['slide_margin'] ) ? 'data-margin="'. esc_attr( $atts['slide_margin'] ) .'"' : '';
		$slider_data['stage_padding']     = isset( $atts['stage_padding'] ) ? 'data-stage-padding="'. esc_attr( $atts['stage_padding'] ) .'"' : '';
		$slider_data['slide_arrow']       = isset( $atts['slide_arrow'] ) ? 'data-nav="'. esc_attr( $atts['slide_arrow'] ) .'"' : '';
		$slider_data['slider_pagination'] = isset( $atts['slider_pagination'] ) ? 'data-dots="'. esc_attr( $atts['slider_pagination'] ) .'"' : '';
		$slider_data['mouse_drag']        = isset( $atts['mouse_drag'] ) ? 'data-mouse-drag="'. esc_attr( $atts['mouse_drag'] ) .'"' : '';
		$slider_data['touch_drag']        = isset( $atts['touch_drag'] ) ? 'data-mouse-drag="'. esc_attr( $atts['touch_drag'] ) .'"' : '';
		$slider_data['stop_on_hover']     = isset( $atts['stop_on_hover'] ) ? 'data-autoplay-hover-pause="'. esc_attr( $atts['stop_on_hover'] ) .'"' : '';

		$slider_data = apply_filters( 'pwc_config_inspiration_slider_data', $slider_data ); // slider data should be as array
		$slider_data = array_filter( $slider_data );

		// Get current user role
		$role = pwc_user_role();

		$key = pwc_remove_duplicate_string( $this->defaut_active_key );

		// Other values
		$social_share = isset( $atts ) && isset( $atts['social_share'] ) ? explode( ',', $atts['social_share'] ) : array( 'facebook', 'twitter', 'google_plus', 'linkedin', 'pinterest', 'copy_to_clipboard' );

		$output .= '<div class="inpiration-parent-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';

			// It prints the Inspiration, Take Screenshot and Reset trigger icons
			$output .= $this->components_trigger( $atts, false );

			// Inspiration List
			$output .= '<div class="inspiration-wrap popup" data-config-id="'. esc_attr( $this->id ) .'" data-key="'. esc_attr( $key ) .'">';

				$output .= '<a class="close-icon close-popup"><i class="'. apply_filters( 'pwc_icons', 'pwcf-close' ) .'"></i></a>';

				$output .= '<div class="inspiration-lists">';

					$output .= '<span class="notice"></span>';

					$output .= '<h3>'. esc_html__( 'Inspiration', 'product-woo-configurator' );
					if( 'administrator' == $role ) {
						$output .= '<span class="add-new-inspiration-form btn btn-sm btn-solid btn-black btn-uppercase btn-oval">'. esc_html__( 'Add New', 'product-woo-configurator' ) .'</span>';
					}
					$output .= '</h3>';

					$output .= '<div class="tab-wrapper lists-scroll">';

						$inspiration = pwc_get_meta_value( $this->id, '_pwc_inspiration', array() );

						if( ! empty( $inspiration ) && isset( $inspiration['group'] ) ) {
							$output .= '<ul class="tab-menu">';
								$i=0;
								foreach( $inspiration['group'] as $key => $group_name ) {

									$active = ( 0 == $i ) ? ' class="active"' : '';

									$output .= '<li'. $active .' data-anchor="'. esc_attr( strtolower( str_replace(' ', '-', $group_name ) ) ) .'">'. esc_html( $group_name );

										if( 'administrator' == $role ) {
											$output .= '<span class="delete-inspiration-group delete-btn" data-type="delete-group" data-group="'. esc_attr( $group_name ) .'" data-group-index="'. esc_attr( $key ) .'"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-cancel' ) .'"></i></span>';
										}

									$output .= '</li>';
									
								$i++;}
							$output .= '</ul>';
						}

						$output .= '<div class="tab-content">';

							if( !empty( $inspiration ) && isset( $inspiration['list'] ) ) {

								$j=0;
								foreach( $inspiration['list'] as $group => $value ){

									$current = ( 0 == $j ) ? 'current' : '';

									$output .= '<div class="tab '. esc_attr( strtolower( str_replace(' ', '-', $group ) ) .' '.$current ) .'">';

										$output .= '<div class="owl-carousel-slider owl-carousel" '. implode( ' ', $slider_data ) .'>';

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
													$output .= "<div class='ins-list' data-value='". json_encode( $val ) ."'>";

														if( ! empty( $image ) ) {
															$output .= '<img src="'. esc_url( $image ) .'" alt="">';
														}

														$output .= '<p class="title"><span>'. esc_html( $name ) .'</span></p>';
														if( !empty( $desc ) ) {
															$output .= '<p class="desc"><span>'. esc_html( $desc ) .'</span></p>';
														}

														$output .= '<a href="#" class="reset-components btn btn-md btn-solid btn-oval btn-primary btn-uppercase">'. esc_html__( 'Select', 'product-woo-configurator' ) .'</a>';

														if( 'administrator' == $role ) {
															$output .= '<div class="ins-icons">';
																$output .= '<span class="update-form"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-pencil' ) .'"></i></span>';
																$output .= '<span data-type="reset" class="reset-inspiration"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-reset' ) .'"></i></span>';
																$output .= '<span data-type="delete" class="delete-inspiration"><i class="icon '. apply_filters( 'pwc_icons', 'pwcf-trash' ) .'"></i></span>';
															$output .= '</div>';
														}
													$output .= '</div>';
												}
											}

										$output .= '</div>'; // .owl-carousel-slider

									$output .= '</div>'; // .tab-content
								$j++;}
							}

						$output .= '</div>'; // .tab-content-wrap

					$output .= '</div>'; // .lists-scroll

				$output .= '</div>'; // .inspiration-lists

				if( 'administrator' == $role ) {

					$output .= '<div class="inspiration-form">';

						$output .= '<span class="form-notice"></span>';

						$output .= '<div class="add-new-inspiration">';

							$output .= '<h3 class="title">'. esc_html__( 'Add New', 'product-woo-configurator' ) .'</h3>';

							$output .= '<div class="ins-field-group">';
								if( isset( $inspiration['group'] ) && !empty( $inspiration['group'] ) ) {
									$output .= '<select class="existing-group">';
										$output .= '<option value="0">'. esc_html__( 'Choose Group Name', 'product-woo-configurator' ) .'</option>';
										foreach ( $inspiration['group'] as $key => $group ) {
											$output .= '<option value="'. esc_attr( $group ) .'">'. esc_html( $group ) .'</option>';
										}
									$output .= '</select>';
								}
							$output .= '</div>';	

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-group" type="text"  placeholder="'. esc_attr__( 'Or Create New Group', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error group-error"></span>';
							$output .= '</div>';	

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-name" type="text" placeholder="'. esc_attr__( 'Inspiration Name', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error name-error"></span>';
							$output .= '</div>';					

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-desc" type="text" placeholder="'. esc_attr__( 'Description', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error"></span>';
							$output .= '</div>';

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-image" type="text" placeholder="'. esc_attr__( 'Image Url', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error"></span>';
							$output .= '</div>';

							$output .= '<div class="ins-field-btn">';
								$output .= '<a href="#" data-type="add-new" class="save-inspiration btn btn-md btn-solid btn-oval btn-primary btn-uppercase">'. esc_html__( 'Create', 'product-woo-configurator' ) .'</a>';
								$output .= '<a href="#" class="cancel-inspiration-form btn btn-md btn-solid btn-oval btn-black btn-uppercase">'. esc_html__( 'Cancel', 'product-woo-configurator' ) .'</a>';
							$output .= '</div>';

						$output .= '</div>';

						$output .= '<div class="update-inspiration">';

							$output .= '<h3 class="title">'. esc_html__( 'Update', 'product-woo-configurator' ) .'</h3>';

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-name" type="text" placeholder="'. esc_attr__( 'Inspiration Name', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error name-error"></span>';
							$output .= '</div>';					

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-desc" type="text" placeholder="'. esc_attr__( 'Description', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error"></span>';
							$output .= '</div>';

							$output .= '<div class="ins-field-group">';
								$output .= '<input class="ins-field custom-ins-image" type="text" placeholder="'. esc_attr__( 'Image Url', 'product-woo-configurator' ) .'">';
								$output .= '<span class="error"></span>';
							$output .= '</div>';

							$output .= '<div class="ins-field-btn">';
								$output .= '<a href="#" data-type="update" class="update-inspiration-list btn btn-md btn-solid btn-oval btn-gradient btn-uppercase">'. esc_html__( 'Update', 'product-woo-configurator' ) .'</a>';
								$output .= '<a href="#" class="cancel-inspiration-form btn btn-md btn-solid btn-oval btn-black btn-uppercase">'. esc_html__( 'Cancel', 'product-woo-configurator' ) .'</a>';
							$output .= '</div>';

						$output .= '</div>';

					$output .= '</div>'; // .inspiration-form
				}

			$output .= '</div>'; // .inspiration-wrap

		$output .= '</div>';

		// Values for filter
		$values['atts']        = $atts;
		$values['config-id']   = $this->id;
		$values['el_classes']  = $el_classes;
		$values['inspiration'] = $inspiration;

		$html = apply_filters( 'pwc_config_inspiration_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * [components_trigger String html fragment of configurator inspiration/screenshot/reset trigger icons]
	 * @param  array  $atts
	 * @param  boolean $echo
	 * @return string
	 */
	public function components_trigger( $atts = '', $echo = false ) {

		// Empty assignment
		$output = '';

		// Other values
		$options = isset( $atts['options'] ) ? explode( ',', $atts['options'] ) : array( 'inspiration', 'screenshot', 'reset' );

		$reset_active_key = pwc_remove_duplicate_string( $this->defaut_active_key );

		$output .= '<div class="insp-screenshot">';

			if( in_array( 'inspiration', $options ) ) {
				$output .= '<a href="#" data-id="'. esc_attr( $this->id ) .'" class="open-inspiration-popup"><i class="'. esc_attr( apply_filters( 'pwc_icons', 'pwcf-inspiration' ) ) .'"></i></a>';
			}
			if( in_array( 'screenshot', $options ) ) {
				$output .= '<a href="#" data-id="'. esc_attr( $this->id ) .'" class="take-photo"><i class="'. esc_attr( apply_filters( 'pwc_icons', 'pwcf-screenshot' ) ) .'"></i></a>';
			}
			if( in_array( 'reset', $options ) ) {
				$output .= '<a href="#" data-id="'. esc_attr( $this->id ) .'" data-key="'. esc_attr( $reset_active_key ) .'" class="reset-config"><i class="'. esc_attr( apply_filters( 'pwc_icons', 'pwcf-reset' ) ) .'"></i></a>';
			}
			
		$output .= '</div>';

		// Values for filter
		$values['atts']             = $atts;
		$values['config-id']        = $this->id;
		$values['reset_active_key'] = $reset_active_key;

		$html = apply_filters( 'pwc_config_inspiration_trigger_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	/**
	 * Get configurator controls html
	 * @return String html fragment of prodcut configurator preview
	 */
	public function get_accordion_controls_html( $atts = '', $echo = false ) {

		// Empty assignment
		$output = $html = '';
		$additonal_class = $el_classes = $values = array();
	
		$atts = apply_filters( 'pwc_config_controls_atts', $atts );

		$additonal_class = apply_filters( 'pwc_config_controls_classes', $additonal_class ); // classes should be as array

		// Extra Class
		if( ! empty( $atts ) ) {			
			$el_classes = apply_filters( 'kc-el-class', $atts );
			$el_classes[] = isset( $atts ) && isset( $atts['extra_class'] ) ? $atts['extra_class'] : '';
			$el_classes = array_filter( array_merge( $el_classes, $additonal_class ) );
		}

		$output .= '<div class="pwc-controls-parent-wrap '. esc_attr( implode( ' ', $el_classes ) ) .'">';

			$output .= '<div class="pwc-controls-wrap pwc-skin-accordion-controls" data-config-id="'. esc_attr( $this->id ) .'">';

				$output .= $this->build_controls_group_html( $this->cs );

			$output .= '</div>'; // .pwc-controls-wrap

		$output .= '</div>'; // $el_classes

		// Values for filter
		$values['atts']       = $atts;
		$values['el_classes'] = $el_classes;
		$values['config-id']  = $this->id;

		$html = apply_filters( 'pwc_config_accordion_control_html', $output, $values ); // html content, values

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}

	}

	/**
	 * [build_controls_group_html String html fragment of configurator controls]
	 * @param  array $components
	 * @param  array  $parent
	 * @return string
	 */
	public function build_controls_group_html( $components, $parent = array() ) {

		$html = $data_attr_ul = $data_attr_li = $close_btn = $hide_child = '';

		if( ! empty( $parent ) ) {

			$optionset        = isset( $parent['parent_name'] ) ? str_replace( ' ', '-', $parent['parent_name'] ) : 'default';
			$parent_optionset = isset( $parent['parent_name'] ) ? str_replace( ' ', '-', $parent['parent_name'] ) : 'default';
			$multiple         = isset( $parent['multiple'] ) ? $parent['multiple'] : 'false';
			// $required         = isset( $parent['required'] ) ? $parent['required'] : 'false';
			$parent_uid       = isset( $parent['parent_uid'] ) ? $parent['parent_uid'] : '';

			$close_btn = '<li class="pwc-controls-list-img" data-text="close" data-open-optionset="' . esc_attr( str_replace( ' ', '-', $parent_optionset ) ) . '"><i class="closes pwc-thin-cross"></i></li>';

			$hide_child = ' hover-hide';

			$data_attr_ul .= ' data-multiple="'. esc_attr( $multiple ) .'"';
			// $data_attr_ul .= ' data-required="'. esc_attr( $required ) .'"';
			$data_attr_ul .= ' data-optionset-parent="'. esc_attr( $parent_optionset ) .'"';
			$data_attr_ul .= ' data-parent-uid="'. esc_attr( $parent_uid ) .'"';

			$html .= '<div class="pwc-controls-list-sec main-sec' . $hide_child . '">';

			$name = isset( $parent['parent_name'] ) ? $parent['parent_name'] : 'default';

			$icon = '';
			if( isset( $parent['icon'] ) && !empty( $parent['icon'] ) ) {
				$icon = pwc_get_image_by_id( null, null, $parent['icon'], 0, 0 );
			}

			$html .= '<h2 class="pwc-layer-title test">' . esc_html( $name ) . '</h2>';

			$html .= '<ul class="pwc-controls-img-list"' . $data_attr_ul . ' data-optionset="' . esc_attr( $optionset ) . '">';

			$html .= $close_btn;

		} else {

			$random = pwc_random_string().'-'.pwc_random_string();
			$data_attr_li = 'data-random="' . esc_attr( $random ) . '"';
			
		}

		foreach ( $components as $top_key => $top_layer ) {	

			// if first (top parent) layer print sub layer values
			if( empty( $parent ) ) {

				if( isset( $top_layer['hide_control'] ) && 'true' == $top_layer['hide_control'] ) {
					continue;
				}

				$data_attr_ul = '';

				$multiple      = isset( $top_layer['multiple'] ) ? $top_layer['multiple'] : 'false';
				$data_attr_ul .= ' data-multiple="'. esc_attr( $multiple ) .'"';

				$required      = isset( $top_layer['required'] ) ? $top_layer['required'] : 'false';
				$data_attr_ul .= ' data-required="'. esc_attr( $required ) .'"';

				$top_layer_uid = isset( $top_layer['uid'] ) ? $top_layer['uid'] : '';
				$data_attr_ul .= ' data-parent-uid="'. esc_attr( $top_layer_uid ) .'"';

				$optionset = isset( $top_layer['name'] ) ? str_replace( ' ', '-', $top_layer['name'] ) : 'default';	

				$icon = $icon_class = '';
				if( isset( $top_layer['icon'] ) && !empty( $top_layer['icon'] ) ) {
					$icon = pwc_get_image_by_id( null, null, $top_layer['icon'], 1, 0 );
					$icon = ( $icon ) ? '<img src="' . $icon . '" alt="" class="pwc-parent-icon">' : '';
					$icon_class = ( $icon ) ? ' pwc-icon-added' : '';
				}

				$html .= '<div class="pwc-controls-list-sec main-sec' . esc_attr( $hide_child . $icon_class ) . '">';

				$html .= '<h2 class="pwc-layer-title">' . $icon . esc_html( $top_layer['name'] ) . '</h2>';			
				
				$html .= '<ul class="pwc-controls-img-list"' . $data_attr_ul . ' data-optionset="' . $optionset . '">';

				if( isset( $top_layer['values'] ) && ! empty( $top_layer['values'] ) ) {

					foreach ( $top_layer['values'] as $sub_key => $sub_layer ) {

						if( isset( $sub_layer['hide_control'] ) && 'true' == $sub_layer['hide_control'] ) {
							continue;
						}
				
						// get control item (li)
						$html .= $this->get_control_item( $sub_layer, $sub_key, $data_attr_li );
						
					}

				}

			} else {

				if( isset( $top_layer['hide_control'] ) && 'true' == $top_layer['hide_control'] ) {
					continue;
				}
				
				$html .= $this->get_control_item( $top_layer, $top_key, $data_attr_li );

			}

			if( empty( $parent ) ) {
				$html .= '</ul>';
				$html .= '</div>'; // end of pwc-controls-list-sec main-sec
			}

		}

		if( ! empty( $parent ) ) {
			$html .= '</ul>';
			$html .= '</div>'; // end of $PARENT pwc-controls-list-sec main-sec
		}

		return $html;

	}

	/**
	 * [get_control_item description]
	 * @param  array $value
	 * @param  string $key
	 * @param  string $data_attr_li
	 * @return string
	 */
	public function get_control_item( $value, $key, $data_attr_li = '' ) {

		$total_views = pwc_get_meta_value( $this->id, '_pwc_views', array( 'front'=> esc_html__( 'Front', 'product-woo-configurator' ) ) );
		$icon_type = get_option( 'pwc_icon_type', 'round' );
		$icon_width = get_option( 'pwc_icon_width', '20' );
		$icon_height = get_option( 'pwc_icon_height', '20' );

		$custom_icon_size_class = ( ( '20' != $icon_width ) || ( '20' != $icon_height ) ) ? ' custom-icon-size' : '';

		$html = '';

		if( isset( $value['values'] ) && ! empty( $value['values'] ) ) {
			$data_attr_li .= ' data-open-optionset="'. $value['name'] .'"';
		}

		$control_class[] = 'pwc-controls-list-img';

		// set current class by setting active item
		if( ( !empty( $this->active_item ) && in_array( $key, $this->active_item ) ) ) {
			$control_class[] = 'current';
		} else if( empty( $this->active_item ) && isset( $value['active'] ) && 'true' == $value['active'] ) {
			$control_class[] = 'current';
		} else {
			$control_class[] = '';
		}

		if( empty( $value['label'] ) ){
			$control_class[] = 'pwc-icon-'.$icon_type . $custom_icon_size_class;
		}
		else {
			$control_class[] = 'pwc-label-type';
		}

		$html .= "<li " . $data_attr_li . " data-key='". esc_attr( $key ) ."' data-uid='". esc_attr( $value['uid'] ) ."' class='". esc_attr( implode( ' ', $control_class ) ) ."'";

			// Title
			if( isset( $value['name'] ) && !empty( $value['name'] ) ) {
				$html .= "data-text='". esc_attr( $value['name'] ) ."'";
			}

			$image_found = false;

			foreach ( $total_views as $preview_key => $screen ) {

				if( isset( $value[$preview_key]['image'] ) ) {

					$data_attr['src']   = isset( $value[$preview_key]['image'] ) ? esc_url( pwc_get_image_by_id( 'full', 'full', $value[$preview_key]['image'], 1, 0 ) ) : '';

					$data_attr['width'] = isset( $value[$preview_key]['width'] ) ? $value[$preview_key]['width'] : '';

					$data_attr['height'] = isset( $value[$preview_key]['height'] ) ? $value[$preview_key]['height'] : '';

					$data_attr['pos_x'] = isset( $value[$preview_key]['pos_x'] ) ? $value[$preview_key]['pos_x'] : '';

					$data_attr['pos_y'] = isset( $value[$preview_key]['pos_y'] ) ? $value[$preview_key]['pos_y'] : '';

					$data_attr['align_h'] = isset( $value[$preview_key]['align_h'] ) ? $value[$preview_key]['align_h'] : '';

					$data_attr['align_v'] = isset( $value[$preview_key]['align_v'] ) ? $value[$preview_key]['align_v'] : '';

					$data_attr['z_index'] = isset( $value[$preview_key]['z_index'] ) ? $value[$preview_key]['z_index'] : '';

					if( !empty( $data_attr['src'] ) ) {
						$html .= " data-" . $preview_key . "='". json_encode( $data_attr ) ."'";
					}

					$image_found = true;

				}				

			}

			if( $image_found ) {
				$html .= ' data-changeimage ';
			}

			$price = isset( $value['price'] ) && ! empty( $value['price'] ) ? $value['price'] : '0';			
			$html .= "data-price='". esc_attr( $price ) ."'";
			$build_price = wp_kses( wc_price( $price ), array() );

		$html .= ">"; // li open tag

			// Icon
			if( isset( $value['icon'] ) && !empty( $value['icon'] ) ) {
				$html .= pwc_get_image_by_id( (int) $icon_width, (int) $icon_height, $value['icon'], 0, 0 );
			}

			// Title
			if( empty( $value['label'] ) && isset( $value['name'] ) && !empty( $value['name'] ) ) {
				$html .= '<p class="pwc-icon-hover-text"><span class="pwc-icon-hover-inner"><span class="config-hover-title">' . esc_html( $value['name'] ) . '</span> <span class="config-hover-price">+ ' . esc_html( $build_price ) . '</span></span></p>';
			}
			else if( ! empty( $value['label'] ) && isset( $value['name'] ) && !empty( $value['name'] ) ) {
				$html .= '<p class="pwc-icon-label"><span class="pwc-icon-label-inner"><span class="config-hover-title">' . esc_html( $value['label'] ) . '</span> <span class="config-hover-price">' . esc_html( $build_price ) .'</span></span></p>';
			}

			$html .= '<span class="li-loader"></span>';


			if( isset( $value['values'] ) && ! empty( $value['values'] ) ) {

				$parent = array( 
					'parent_name' => $value['name'], 
					'parent_key'  => $key,
					'parent_uid'  => $value['uid']
				);

				if( isset( $value['multiple'] ) ) {
					$parent['multiple'] = $value['multiple'];
				}

				$html .= $this->build_controls_group_html( $value['values'], $parent );

			} else {
				$parent = array();
			}
			
		$html .= "</li>";

		return $html;

	}

	/**
	 * Get components from meta and save in property from future use
	 * @param  int $id 	post id
	 */
	public function iniatialize_shortcode( $id ) {

		wp_enqueue_script( 'pwc-configurator-plugin-js' );
		wp_enqueue_script( 'pwc-configurator-script-js' );

		$this->cs = array_map( 'array_filter', pwc_array_filter_recursive( pwc_get_meta_value( $id, 'components', array() ) ) );
		$this->cs = apply_filters( 'pwc_components_filter', $this->cs );

	}

	/**
	 * Returns active array from components
	 * @return array active_array
	 */
	public function get_active_array() {

		if( ! empty( $this->active_on_load ) && $this->id == $this->temp_id ) {
			return $this->active_on_load;
		}

		// set temp values
		$this->ancestor_uid = false;
		$this->level = -1;
		$this->active_array = array();

		$this->build_active_array( $this->cs, $this->get_active_item() );

		$active_array = $this->active_array;

		$this->active_on_load = $active_array;

		$this->temp_id = $this->id;

		// reset
		$this->active_array = array();
		$this->level = -1;
		$this->ancestor_uid = false;

		return $active_array;
	}

	/**
	 * Build Active Array from components
	 * @param  array $components  
	 * @param  string $active_item 
	 * @param  array  $parent      
	 */
	public function build_active_array( $components, $active_item = '', $parent = array(), $array_key = array() ) {

		$this->level++;

		foreach ( $components as $key => $value ) {

			if( $this->level == 0 ) {
				$this->ancestor_uid = $value['uid'];
			}

			$multiple = isset( $value['multiple'] ) ? $value['multiple'] : 'false';
			$required = isset( $value['required'] ) ? $value['required'] : 'false';

			$array_key_length = count( $array_key );
			$array_key[$this->level - 1 ] = array( 
					'parent_name' => $value['name'], 
					'parent_key'  => $key,
					'parent_uid'  => $value['uid'],
					'multiple'    => $multiple,
					'required'    => $required,
				);			

			if( ( isset( $value['active'] ) && 'true' == $value['active'] && empty( $active_item ) ) || ( $active_item && in_array( $key, $active_item ) ) ) {

				$temp = array();
				
				$temp = $value;
				unset( $temp['values'] );

				if( $parent && !empty( $parent ) ) {
					$temp['parent'] = $parent;
				}

				$this->active_key .= $key .', ';

				if( ( isset( $value['active'] ) && 'true' == $value['active'] ) ) {
					$this->defaut_active_key .= $key . ', ';
				}

				$temp['ancestor_uid'] = $this->ancestor_uid;

				$this->active_array[$key] = $temp;

			}

			if( isset( $value['values'] ) && !empty( $value['values'] ) ) {				

				$parent = isset( $array_key[ $this->level - 1 ] ) ? $array_key[ $this->level - 1 ] : array();

				$this->build_active_array( $value['values'] , $this->active_item, $parent, $array_key );

			}

		}

		array_pop( $array_key );

		$this->level--;

	}

	public function get_key_html() {

		// Remove duplicate string
		if( ! empty( $this->default_item ) ) {
			$key_string = $this->default_item;
		}
		else {
			$key_string = pwc_remove_duplicate_string( $this->active_key );
		}

		if( !empty( $this->defaut_active_key ) ) {
			$reset_key_string = pwc_remove_duplicate_string( $this->defaut_active_key );
		} else {
			$reset_key_string = '';
		}
		
		$html  = '<input class="default-active-key" name="active-key" type="hidden" value="'. esc_attr( $key_string ) .'">';
		$html .= '<input class="reset-active-key" type="hidden" value="'. esc_attr( $reset_key_string ) .'">';

		return $html;
	}

}

function PWCSE() {
	return new PWCSE();
}
