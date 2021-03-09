<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class wfob_AJAX_Controller
 * Handles All the request came from front end or the backend
 */
abstract class WFOB_AJAX_Controller {
	public static function init() {
		/**
		 * Backend AJAX actions
		 */
		if ( is_admin() ) {
			self::handle_admin_ajax();
		}
		self::handle_public_ajax();
	}

	public static function handle_admin_ajax() {
		add_action( 'wp_ajax_wfob_global_save_settings', [ __CLASS__, 'save_global_settings' ] );
		add_action( 'wp_ajax_wfob_create_bump', array( __CLASS__, 'create_bump' ) );
		add_action( 'wp_ajax_wfob_add_product', array( __CLASS__, 'add_product' ) );
		add_action( 'wp_ajax_wfob_remove_product', array( __CLASS__, 'remove_product' ) );
		add_action( 'wp_ajax_wfob_product_search', array( __CLASS__, 'product_search' ) );
		add_action( 'wp_ajax_wfob_save_products', array( __CLASS__, 'save_products' ) );
		add_action( 'wp_ajax_wfob_update_rules', array( __CLASS__, 'update_rules' ) );
		add_action( 'wp_ajax_wfob_save_design', array( __CLASS__, 'save_design' ) );
		add_action( 'wp_ajax_wfob_save_settings', array( __CLASS__, 'save_settings' ) );

		add_action( 'wp_ajax_wfob_update_page_status', [ __CLASS__, 'update_page_status' ] );
		add_action( 'wp_ajax_wfob_make_wpml_duplicate', [ __CLASS__, 'make_wpml_duplicate' ] );
	}

	public static function handle_public_ajax() {
		$endpoints = self::get_available_public_endpoints();
		foreach ( $endpoints as $action => $function ) {
			if ( method_exists( __CLASS__, $function ) ) {
				add_action( 'wc_ajax_' . $action, [ __CLASS__, $function ] );
			}
		}
	}

	public static function get_available_public_endpoints() {
		$endpoints = [
			'wfob_add_order_bump'    => 'add_order_bump_order',
			'wfob_remove_order_bump' => 'remove_order_bump',
			'wfob_quick_view_ajax'   => 'wf_quick_view_ajax',
		];

		return $endpoints;
	}

	public static function get_public_endpoints() {
		$endpoints        = [];
		$public_endpoints = self::get_available_public_endpoints();
		if ( count( $public_endpoints ) > 0 ) {
			foreach ( $public_endpoints as $key => $function ) {
				$endpoints[ $key ] = WC_AJAX::get_endpoint( $key );
			}
		}

		return $endpoints;
	}

	public static function create_bump() {
		self::check_nonce();
		$resp = array(
			'msg'    => 'Checkout Page not found',
			'status' => false,
		);
		if ( isset( $_POST['wfob_name'] ) && $_POST['wfob_name'] != '' ) {
			$post                 = array();
			$post['post_title']   = $_POST['wfob_name'];
			$post['post_type']    = WFOB_Common::get_bump_post_type_slug();
			$post['post_status']  = 'publish';
			$post['post_name']    = isset( $_POST['post_name'] ) ? $_POST['post_name'] : $post['post_title'];
			$post['post_content'] = '';
			if ( ! empty( $post ) ) {
				if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 ) {
					$wfob_id = absint( $_POST['wfob_id'] );
					$status  = wp_update_post( [
						'ID'         => $wfob_id,
						'post_title' => $post['post_title'],
						'post_name'  => $post['post_name'],
					] );
					if ( ! is_wp_error( $status ) ) {
						$resp['status']       = true;
						$resp['new_url']      = get_the_permalink( $wfob_id );
						$resp['redirect_url'] = '#';
						$resp['msg']          = __( 'Order bump Successfully Update', 'woofunnels-order-bump' );

					}
					self::send_resp( $resp );
				}

				$menu_order = WFOB_Common::get_highest_menu_order();

				$post['menu_order'] = $menu_order + 1;
				$wfob_id            = wp_insert_post( $post );
				if ( $wfob_id !== 0 && ! is_wp_error( $wfob_id ) ) {
					$resp['status']       = true;
					$resp['redirect_url'] = add_query_arg( array(
						'page'    => 'wfob',
						'section' => 'rules',
						'wfob_id' => $wfob_id,
					), admin_url( 'admin.php' ) );
					$resp['msg']          = 'Order bump Successfully Created';
				} else {
					$resp['redirect_url'] = '#';
					$resp['msg']          = 'Order bump Successfully Updated';
				}
			}
		}

