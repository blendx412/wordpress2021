<?php

/**
 * This class take care of ecommerce tracking setup
 * It renders necessary javascript code to fire events as well as creates dynamic data for the tracking
 * @author woofunnels.
 */
class WFOCU_Ecomm_Tracking {
	private static $ins = null;

	public function __construct() {

		/**
		 * Global settings script should render on every mode, they should not differentiate between preview and real funnel
		 */
		add_action( 'wfocu_footer_before_print_scripts', array( $this, 'render_global_external_scripts' ), 999 );
		add_action( 'wp_head', array( $this, 'render_global_external_scripts_head' ), 999 );
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_global_external_scripts_head' ), 999 );

		if ( true === WFOCU_Core()->template_loader->is_customizer_preview() ) {
			return;
		}
		/**
		 * Print js on pages
		 */
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_fb' ), 90 );
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_ga' ), 95 );
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_gad' ), 100 );
		add_action( 'wfocu_header_print_in_head', array( $this, 'maybe_remove_track_data' ), 9999 );

		/**
		 * Tracking js on custom pages/thankyou page
		 */
		add_action( 'wp_head', array( $this, 'render_fb' ), 90 );
		add_action( 'wp_head', array( $this, 'render_ga' ), 95 );
		add_action( 'wp_head', array( $this, 'render_gad' ), 100 );
		add_action( 'wp_head', array( $this, 'maybe_remove_track_data' ), 9999 );

		/**
		 * Offer view and offer success script on upsell pages
		 */
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_offer_view_script' ), 100 );
		add_action( 'wfocu_header_print_in_head', array( $this, 'render_offer_success_script' ), 110 );

		/**
		 * Offer view and offer success script on upsell pages for custom pages/thankyou page
		 */
		add_action( 'wp_head', array( $this, 'render_offer_view_script' ), 100 );
		add_action( 'wp_head', array( $this, 'render_offer_success_script' ), 110 );

		/**
		 * Funnel success on thank you page
		 */
		add_action( 'woocommerce_thankyou', array( $this, 'render_funnel_end' ), 200 );

		/**
		 * Generate data on these events that will further used by print functions
		 */
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'maybe_save_order_data' ), 999, 3 );
		add_action( 'wfocu_offer_accepted_and_processed', array( $this, 'maybe_save_data_offer_accepted' ), 10, 4 );

		add_action( 'wp_head', array( $this, 'render_js_to_track_referer' ), 10 );

		add_action( 'wfocu_header_print_in_head', array( $this, 'render_js_to_track_referer' ), 10 );
	}

	public static function get_instance() {
		if ( self::$ins === null ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * render script to load facebook pixel core js
	 */
	public function render_fb() {
		if ( $this->is_tracking_on() && false !== $this->is_fb_pixel() && $this->should_render() ) {
			$fb_advanced_pixel_data = $this->get_advanced_pixel_data(); ?>
            <!-- Facebook Analytics Script Added By WooFunnels -->
            <script>
                !function (f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function () {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
				<?php

				$get_all_fb_pixel = $this->is_fb_pixel();
				$get_each_pixel_id = explode( ',', $get_all_fb_pixel );
				if ( is_array( $get_each_pixel_id ) && count( $get_each_pixel_id ) > 0 ) {
				foreach ( $get_each_pixel_id as $pixel_id ) {
				?>
				<?php if ( true === $this->is_fb_advanced_tracking_on() && count( $fb_advanced_pixel_data ) > 0 ) { ?>
                fbq('init', '<?php echo esc_js( $pixel_id ); ?>', <?php echo wp_json_encode( $fb_advanced_pixel_data ); ?>);
				<?php } else { ?>
                fbq('init', '<?php echo esc_js( $pixel_id ); ?>');
				<?php } ?>
				<?php
				}
				?>

				<?php esc_js( $this->render_fb_view() ); ?>
				<?php esc_js( $this->maybe_print_fb_script() ); ?>
				<?php
				}
				?>
            </script>
			<?php
		}
	}

	public function is_tracking_on() {
		return apply_filters( 'wfocu_front_ecomm_tracking', true );
	}

	public function is_fb_pixel() {
		$get_pixel_key = WFOCU_Core()->data->get_option( 'fb_pixel_key' );

		return empty( $get_pixel_key ) ? false : $get_pixel_key;

	}

	/**
	 * Decide whether script should render or not
	 * Bases on condition given and based on the action we are in there exists some boolean checks
	 *
	 * @param bool $allow_thank_you whether consider thank you page
	 * @param bool $without_offer render without an valid offer (valid funnel)
	 *
	 * @return bool
	 */
	public function should_render( $allow_thank_you = true, $without_offer = true ) {

		/**
		 * For customizer templates
		 */
		if ( current_action() === 'wfocu_header_print_in_head' && ( $without_offer === true || ( false === $without_offer && false === WFOCU_Core()->public->is_preview ) ) ) {
			return true;
		}

		/**
		 * For custom pages and single offer post front request
		 */
		if ( current_action() === 'wp_head' && ( ( did_action( 'wfocu_front_before_custom_offer_page' ) || did_action( 'wfocu_front_before_single_page_load' ) ) && ( $without_offer === true || ( false === $without_offer && false === WFOCU_Core()->public->is_preview ) ) || ( $allow_thank_you && is_order_received_page() ) ) ) {

			return true;
		}

		return false;
	}

	public function get_advanced_pixel_data() {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );

		if ( ! is_array( $data ) ) {
			return array();
		}

		if ( ! isset( $data['fb'] ) ) {
			return array();
		}

		if ( ! isset( $data['fb']['advanced'] ) ) {
			return array();
		}

		return $data['fb']['advanced'];
	}

	public function is_fb_advanced_tracking_on() {
		$is_fb_advanced_tracking_on = WFOCU_Core()->data->get_option( 'is_fb_advanced_event' );
		if ( is_array( $is_fb_advanced_tracking_on ) && count( $is_fb_advanced_tracking_on ) > 0 && 'yes' === $is_fb_advanced_tracking_on[0] ) {
			return true;
		}

	}

	/**
	 * maybe render script to fire fb pixel view event
	 */
	public function render_fb_view() {

		if ( $this->is_tracking_on() && $this->do_track_fb_view() && WFOCU_Core()->public->if_is_offer() ) {
			?>
            fbq('track', 'PageView');
			<?php
		}
	}

	public function do_track_fb_view() {

		$do_track_fb_view = WFOCU_Core()->data->get_option( 'is_fb_view_event' );
		if ( is_array( $do_track_fb_view ) && count( $do_track_fb_view ) > 0 && 'yes' === $do_track_fb_view[0] ) {
			return true;
		}

		return false;
	}

	/**
	 * Maybe print facebook pixel javascript
	 * @see WFOCU_Ecomm_Tracking::render_fb();
	 */
	public function maybe_print_fb_script() {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );


		include_once plugin_dir_path( WFOCU_PLUGIN_FILE ) . '/views/js-blocks/wfocu-analytics-fb.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile
		if ( $this->do_track_fb_general_event() && current_action() === 'wfocu_header_print_in_head' ) {

			$get_offer              = WFOCU_Core()->data->get_current_offer();
			$getEventName           = WFOCU_Core()->data->get_option( 'general_event_name' );
			$params                 = array();
			$params['post_type']    = 'wfocu_offer';
			$params['content_name'] = get_the_title( $get_offer );
			$params['post_id']      = $get_offer;
			?>
            var wfocuGeneralData = <?php echo wp_json_encode( $params ); ?>;
            wfocuGeneralData = wfocuAddTrafficParamsToEvent(wfocuGeneralData);
            fbq('trackCustom', '<?php echo esc_js( $getEventName ); ?>', wfocuGeneralData);
			<?php
		}

	}

	public function do_track_fb_synced_purchase() {

		$do_track_fb_synced_purchase = WFOCU_Core()->data->get_option( 'is_fb_synced_event' );
		if ( is_array( $do_track_fb_synced_purchase ) && count( $do_track_fb_synced_purchase ) > 0 && 'yes' === $do_track_fb_synced_purchase[0] ) {
			return true;
		}

		return false;
	}

	public function do_track_fb_purchase_event() {

		$do_track_fb_purchase_event = WFOCU_Core()->data->get_option( 'is_fb_purchase_event' );
		if ( is_array( $do_track_fb_purchase_event ) && count( $do_track_fb_purchase_event ) > 0 && 'yes' === $do_track_fb_purchase_event[0] ) {
			return true;
		}

		return false;
	}

	public function do_track_fb_general_event() {

		$enable_general_event = WFOCU_Core()->data->get_option( 'enable_general_event' );
		if ( is_array( $enable_general_event ) && count( $enable_general_event ) > 0 && 'yes' === $enable_general_event[0] ) {
			return true;
		}

		return false;
	}

	/**
	 * render google analytics core script to load framework
	 */
	public function render_ga() {
		$get_tracking_code = $this->ga_code();
		if ( $this->is_tracking_on() && false !== $get_tracking_code && $this->should_render() ) {
			?>
            <!-- Google Analytics Script Added By WooFunnels -->
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                ga('create', '<?php echo esc_js( $this->ga_code() ); ?>', 'auto');
				<?php esc_js( $this->maybe_print_ga_script() ); ?>
            </script>


			<?php
		}
	}

	public function ga_code() {
		$get_ga_key = WFOCU_Core()->data->get_option( 'ga_key' );

		return empty( $get_ga_key ) ? false : $get_ga_key;
	}

	/**
	 * Maybe print google analytics javascript
	 * @see WFOCU_Ecomm_Tracking::render_ga();
	 */
	public function maybe_print_ga_script() {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );
		if ( $this->do_track_ga_purchase() && is_array( $data ) && isset( $data['ga'] ) ) {
			include_once plugin_dir_path( WFOCU_PLUGIN_FILE ) . '/views/js-blocks/wfocu-analytics-ga.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile
		}

	}

	public function do_track_ga_purchase() {

		$do_track_ga_purchase = WFOCU_Core()->data->get_option( 'is_ga_purchase_event' );
		if ( is_array( $do_track_ga_purchase ) && count( $do_track_ga_purchase ) > 0 && 'yes' === $do_track_ga_purchase[0] ) {
			return true;
		}

		return false;

	}

	public function do_track_ga_view() {

		$do_track_ga_view = WFOCU_Core()->data->get_option( 'is_ga_view_event' );
		if ( is_array( $do_track_ga_view ) && count( $do_track_ga_view ) > 0 && 'yes' === $do_track_ga_view[0] ) {
			return true;
		}

		return false;
	}

	/**
	 * render google analytics core script to load framework
	 */
	public function render_gad() {
		$get_tracking_code  = $this->gad_code();
		$get_purchase_label = $this->gad_purchase_label();
		if ( $this->is_tracking_on() && ( false !== $get_tracking_code && false !== $get_purchase_label ) && $this->should_render() ) {
			?>
            <!-- Google Ads Script Added By WooFunnels -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $this->gad_code() ); ?>"></script>   <?php //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }

                gtag('js', new Date());

                gtag('config', '<?php echo esc_js( $this->gad_code() ); ?>');
				<?php esc_js( $this->maybe_print_gad_script() ); ?>
            </script>


			<?php
		}
	}

	public function gad_code() {
		$get_gad_key = WFOCU_Core()->data->get_option( 'gad_key' );

		return empty( $get_gad_key ) ? false : $get_gad_key;
	}

	public function gad_purchase_label() {
		$get_gad_conversion_label = WFOCU_Core()->data->get_option( 'gad_conversion_label' );

		return empty( $get_gad_conversion_label ) ? false : $get_gad_conversion_label;
	}

	/**
	 * Maybe print google analytics javascript
	 * @see WFOCU_Ecomm_Tracking::render_ga();
	 */
	public function maybe_print_gad_script() {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );
		if ( $this->do_track_gad_purchase() && is_array( $data ) && isset( $data['gad'] ) ) {

			include_once plugin_dir_path( WFOCU_PLUGIN_FILE ) . '/views/js-blocks/wfocu-analytics-gad.phtml'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingNonPHPFile.IncludingNonPHPFile
		}

	}

	public function do_track_gad_purchase() {

		$do_track_gad_purchase = WFOCU_Core()->data->get_option( 'is_gad_purchase_event' );
		if ( is_array( $do_track_gad_purchase ) && count( $do_track_gad_purchase ) > 0 && 'yes' === $do_track_gad_purchase[0] ) {
			return true;
		}

		return false;
	}

	/**
	 * @hooked over `woocommerce_checkout_order_processed`
	 * Just after funnel initiated we try and setup cookie data for the parent order
	 * That will be further used by WFOCU_Ecomm_Tracking::render_ga() && WFOCU_Ecomm_Tracking::render_ga()
	 *
	 * @param WC_Order $order
	 */
	public function maybe_save_order_data( $order_id, $posted_data = array(), $order = null ) {
		if ( $this->is_tracking_on() ) {
			if ( ! $order instanceof WC_Order ) {
				$order = wc_get_order( $order_id );
			}
			$items               = $order->get_items( 'line_item' );
			$content_ids         = [];
			$content_name        = [];
			$category_names      = [];
			$num_qty             = 0;
			$products            = [];
			$google_products     = [];
			$google_ads_products = [];

			foreach ( $items as $item ) {
				$pid     = $item->get_product_id();
				$product = wc_get_product( $pid );
				if ( $product instanceof WC_product ) {

					$category       = $product->get_category_ids();
					$content_name[] = $product->get_title();
					$variation_id   = $item->get_variation_id();
					$get_content_id = 0;
					if ( empty( $variation_id ) || ( ! empty( $variation_id ) && true === $this->do_treat_variable_as_simple() ) ) {
						$get_content_id = $content_ids[] = $this->get_woo_product_content_id( $item->get_product_id() );

					} elseif ( false === $this->do_treat_variable_as_simple() ) {

						$get_content_id = $content_ids[] = $this->get_woo_product_content_id( $item->get_variation_id() );

					}
					$category_name = '';

					if ( is_array( $category ) && count( $category ) > 0 ) {
						$category_id = $category[0];
						if ( is_numeric( $category_id ) && $category_id > 0 ) {
							$cat_term = get_term_by( 'id', $category_id, 'product_cat' );
							if ( $cat_term ) {
								$category_name    = $cat_term->name;
								$category_names[] = $category_name;
							}
						}
					}
					$num_qty           += $item->get_quantity();
					$products[]        = array_map( 'html_entity_decode', array(
						'name'       => $product->get_title(),
						'category'   => ( $category_name ),
						'id'         => $get_content_id,
						'quantity'   => $item->get_quantity(),
						'item_price' => $order->get_line_subtotal( $item ),
					) );
					$google_products[] = array_map( 'html_entity_decode', array(
						'id'       => $pid,
						'sku'      => $product->get_sku(),
						'category' => $category_name,
						'name'     => $product->get_title(),
						'quantity' => $item->get_quantity(),
						'price'    => $order->get_line_subtotal( $item ),
					) );

					$google_ads_products[] = array_map( 'html_entity_decode', array(
						'id'       => $this->gad_product_id( $pid ),
						'sku'      => $product->get_sku(),
						'category' => $category_name,
						'name'     => $product->get_title(),
						'quantity' => $item->get_quantity(),
						'price'    => $order->get_line_subtotal( $item ),
					) );
				}
			}

			$advanced = array();
			/**
			 * Facebook advanced matching
			 */
			if ( $this->is_fb_advanced_tracking_on() ) {
				$billing_email = WFOCU_WC_Compatibility::get_order_data( $order, 'billing_email' );
				if ( ! empty( $billing_email ) ) {
					$advanced['em'] = $billing_email;
				}

				$billing_phone = WFOCU_WC_Compatibility::get_order_data( $order, 'billing_phone' );
				if ( ! empty( $billing_phone ) ) {
					$advanced['ph'] = $billing_phone;
				}

				$shipping_first_name = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_first_name' );
				if ( ! empty( $shipping_first_name ) ) {
					$advanced['fn'] = $shipping_first_name;
				}

				$shipping_last_name = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_last_name' );
				if ( ! empty( $shipping_last_name ) ) {
					$advanced['ln'] = $shipping_last_name;
				}

				$shipping_city = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_city' );
				if ( ! empty( $shipping_city ) ) {
					$advanced['ct'] = $shipping_city;
				}

				$shipping_state = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_state' );
				if ( ! empty( $shipping_state ) ) {
					$advanced['st'] = $shipping_state;
				}

				$shipping_postcode = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_postcode' );
				if ( ! empty( $shipping_postcode ) ) {
					$advanced['zp'] = $shipping_postcode;
				}
			}
			WFOCU_Core()->data->set( 'data', array(
				'fb'  => array(
					'products'       => $products,
					'total'          => $this->get_total_order_value( $order, 'order' ),
					'currency'       => WFOCU_WC_Compatibility::get_order_currency( $order ),
					'advanced'       => $advanced,
					'content_ids'    => $content_ids,
					'content_name'   => $content_name,
					'category_name'  => array_map( 'html_entity_decode', $category_names ),
					'num_qty'        => $num_qty,
					'additional'     => $this->purchase_custom_aud_params( $order ),
					'transaction_id' => WFOCU_WC_Compatibility::get_order_id( $order ),

				),
				'ga'  => array(
					'products'    => $google_products,
					'transaction' => array(
						'id'          => WFOCU_WC_Compatibility::get_order_id( $order ),
						'affiliation' => esc_attr( get_bloginfo( 'name' ) ),
						'currency'    => WFOCU_WC_Compatibility::get_order_currency( $order ),
						'revenue'     => $order->get_total(),
						'shipping'    => WFOCU_WC_Compatibility::get_order_shipping_total( $order ),
						'tax'         => $order->get_total_tax(),
					),
				),
				'gad' => array(
					'event_category'   => 'ecommerce',
					'transaction_id'   => WFOCU_WC_Compatibility::get_order_id( $order ),
					'value'            => $this->get_total_order_value( $order, 'order', 'google' ),
					'currency'         => WFOCU_WC_Compatibility::get_order_currency( $order ),
					'items'            => $google_ads_products,
					'tax'              => $order->get_total_tax(),
					'shipping'         => WFOCU_WC_Compatibility::get_order_shipping_total( $order ),
					'ecomm_prodid'     => array_map( array( $this, 'gad_product_id' ), wp_list_pluck( $google_ads_products, 'id' ) ),
					'ecomm_pagetype'   => 'purchase',
					'ecomm_totalvalue' => array_sum( wp_list_pluck( $google_ads_products, 'price' ) ),

				),
			), 'track' );
			WFOCU_Core()->data->save( 'track' );
			WFOCU_Core()->log->log( 'Order #' . $order_id . ': Data for the parent order collected successfully' );
		}

	}

	public function do_treat_variable_as_simple() {
		$do_treat_variable_as_simple = WFOCU_Core()->data->get_option( 'content_id_variable' );
		if ( is_array( $do_treat_variable_as_simple ) && count( $do_treat_variable_as_simple ) > 0 && 'yes' === $do_treat_variable_as_simple[0] ) {
			return true;
		}

		return false;
	}

	public function get_woo_product_content_id( $product_id ) {

		$content_id_format = WFOCU_Core()->data->get_option( 'content_id_value' );

		if ( $content_id_format === 'product_sku' ) {
			$content_id = get_post_meta( $product_id, '_sku', true );
		} else {
			$content_id = $product_id;
		}

		$prefix = WFOCU_Core()->data->get_option( 'content_id_suffix' );
		$suffix = WFOCU_Core()->data->get_option( 'content_id_suffix' );

		$value = $prefix . $content_id . $suffix;

		return ( $value );

	}

	public function gad_product_id( $product_id ) {

		$prefix = WFOCU_Core()->data->get_option( 'id_prefix_gad' );
		$suffix = WFOCU_Core()->data->get_option( 'id_suffix_gad' );

		$value = $prefix . $product_id . $suffix;

		return $value;
	}

	/**
	 * Get the value of purchase event for the different cases of calculations.
	 *
	 * @param WC_Order/offer_Data $data
	 * @param string $type type for which this function getting called, order|offer
	 *
	 * @return string the modified order value
	 */
	public function get_total_order_value( $data, $type = 'order', $party = 'fb' ) {

		$disable_shipping = $this->is_disable_shipping( $party );
		$disable_taxes    = $this->is_disable_taxes( $party );
		if ( 'order' === $type ) {
			//process order
			if ( ! $disable_taxes && ! $disable_shipping ) {
				//send default total
				$total = $data->get_total();
			} elseif ( ! $disable_taxes && $disable_shipping ) {

				$cart_total     = floatval( $data->get_total( 'edit' ) );
				$shipping_total = floatval( $data->get_shipping_total( 'edit' ) );
				$shipping_tax   = floatval( $data->get_shipping_tax( 'edit' ) );

				$total = $cart_total - $shipping_total - $shipping_tax;
			} elseif ( $disable_taxes && ! $disable_shipping ) {

				$cart_subtotal = $data->get_subtotal();

				$discount_total = floatval( $data->get_discount_total( 'edit' ) );
				$shipping_total = floatval( $data->get_shipping_total( 'edit' ) );

				$total = $cart_subtotal - $discount_total + $shipping_total;
			} else {
				$cart_subtotal = $data->get_subtotal();

				$discount_total = floatval( $data->get_discount_total( 'edit' ) );

				$total = $cart_subtotal - $discount_total;
			}
		} else {
			//process offer
			if ( ! $disable_taxes && ! $disable_shipping ) {

				//send default total
				$total = $data['total'];

			} elseif ( ! $disable_taxes && $disable_shipping ) {
				//total - shipping cost - shipping tax
				$total = $data['total'] - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['cost'] ) ? $data['shipping']['diff']['cost'] : 0 ) - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['tax'] ) ? $data['shipping']['diff']['tax'] : 0 );

			} elseif ( $disable_taxes && ! $disable_shipping ) {
				//total - taxes
				$total = $data['total'] - ( isset( $data['taxes'] ) ? $data['taxes'] : 0 );

			} else {

				//total - taxes - shipping cost
				$total = $data['total'] - ( isset( $data['taxes'] ) ? $data['taxes'] : 0 ) - ( isset( $data['shipping']['diff'] ) && isset( $data['shipping']['diff']['cost'] ) ? $data['shipping']['diff']['cost'] : 0 );

			}
		}
		$total = apply_filters( 'wfocu_ecommerce_pixel_tracking_value', $total, $data );

		return number_format( $total, wc_get_price_decimals(), '.', '' );
	}

	public function is_disable_shipping( $party = 'fb' ) {
		if ( $party === 'fb' ) {
			$exclude_from_total = WFOCU_Core()->data->get_option( 'exclude_from_total' );
		} else {
			$exclude_from_total = WFOCU_Core()->data->get_option( 'gad_exclude_from_total' );
		}

		if ( is_array( $exclude_from_total ) && count( $exclude_from_total ) > 0 && in_array( 'is_disable_shipping', $exclude_from_total, true ) ) {
			return true;
		}

		return false;

	}

	public function is_disable_taxes( $party = 'fb' ) {
		if ( $party === 'fb' ) {
			$exclude_from_total = WFOCU_Core()->data->get_option( 'exclude_from_total' );
		} else {
			$exclude_from_total = WFOCU_Core()->data->get_option( 'gad_exclude_from_total' );
		}

		if ( is_array( $exclude_from_total ) && count( $exclude_from_total ) > 0 && in_array( 'is_disable_taxes', $exclude_from_total, true ) ) {
			return true;
		}

		return false;

	}

	/**
	 * @param WC_Order $order
	 *
	 * @return array
	 */
	public function purchase_custom_aud_params( $order ) {

		$params                = array();
		$get_custom_aud_config = WFOCU_Core()->data->get_option( 'custom_aud_opt_conf' );
		$add_address           = in_array( 'add_town_s_c', $get_custom_aud_config, true );
		$add_payment_method    = in_array( 'add_payment_method', $get_custom_aud_config, true );
		$add_shipping_method   = in_array( 'add_shipping_method', $get_custom_aud_config, true );
		$add_coupons           = in_array( 'add_coupon', $get_custom_aud_config, true );

		if ( WFOCU_WC_Compatibility::is_wc_version_gte_3_0() ) {

			// town, state, country
			if ( $add_address ) {

				$params['town']    = $order->get_billing_city();
				$params['state']   = $order->get_billing_state();
				$params['country'] = $order->get_billing_country();

			}

			// payment method
			if ( $add_payment_method ) {
				$params['payment'] = $order->get_payment_method_title();
			}
		} else {

			// town, state, country
			if ( $add_address ) {

				$params['town']    = $order->billing_city;
				$params['state']   = $order->billing_state;
				$params['country'] = $order->billing_country;

			}

			// payment method
			if ( $add_payment_method ) {
				$params['payment'] = $order->payment_method_title;
			}
		}

		// shipping method
		$shipping_methods = $order->get_items( 'shipping' );
		if ( $add_shipping_method && $shipping_methods ) {

			$labels = array();
			foreach ( $shipping_methods as $shipping ) {
				$labels[] = $shipping['name'] ? $shipping['name'] : null;
			}

			$params['shipping'] = implode( ', ', $labels );

		}

		// coupons
		$coupons = $order->get_items( 'coupon' );
		if ( $add_coupons && $coupons ) {

			$labels = array();
			foreach ( $coupons as $coupon ) {
				$labels[] = $coupon['name'] ? $coupon['name'] : null;
			}

			$params['coupon_used'] = 'yes';
			$params['coupon_name'] = implode( ', ', $labels );

		} elseif ( $add_coupons ) {

			$params['coupon_used'] = 'no';

		}

		return $params;

	}

	/**
	 * @hooked over `wfocu_offer_accepted_and_processed`
	 * Sets up a cookie data for tracking based on the offer/upsell accepted by the customer
	 *
	 * @param int $get_current_offer Current offer
	 * @param array $get_package current package
	 */
	public function maybe_save_data_offer_accepted( $get_current_offer, $get_package, $get_parent_order, $new_order ) {
		$get_offer_Data = WFOCU_Core()->data->get( '_current_offer' );
		if ( $this->is_tracking_on() ) {
			$content_ids         = [];
			$content_name        = [];
			$category_names      = [];
			$num_qty             = 0;
			$google_products     = [];
			$products            = [];
			$google_ads_products = [];
			$content_id_format   = WFOCU_Core()->data->get_option( 'content_id_value' );

			foreach ( $get_package['products'] as $product ) {

				$pid         = $fbpid = $product['id'];
				$product_obj = wc_get_product( $pid );
				if ( $product_obj instanceof WC_product ) {
					$content_name[] = $product_obj->get_title();

					if ( $product_obj->is_type( 'variation' ) && false === $this->do_treat_variable_as_simple() ) {
						$content_ids[] = $this->get_woo_product_content_id( $product_obj->get_id() );
						$fbpid         = $product_obj->get_id();
					} else {
						if ( $product_obj->is_type( 'variation' ) ) {
							$content_ids[] = $this->get_woo_product_content_id( $product_obj->get_parent_id() );
							$fbpid         = $product_obj->get_parent_id();
						} else {
							$content_ids[] = $this->get_woo_product_content_id( $product_obj->get_id() );
							$fbpid         = $product_obj->get_id();
						}
					}
					$category      = $product_obj->get_category_ids();
					$category_name = '';
					if ( is_array( $category ) && count( $category ) > 0 ) {
						$category_id = $category[0];
						if ( is_numeric( $category_id ) && $category_id > 0 ) {
							$cat_term = get_term_by( 'id', $category_id, 'product_cat' );
							if ( $cat_term ) {
								$category_name    = $cat_term->name;
								$category_names[] = $cat_term->name;
							}
						}
					}
					$num_qty           += $product['qty'];
					$products[]        = array_map( 'html_entity_decode', array(
						'name'       => $product['_offer_data']->name,
						'category'   => esc_attr( $category_name ),
						'id'         => ( 'product_sku' === $content_id_format ) ? get_post_meta( $fbpid, '_sku', true ) : $fbpid,
						'quantity'   => $product['qty'],
						'item_price' => $product['args']['total'],
					) );
					$google_products[] = array_map( 'html_entity_decode', array(
						'id'       => $pid,
						'sku'      => $product_obj->get_sku(),
						'category' => $category_name,
						'name'     => $product['_offer_data']->name,
						'quantity' => $product['qty'],
						'price'    => $product['args']['total'],
					) );

					$google_ads_products[] = array_map( 'html_entity_decode', array(
						'id'       => $this->gad_product_id( $pid ),
						'sku'      => $product_obj->get_sku(),
						'category' => $category_name,
						'name'     => $product['_offer_data']->name,
						'quantity' => $product['qty'],
						'price'    => $product['args']['total'],
					) );
				}
			}
			$order = WFOCU_Core()->data->get_current_order();

			$advanced = array();
			/**
			 * Facebook advanced matching
			 */
			if ( $this->is_fb_advanced_tracking_on() ) {
				$billing_email = WFOCU_WC_Compatibility::get_order_data( $order, 'billing_email' );
				if ( ! empty( $billing_email ) ) {
					$advanced['em'] = $billing_email;
				}

				$billing_phone = WFOCU_WC_Compatibility::get_order_data( $order, 'billing_phone' );
				if ( ! empty( $billing_phone ) ) {
					$advanced['ph'] = $billing_phone;
				}

				$shipping_first_name = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_first_name' );
				if ( ! empty( $shipping_first_name ) ) {
					$advanced['fn'] = $shipping_first_name;
				}

				$shipping_last_name = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_last_name' );
				if ( ! empty( $shipping_last_name ) ) {
					$advanced['ln'] = $shipping_last_name;
				}

				$shipping_city = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_city' );
				if ( ! empty( $shipping_city ) ) {
					$advanced['ct'] = $shipping_city;
				}

				$shipping_state = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_state' );
				if ( ! empty( $shipping_state ) ) {
					$advanced['st'] = $shipping_state;
				}

				$shipping_postcode = WFOCU_WC_Compatibility::get_order_data( $order, 'shipping_postcode' );
				if ( ! empty( $shipping_postcode ) ) {
					$advanced['zp'] = $shipping_postcode;
				}
			}

			if ( $new_order instanceof WC_Order ) {
				$ga_transaction_id = WFOCU_WC_Compatibility::get_order_id( $new_order );
			} else {
				$ga_transaction_id = WFOCU_WC_Compatibility::get_order_id( $get_parent_order );
			}
			WFOCU_Core()->data->set( 'data', array(
				'fb'  => array(
					'products'       => $products,
					'total'          => $this->get_total_order_value( $get_package, 'offer' ),
					'currency'       => WFOCU_WC_Compatibility::get_order_currency( $order ),
					'advanced'       => $advanced,
					'content_ids'    => $content_ids,
					'content_name'   => $content_name,
					'category_name'  => array_map( 'html_entity_decode', $category_names ),
					'num_qty'        => $num_qty,
					'additional'     => $this->purchase_custom_aud_params( $order ),
					'transaction_id' => WFOCU_WC_Compatibility::get_order_id( $order ) . '-' . $get_current_offer,
				),
				'ga'  => array(
					'products'    => $google_products,
					'transaction' => array(
						'id'          => $ga_transaction_id,
						'affiliation' => esc_attr( get_bloginfo( 'name' ) ),
						'currency'    => WFOCU_WC_Compatibility::get_order_currency( $order ),
						'revenue'     => $get_package['total'],
						'shipping'    => ( $get_package['shipping'] && isset( $get_package['shipping']['diff']['cost'] ) ) ? $get_package['shipping']['diff']['cost'] : 0,
						'tax'         => $get_package['taxes'],
						'offer'       => true,
					),
				),
				'gad' => array(
					'event_category'   => 'ecommerce',
					'transaction_id'   => $ga_transaction_id,
					'value'            => $this->get_total_order_value( $get_package, 'offer', 'google' ),
					'currency'         => WFOCU_WC_Compatibility::get_order_currency( $order ),
					'items'            => $google_ads_products,
					'tax'              => $get_package['taxes'],
					'shipping'         => ( $get_package['shipping'] && isset( $get_package['shipping']['diff']['cost'] ) ) ? $get_package['shipping']['diff']['cost'] : 0,
					'ecomm_prodid'     => array_map( array( $this, 'gad_product_id' ), wp_list_pluck( $google_ads_products, 'id' ) ),
					'ecomm_pagetype'   => 'purchase',
					'ecomm_totalvalue' => array_sum( wp_list_pluck( $google_ads_products, 'price' ) ),

				),

				'success_offer'           => $get_offer_Data->settings->upsell_page_purchase_code,
				'purchase_script_enabled' => $get_offer_Data->settings->check_add_offer_purchase,
			), 'track' );
			WFOCU_Core()->data->save( 'track' );
		}

	}

	public function render_global_external_scripts() {

		if ( '' !== WFOCU_Core()->data->get_option( 'scripts' ) ) {
			echo WFOCU_Core()->data->get_option( 'scripts' );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function render_global_external_scripts_head() {

		if ( $this->should_render( false ) && '' !== WFOCU_Core()->data->get_option( 'scripts_head' ) ) {
			echo WFOCU_Core()->data->get_option( 'scripts_head' );  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render Offer View script
	 */
	public function render_offer_view_script() {
		$get_offer_Data = WFOCU_Core()->data->get( '_current_offer' );
		if ( $this->should_render( false, false ) && $get_offer_Data && is_object( $get_offer_Data ) && true === $get_offer_Data->settings->check_add_offer_script && '' !== $get_offer_Data->settings->upsell_page_track_code ) {
			echo $get_offer_Data->settings->upsell_page_track_code;   //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render successful offer script
	 */
	public function render_offer_success_script() {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );

		if ( ! is_array( $data ) ) {
			return;
		}

		if ( ! isset( $data['success_offer'] ) || ( isset( $data['purchase_script_enabled'] ) && false === $data['purchase_script_enabled'] ) ) {
			return;
		}

		echo $data['success_offer'];  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render funnel end script
	 */
	public function render_funnel_end() {
		$funnel_id = WFOCU_Core()->data->get_funnel_id();

		if ( empty( $funnel_id ) ) {
			return;
		}

		$script = WFOCU_Core()->funnels->setup_funnel_options( $funnel_id )->get_funnel_option( 'funnel_success_script' );

		if ( '' === $script ) {
			return;
		}

		echo $script;  //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function maybe_remove_track_data() {

		$get_tracking_data = WFOCU_Core()->data->get( 'data', array(), 'track' );

		/**
		 * only set it blank when it exists
		 */
		if ( ! empty( $get_tracking_data ) ) {
			$data = array();
			WFOCU_Core()->data->set( 'data', $data, 'track' );
			WFOCU_Core()->data->save( 'track' );
		}

	}

	public function render_js_to_track_referer() {
		?>
        <script>
            var wfocuPixelOptions = {};
            wfocuPixelOptions.site_url = '<?php echo esc_url( site_url() ); ?>';
            wfocuPixelOptions.DotrackTrafficSource = '<?php echo esc_js( wc_string_to_bool( count( WFOCU_Core()->data->get_option( 'track_traffic_source' ) ) ) ); ?>';
            var wfocuUtm_terms = ['utm_source', 'utm_media', 'utm_campaign', 'utm_term', 'utm_content'];
            var wfocuCookieManage = {
                'setCookie': function (cname, cvalue, exdays) {
                    var d = new Date();
                    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                },
                'getCookie': function (cname) {
                    var name = cname + "=";
                    var ca = document.cookie.split(';');
                    for (var i = 0; i < ca.length; i++) {
                        var c = ca[i];
                        while (c.charAt(0) == ' ') {
                            c = c.substring(1);
                        }
                        if (c.indexOf(name) == 0) {
                            return c.substring(name.length, c.length);
                        }
                    }
                    return "";
                },
                'remove': function (cname) {
                    var d = new Date();
                    d.setTime(d.getTime() - (24 * 60 * 60 * 1000));
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = cname + "=" + '' + ";" + expires + ";path=/";

                },

                'commons': {
                    'inArray': function (value, ar) {

                        if (ar.indexOf(value) !== -1) {
                            return false;
                        }
                        return true;
                    }
                }

            };

            /**
             * Return query variables object with where property name is query variable and property value is query variable value.
             */
            function wfocuGetQueryVars() {

                try {

                    var result = {}, tmp = [];

                    window.location.search
                        .substr(1)
                        .split("&")
                        .forEach(function (item) {

                            tmp = item.split('=');

                            if (tmp.length > 1) {
                                result[tmp[0]] = tmp[1];
                            }

                        });

                    return result;

                } catch (e) {
                    console.log(e);
                    return {};
                }

            }

            function wfocuGetTrafficSource() {
                try {

                    var referrer = document.referrer.toString();

                    var direct = referrer.length === 0;
                    //noinspection JSUnresolvedVariable
                    var internal = direct ? false : referrer.indexOf(wfocuPixelOptions.site_url) === 0;
                    var external = !(direct || internal);
                    var cookie = wfocuCookieManage.getCookie('wfocu_fb_pixel_traffic_source') === '' ? false : wfocuCookieManage.getCookie('wfocu_fb_pixel_traffic_source');

                    if (external === false) {
                        return cookie ? cookie : 'direct';
                    } else {
                        return cookie && cookie === referrer ? cookie : referrer;
                    }

                } catch (e) {

                    console.log(e);
                    return '';

                }


            }

            function wfocuManageCookies() {


                try {

                    var source = wfocuGetTrafficSource();
                    if (source !== 'direct') {
                        wfocuCookieManage.setCookie('wfocu_fb_pixel_traffic_source', source, 2);
                    } else {
                        wfocuCookieManage.remove('wfocu_fb_pixel_traffic_source');
                    }

                    var queryVars = wfocuGetQueryVars();


                    for (var k in wfocuUtm_terms) {
                        if (wfocuCookieManage.getCookie('wfocu_fb_pixel_' + wfocuUtm_terms[k]) === '' && queryVars.hasOwnProperty(wfocuUtm_terms[k])) {

                            wfocuCookieManage.setCookie('wfocu_fb_pixel_' + wfocuUtm_terms[k], queryVars[wfocuUtm_terms[k]], 2);
                        }
                    }


                } catch (e) {
                    console.log(e);
                }


            }

            /**
             * Return UTM terms from request query variables or from cookies.
             */
            function wfocuGetUTMs() {

                try {

                    var terms = {};
                    var queryVars = wfocuGetQueryVars();

                    for (var k in wfocuUtm_terms) {
                        if (wfocuCookieManage.getCookie('wfocu_fb_pixel_' + wfocuUtm_terms[k])) {
                            terms[wfocuUtm_terms[k]] = wfocuCookieManage.getCookie('wfocu_fb_pixel_' + wfocuUtm_terms[k]);
                        } else if (queryVars.hasOwnProperty(wfocuUtm_terms[k])) {
                            terms[wfocuUtm_terms[k]] = queryVars[wfocuUtm_terms[k]];
                        }
                    }

                    return terms;

                } catch (e) {
                    console.log(e);
                    return {};
                }

            }

            function wfocuAddTrafficParamsToEvent(params) {
                try {

                    var get_generic_params = '<?php echo wp_json_encode( $this->get_generic_event_params() ); ?>';
                    var json_get_generic_params = JSON.parse(get_generic_params);

                    for (var k in json_get_generic_params) {
                        params[k] = json_get_generic_params[k];
                    }


                    /**
                     * getting current day and time to send with this event
                     */
                    var e = new Date;
                    var a = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][e.getDay()],
                        b = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][e.getMonth()],
                        c = ["00-01", "01-02", "02-03", "03-04", "04-05", "05-06", "06-07", "07-08", "08-09", "09-10", "10-11", "11-12", "12-13", "13-14", "14-15", "15-16", "16-17", "17-18", "18-19", "19-20", "20-21", "21-22", "22-23", "23-24"][e.getHours()];

                    params.event_month = b;
                    params.event_day = a;
                    params.event_hour = c;

                    //noinspection JSUnresolvedVariable
                    if (wfocuPixelOptions.DotrackTrafficSource !== '1') {
                        return params;
                    }

                    params.traffic_source = wfocuGetTrafficSource();


                    var getUTMs = wfocuGetUTMs();

                    for (var k in getUTMs) {
                        if (wfocuCookieManage.commons.inArray(getUTMs[k], wfocuUtm_terms) >= 0) {
                            params[getUTMs[k]] = value;
                        }
                    }

                    return params;

                } catch (e) {


                    return params;

                }
            }


            wfocuManageCookies();


        </script>
		<?php
	}

	/**
	 * Add Generic event params to the data in events
	 * @return array
	 */
	public function get_generic_event_params() {

		$user = wp_get_current_user();

		if ( $user->ID !== 0 ) {
			$user_roles = implode( ',', $user->roles );
		} else {
			$user_roles = 'guest';
		}

		return array(
			'domain'     => substr( get_home_url( null, '', 'http' ), 7 ),
			'user_roles' => $user_roles,
			'plugin'     => 'UpStroke',
		);

	}

	/**
	 * @param string $taxonomy Taxonomy name
	 * @param int $post_id (optional) Post ID. Current will be used of not set
	 *
	 * @return string|array List of object terms
	 */
	public function get_object_terms( $taxonomy, $post_id = null, $implode = true ) {

		$post_id = isset( $post_id ) ? $post_id : get_the_ID();
		$terms   = get_the_terms( $post_id, $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $implode ? '' : array();
		}

		$results = array();

		foreach ( $terms as $term ) {
			$results[] = html_entity_decode( $term->name );
		}

		if ( $implode ) {
			return implode( ', ', $results );
		} else {
			return $results;
		}

	}


	public function get_localstorage_hash( $key ) {
		$data = WFOCU_Core()->data->get( 'data', array(), 'track' );
		if ( ! isset( $data[ $key ] ) ) {
			return 0;
		}

		return md5( wp_json_encode( array( 'key' => WFOCU_Core()->data->get_transient_key(), 'data' => $data[ $key ] ) ) );
	}

}


if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'ecom_tracking', 'WFOCU_Ecomm_Tracking' );
}
