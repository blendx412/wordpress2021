<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/** @noinspection PhpIncludeInspection */
require_once PYS_FREE_PATH . '/modules/facebook/function-helpers.php';
require_once PYS_FREE_PATH . '/modules/facebook/FDPEvent.php';



use PixelYourSite\Facebook\Helpers;
use FacebookAds\Api;
use FacebookAds\Object\ServerSide\EventRequest;

class Facebook extends Settings implements Pixel {
	
	private static $_instance;
	
	private $configured;
	
	public static function instance() {
		
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
		
	}

    public function __construct() {
		
        parent::__construct( 'facebook' );
        
        $this->locateOptions(
	        PYS_FREE_PATH . '/modules/facebook/options_fields.json',
	        PYS_FREE_PATH . '/modules/facebook/options_defaults.json'
        );
	
	    add_action( 'pys_register_pixels', function( $core ) {
	    	/** @var PYS $core */
	    	$core->registerPixel( $this );
	    } );
    }
    
    public function enabled() {
	    return $this->getOption( 'enabled' );
    }
	
	public function configured() {
		
		if ( $this->configured === null ) {

			$pixel_id = $this->getPixelIDs();
			$this->configured = $this->enabled()
			                    && count( $pixel_id ) > 0
                                && !empty($pixel_id[0])
			                    && ! apply_filters( 'pys_pixel_disabled', false, $this->getSlug() );
		}
		
		return $this->configured;
		
	}
	
	public function getPixelIDs() {
		
		$ids = (array) $this->getOption( 'pixel_id' );
		$ids = (array) reset( $ids );// return first id only
		return apply_filters("pys_facebook_ids",$ids);

	}
	
	public function getPixelOptions() {
		
		return array(
			'pixelIds'            => $this->getPixelIDs(),
			'advancedMatching'    => $this->getOption( 'advanced_matching_enabled' ) ? Helpers\getAdvancedMatchingParams() : array(),
			'removeMetadata'      => $this->getOption( 'remove_metadata' ),
			'contentParams'       => getTheContentParams(),
			'commentEventEnabled' => $this->getOption( 'comment_event_enabled' ),
			'wooVariableAsSimple' => $this->getOption( 'woo_variable_as_simple' ),
            'downloadEnabled' => $this->getOption( 'download_event_enabled' ),
            'formEventEnabled' => $this->getOption( 'form_event_enabled' ),
            "ajaxForServerEvent"  => $this->getOption( "server_event_use_ajax" ),
            'serverApiEnabled'    => $this->isServerApiEnabled() && count($this->getApiToken()) > 0,
            'wooCRSendFromServer' => $this->getOption("woo_complete_registration_send_from_server") && $this->getOption("woo_complete_registration_fire_every_time")
		);
		
	}
	
