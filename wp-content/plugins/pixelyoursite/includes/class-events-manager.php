<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Content;

class EventsManager {

    public $facebookServerEvents = array();

	public $doingAMP = false;

    private $dynamicEventsParams = array();
    private $dynamicEventsTriggers = array();
	private $staticEvents = array();

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

		add_action( 'wp_head', array( $this, 'setupEventsParams' ), 3 );
		add_action( 'wp_head', array( $this, 'outputData' ), 4 );
		add_action( 'wp_footer', array( $this, 'outputNoScriptData' ), 10 );

	}

	public function enqueueScripts() {
	    
        wp_register_script( 'jquery-bind-first', PYS_FREE_URL . '/dist/scripts/jquery.bind-first-0.2.3.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-bind-first' );
  
        wp_register_script( 'js-cookie', PYS_FREE_URL . '/dist/scripts/js.cookie-2.1.3.min.js', array(), '2.1.3' );
        wp_enqueue_script( 'js-cookie' );
		
		wp_enqueue_script( 'pys', PYS_FREE_URL . '/dist/scripts/public.js',
			array( 'jquery', 'js-cookie', 'jquery-bind-first' ), PYS_FREE_VERSION );

	}

	public function outputData() {

		$data = array(
			'staticEvents'          => $this->staticEvents,
            'dynamicEventsParams'   => $this->dynamicEventsParams,
            'dynamicEventsTriggers' => $this->dynamicEventsTriggers,
		);

		// collect options for configured pixel
		foreach ( PYS()->getRegisteredPixels() as $pixel ) {
			/** @var Pixel|Settings $pixel */

		    if ( $pixel->configured() ) {
			    $data[ $pixel->getSlug() ] = $pixel->getPixelOptions();
		    }

		}

		$options = array(
			'debug' => PYS()->getOption( 'debug_enabled' ),
			'siteUrl' => site_url(),
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'commonEventParams'    => getCommonEventParams(),
			'commentEventEnabled'  => isEventEnabled( 'comment_event_enabled' ),
            'downloadEventEnabled' => isEventEnabled( 'download_event_enabled' ),
            'downloadExtensions'   => PYS()->getOption( 'download_event_extensions' ),
            'formEventEnabled' => isEventEnabled( 'form_event_enabled' ),
		);

		$options['gdpr'] = array(
			'ajax_enabled'              => PYS()->getOption( 'gdpr_ajax_enabled' ),
			'all_disabled_by_api'       => apply_filters( 'pys_disable_by_gdpr', false ),
			'facebook_disabled_by_api'  => apply_filters( 'pys_disable_facebook_by_gdpr', false ),
			'analytics_disabled_by_api' => apply_filters( 'pys_disable_analytics_by_gdpr', false ),
            'google_ads_disabled_by_api' => apply_filters( 'pys_disable_google_ads_by_gdpr', false ),
			'pinterest_disabled_by_api' => apply_filters( 'pys_disable_pinterest_by_gdpr', false ),
            'bing_disabled_by_api' => apply_filters( 'pys_disable_bing_by_gdpr', false ),

			'facebook_prior_consent_enabled'   => PYS()->getOption( 'gdpr_facebook_prior_consent_enabled' ),
			'analytics_prior_consent_enabled'  => PYS()->getOption( 'gdpr_analytics_prior_consent_enabled' ),
			'google_ads_prior_consent_enabled' => PYS()->getOption( 'gdpr_google_ads_prior_consent_enabled' ),
			'pinterest_prior_consent_enabled'  => PYS()->getOption( 'gdpr_pinterest_prior_consent_enabled' ),
            'bing_prior_consent_enabled' => PYS()->getOption( 'gdpr_bing_prior_consent_enabled' ),

			'cookiebot_integration_enabled'         => isCookiebotPluginActivated() && PYS()->getOption( 'gdpr_cookiebot_integration_enabled' ),
			'cookiebot_facebook_consent_category'   => PYS()->getOption( 'gdpr_cookiebot_facebook_consent_category' ),
			'cookiebot_analytics_consent_category'  => PYS()->getOption( 'gdpr_cookiebot_analytics_consent_category' ),
			'cookiebot_google_ads_consent_category' => PYS()->getOption( 'gdpr_cookiebot_google_ads_consent_category' ),
			'cookiebot_pinterest_consent_category'  => PYS()->getOption( 'gdpr_cookiebot_pinterest_consent_category' ),
            'cookiebot_bing_consent_category' => PYS()->getOption( 'gdpr_cookiebot_bing_consent_category' ),

			'ginger_integration_enabled' => isGingerPluginActivated() && PYS()->getOption( 'gdpr_ginger_integration_enabled' ),
			'cookie_notice_integration_enabled' => isCookieNoticePluginActivated() && PYS()->getOption( 'gdpr_cookie_notice_integration_enabled' ),
			'cookie_law_info_integration_enabled' => isCookieLawInfoPluginActivated() && PYS()->getOption( 'gdpr_cookie_law_info_integration_enabled' ),
		);

		$options['woo'] = array(
			'enabled'                       => isWooCommerceActive() && PYS()->getOption( 'woo_enabled' ),
			'addToCartOnButtonEnabled'      => isEventEnabled( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_button_click' ),
			'addToCartOnButtonValueEnabled' => PYS()->getOption( 'woo_add_to_cart_value_enabled' ),
			'addToCartOnButtonValueOption'  => PYS()->getOption( 'woo_add_to_cart_value_option' ),
			'removeFromCartEnabled'         => isEventEnabled( 'woo_remove_from_cart_enabled' ),
			'removeFromCartSelector'        => isWooCommerceVersionGte( '3.0.0' )
                ? 'form.woocommerce-cart-form .remove'
				: '.cart .product-remove .remove',
		);

		$options['edd'] = array(
			'enabled'                       => isEddActive() && PYS()->getOption( 'edd_enabled' ),
			'addToCartOnButtonEnabled'      => isEventEnabled( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_button_click' ),
			'addToCartOnButtonValueEnabled' => PYS()->getOption( 'edd_add_to_cart_value_enabled' ),
			'addToCartOnButtonValueOption'  => PYS()->getOption( 'edd_add_to_cart_value_option' ),
			'removeFromCartEnabled'         => isEventEnabled( 'edd_remove_from_cart_enabled' ),
		);
  
		$data = array_merge( $data, $options );

		wp_localize_script( 'pys', 'pysOptions', $data );

	}
	
	public function outputNoScriptData() {

		foreach ( PYS()->getRegisteredPixels() as $pixel ) {
			/** @var Pixel|Settings $pixel */
			$pixel->outputNoScriptEvents();
		}

    }

	public function setupEventsParams() {

        $this->facebookServerEvents = array();

		// initial event
		$this->addStaticEvent( 'init_event' );

		if ( isEventEnabled( 'general_event_enabled' ) ) {
			$this->addStaticEvent( 'general_event' );
		}

		if ( isEventEnabled( 'search_event_enabled' ) && is_search() ) {
			$this->addStaticEvent( 'search_event' );
		}
		
		if ( PYS()->getOption( 'custom_events_enabled' ) ) {
			$this->setupCustomEvents();
		}

        $this->setupFDPEvents();

	    if ( isWooCommerceActive() && PYS()->getOption( 'woo_enabled' ) ) {
			$this->setupWooCommerceEvents();

	    }

		if ( isEddActive() && PYS()->getOption( 'edd_enabled' ) ) {
			$this->setupEddEvents();
		}

        if(count($this->facebookServerEvents)>0 && Facebook()->enabled()) {
            FacebookServer()->addAsyncEvents($this->facebookServerEvents);
        }
	}

    /**
     * Always returns empty customer LTV-related values to make plugin compatible with PRO version.
     * Used by Pinterest add-on.
     *
     * @return array
     */
    public function getWooCustomerTotals() {
        return [
            'ltv' => null,
            'avg_order_value' => null,
            'orders_count' => null,
        ];
    }

	public function getStaticEvents( $context ) {
	    return isset( $this->staticEvents[ $context ] ) ? $this->staticEvents[ $context ] : array();
    }

	/**
	 * Add static event for each pixel
	 *
	 * @param string           $eventType Event name for internal usage
	 * @param CustomEvent|null $customEvent
	 */
	private function addStaticEvent( $eventType, $customEvent = null, $filterPixelSlug = null ) {

		foreach ( PYS()->getRegisteredPixels() as $pixel ) {
			/** @var Pixel|Settings $pixel */

            if($filterPixelSlug != null && $filterPixelSlug != $pixel->getSlug()) {
                continue;
            }

			$eventData = $pixel->getEventData( $eventType, $customEvent );

			if ( false === $eventData ) {
				continue; // event is disabled or not supported for the pixel
			}

			$eventName = $eventData['name'];
			$ids = isset( $eventData['ids'] ) ? $eventData['ids'] : array();
			
			 $data = array(
				'params' => sanitizeParams( $eventData['data'] ),
				'delay'  => isset( $eventData['delay'] ) ? $eventData['delay'] : 0,
				'ids'    => $ids,
                'eventID' => isset( $eventData['eventID'] ) ? $eventData['eventID'] : "",
			);

            // fire fb server api event
            if($pixel->getSlug() == "facebook" && !Facebook()->getOption( "server_event_use_ajax" ) ) {
                if ($eventData != null) {
                    if ($eventData != null && (!isset($eventData['delay']) || $eventData['delay'] == 0)) {
                        $this->addEventToFacebookServerApi($data["eventID"], $eventType, $eventData);
                    }
                }
            }

            $this->staticEvents[ $pixel->getSlug() ][ $eventName ][] = $data;
		}

	}
	
	private function setupCustomEvents() {
	 
		foreach ( CustomEventFactory::get( 'active' ) as $event ) {
			/** @var CustomEvent $event */
			
            $triggers = $event->getPageVisitTriggers();
            
			// no triggers were defined
			if ( empty( $triggers ) ) {
				continue;
			}

            // match triggers with current page URL
            if ( ! compareURLs( $triggers ) ) {
                continue;
            }

            $this->addStaticEvent( 'custom_event', $event );

		}

	}
    /**
     * @param FDPEvent $event
     * @param $triggers
     */

    private function addFDPDynamicEvent( $event ) {

        foreach ( PYS()->getRegisteredPixels() as $pixel ) {
            /** @var Pixel|Settings $pixel */

            $eventData = $pixel->getEventData( 'fdp_event', $event );
            if ( false === $eventData ) {
                continue;
            }

            if ( $pixel->getSlug() == 'facebook' ) {

                $this->dynamicEventsParams[ $event->event_name ]['facebook'] = array(
                    'name'   => $eventData['name'],
                    'params' => sanitizeParams( $eventData['data'] ),
                    'hasTimeWindow'    => $event->hasTimeWindow(),
                    'timeWindow'    => $event->getTimeWindow(),
                );
            }

            $this->dynamicEventsTriggers[ $event->trigger_type ][ $event->event_name ][] = $event->trigger_value;
        }
    }

    private function setupFDPEvents() {

        if(PYS()->getRegisteredPixels()['facebook'] == null ) return;

        $pixel = PYS()->getRegisteredPixels()['facebook'];

        foreach ( $pixel->getFDPEvents() as $event ) {

            if ( 'fdp_view_content' == $event->event_name && is_single()&& get_post_type() == 'post' ) {
                $this->addStaticEvent( 'fdp_event', $event );
            }

            if ( 'fdp_view_category' == $event->event_name && is_category() ) {
                $this->addStaticEvent( 'fdp_event', $event );
            }

            if ( 'fdp_add_to_cart' == $event->event_name && is_single()&& get_post_type() == 'post' ) {

                $this->addFDPDynamicEvent( $event );
            }

            if ( 'fdp_purchase' == $event->event_name && is_single()&& get_post_type() == 'post' ) {

                $this->addFDPDynamicEvent( $event );
            }
        }

    }

	private function setupWooCommerceEvents() {
	 
		// AddToCart on button
		if ( isEventEnabled( 'woo_add_to_cart_enabled') && PYS()->getOption( 'woo_add_to_cart_on_button_click' ) ) {
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'setupWooLoopProductData' ) );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'setupWooSingleProductData' ) );
            add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'setupWooBlocksProductData' ), 10, 3 );
		}

		// ViewContent
		if ( isEventEnabled( 'woo_view_content_enabled' ) && is_product() ) {

			$this->addStaticEvent( 'woo_view_content' );
			return;

		}

		// ViewCategory
		if ( isEventEnabled( 'woo_view_category_enabled' ) && is_tax( 'product_cat' ) ) {

			$this->addStaticEvent( 'woo_view_category' );
			return;

		}

		// AddToCart on Cart page
		if ( isEventEnabled( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_cart_page' )
		     && is_cart() ) {

			$this->addStaticEvent( 'woo_add_to_cart_on_cart_page' );

		}

		// AddToCart on Checkout page
		if ( isEventEnabled( 'woo_add_to_cart_enabled' ) && PYS()->getOption( 'woo_add_to_cart_on_checkout_page' )
		     && is_checkout() && ! is_wc_endpoint_url() ) {

			$this->addStaticEvent( 'woo_add_to_cart_on_checkout_page' );

		}

		// RemoveFromCart
		if ( isEventEnabled( 'woo_remove_from_cart_enabled') && is_cart() ) {
			add_action( 'woocommerce_after_cart', array( $this, 'setupWooRemoveFromCartData' ) );
		}

		// InitiateCheckout Event
		if ( isEventEnabled( 'woo_initiate_checkout_enabled' ) && is_checkout() && ! is_wc_endpoint_url() ) {
			$this->addStaticEvent( 'woo_initiate_checkout' );
		}
		
		// Purchase Event
		if ( isEventEnabled( 'woo_purchase_enabled' ) && is_order_received_page() && isset( $_REQUEST['key'] ) ) {
			
			
						
		/* NOVO */

		 $order_key = sanitize_key($_REQUEST['key']);
		$order_id = (int) wc_get_order_id_by_order_key( $order_key );

		// skip if event was fired before
		if ( get_post_meta( $order_id, '_pys_purchase_event_fired', true ) ) {
		  return;
		}

		update_post_meta( $order_id, '_pys_purchase_event_fired', true );


		/* NOVO */
			
			
			$this->addStaticEvent( 'woo_purchase' );

            /**
             * Add complete registration event
             */
            if ( Facebook()->enabled() && isEventEnabled( 'complete_registration_event_enabled' ) &&
                Facebook()->getOption("woo_complete_registration_fire_every_time")
            ) {
                $isGdprEnabled = $this->isGdprPluginEnabled();

                if(Facebook()->isServerApiEnabled()) {
                    if(!Facebook()->getOption("woo_complete_registration_send_from_server") ) {
                        $this->addStaticEvent('complete_registration', null, "facebook");
                    } else {
                        if($isGdprEnabled) {
                            $this->addStaticEvent("hCR", null, "facebook");
                        }
                    }

                } else {
                    $this->addStaticEvent('complete_registration', null, "facebook");
                }

            }

		}



	}

    function isGdprPluginEnabled() {
        return apply_filters( 'pys_disable_by_gdpr', false ) ||
            apply_filters( 'pys_disable_facebook_by_gdpr', false ) ||
            isCookiebotPluginActivated() && PYS()->getOption( 'gdpr_cookiebot_integration_enabled' ) ||
            isGingerPluginActivated() && PYS()->getOption( 'gdpr_ginger_integration_enabled' ) ||
            isCookieNoticePluginActivated() && PYS()->getOption( 'gdpr_cookie_notice_integration_enabled' ) ||
            isCookieLawInfoPluginActivated() && PYS()->getOption( 'gdpr_cookie_law_info_integration_enabled' );
    }

    function addEventToFacebookServerApi($eventID,$eventType,$eventData) {
        $isEnabled = $this->isGdprPluginEnabled();


        if( !$isEnabled ) {
            $name = $eventData['name'];
            $data = $eventData['data'];

            if(isset($data['contents'])) {
                $contents = json_decode(stripslashes($data['contents']));
                $data['contents']=$contents;
            }

            $serverEvent = FacebookServer()->createEvent($eventID,$name,$data);
            $this->facebookServerEvents[] = array("pixelIds" => Facebook()->getPixelIDs(), "event" => $serverEvent );
        }
    }


    public function setupWooLoopProductData()
    {
        global $product;
        $this->setupWooProductData($product);
    }

    public function setupWooBlocksProductData($html, $data, $product)
    {
        $this->setupWooProductData($product);
        return $html;
    }

    public function setupWooProductData($product) {

		if ( wooProductIsType( $product, 'variable' ) ) {
			return; // skip variable products
		}
		
		/** @var \WC_Product $product */
		if ( isWooCommerceVersionGte( '2.6' ) ) {
			$product_id = $product->get_id();
		} else {
			$product_id = $product->post->ID;
		}

		$params = array();

		foreach ( PYS()->getRegisteredPixels() as $pixel ) {
			/** @var Pixel|Settings $pixel */

			$eventData = $pixel->getEventData( 'woo_add_to_cart_on_button_click', $product_id );

			if ( false === $eventData ) {
				continue; // event is disabled or not supported for the pixel
			}

			$params[ $pixel->getSlug() ] = sanitizeParams( $eventData['data'] );

		}

		if ( empty( $params ) ) {
			return;
		}

		$params = json_encode( $params );

		?>

		<script type="text/javascript">
            /* <![CDATA[ */
            window.pysWooProductData = window.pysWooProductData || [];
            window.pysWooProductData[ <?php echo $product_id; ?> ] = <?php echo $params; ?>;
            /* ]]> */
		</script>

		<?php

	}

	public function setupWooSingleProductData() {
		global $product;

        if($product == null) return;

		/** @var \WC_Product $product */
		if ( isWooCommerceVersionGte( '2.6' ) ) {
			$product_id = $product->get_id();
		} else {
			$product_id = $product->post->ID;
		}

		// main product id
		$product_ids[] = $product_id;

		// variations ids
		if ( wooProductIsType( $product, 'variable' ) ) {

			/** @var \WC_Product_Variable $variation */
			foreach ( $product->get_available_variations() as $variation ) {

				$variation = wc_get_product( $variation['variation_id'] );
                if(!$variation) continue;
				if ( isWooCommerceVersionGte( '2.6' ) ) {
					$product_ids[] = $variation->get_id();
				} else {
					$product_ids[] = $variation->post->ID;
				}

			}

		}

		$params = array();

		foreach ( $product_ids as $product_id ) {
			foreach ( PYS()->getRegisteredPixels() as $pixel ) {
				/** @var Pixel|Settings $pixel */

				$eventData = $pixel->getEventData( 'woo_add_to_cart_on_button_click', $product_id );

				if ( false === $eventData ) {
					continue; // event is disabled or not supported for the pixel
				}

				$params[ $product_id ][ $pixel->getSlug() ] = sanitizeParams( $eventData['data'] );

			}
		}

		if ( empty( $params ) ) {
			return;
		}

		?>

		<script type="text/javascript">
            /* <![CDATA[ */
            window.pysWooProductData = window.pysWooProductData || [];
			<?php foreach ( $params as $product_id => $product_data ) : ?>
            window.pysWooProductData[<?php echo $product_id; ?>] = <?php echo json_encode( $product_data ); ?>;
			<?php endforeach; ?>
            /* ]]> */
		</script>

		<?php

	}

	public function setupWooRemoveFromCartData() {

		$data = array();

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$item_data = array();

			foreach ( PYS()->getRegisteredPixels() as $pixel ) {
				/** @var Pixel|Settings $pixel */

				$eventData = $pixel->getEventData( 'woo_remove_from_cart', $cart_item );

				if ( false === $eventData ) {
					continue; // event is disabled or not supported for the pixel
				}

				$item_data[ $pixel->getSlug() ] = sanitizeParams( $eventData['data'] );

			}

			if ( ! empty( $item_data ) ) {
				$data[ $cart_item_key ] = $item_data;
			}

		}

		?>

		<script type="text/javascript">
            /* <![CDATA[ */
            window.pysWooRemoveFromCartData = window.pysWooRemoveFromCartData || [];
            window.pysWooRemoveFromCartData = <?php echo json_encode( $data ); ?>;
            /* ]]> */
		</script>

		<?php

	}

	private function setupEddEvents() {
	 
		// AddToCart on button
		if ( isEventEnabled( 'edd_add_to_cart_enabled') && PYS()->getOption( 'edd_add_to_cart_on_button_click' ) ) {
			add_action( 'edd_purchase_link_end', array( $this, 'setupEddSingleDownloadData' ) );
		}

		// ViewContent
		if ( isEventEnabled( 'edd_view_content_enabled' ) && is_singular( 'download' ) ) {

			$this->addStaticEvent( 'edd_view_content' );
			return;

		}

		// ViewCategory
		if ( isEventEnabled( 'edd_view_category_enabled' ) && is_tax( 'download_category' ) ) {

			$this->addStaticEvent( 'edd_view_category' );
			return;

		}

		// AddToCart on Checkout page
		if ( isEventEnabled( 'edd_add_to_cart_enabled' ) && PYS()->getOption( 'edd_add_to_cart_on_checkout_page' )
		     && edd_is_checkout() ) {

			$this->addStaticEvent( 'edd_add_to_cart_on_checkout_page' );

		}

		// RemoveFromCart
		if ( isEventEnabled( 'edd_remove_from_cart_enabled') && edd_is_checkout() ) {
			add_action( 'edd_cart_items_after', array( $this, 'setupEddRemoveFromCartData' ) );
		}

		// InitiateCheckout Event
		if ( isEventEnabled( 'edd_initiate_checkout_enabled' ) && edd_is_checkout() ) {

			$this->addStaticEvent( 'edd_initiate_checkout' );
			return;

		}

		// Purchase Event
		if ( isEventEnabled( 'edd_purchase_enabled' ) && edd_is_success_page() ) {

			/**
			 * When a payment gateway used, user lands to Payment Confirmation page first, which does automatic
			 * redirect to Purchase Confirmation page. We filter Payment Confirmation to avoid double Purchase event.
			 */
			if ( isset( $_GET['payment-confirmation'] ) ) {
				//return;
			}
			
			$payment_key = getEddPaymentKey();
			$order_id = (int) edd_get_purchase_id_by_key( $payment_key );
			$status = edd_get_payment_status( $order_id );

			// pending payment status used because we can't fire event on IPN
			if ( strtolower( $status ) != 'publish' && strtolower( $status ) != 'pending' ) {
				return;
			}
			
			$this->addStaticEvent( 'edd_purchase' );
			return;

		}

	}

	public function setupEddSingleDownloadData() {
		global $post;

		$download_ids = array();

        if ( edd_has_variable_prices( $post->ID ) ) {

            $prices = edd_get_variable_prices( $post->ID );

	        foreach ( $prices as $price_index => $price_data ) {
		        $download_ids[] = $post->ID . '_' . $price_index;
            }

        } else {

	        $download_ids[] = $post->ID;

        }

		$params = array();

		foreach ( $download_ids as $download_id ) {
			foreach ( PYS()->getRegisteredPixels() as $pixel ) {
				/** @var Pixel|Settings $pixel */

				$eventData = $pixel->getEventData( 'edd_add_to_cart_on_button_click', $download_id );

				if ( false === $eventData ) {
					continue; // event is disabled or not supported for the pixel
				}

				$params[ $download_id ][ $pixel->getSlug() ] = sanitizeParams( $eventData['data'] );

			}
		}

		if ( empty( $params ) ) {
			return;
		}

		/**
		 * Format is pysEddProductData[ id ][ id ] or pysEddProductData[ id ] [ id_1, id_2, ... ]
		 */

		?>

        <script type="text/javascript">
            /* <![CDATA[ */
            window.pysEddProductData = window.pysEddProductData || [];
            window.pysEddProductData[<?php echo $post->ID; ?>] = <?php echo json_encode( $params ); ?>;
            /* ]]> */
        </script>

		<?php

    }

    public function setupEddRemoveFromCartData() {

	    $data = array();

	    foreach ( edd_get_cart_contents() as $cart_item_key => $cart_item ) {

		    $item_data = array();

		    foreach ( PYS()->getRegisteredPixels() as $pixel ) {
			    /** @var Pixel|Settings $pixel */

			    $eventData = $pixel->getEventData( 'edd_remove_from_cart', $cart_item );

			    if ( false === $eventData ) {
				    continue; // event is disabled or not supported for the pixel
			    }

			    $item_data[ $pixel->getSlug() ] = sanitizeParams( $eventData['data'] );

		    }

		    if ( ! empty( $item_data ) ) {
			    $data[ $cart_item_key ] = $item_data;
		    }

	    }

	    ?>

        <script type="text/javascript">
            /* <![CDATA[ */
            window.pysEddRemoveFromCartData = window.pysEddRemoveFromCartData || [];
            window.pysEddRemoveFromCartData = <?php echo json_encode( $data ); ?>;
            /* ]]> */
        </script>

	    <?php

    }


}