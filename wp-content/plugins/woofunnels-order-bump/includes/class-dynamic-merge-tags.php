<?php

defined( 'ABSPATH' ) || exit;

class WFOB_Product_Switcher_Merge_Tags {

	public static $threshold_to_date = 30;
	/**
	 * @var $pro WC_Product || WC_Product_subscription || WC_Product_Subscription_Variation;
	 */
	protected static $pro;
	protected static $price_data;
	protected static $_data_shortcode = array();
	protected static $cart_item = [];
	protected static $product_data = [];
	protected static $cart_item_key = '';

	/**
	 * Maybe try and parse content to found the wfacp merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content, $price_data, $pro = false, $product_data = [], $cart_item = [], $cart_item_key = '' ) {

		if ( $pro instanceof WC_Product ) {
			self::$pro = $pro;
		}
		if ( ! empty( $product_data ) ) {
			self::$product_data = $product_data;
		}
		if ( ! empty( $cart_item ) ) {
			self::$cart_item = $cart_item;
		}
		if ( '' != $cart_item_key ) {
			self::$cart_item_key = $cart_item_key;
		}
		self::$price_data = $price_data;


		$get_all      = self::get_all_tags();
		$get_all_tags = wp_list_pluck( $get_all, 'tag' );
		//iterating over all the merge tags
		if ( $get_all_tags && is_array( $get_all_tags ) && count( $get_all_tags ) > 0 ) {
			foreach ( $get_all_tags as $tag ) {

				$matches = array();
				$re      = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match = $exact_match;

						$single = str_replace( '{{', '', $old_match );
						$single = str_replace( '}}', '', $single );

						if ( method_exists( __CLASS__, $single ) ) {
							$get_parsed_value = call_user_func( array( __CLASS__, $single ) );
							$content          = trim( str_replace( $old_match, $get_parsed_value, $content ) );
						}
					}
				}
			}
		}

		return $content;

	}

	public static function get_all_tags() {

		$tags = array(
			array(
				'name' => __( 'Subscription Summary', 'woofunnels-aero-checkout' ),
				'tag'  => 'subscription_summary',
			),
			array(
				'name' => __( 'Subscription Summary', 'woofunnels-aero-checkout' ),
				'tag'  => 'product_name',
			),
			array(
				'name' => __( 'You Save', 'woofunnels-aero-checkout' ),
				'tag'  => 'saving_value',
			),
			array(
				'name' => __( 'You Save', 'woofunnels-aero-checkout' ),
				'tag'  => 'saving_percentage',
			),
			array(
				'name' => __( 'Quantity', 'woocommerce' ),
				'tag'  => 'quantity',
			),

		);

		return $tags;
	}

	public static function saving_value() {
		$difference = floatval( self::$price_data['regular_org'] ) - floatval( self::$price_data['price'] );

		if ( 0 < $difference ) {
			return wc_price( $difference );
		}

		return '';
	}

	public static function saving_percentage() {
		$regular_org = floatval( self::$price_data['regular_org'] );
		$price       = floatval( self::$price_data['price'] );
		$percentage  = 0;

		if ( $regular_org == 0 ) {
			return '';
		}
		// get price of product is zero means 100% off
		if ( $price == 0 ) {
			return 100 . '%';
		}
		if ( $regular_org == $price ) {
			return '';
		}
		if ( $price > $regular_org ) {
			return '';
		}


		$temp_precentage = ( ( ( $price / $regular_org ) * 100 ) );
		if ( $temp_precentage > 0 ) {
			$percentage = 100 - ( ( $price / $regular_org ) * 100 );
		} else {
			return '';
		}

		$t = absint( $percentage );

		if ( ( $percentage / $t ) > 0 ) {
			$percentage = number_format( $percentage, 2 );
		}
		unset( $t );

		return $percentage . '%';
	}

	public static function quantity() {
		return self::$price_data['quantity'];
	}


	public static function subscription_summary() {
		if ( self::$pro instanceof WC_Product_Subscription || self::$pro instanceof WC_Product_Subscription_Variation ) {
			return WFOB_Common::subscription_product_string( self::$pro, self::$price_data, self::$cart_item, self::$cart_item_key );
		} else {
			return '';
		}
	}


	public static function product_name() {
		if ( self::$pro instanceof WC_Product ) {


			if ( isset( self::$product_data['variable'] ) ) {
				if ( '' !== self::$cart_item_key ) {
					$variation_id = absint( self::$product_data['default_variation'] );
					if ( $variation_id > 0 ) {
						$product = wc_get_product( $variation_id );
						if ( $product instanceof WC_Product ) {
							return $product->get_name();
						}
					}
				} else {
					return self::$pro->get_title();
				}
			}
			if ( in_array( self::$pro->get_type(), WFOB_Common::get_variation_product_type() ) ) {

				return self::$pro->get_name();
			}

			return self::$pro->get_title();
		}

		return '';
	}
}