	public function getEventData( $eventType, $args = null ) {
		
		if ( ! $this->configured() ) {
			return false;
		}
        $eventData = false;

		switch ( $eventType ) {
			case 'init_event':
                $eventData = $this->getPageViewEventParams(); break;

			case 'general_event':
                $eventData =  $this->getGeneralEventParams(); break;

			case 'search_event':
                $eventData =  $this->getSearchEventParams(); break;

			case 'custom_event':
                $eventData =  $this->getCustomEventParams( $args ); break;

            case 'fdp_event':
                $eventData =  $this->getFDPEventParams( $args ); break;

			case 'woo_view_content':
                $eventData =  $this->getWooViewContentEventParams(); break;

			case 'woo_add_to_cart_on_button_click':
                $eventData =  $this->getWooAddToCartOnButtonClickEventParams( $args ); break;

			case 'woo_add_to_cart_on_cart_page':
			case 'woo_add_to_cart_on_checkout_page':
                $eventData =  $this->getWooAddToCartOnCartEventParams(); break;

			case 'woo_remove_from_cart':
                $eventData =  $this->getWooRemoveFromCartParams( $args ); break;

			case 'woo_view_category':
                $eventData =  $this->getWooViewCategoryEventParams(); break;

			case 'woo_initiate_checkout':
                $eventData =  $this->getWooInitiateCheckoutEventParams(); break;

			case 'woo_purchase':
                $eventData =  $this->getWooPurchaseEventParams(); break;

			case 'edd_view_content':
                $eventData =  $this->getEddViewContentEventParams(); break;

			case 'edd_add_to_cart_on_button_click':
                $eventData =  $this->getEddAddToCartOnButtonClickEventParams( $args ); break;

			case 'edd_add_to_cart_on_checkout_page':
                $eventData =  $this->getEddCartEventParams( 'AddToCart' ); break;

			case 'edd_remove_from_cart':
                $eventData =  $this->getEddRemoveFromCartParams( $args ); break;

			case 'edd_view_category':
                $eventData =  $this->getEddViewCategoryEventParams(); break;

			case 'edd_initiate_checkout':
                $eventData =  $this->getEddCartEventParams( 'InitiateCheckout' ); break;

			case 'edd_purchase':
                $eventData =  $this->getEddCartEventParams( 'Purchase' ); break;

            case 'hCR':
                $eventData =  $this->getCompleteRegistrationEventParams('hCR'); break;

            case 'complete_registration':
                $eventData =  $this->getCompleteRegistrationEventParams(); break;

            default: return false;   // event does not supported
		}

        if($eventData) {
            if($this->isServerApiEnabled()) {
                $eventData['eventID'] = EventIdGenerator::guidv4();
            }
        }

        return $eventData;
	}

	public function outputNoScriptEvents() {
	 
		if ( ! $this->configured() ) {
			return;
		}

		$eventsManager = PYS()->getEventsManager();

		foreach ( $eventsManager->getStaticEvents( 'facebook' ) as $eventName => $events ) {
            if($eventName == "hCR") continue;
			foreach ( $events as $event ) {
				foreach ( $this->getPixelIDs() as $pixelID ) {

					$args = array(
						'id'       => $pixelID,
						'ev'       => urlencode( $eventName ),
						'noscript' => 1,
					);

					foreach ( $event['params'] as $param => $value ) {
						@$args[ 'cd[' . $param . ']' ] = urlencode( $value );
					}

					// ALT tag used to pass ADA compliance
					printf( '<noscript><img height="1" width="1" style="display: none;" src="%s" alt="facebook_pixel"></noscript>',
						add_query_arg( $args, 'https://www.facebook.com/tr' ) );

					echo "\r\n";

				}
			}
		}
		
	}
	
	private function getPageViewEventParams() {
	    $data = array();
		return array(
			'name'  => 'PageView',
			'data'  => $data,
		);

	}

	private function getGeneralEventParams() {

		if ( ! $this->getOption( 'general_event_enabled' ) ) {
			return false;
		}

		$eventName = PYS()->getOption( 'general_event_name' );
		$eventName = sanitizeKey( $eventName );

		if ( empty( $eventName ) ) {
			$eventName = 'GeneralEvent';
		}

		$allowedContentTypes = array(
			'on_posts_enabled'      => PYS()->getOption( 'general_event_on_posts_enabled' ),
			'on_pages_enables'      => PYS()->getOption( 'general_event_on_pages_enabled' ),
			'on_taxonomies_enabled' => PYS()->getOption( 'general_event_on_tax_enabled' ),
			'on_cpt_enabled'        => PYS()->getOption( 'general_event_on_' . get_post_type() . '_enabled', false ),
			'on_woo_enabled'        => PYS()->getOption( 'general_event_on_woo_enabled' ),
			'on_edd_enabled'        => PYS()->getOption( 'general_event_on_edd_enabled' ),
		);

		$params = getTheContentParams( $allowedContentTypes );

		return array(
			'name'  => $eventName,
			'data'  => $params,
			'delay' => (int) PYS()->getOption( 'general_event_delay' ),
		);

	}