		self::send_resp( $resp );
	}

	public static function check_nonce() {
		$rsp = [
			'status' => 'false',
			'msg'    => 'Invalid Call',
		];
		if ( ! isset( $_REQUEST['wfob_nonce'] ) || ! wp_verify_nonce( $_REQUEST['wfob_nonce'], 'wfob_secure_key' ) ) {
			wp_send_json( $rsp );
		}
	}

	public static function send_resp( $data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = [];
		}
		$data['nonce'] = wp_create_nonce( 'wfob_secure_key' );
		if ( isset( $_REQUEST['wfob_id'] ) && $_REQUEST['wfob_id'] > 0 ) {
			WFOB_Common::delete_transient( $_REQUEST['wfob_id'] );
		}

		if ( function_exists( 'WC' ) && WC()->cart instanceof WC_Cart && ! is_null( WC()->cart ) ) {
			$data['cart_total'] = WC()->cart->get_total( 'edit' );
		}
		wp_send_json( $data );
	}

	public static function save_checkout_products() {
		self::check_nonce();
		$resp = array(
			'msg'      => '',
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['wfob_id'] ) && count( $_POST['products'] ) > 0 ) {
			$wfob_id          = $_POST['wfob_id'];
			$products         = $_POST['products'];
			$existing_product = WFOB_Common::get_bump_products( $wfob_id );
			wp_send_json( $existing_product );
			foreach ( $products as $pid ) {
				$unique_id = uniqid( 'wfob' );
				$product   = wc_get_product( $pid );
				if ( $product instanceof WC_Product ) {
					$image_id                       = $product->get_image_id();
					$default                        = WFOB_Common::get_default_product_config();
					$default['id']                  = $product->get_id();
					$default['title']               = $product->get_title();
					$default['image']               = wp_get_attachment_image_src( $image_id )[0];
					$default['type']                = $product->get_type();
					$default['price']               = $product->get_price();
					$default['regular_price']       = $product->get_regular_price();
					$default['sale_price']          = $product->get_sale_price();
					$resp['products'][ $unique_id ] = $default;
					$existing_product[ $unique_id ] = $pid;
				}
			}
		}
		self::send_resp( $resp );
	}

	public static function product_search( $term = false, $return = false ) {
		self::check_nonce();
		$term = wc_clean( empty( $term ) ? stripslashes( $_POST['term'] ) : $term );
		if ( empty( $term ) ) {
			wp_die();
		}
		$variations = true;
		$ids        = WFOB_Common::search_products( $term, $variations );
		/**
		 * Products types that are allowed in the offers
		 */
		$allowed_types   = apply_filters( 'wfob_offer_product_types', array(
			'simple',
			'variable',
			'course',
			'variation',
			'subscription',
			'variable-subscription',
			'subscription_variation',
			'bundle',
			'yith_bundle',
			'woosb',
		) );
		$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_editable' );
		$product_objects = array_filter( $product_objects, function ( $arr ) use ( $allowed_types ) {
			return $arr && is_a( $arr, 'WC_Product' ) && in_array( $arr->get_type(), $allowed_types );
		} );
		$products        = array();
		/**
		 * @var $product_object WC_Product;
		 */
		foreach ( $product_objects as $product_object ) {
			$products[] = array(
				'id'      => $product_object->get_id(),
				'product' => rawurldecode( WFOB_Common::get_formatted_product_name( $product_object ) ),
			);
		}
		wp_send_json( apply_filters( 'wfob_woocommerce_json_search_found_products', $products ) );
	}

	public static function add_product() {
		self::check_nonce();
		$resp = array(
			'msg'      => '',
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['wfob_id'] ) && count( $_POST['products'] ) > 0 ) {
			$wfob_id          = absint( $_POST['wfob_id'] );
			$products         = $_POST['products'];
			$existing_product = WFOB_Common::get_bump_products( $wfob_id );
			foreach ( $products as $pid ) {
				$unique_id = uniqid( 'wfob_' );
				$product   = wc_get_product( $pid );
				if ( $product instanceof WC_Product ) {
					$product_type = $product->get_type();
					$image_id     = $product->get_image_id();
					$default      = WFOB_Common::get_default_product_config();

					$product_image_url = '';
					$images            = wp_get_attachment_image_src( $image_id );
					if ( is_array( $images ) && count( $images ) > 0 ) {
						$product_image_url = wp_get_attachment_image_src( $image_id )[0];
					}
					$default['image'] = apply_filters( 'wfob_product_image', $product_image_url, $product );
					if ( '' == $default['image'] ) {
						$default['image'] = WFOB_PLUGIN_URL . '/admin/assets/img/product_default_icon.jpg';
					}

					$default['type']              = $product_type;
					$default['id']                = $product->get_id();
					$default['stock']             = $product->is_in_stock();
					$default['parent_product_id'] = $product->get_parent_id();
					$default['title']             = $product->get_title();
					if ( in_array( $product_type, WFOB_Common::get_variable_product_type() ) ) {
						$default['variable'] = 'yes';
						$default['price']    = $product->get_price_html();
					} else {
						if ( in_array( $product_type, WFOB_Common::get_variation_product_type() ) ) {
							$default['title'] = $product->get_name();
						}
						$row_data                 = $product->get_data();
						$sale_price               = $row_data['sale_price'];
						$default['price']         = wc_price( $row_data['price'] );
						$default['regular_price'] = wc_price( $row_data['regular_price'] );
						if ( '' != $sale_price ) {
							$default['sale_price'] = wc_price( $sale_price );
						}
					}
					$resp['products'][ $unique_id ] = $default;
					$default                        = WFOB_Common::remove_product_keys( $default );
					$existing_product[ $unique_id ] = $default;
				}
			}

			WFOB_Common::update_page_product( $wfob_id, $existing_product );
			if ( count( $resp['products'] ) > 0 ) {
				$resp['status'] = true;
			}
		}
		self::send_resp( $resp );
	}

	public static function remove_product() {
		self::check_nonce();
		$resp = array(
			'status' => false,
			'msg'    => '',
		);
		if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 && isset( $_POST['product_key'] ) && $_POST['product_key'] != '' ) {
			$wfob_id          = absint( $_POST['wfob_id'] );
			$product_key      = trim( $_POST['product_key'] );
			$existing_product = WFOB_Common::get_bump_products( $wfob_id );
			if ( isset( $existing_product[ $product_key ] ) ) {
				unset( $existing_product[ $product_key ] );
				WFOB_Common::update_page_product( $wfob_id, $existing_product );
				$resp['status'] = true;
				$resp['msg']    = __( 'Product removed from checkout page' );
			}
		}
		self::send_resp( $resp );
	}

	public static function save_products() {
		self::check_nonce();
		$resp = array(
			'msg'      => __( 'Product(s) Saved Successfully', 'woofunnels-order-bump' ),
			'status'   => false,
			'products' => [],
		);
		if ( isset( $_POST['products'] ) && count( $_POST['products'] ) > 0 ) {
			$products = $_POST['products'];
			$wfob_id  = $_POST['wfob_id'];
			foreach ( $products as $key => $val ) {
				if ( isset( $products[ $key ]['variable'] ) && ! isset( $products[ $key ]['default_variation'] ) ) {
					$pro                = WFOB_Common::wc_get_product( $products[ $key ]['id'] );
					$is_found_variation = WFOB_Common::get_default_variation( $pro );
					if ( count( $is_found_variation ) > 0 ) {
						$products[ $key ]['default_variation']      = $is_found_variation['variation_id'];
						$products[ $key ]['default_variation_attr'] = $is_found_variation['attributes'];
					}
				}
				$products[ $key ] = WFOB_Common::remove_product_keys( $products[ $key ] );
			}
			WFOB_Common::update_page_product( $wfob_id, $products );
			$resp['status'] = true;
		}
		self::send_resp( $resp );
	}

	public static function update_rules() {
		self::check_nonce();
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		$data = array();

		if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 && isset( $_POST['wfob_rule'] ) && ! empty( $_POST['wfob_rule'] ) > 0 ) {
			$bump_id = $_POST['wfob_id'];
			$rules   = $_POST['wfob_rule'];
			$post    = get_post( $bump_id );

			if ( ! is_wp_error( $post ) ) {
				WFOB_Common::update_bump_rules( $bump_id, $rules );
				WFOB_Common::update_bump_time( $bump_id );
				$resp = array(
					'msg'    => __( 'Rules Updated successfuly' ),
					'status' => true,
				);
			}
		}
		self::send_resp( $resp );
	}

	public static function save_design() {
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 && isset( $_POST['settings'] ) && count( $_POST['settings'] ) > 0 ) {

			WFOB_Common::update_design_data( $_POST['wfob_id'], $_POST['settings'] );
			$resp = array(
				'msg'    => __( 'Design Updated successfuly' ),
				'status' => true,
			);
		}
		self::send_resp( $resp );
	}

	public static function save_settings() {
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 && isset( $_POST['settings'] ) && count( $_POST['settings'] ) > 0 ) {

			WFOB_Common::update_setting_data( $_POST['wfob_id'], $_POST['settings'] );
			$resp = array(
				'msg'    => __( 'Settings Updated successfuly' ),
				'status' => true,
			);
		}
		self::send_resp( $resp );
	}

	/**
	 * Save Order Bump global settings
	 */

	public static function save_global_settings() {

		self::check_nonce();
		$resp = [
			'msg'    => __( 'Settings Saved Successfully', 'woofunnels-aero-checkout' ),
			'status' => false,
		];
		if ( isset( $_POST['settings'] ) ) {
			$settings = $_POST['settings'];

			$settings['css']                 = stripslashes_deep( $settings['css'] );
			$old_settings                    = get_option( '_wfacp_global_settings', [] );
			$settings['update_rewrite_slug'] = 'no';
			if ( $settings['rewrite_slug'] !== ( isset( $old_settings['rewrite_slug'] ) ? $old_settings['rewrite_slug'] : '' ) ) {
				$settings['update_rewrite_slug'] = 'yes';
			}
			update_option( '_wfob_global_settings', $settings, true );
			$resp['status'] = true;
		}
		self::send_resp( $resp );
	}

	public static function update_page_status() {
		self::check_nonce();
		$resp = array(
			'msg'    => '',
			'status' => false,
		);
		if ( isset( $_POST['id'] ) && $_POST['id'] > 0 && isset( $_POST['post_status'] ) ) {
			$args    = [
				'ID'          => $_POST['id'],
				'post_status' => 'true' == $_POST['post_status'] ? 'publish' : 'draft',
			];
			$post_id = wp_update_post( $args );

			$resp = array(
				'msg'     => __( 'Order Bump status updated', 'woofunnels-order-bump' ),
				'status'  => true,
				'post_id' => $post_id,
			);
		}
		self::send_resp( $resp );
	}


	public static function add_order_bump_order() {
		self::check_nonce();
		$post    = $_POST;
		$resp    = array(
			'msg'    => '',
			'status' => false,

		);
		$success = '';
		if ( ! isset( $post['wfob_id'] ) || empty( $post['wfob_id'] ) ) {
			self::send_resp( $resp );
		}
		$wfob_id                   = absint( $post['wfob_id'] );
		$available_before_gateways = WC()->payment_gateways()->get_available_payment_gateways();
		WFOB_Common::set_id( absint( $wfob_id ) );
		$products         = WFOB_Common::get_bump_products( absint( $wfob_id ) );
		$product_design   = WFOB_Common::get_design_data_meta( $wfob_id );
		$session_products = WC()->session->get( 'wfob_added_bump_product', [] );
		$product_key      = $post['product_key'];
		do_action( 'wfob_before_add_to_cart', $_REQUEST );
		if ( ! isset( $products[ $product_key ] ) || count( $products[ $product_key ] ) == 0 ) {

			self::send_resp( $resp );
		}

		$remove_item_key = ( isset( $post['remove_item_key'] ) ? trim( $post['remove_item_key'] ) : '' );
		if ( '' !== $remove_item_key ) {
			$item = WC()->cart->get_cart_item( $remove_item_key );
			if ( is_array( $item ) && count( $item ) ) {
				$item_key = $item['_wfob_product_key'];
				if ( isset( $session_products[ $wfob_id ] ) && $session_products[ $wfob_id ] [ $item_key ] ) {
					unset( $session_products[ $wfob_id ] [ $item_key ] );
					WC()->session->set( 'wfob_added_bump_product', $session_products );
				}
				WC()->cart->remove_cart_item( $remove_item_key );
			}
		}

		$product_variation_id = ( isset( $post['variation_id'] ) ? absint( $post['variation_id'] ) : 0 );
		$attributes           = ( isset( $post['attributes'] ) && is_array( $post['attributes'] ) ) ? $post['attributes'] : [];

		$product      = $products[ $product_key ];
		$product_id   = absint( $product['id'] );
		$quantity     = absint( $product['quantity'] );
		$custom_data  = [];
		$variation_id = 0;

		if ( $product['parent_product_id'] && $product['parent_product_id'] > 0 ) {
			$product_id   = absint( $product['parent_product_id'] );
			$variation_id = absint( $product['id'] );
		}

		if ( $product_variation_id > 0 ) {
			$variation_id  = $product_variation_id;
			$product['id'] = $variation_id;
		}

		$product_obj = WFOB_Common::wc_get_product( $product_id );
		if ( ! $product_obj instanceof WC_Product ) {
			self::send_resp( $resp );
		}

		if ( ! $product_obj->is_purchasable() ) {
			self::send_resp( $resp );
		}

		if ( $variation_id > 0 ) {
			// if variation_id found then we fetch attributes of variation from below function
			$is_found_variation = WFOB_Common::get_first_variation( $product_obj, $variation_id );
			if ( count( $attributes ) == 0 ) {
				$attributes = $is_found_variation['attributes'];
			}
		} else {
			if ( isset( $product['variable'] ) ) {
				$variation_id = absint( $product['default_variation'] );
				$attributes   = $product['default_variation_attr'];
			}
		}
		if ( $variation_id > 0 ) {
			$custom_data['wfob_variable_attributes'] = $attributes;
			$product_obj                             = wc_get_product( $variation_id );
		}

		$stock_status = WFOB_Common::check_manage_stock( $product_obj, $quantity );

		if ( false == $stock_status ) {

			$resp['error']  = __( 'Sorry, we do not have enough stock to fulfill your order. Please change quantity and try again. We apologize for any inconvenience caused.', 'woofunnels-order-bump' );
			$resp['status'] = false;
			WFOB_AJAX_Controller::send_resp( $resp );
		}

		if ( isset( $product_design["product_{$product_key}_title"] ) && '' != $product_design["product_{$product_key}_title"] ) {
			$product['title'] = $product_design["product_{$product_key}_title"];
		};

		$custom_data['_wfob_product']             = true;
		$custom_data['_wfob_product_key']         = $product_key;
		$custom_data['_wfob_options']             = $product;
		$custom_data['_wfob_options']['_wfob_id'] = $wfob_id;
		try {


			$success = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $attributes, $custom_data );
			if ( '' !== $success && false !== $success ) {
				$session_products[ $wfob_id ][ $product_key ] = true;
				WC()->session->set( 'wfob_added_bump_product', $session_products );
			} else {
				$resp['error']  = __( 'Sorry, we do not have enough stock to fulfill your order. Please change quantity and try again. We apologize for any inconvenience caused.', 'woofunnels-order-bump' );
				$resp['status'] = false;
			}
			do_action( 'wfob_after_add_to_cart', $_REQUEST );
		} catch ( Exception $e ) {
			$resp['msg']    = $e->getMessage();
			$resp['status'] = false;
			self::send_resp( $resp );
		}
		if ( $success != '' ) {

			$available_after_gateways = WC()->payment_gateways()->get_available_payment_gateways();


			$resp = array(
				'new_item'        => $success,
				'status'          => true,
				'fragments'       => WFOB_Common::get_fragments(),
				'gateways_change' => ! ( $available_after_gateways == $available_before_gateways )
			);
		}
		self::send_resp( $resp );
	}

	public static function remove_order_bump() {
		WFOB_AJAX_Controller::check_nonce();
		$post = $_POST;
		$resp = array(
			'msg'           => '',
			'status'        => false,
			'cart_is_empty' => false,
			'products'      => [],
		);
		if ( isset( $post['cart_key'] ) && $post['cart_key'] != '' ) {
			do_action( 'wfob_before_remove_bump_from_cart', $_REQUEST );
			$cart_key = trim( $post['cart_key'] );
			$item     = WC()->cart->get_cart_item( $cart_key );

			if ( isset( $item['_wfob_product_key'] ) ) {
				$wfob_id          = $post['wfob_id'];
				$item_key         = $item['_wfob_product_key'];
				$session_products = WC()->session->get( 'wfob_added_bump_product', [] );
				if ( isset( $session_products[ $wfob_id ] ) && $session_products[ $wfob_id ] [ $item_key ] ) {
					unset( $session_products[ $wfob_id ] [ $item_key ] );
					WC()->session->set( 'wfob_added_bump_product', $session_products );
				}
			}
			$available_before_gateways = WC()->payment_gateways()->get_available_payment_gateways();
			WC()->cart->remove_cart_item( $cart_key );
			$available_after_gateways = WC()->payment_gateways()->get_available_payment_gateways();
			$resp['cart_is_empty']    = WC()->cart->is_empty();
			$resp['status']           = true;
			$resp['fragments']        = WFOB_Common::get_fragments();
			$resp['gateways_change']  = ! ( $available_after_gateways == $available_before_gateways );
		}
		WFOB_AJAX_Controller::send_resp( $resp );
	}

	/**
	 * Quick view ajax
	 */
	public static function wf_quick_view_ajax() {
		self::check_nonce();
		$resp = [
			'msg'    => '',
			'status' => false,
		];
		if ( isset( $_POST['wfob_id'] ) && $_POST['wfob_id'] > 0 && isset( $_POST['product_id'] ) && $_POST['product_id'] > 0 ) {
			$wfob_id  = absint( $_POST['wfob_id'] );
			$item_key = $_POST['item_key'];
			$cart_key = isset( $_POST['cart_key'] ) ? $_POST['cart_key'] : '';
			WFOB_Common::set_id( $wfob_id );
			$save_product_list = WFOB_Common::get_bump_products( $wfob_id );

			if ( isset( $save_product_list[ $item_key ] ) ) {
				$product_id = absint( $_POST['product_id'] );
				$params     = array(
					'p'         => $product_id,
					'post_type' => array( 'product', 'product_variation' ),
				);
				$query      = new WP_Query( $params );

				global $wfob_product, $wfob_post, $wfob_qv_data;
				$wfob_qv_data = $_POST;

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						ob_start();
						global $post, $product;
						global $wfob_item_data;
						$wfob_item_data             = $save_product_list[ $item_key ];
						$wfob_item_data['item_key'] = $item_key;

						// checking for product is variation or subscription_variation variation

						if ( in_array( $product->get_type(), [ 'variation', 'subscription_variation' ] ) ) {

							// stored child product object to our global variable
							// using this variable we
							$wfob_product = $product;
							$wfob_post    = $post;

							$parent_id = $product->get_parent_id();
							$product   = null;
							$post      = get_post( $parent_id );
							$product   = wc_get_product( $parent_id );
							// we set global product  variable to parent product because of child product not description and title of of the product
							// i am using some woocommerce part like add-to-cart button quantity
							//this template using Global $product variation
						}
						require_once( __DIR__ . '/quick-view/qv-template.php' );
						$html           = ob_get_clean();
						$resp['status'] = true;
						$resp['html']   = $html;
						break;
					}
				}
				wp_reset_postdata();
			}
			self::send_resp( $resp );
		}
	}

	public function make_wpml_duplicate() {
		self::check_nonce();
		$resp = [
			'msg'    => __( 'Something went wrong', 'woofunnel-order-bump' ),
			'status' => false,
		];
		if ( isset( $_POST['trid'] ) && $_POST['trid'] > 0 && class_exists( 'SitePress' ) && method_exists( 'SitePress', 'get_original_element_id_by_trid' ) ) {
			$trid          = absint( $_POST['trid'] );
			$lang          = isset( $_POST['lang'] ) ? trim( $_POST['lang'] ) : '';
			$language_code = isset( $_POST['language_code'] ) ? trim( $_POST['language_code'] ) : '';
			$lang          = empty( $lang ) ? $language_code : $lang;

			$master_post_id = SitePress::get_original_element_id_by_trid( $trid );
			if ( false !== $master_post_id ) {
				global $sitepress;
				$duplicate_id = $sitepress->make_duplicate( $master_post_id, $lang );
				if ( is_int( $duplicate_id ) && $duplicate_id > 0 ) {
					WFOB_Common::get_duplicate_data( $duplicate_id, $master_post_id );

					$resp['redirect_url'] = add_query_arg( [
						'section' => 'rules',
						'wfob_id' => $duplicate_id,
					], admin_url( 'admin.php?page=wfob' ) );
					$resp['duplicate_id'] = $duplicate_id;
					$resp['status']       = true;
				}
			}
		}
		self::send_resp( $resp );
	}

}

WFOB_AJAX_Controller::init();
