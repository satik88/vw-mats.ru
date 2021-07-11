<?php
/**
 * WooCommerce filters and actions helps to change default value
 * to configurator value
 *
 * @class     PWC_Woo_Actions_Filters
 * @version   1.0
 * @author    Innwithemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PWC_Woo_Actions_Filters Class.
 */

if ( ! class_exists( 'PWC_Woo_Actions_Filters' ) ) {

	class PWC_Woo_Actions_Filters {

		public $first_view          = '';
		private $active_array       = '';
		protected static $_instance = null;

		/**
		 * Main Helper Instance.
		 *
		 * Ensures only one instance of Helper is loaded or can be loaded.
		 *
		 * @static
		 * @return PWC - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Hook in methods.
		 */
		public function __construct() {

			// Backend
			add_action( 'woocommerce_product_options_general_product_data', array( $this, 'get_configurator_post' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'get_configurator_post_save' ) );

			// Front end
			add_filter( 'woocommerce_is_purchasable', array( $this, 'is_purchasable' ), 10, 2 );
			add_action( 'woocommerce_after_cart_item_name', array( $this, 'add_configs_after_cart_item_name' ), 10, 2 ); // add extra items below cart item name
			add_filter( 'wc_get_template_part', array( $this, 'filter_wc_get_template_part' ), 10, 3 );  // Load woo templates from plugin

			add_action( 'woocommerce_before_calculate_totals', array( $this, 'add_custom_price' ), 10, 1 ); // Change cart price

			add_filter( 'woocommerce_cart_item_price', array( $this, 'cart_item_price_filter' ), 10, 3 ); // Modify mini cart item price.

			add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_items_meta' ), 10, 3 ); // woocommerce add order meta
			add_action( 'woocommerce_cart_item_thumbnail', array( $this, 'adjust_image_to_config' ), 99, 3 ); // Change cart thumbnail

			add_action( 'wp_loaded', array( $this, 'add_to_cart' ), 20 );

		}

		public function get_configurator_post() {

			global $woocommerce, $post;
			$post_id = $post->ID;

			echo '<div class="options_group show_if_simple">';

				// Create a field to product metabox
				woocommerce_wp_select(
					array(
						'id'          => '_configurator_post_id',
						'label'       => esc_html__( 'Configurator', 'product-woo-configurator' ),
						'placeholder' => '',
						'desc_tip'    => 'false',
						'description' => '',
						'options'     => pwc_configurator_posts(),
						'type'        => 'select',
					)
				);

				woocommerce_wp_select(
					array(
						'id'          => '_configurator_style',
						'label'       => esc_html__( 'Configurator Style', 'product-woo-configurator' ),
						'placeholder' => '',
						'desc_tip'    => 'false',
						'description' => '',
						'options'     => pwc_get_styles(),
						'type'        => 'select',
					)
				);

			echo '</div>'; // .options_group

		}

		public function get_configurator_post_save( $post_id ) {

			$config_id = isset( $_POST['_configurator_post_id'] ) ? $_POST['_configurator_post_id'] : '';

			if ( $config_id ) {
				update_post_meta( $post_id, '_configurator_post_id', esc_attr( $config_id ) );
			} else {
				delete_post_meta( $post_id, '_configurator_post_id' );
			}

			$style = isset( $_POST['_configurator_style'] ) ? $_POST['_configurator_style'] : '';

			if ( $style ) {
				update_post_meta( $post_id, '_configurator_style', esc_attr( $style ) );
			} else {
				delete_post_meta( $post_id, '_configurator_style' );
			}

		}

		/**
		 * [is_purchasable Set is_purchasable as true if it product has configuration]
		 *
		 * @param  bool   $is_purchasable
		 * @param  object $object
		 * @return bool
		 */

		public function is_purchasable( $is_purchasable, $object ) {

			$config_id = get_post_meta( $object->get_id(), '_configurator_post_id', true );

			if ( $config_id || $is_purchasable ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * [add_configs_after_cart_item_name Change cart title]
		 *
		 * @param string $link_text
		 * @param array  $cart_item
		 */

		public function add_configs_after_cart_item_name( $cart_item, $cart_item_key ) {

			$output = '';

			// base price
			$base_price = isset( $cart_item['pwc_base_price'] ) ? $cart_item['pwc_base_price'] : '';

			$output .= '<p class="pwc-inner pwc-child"><strong>' . esc_html__( 'Base Price', 'product-woo-configurator' ) . '</strong> <span>- (' . pwc_apply_currency_position( $base_price ) . ')</span></p>';

			// user configuration
			$active_array = isset( $cart_item['user_config'] ) && ! empty( $cart_item['user_config'] ) ? $cart_item['user_config'] : '';

			if ( $active_array && ! empty( $active_array ) ) {

				foreach ( $active_array as $key => $value ) {

					$value['price']        = ( $value['price'] == '' ) ? '0' : $value['price'];
					$value['hide_control'] = isset( $value['hide_control'] ) ? $value['hide_control'] : 'false';

					if ( 'false' == $value['hide_control'] || '' == $value['hide_control'] ) {
						if ( isset( $value['parent'] ) && $value['parent'] ) {
							$output .= '<p class="pwc-inner pwc-child">' . sprintf( '<strong>%s</strong> - %s <span>(%s)</span>', $value['parent']['parent_name'], $value['name'], pwc_apply_currency_position( $value['price'] ) ) . '</p>';
						} else {
							$output .= '<h4 class="pwc-title pwc-parent">' . sprintf( '%s <span>(%s)</span>', $value['name'], pwc_apply_currency_position( $value['price'] ) ) . '</h4>';
						}
					}
				}
			}

			echo $output;

		}

		/**
		 * [add_custom_price Change cart custom price if configuration had price]
		 *
		 * @param object $cart_object
		 */

		public function add_custom_price( $cart_object ) {

			// This is necessary for WC 3.0+
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}

			foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {

				// user configuration
				$active_item = isset( $value['user_config'] ) && ! empty( $value['user_config'] ) ? $value['user_config'] : '';

				// base price
				$base_price = isset( $value['pwc_base_price'] ) && ! empty( $value['pwc_base_price'] ) ? $value['pwc_base_price'] : '0';

				if ( $active_item ) {

					$custom_price = $this->get_configurated_price( $active_item );

					$value['data']->set_price( floatval( $base_price ) + floatval( $custom_price ) );

				}
			}

		}

		/**
		 * Modify cart item price.
		 *
		 * @param string $price Price html.
		 * @param array  $cart_item Cart order item array.
		 * @param string $cart_item_key Order item generated key.
		 * @return string
		 */
		public function cart_item_price_filter( $price = '', $cart_item = array(), $cart_item_key = '' ) {

			$product_id = isset( $cart_item['product_id'] ) ? $cart_item['product_id'] : 0;

			if ( ! $product_id ) {
				return;
			}

			$product = wc_get_product( $product_id );

			// User configuration.
			$active_item = isset( $cart_item['user_config'] ) && ! empty( $cart_item['user_config'] ) ? $cart_item['user_config'] : '';

			// Base price.
			$base_price = isset( $cart_item['pwc_base_price'] ) && ! empty( $cart_item['pwc_base_price'] ) ? $cart_item['pwc_base_price'] : '0';

			if ( $active_item ) {

				$custom_price = $this->get_configurated_price( $active_item );
				$custom_price = floatval( $base_price ) + floatval( $custom_price );

				return $this->calculated_price( $product, $custom_price );

			}

			return $price;

		}

		/**
		 * Calculate price with Inclusive/Exclusive tax.
		 *
		 * @param object  $product Product Object.
		 * @param integer $price Custom Price.
		 * @return string
		 */
		public function calculated_price( $product = 0, $price = 0 ) {

			$args['price'] = $price;

			if ( WC()->cart->display_prices_including_tax() ) {
				$product_price = wc_get_price_including_tax( $product, $args );
			} else {
				$product_price = wc_get_price_excluding_tax( $product, $args );
			}

			return apply_filters( 'woocommerce_cart_product_price', wc_price( $product_price ), $product );

		}

		/**
		 * [get_configurated_price Build custom price if configuration had price]
		 *
		 * @param  array $active_array
		 * @return integer
		 */

		public function get_configurated_price( $active_array ) {

			$price = 0;

			if ( $active_array ) {
				foreach ( $active_array as $key => $value ) {

					if ( $value['price'] && ( 'false' == $value['hide_control'] || '' == $value['hide_control'] ) ) {
						$price += floatval( $value['price'] );
					}
				}
			}

			return $price;

		}

		/**
		 * [add_order_items_meta Pass custom values to the order meta]
		 *
		 * @param string $item_id
		 * @param array  $values
		 * @param string $cart_item_key
		 */

		public function add_order_items_meta( $item_id, $values, $cart_item_key ) {

			// user configuration
			$active_array = isset( $values['user_config'] ) && ! empty( $values['user_config'] ) ? $values['user_config'] : '';

			if ( $active_array && ! empty( $active_array ) ) {

				wc_add_order_item_meta( $item_id, 'Configuration', '' );
				foreach ( $active_array as $value ) {

					$value['price']        = ( $value['price'] == '' ) ? '0' : $value['price'];
					$value['hide_control'] = isset( $value['hide_control'] ) ? $value['hide_control'] : 'false';

					if ( 'false' == $value['hide_control'] || '' == $value['hide_control'] ) {
						if ( $value['price'] != 0 ) {
							wc_add_order_item_meta( $item_id, $value['parent']['parent_name'], $value['name'] . ' <span>(' . pwc_apply_currency_position( $value['price'] ) . ')</span>' );
						} else {
							wc_add_order_item_meta( $item_id, $value['parent']['parent_name'], $value['name'] );
						}
					}
				}
			}

		}

		/**
		 * [adjust_image_to_config Change woocomerce cart thumbnail]
		 *
		 * @param  string $product_image default woocommerce cart thumbnail
		 * @param  array  $values
		 * @param  string $cart_item_key
		 * @return string
		 */

		public function adjust_image_to_config( $product_image, $values, $cart_item_key ) {
			// user configuration
			$output       = '';
			$active_array = isset( $values['user_config'] ) && ! empty( $values['user_config'] ) ? $values['user_config'] : '';
			$views        = isset( $values['views'] ) && ! empty( $values['views'] ) ? $values['views'] : array( 'front' => esc_html__( 'Front', 'product-woo-configurator' ) );

			$first_view = key( $views );

			if ( $active_array && ! empty( $active_array ) ) {

				$output .= '<div class="pwc-config-image">';

				foreach ( $active_array as $value ) {

					if ( $value[ $first_view ]['image'] ) {
						$src     = pwc_get_image_by_id( 'full', 'full', $value[ $first_view ]['image'], 1, 0, 0 );
						$width   = $value[ $first_view ]['width'];
						$height  = $value[ $first_view ]['height'];
						$pos_x   = $value[ $first_view ]['pos_x'];
						$pos_y   = $value[ $first_view ]['pos_y'];
						$align_h = $value[ $first_view ]['align_h'];
						$align_v = $value[ $first_view ]['align_v'];
						$z_index = $value[ $first_view ]['z_index'];

						$data = ' data-align-h="' . esc_attr( $align_h ) . '" data-align-v="' . esc_attr( $align_v ) . '" data-offset-x="' . esc_attr( $pos_x ) . '" data-offset-y="' . esc_attr( $pos_y ) . '" data-width="' . esc_attr( $width ) . '" data-height="' . esc_attr( $height ) . '" data-z-index="' . esc_attr( $z_index ) . '"';

						$output .= '<img class="subset" src="' . esc_url( $src ) . '" ' . $data . ' alt="">';
					}
				}
				$output .= '</div>';
			} else {
				$output = $product_image;
			}

			return $output;

		}

		public function add_to_cart( $url = false ) {

			if ( empty( $_REQUEST['pwc-add-to-cart'] ) || ! is_numeric( $_REQUEST['pwc-add-to-cart'] ) ) {
				return;
			}

			$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['pwc-add-to-cart'] ) );
			$was_added_to_cart = false;
			$adding_to_cart    = wc_get_product( $product_id );

			if ( ! $adding_to_cart ) {
				return;
			}

			$config_id = absint( get_post_meta( $product_id, '_configurator_post_id', true ) );
			$views     = pwc_get_meta_value( $config_id, '_pwc_views', array( 'front' => esc_html__( 'Front', 'product-woo-configurator' ) ) );

			$this->first_view = key( $views );

			if ( ! $config_id ) {
				return;
			}

			$active_item = ( isset( $_REQUEST['active-key'] ) && ! empty( $_REQUEST['active-key'] ) ) ? array_unique( explode( ',', $_REQUEST['active-key'] ) ) : array();

			if ( ! empty( $active_item ) ) {

				// configurator data
				$cs = pwc_get_meta_value( $config_id, 'components', array() );

				$active_array = $this->get_active_array( $cs, $active_item );

			} else {

				$active_array = array();

			}

			// base price
			$base_price = pwc_get_meta_value( $config_id, '_pwc_base_price', '0' );

			// add to cart
			// todo: check active_key is equal, if equal then add count or add new.
			if ( function_exists( 'WC' ) && $product_id ) {

				$quantity          = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

				if ( $passed_validation && WC()->cart->add_to_cart(
					$product_id,
					$quantity,
					0,
					array(),
					array(
						'user_config'    => $active_array,
						'config_id'      => $config_id,
						'pwc_base_price' => $base_price,
						'views'          => $views,
					)
				) !== false ) {
					wc_add_to_cart_message( array( $product_id => $quantity ), true );
					$was_added_to_cart = true;
				}

				// If we added the product to the cart we can now optionally do a redirect.
				if ( $was_added_to_cart && wc_notice_count( 'error' ) === 0 ) {
					// If has custom URL redirect there
					if ( $url = apply_filters( 'woocommerce_add_to_cart_redirect', $url ) ) {
						wp_safe_redirect( $url );
						exit;
					} elseif ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
						wp_safe_redirect( wc_get_cart_url() );
						exit;
					}
				}
			}

		}

		public function filter_wc_get_template_part( $template, $slug, $name ) {

			$config_id = get_post_meta( get_the_id(), '_configurator_post_id', true );

			if ( is_product() && $config_id && defined( 'PWC_VERSION' ) && 'content' == $slug && 'single-product' == $name ) {
				$template = pwc_get_template_part( 'content-single-configurator' );
			}

			return $template;

		}

		public function get_active_array( $cs, $active_item ) {

			$this->active_array = '';
			$this->build_active_array( $cs, $active_item );

			$active_array       = $this->active_array;
			$this->active_array = '';

			return $active_array;
		}

		public function build_active_array( $components, $active_item, $parent = '' ) {

			foreach ( $components as $key => $value ) {

				if ( $active_item && in_array( $key, $active_item ) ) {
					$temp = array();

					$temp['uid']               = $value['uid'];
					$temp['name']              = $value['name'];
					$temp['price']             = $value['price'];
					$temp['hide_control']      = $value['hide_control'];
					$temp[ $this->first_view ] = $value[ $this->first_view ];

					if ( $parent && ! empty( $parent ) ) {
						$temp['parent'] = $parent;
					}

					if ( empty( $this->active_array ) ) {
						$this->active_array = array();
					}

					$this->active_array[ $key ] = $temp;

				}

				if ( isset( $value['values'] ) && ! empty( $value['values'] ) ) {

					$multiple = isset( $value['multiple'] ) ? $value['multiple'] : 'false';
					$required = isset( $value['required'] ) ? $value['required'] : 'false';

					$parent = array(
						'parent_name' => $value['name'],
						'parent_key'  => $key,
						'parent_uid'  => $value['uid'],
						'multiple'    => $multiple,
						'required'    => $required,
					);

					$this->build_active_array( $value['values'], $active_item, $parent );

				} else {
					$temp['parent'] = false;
				}
			}

		}

	}

}

new PWC_Woo_Actions_Filters();

function PWCAF() {
	return PWC_Woo_Actions_Filters::instance();
}