	private function getSearchEventParams() {
		global $posts;

		if ( ! $this->getOption( 'search_event_enabled' ) ) {
			return false;
		}

		$params['search_string'] = empty( $_GET['s'] ) ? null : $_GET['s'];

		if ( isWooCommerceActive() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product' ) {

			$limit = min( count( $posts ), 5 );
			$post_ids = array();

			for ( $i = 0; $i < $limit; $i ++ ) {
				$post_ids = array_merge( Helpers\getFacebookWooProductContentId( $posts[ $i ]->ID ), $post_ids );
			}

			$params['content_type'] = 'product';
			$params['content_ids']  = json_encode( $post_ids );

		}

		return array(
			'name'  => 'Search',
			'data'  => $params,
		);

	}

    public function getFDPEvents() {
        $events = array();
        $contentType = $this->getOption("fdp_content_type");
        if($this->getOption("fdp_view_content_enabled")) {
            $event = new FDPEvent();
            $event->event_name = "fdp_view_content";
            $event->content_type = $contentType;
            $events[] = $event;
        }
        if($this->getOption("fdp_view_category_enabled")) {
            $event = new FDPEvent();
            $event->event_name = "fdp_view_category";
            $event->content_type = $contentType;
            $events[] = $event;
        }
        if($this->getOption("fdp_add_to_cart_enabled")) {
            $event = new FDPEvent();
            $event->event_name = "fdp_add_to_cart";
            $event->content_type = $contentType;
            $event->trigger_type = "scroll_pos";
            $event->trigger_value = $this->getOption("fdp_add_to_cart_event_fire_scroll");
            $events[] = $event;
        }
        if($this->getOption("fdp_purchase_enabled")) {
            $event = new FDPEvent();
            $event->event_name = "fdp_purchase";
            $event->content_type = $contentType;
            $event->trigger_type = $this->getOption("fdp_purchase_event_fire");
            if($event->trigger_type == "scroll_pos") {
                $event->trigger_value = $this->getOption("fdp_purchase_event_fire_scroll");
            }
            if($event->trigger_type == "css_click") {
                $event->trigger_value = $this->getOption("fdp_purchase_event_fire_css");
            }

            $events[] = $event;
        }
        return $events;
    }

    /**
     * @param FDPEvent $event
     * @return array
     */

    private function getFDPEventParams($event){

        $name = "";
        $params = "";

        if($event->event_name == "fdp_view_content") {
            $name = "ViewContent";
            $params = Helpers\getFDPViewContentEventParams();
        }

        if($event->event_name == "fdp_view_category") {
            $name = "ViewCategory";
            $params = Helpers\getFDPViewCategoryEventParams();
        }

        if($event->event_name == "fdp_add_to_cart") {
            $name = "AddToCart";
            $params = Helpers\getFDPAddToCartEventParams();
            $params["value"] = $this->getOption("fdp_add_to_cart_value");
            $params["currency"] = $this->getOption("fdp_currency");
        }

        if($event->event_name == "fdp_purchase") {
            $name = "Purchase";
            $params = Helpers\getFDPPurchaseEventParams();
            $params["value"] = $this->getOption("fdp_purchase_value");
            $params["currency"] = $this->getOption("fdp_currency");
        }


        if($event->content_type) {
            $params["content_type"] = $event->content_type;
        }

        return array(
            'name'  => $name,
            'data'  => $params,
            'delay' => 0,
        );
    }

	private function getWooViewContentEventParams() {
		global $post;

		if ( ! $this->getOption( 'woo_view_content_enabled' ) ) {
			return false;
		}

		$params = array();

		$product = wc_get_product( $post->ID );
        if(!$product) return false;
		$content_id = Helpers\getFacebookWooProductContentId( $post->ID );
		$params['content_ids']  = json_encode( $content_id );

		if ( wooProductIsType( $product, 'variable' ) && ! $this->getOption( 'woo_variable_as_simple' ) ) {
			$params['content_type'] = 'product_group';
		} else {
			$params['content_type'] = 'product';
		}

		// Facebook for WooCommerce plugin integration
		if ( ! Helpers\isDefaultWooContentIdLogic() && wooProductIsType( $product, 'variable' ) ) {
			$params['content_type'] = 'product_group';
		}

		// content_name, category_name, tags
		$params['tags'] = implode( ', ', getObjectTerms( 'product_tag', $post->ID ) );
		$params = array_merge( $params, Helpers\getWooCustomAudiencesOptimizationParams( $post->ID ) );

		// currency, value
		if ( PYS()->getOption( 'woo_view_content_value_enabled' ) ) {

            $value_option = PYS()->getOption( 'woo_view_content_value_option' );
            $global_value = PYS()->getOption( 'woo_view_content_value_global', 0 );


            $params['value']    = getWooEventValue( $value_option, $global_value,100, $post->ID ,1);
            $params['currency'] = get_woocommerce_currency();

		}

		// contents
		if ( Helpers\isDefaultWooContentIdLogic() ) {

			// Facebook for WooCommerce plugin does not support new Dynamic Ads parameters
			$params['contents'] = json_encode( array(
				array(
					'id'         => (string) reset( $content_id ),
					'quantity'   => 1,
					//'item_price' => getWooProductPriceToDisplay( $post->ID ),
				)
			) );

		}


		$params['product_price'] = getWooProductPriceToDisplay( $post->ID );

		return array(
			'name'  => 'ViewContent',
			'data'  => $params,
			'delay' => (int) PYS()->getOption( 'woo_view_content_delay' ),
		);

	}

	private function getWooAddToCartOnButtonClickEventParams( $product_id ) {

		if ( ! $this->getOption( 'woo_add_to_cart_enabled' ) || ! PYS()->getOption( 'woo_add_to_cart_on_button_click' ) ) {
			return false;
		}

		$params = Helpers\getWooSingleAddToCartParams( $product_id, 1 );

		return array(
			'data' => $params,
		);

	}

	private function getWooAddToCartOnCartEventParams() {

		if ( ! $this->getOption( 'woo_add_to_cart_enabled' ) ) {
			return false;
		}

		$params = Helpers\getWooCartParams();

		return array(
			'name' => 'AddToCart',
			'data' => $params,
		);

	}

	private function getWooRemoveFromCartParams( $cart_item ) {

		if ( ! $this->getOption( 'woo_remove_from_cart_enabled' ) ) {
			return false;
		}

		$product_id = Helpers\getFacebookWooCartItemId( $cart_item );
		$content_id = Helpers\getFacebookWooProductContentId( $product_id );

		$params['content_type'] = 'product';
		$params['content_ids']  = json_encode( $content_id );

		// content_name, category_name, tags
		$params['tags'] = implode( ', ', getObjectTerms( 'product_tag', $product_id ) );
		$params = array_merge( $params, Helpers\getWooCustomAudiencesOptimizationParams( $product_id ) );

		$params['num_items'] = $cart_item['quantity'];
		$params['product_price'] = getWooProductPriceToDisplay( $product_id );

		$params['contents'] = json_encode( array(
			array(
				'id'         => (string) reset( $content_id ),
				'quantity'   => $cart_item['quantity'],
				//'item_price' => getWooProductPriceToDisplay( $product_id ),
			)
		) );

		return array( 'data' => $params );

	}

	private function getWooViewCategoryEventParams() {
		global $posts;

		if ( ! $this->getOption( 'woo_view_category_enabled' ) ) {
			return false;
		}
        
        if ( Helpers\isDefaultWooContentIdLogic() ) {
            $params['content_type'] = 'product';
        } else {
            $params['content_type'] = 'product_group';
        }
        
        $params['content_category'] = array();
        $term = get_term_by( 'slug', get_query_var( 'term' ), 'product_cat' );
        
        if ( $term ) {
            
            $params['content_name'] = $term->name;
            
            $parent_ids = get_ancestors( $term->term_id, 'product_cat', 'taxonomy' );
            
            foreach ( $parent_ids as $term_id ) {
                $term = get_term_by( 'id', $term_id, 'product_cat' );
                $params['content_category'][] = $term->name;
            }
            
        }
        
        $params['content_category'] = implode( ', ', $params['content_category'] );

		$content_ids = array();
		$limit       = min( count( $posts ), 5 );

		for ( $i = 0; $i < $limit; $i ++ ) {
			$content_ids = array_merge( Helpers\getFacebookWooProductContentId( $posts[ $i ]->ID ), $content_ids );
		}

		$params['content_ids']  = json_encode( $content_ids );

		return array(
			'name' => 'ViewCategory',
			'data' => $params,
		);

	}

	private function getWooInitiateCheckoutEventParams() {

		if ( ! $this->getOption( 'woo_initiate_checkout_enabled' ) ) {
			return false;
		}

		$params = Helpers\getWooCartParams( 'InitiateCheckout' );


		return array(
			'name' => 'InitiateCheckout',
			'data' => $params,
		);

	}

	private function getWooPurchaseEventParams() {

		if ( ! $this->getOption( 'woo_purchase_enabled' ) ) {
			return false;
		}
        $key = sanitize_key($_REQUEST['key']);
        $order_id = (int) wc_get_order_id_by_order_key( $key );
        $order    = new \WC_Order( $order_id );

				
		$pagename = get_query_var('pagename');
		if(get_post_meta($order_id, 'jackopot', true) == 1 && $pagename == 'ukoncit-nakup'){  
			return false;
		}

        $content_ids        = array();
        $content_names      = array();
        $content_categories = array();
        $tags               = array();
        $num_items          = 0;
        $contents           = array();
        
        foreach ( $order->get_items( 'line_item' ) as $line_item ) {
            
            $product_id = Helpers\getFacebookWooCartItemId( $line_item );
            $content_id = Helpers\getFacebookWooProductContentId( $product_id );
            
            $content_ids = array_merge( $content_ids, $content_id );
            
            $num_items += $line_item['qty'];
            
            // content_name, category_name, tags
            $custom_audiences = Helpers\getWooCustomAudiencesOptimizationParams( $product_id );
            
            $content_names[]      = $custom_audiences['content_name'];
            $content_categories[] = $custom_audiences['category_name'];
            
            $cart_item_tags = getObjectTerms( 'product_tag', $product_id );
            $tags           = array_merge( $tags, $cart_item_tags );
            
            // raw product id
            $_product_id = empty( $line_item['variation_id'] ) ? $line_item['product_id']
                : $line_item['variation_id'];
            
            // contents
            $contents[] = array(
                'id'         => (string) reset( $content_id ),
                'quantity'   => $line_item['qty'],
                'item_price' => getWooProductPriceToDisplay( $_product_id ),
            );
            
        }
        
        $params['content_type']  = 'product';
        $params['content_ids']   = json_encode( $content_ids );
        $params['content_name']  = implode( ', ', $content_names );
        $params['category_name'] = implode( ', ', $content_categories );
        
        // contents
        if ( Helpers\isDefaultWooContentIdLogic() ) {
            
            // Facebook for WooCommerce plugin does not support new Dynamic Ads parameters
            $params['contents'] = json_encode( $contents );
            
        }
        
        $tags           = array_unique( $tags );
        $tags           = array_slice( $tags, 0, 100 );
        $params['tags'] = implode( ', ', $tags );
        
        $params['num_items'] = $num_items;
        

        $value_option = PYS()->getOption( 'woo_purchase_value_option' );
        $global_value = PYS()->getOption( 'woo_purchase_value_global', 0 );

        $params['value'] = getWooEventValueOrder( $value_option, $order, $global_value );
        $params['currency'] = get_woocommerce_currency();
        //$params['transaction_id'] = $order_id;

		return array(
			'name' => 'Purchase',
			'data' => $params,
		);

	}

	/**
	 * @param CustomEvent $customEvent
	 *
	 * @return array|bool
	 */
	private function getCustomEventParams( $customEvent ) {

		$event_type = $customEvent->getFacebookEventType();

		if ( ! $customEvent->isFacebookEnabled() || empty( $event_type ) ) {
			return false;
		}

		$params = array();

		// add pixel params
		if ( $customEvent->isFacebookParamsEnabled() ) {

			$params = $customEvent->getFacebookParams();

			// use custom currency if any
			if ( ! empty( $params['custom_currency'] ) ) {
				$params['currency'] = $params['custom_currency'];
				unset( $params['custom_currency'] );
			}

			// add custom params
			foreach ( $customEvent->getFacebookCustomParams() as $custom_param ) {
				$params[ $custom_param['name'] ] = $custom_param['value'];
			}

		}

		return array(
			'name'  => $customEvent->getFacebookEventType(),
			'data'  => $params,
			'delay' => $customEvent->getDelay(),
		);

	}

	private function getEddViewContentEventParams() {
		global $post;

		if ( ! $this->getOption( 'edd_view_content_enabled' ) ) {
			return false;
		}

		$params = array(
			'content_type' => 'product',
			'content_ids'  => json_encode( Helpers\getFacebookEddDownloadContentId( $post->ID ) ),
		);

		// content_name, category_name
		$params['tags'] = implode( ', ', getObjectTerms( 'download_tag', $post->ID ) );
		$params = array_merge( $params, Helpers\getEddCustomAudiencesOptimizationParams( $post->ID ) );

		// currency, value
		if ( PYS()->getOption( 'edd_view_content_value_enabled' ) ) {
            
            $amount = getEddDownloadPriceToDisplay( $post->ID );
			$value_option   = PYS()->getOption( 'edd_view_content_value_option' );
			$global_value   = PYS()->getOption( 'edd_view_content_value_global', 0 );

			$params['value'] = getEddEventValue( $value_option, $amount, $global_value );
            $params['currency'] = edd_get_currency();

		}


		// contents
		$params['contents'] = json_encode( array(
			array(
				'id'         => (string) $post->ID,
				'quantity'   => 1,
				//'item_price' => getEddDownloadPriceToDisplay( $post->ID ),
			)
		) );

		return array(
			'name'  => 'ViewContent',
			'data'  => $params,
			'delay' => (int) PYS()->getOption( 'edd_view_content_delay' ),
		);

	}

	private function getEddAddToCartOnButtonClickEventParams( $download_id ) {
		global $post;

		if ( ! $this->getOption( 'edd_add_to_cart_enabled' ) || ! PYS()->getOption( 'edd_add_to_cart_on_button_click' ) ) {
			return false;
		}

		// maybe extract download price id
		if ( strpos( $download_id, '_') !== false ) {
			list( $download_id, $price_index ) = explode( '_', $download_id );
		} else {
			$price_index = null;
		}

		$params = array(
			'content_type' => 'product',
			'content_ids'  => json_encode( Helpers\getFacebookEddDownloadContentId( $post->ID ) ),
		);

		// content_name, category_name
		$params['tags'] = implode( ', ', getObjectTerms( 'download_tag', $post->ID ) );
		$params = array_merge( $params, Helpers\getEddCustomAudiencesOptimizationParams( $post->ID ) );

		// currency, value
		if ( PYS()->getOption( 'edd_add_to_cart_value_enabled' ) ) {
            
            $amount = getEddDownloadPriceToDisplay( $post->ID, $price_index );
			$value_option = PYS()->getOption( 'edd_add_to_cart_value_option' );
			$global_value = PYS()->getOption( 'edd_add_to_cart_value_global', 0 );

			$params['value'] = getEddEventValue( $value_option, $amount, $global_value );
            $params['currency'] = edd_get_currency();

		}

		// contents
		$params['contents'] = json_encode( array(
			array(
				'id'         => (string) $download_id,
				'quantity'   => 1,
				//'item_price' => getEddDownloadPriceToDisplay( $download_id ),
			)
		) );

		return array(
			'data' => $params,
		);

	}

	private function getEddCartEventParams( $context = 'AddToCart' ) {

		if ( $context == 'AddToCart' && ! $this->getOption( 'edd_add_to_cart_enabled' ) ) {
			return false;
		} elseif ( $context == 'InitiateCheckout' && ! $this->getOption( 'edd_initiate_checkout_enabled' ) ) {
			return false;
		} elseif ( $context == 'Purchase' && ! $this->getOption( 'edd_purchase_enabled' ) ) {
			return false;
		}

		if ( $context == 'AddToCart' ) {
			$value_enabled  = PYS()->getOption( 'edd_add_to_cart_value_enabled' );
			$value_option   = PYS()->getOption( 'edd_add_to_cart_value_option' );
			$global_value   = PYS()->getOption( 'edd_add_to_cart_value_global', 0 );
		} elseif ( $context == 'InitiateCheckout' ) {
			$value_enabled  = PYS()->getOption( 'edd_initiate_checkout_value_enabled' );
			$value_option   = PYS()->getOption( 'edd_initiate_checkout_value_option' );
			$global_value   = PYS()->getOption( 'edd_initiate_checkout_global', 0 );
		} else {
			$value_enabled  = PYS()->getOption( 'edd_purchase_value_enabled' );
			$value_option   = PYS()->getOption( 'edd_purchase_value_option' );
			$global_value   = PYS()->getOption( 'edd_purchase_value_global', 0 );
		}

		$params = array(
			'content_type' => 'product'
		);

		$content_ids        = array();
		$content_names      = array();
		$content_categories = array();
		$tags               = array();
		$contents           = array();

		$num_items = 0;
        $amount = 0;
		
		if ( $context == 'AddToCart' || $context == 'InitiateCheckout' ) {
			$cart = edd_get_cart_contents();
		} else {
			$cart = edd_get_payment_meta_cart_details( edd_get_purchase_id_by_key( getEddPaymentKey() ), true );
		}

		foreach ( $cart as $cart_item_key => $cart_item ) {

			$download_id   = (int) $cart_item['id'];
			$content_ids[] = Helpers\getFacebookEddDownloadContentId( $download_id );

			// content_name, category_name
			$custom_audiences = Helpers\getEddCustomAudiencesOptimizationParams( $download_id );

			$content_names[]      = $custom_audiences['content_name'];
			$content_categories[] = $custom_audiences['category_name'];

			$tags = array_merge( $tags, getObjectTerms( 'download_tag', $download_id ) );

			$num_items += $cart_item['quantity'];

			if ( in_array( $context, array( 'Purchase', 'FrequentShopper', 'VipClient', 'BigWhale' ) ) ) {
				$item_options = $cart_item['item_number']['options'];
			} else {
				$item_options = $cart_item['options'];
			}

			if ( ! empty( $item_options ) && $item_options['price_id'] !== 0 ) {
				$price_index = $item_options['price_id'];
			} else {
				$price_index = null;
			}

			// calculate cart items total
			if ( $value_enabled ) {

				if ( $context == 'Purchase' ) {
                    $amount += $cart_item['price'];
				} else {
                    $amount += edd_get_cart_item_final_price( $cart_item_key );
				}

			}
			
			// contents
			$contents[] = array(
				'id'         => (string) $download_id,
				'quantity'   => $cart_item['quantity'],
				//'item_price' => getEddDownloadPriceToDisplay( $download_id, $price_index ),
			);

		}

		$params['content_ids']   = json_encode( $content_ids );
		$params['content_name']  = implode( ', ', $content_names );
		$params['category_name'] = implode( ', ', $content_categories );
		$params['contents']      = json_encode( $contents );

		$tags           = array_slice( array_unique( $tags ), 0, 100 );
		$params['tags'] = implode( ', ', $tags );

		$params['num_items'] = $num_items;

		// currency, value
		if ( $value_enabled ) {
            $params['currency'] = edd_get_currency();
			$params['value']    = getEddEventValue( $value_option, $amount, $global_value );
		}



		if ( $context == 'Purchase' ) {
			$payment_key = getEddPaymentKey();
			$payment_id = (int) edd_get_purchase_id_by_key( $payment_key );
            $params['value'] = edd_get_payment_amount( $payment_id );
            $params['currency'] = edd_get_currency();
		}

		return array(
			'name' => $context,
			'data' => $params,
		);

	}

	private function getEddRemoveFromCartParams( $cart_item ) {

		if ( ! $this->getOption( 'edd_remove_from_cart_enabled' ) ) {
			return false;
		}

		$download_id = $cart_item['id'];
		$price_index = ! empty( $cart_item['options'] ) ? $cart_item['options']['price_id'] : null;

		$params = array(
			'content_type' => 'product',
			'content_ids' => Helpers\getFacebookEddDownloadContentId( $download_id )
		);

		// content_name, category_name, tags
		$params['tags'] = implode( ', ', getObjectTerms( 'download_tag', $download_id ) );
		$params = array_merge( $params, Helpers\getEddCustomAudiencesOptimizationParams( $download_id ) );

		$params['num_items'] = $cart_item['quantity'];
		$params['contents'] = json_encode( array(
			array(
				'id'         => (string) $download_id,
				'quantity'   => $cart_item['quantity'],
				//'item_price' => getEddDownloadPriceToDisplay( $download_id, $price_index ),
			)
		) );

		return array( 'data' => $params );

	}

	private function getEddViewCategoryEventParams() {
		global $posts;

		if ( ! $this->getOption( 'edd_view_category_enabled' ) ) {
			return false;
		}

		$params['content_type'] = 'product';

		$term = get_term_by( 'slug', get_query_var( 'term' ), 'download_category' );
		$params['content_name'] = $term->name;

		$parent_ids = get_ancestors( $term->term_id, 'download_category', 'taxonomy' );
		$params['content_category'] = array();

		foreach ( $parent_ids as $term_id ) {
			$term = get_term_by( 'id', $term_id, 'download_category' );
			$params['content_category'][] = $term->name;
		}

		$params['content_category'] = implode( ', ', $params['content_category'] );

		$content_ids = array();
		$limit       = min( count( $posts ), 5 );

		for ( $i = 0; $i < $limit; $i ++ ) {
			$content_ids = array_merge( array( Helpers\getFacebookEddDownloadContentId( $posts[ $i ]->ID ) ),
				$content_ids );
		}

		$params['content_ids']  = json_encode( $content_ids );

		return array(
			'name' => 'ViewCategory',
			'data' => $params,
		);

	}

    /**
     * @return array
     */
    public function getApiToken() {
        $tokens = array();
        $pixelids = (array) $this->getOption( 'pixel_id' );
        $serverids = (array) $this->getOption( 'server_access_api_token' );

        if(count($pixelids) == 0) return array();

        if ( isSuperPackActive() && SuperPack()->getOption( 'enabled' ) && SuperPack()->getOption( 'additional_ids_enabled' ) ) {
            foreach ($pixelids as $key => $val) {
                if(isset($serverids[$key]))
                    $tokens[$val] = $serverids[$key];
            }
        } else {
            $tokens[$pixelids[0]] =  reset( $serverids ); // return first id only
        }

        return $tokens;
    }

    /**
     * @return array
     */
    public function getApiTestCode() {
        $testCode = array();
        $pixelids = (array) $this->getOption( 'pixel_id' );
        $serverTestCode = (array) $this->getOption( 'test_api_event_code' );
        if ( isSuperPackActive() && SuperPack()->getOption( 'enabled' ) && SuperPack()->getOption( 'additional_ids_enabled' ) ) {
            foreach ($pixelids as $key => $val) {
                if(isset($serverTestCode[$key]))
                    $testCode[$val] = $serverTestCode[$key];
            }
        } else {
            $testCode[$pixelids[0]] =  reset( $serverTestCode ); // return first id only
        }
        return $testCode;
    }

    /**
     * @return bool
     */
    public function isServerApiEnabled() {
        return $this->getOption("use_server_api");
    }



    private function getCompleteRegistrationEventParams($args=null) {

        if ( ! $this->getOption( 'complete_registration_event_enabled' ) ) {
            return false;
        }
        $params = array();
        if($this->getOption("woo_complete_registration_fire_every_time") &&
            $this->getOption("woo_complete_registration_use_custom_value") &&
            isset( $_REQUEST['key'] ) ) {
            $params = Helpers\getCompleteRegistrationOrderParams();
        }
        $name = isset($args) && $args == "hCR" ? "hCR" : 'CompleteRegistration';

        return $params = array(
            'name'  => $name,
            'data'  => $params,
        );

    }
}

/**
 * @return Facebook
 */
function Facebook() {
	return Facebook::instance();
}

Facebook();