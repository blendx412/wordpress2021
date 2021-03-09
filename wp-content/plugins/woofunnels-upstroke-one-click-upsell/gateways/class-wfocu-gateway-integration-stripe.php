<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * WC_Gateway_Stripe_Addons class.
 *
 * @extends WFOCU_Gateway
 */
class WFOCU_Gateway_Integration_Stripe extends WFOCU_Gateway {


	protected static $ins = null;
	public $key = 'stripe';
	public $token = false;
	public $has_intent_secret = false;

	/**
	 * Constructor
	 */
	public function __construct() {

		parent::__construct();
		add_filter( 'wc_stripe_force_save_source', array( $this, 'should_tokenize_stripe' ), 999 );
		add_filter( 'wc_stripe_display_save_payment_method_checkbox', array( $this, 'maybe_hide_save_payment' ), 999 );
		add_filter( 'wc_stripe_3ds_source', array( $this, 'maybe_modify_3ds_prams' ), 10, 2 );

		add_action( 'wc_gateway_stripe_process_response', array( $this, 'maybe_handle_redirection_stripe' ), 10, 2 );

		add_action( 'wc_gateway_stripe_process_redirect_payment', array( $this, 'maybe_log_process_redirect' ), 1 );

		add_action( 'wfocu_offer_new_order_created_stripe', array( $this, 'add_stripe_payouts_to_new_order' ), 10, 2 );

		add_filter( 'woocommerce_payment_successful_result', array( $this, 'maybe_flag_has_intent_secret' ), 9999, 2 );
		add_filter( 'woocommerce_payment_successful_result', array( $this, 'modify_successful_payment_result_for_upstroke' ), 999910, 2 );

		$this->refund_supported = true;

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function maybe_hide_save_payment( $is_show ) {
		if ( false !== $this->should_tokenize() ) {
			return false;
		}

		return $is_show;
	}


	public function should_tokenize_stripe( $save_token ) {

		if ( false !== $this->should_tokenize() ) {

			return true;
		}

		return $save_token;
	}

	/**
	 * Try and get the payment token saved by the gateway
	 *
	 * @param WC_Order $order
	 *
	 * @return true on success false otherwise
	 */
	public function has_token( $order ) {
		$get_id = WFOCU_WC_Compatibility::get_order_id( $order );

		$this->token = get_post_meta( $get_id, '_wfocu_stripe_source_id', true );
		if ( $this->token === '' ) {
			$this->token = get_post_meta( $get_id, '_stripe_source_id', true );

		}

		if ( ! empty( $this->token ) ) {
			return true;
		}

		return false;

	}

	public function process_charge( $order ) {

		$is_successful = false;

		$gateway = $this->get_wc_gateway();

		$source = $gateway->prepare_order_source( $order );

		/**
		 * IN case of source is not returned by the gateway class, we try and prepare the data
		 */
		if ( empty( $source ) ) {
			$source = $this->prepare_order_source( $order );
		}
		$response = WC_Stripe_API::request( $this->generate_payment_request( $order, $source ) );

		WFOCU_Core()->log->log( print_r( $response, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

		if ( is_wp_error( $response ) ) {
			WFOCU_Core()->log->log( 'Order #' . WFOCU_WC_Compatibility::get_order_id( $order ) . ': Payment Failed For Stripe' );
		} else {
			if ( ! empty( $response->error ) ) {
				$is_successful = false;
				throw new WFOCU_Payment_Gateway_Exception( $response->error->message, 102, $this->get_key() );


			} else {
				WFOCU_Core()->data->set( '_transaction_id', $response->id );

				$is_successful = true;
			}
		}


		if ( true === $is_successful ) {

			$fee      = ! empty( $response->balance_transaction->fee ) ? WC_Stripe_Helper::format_balance_fee( $response->balance_transaction, 'fee' ) : 0;
			$net      = ! empty( $response->balance_transaction->net ) ? WC_Stripe_Helper::format_balance_fee( $response->balance_transaction, 'net' ) : 0;
			$currency = ! empty( $response->balance_transaction->currency ) ? strtoupper( $response->balance_transaction->currency ) : null;

			/**
			 * Handling for Stripe Fees
			 */
			$order_behavior = WFOCU_Core()->funnels->get_funnel_option( 'order_behavior' );
			$is_batching_on = ( 'batching' === $order_behavior ) ? true : false;
			if ( true === $is_batching_on ) {
				$fee = $fee + WC_Stripe_Helper::get_stripe_fee( $order );
				$net = $net + WC_Stripe_Helper::get_stripe_net( $order );
				WC_Stripe_Helper::update_stripe_fee( $order, $fee );
				WC_Stripe_Helper::update_stripe_net( $order, $net );
			}
			WFOCU_Core()->data->set( 'wfocu_stripe_fee', $fee );
			WFOCU_Core()->data->set( 'wfocu_stripe_net', $net );
			WFOCU_Core()->data->set( 'wfocu_stripe_currency', $currency );
		}

		return $this->handle_result( $is_successful );
	}

	/**
	 * Generate the request for the payment.
	 *
	 * @param WC_Order $order
	 * @param object $source
	 *
	 * @return array()
	 */
	protected function generate_payment_request( $order, $source ) {
		$get_package = WFOCU_Core()->data->get( '_upsell_package' );

		$gateway               = $this->get_wc_gateway();
		$post_data             = array();
		$post_data['currency'] = strtolower( WFOCU_WC_Compatibility::get_order_currency( $order ) );
		$total                 = WC_Stripe_Helper::get_stripe_amount( $get_package['total'], $post_data['currency'] );

		if ( $total < WC_Stripe_Helper::get_minimum_amount() ) {
			/* translators: 1) dollar amount */
			throw new WFOCU_Payment_Gateway_Exception( sprintf( __( 'Sorry, the minimum allowed order total is %1$s to use this payment method.', 'woocommerce-gateway-stripe' ), wc_price( WC_Stripe_Helper::get_minimum_amount() / 100 ) ), 101, $this->get_key() );
		}
		$post_data['amount']      = $total;
		$post_data['description'] = sprintf( __( '%1$s - Order %2$s - 1 click upsell: %3$s', 'woocommerce-gateway-stripe' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number(), 1211 );
		$post_data['capture']     = $gateway->capture ? 'true' : 'false';
		$billing_first_name       = WFOCU_WC_Compatibility::get_billing_first_name( $order );
		$billing_last_name        = WFOCU_WC_Compatibility::get_billing_last_name( $order );
		$billing_email            = WFOCU_WC_Compatibility::get_order_data( $order, 'billing_email' );
		$settings                 = get_option( 'woocommerce_stripe_settings', array() );
		$statement_descriptor     = ! empty( $settings['statement_descriptor'] ) ? str_replace( "'", '', $settings['statement_descriptor'] ) : '';
		if ( ! empty( $statement_descriptor ) ) {
			$post_data['statement_descriptor'] = WC_Stripe_Helper::clean_statement_descriptor( $statement_descriptor );
		}
		if ( ! empty( $billing_email ) && apply_filters( 'wc_stripe_send_stripe_receipt', false ) ) {
			$post_data['receipt_email'] = $billing_email;
		}
		$metadata              = array(
			__( 'customer_name', 'woocommerce-gateway-stripe' )  => sanitize_text_field( $billing_first_name ) . ' ' . sanitize_text_field( $billing_last_name ),
			__( 'customer_email', 'woocommerce-gateway-stripe' ) => sanitize_email( $billing_email ),
			'order_id'                                           => $this->get_order_number( $order ),
		);
		$post_data['expand[]'] = 'balance_transaction';
		$post_data['metadata'] = apply_filters( 'wc_stripe_payment_metadata', $metadata, $order, $source );

		if ( $source->customer ) {
			$post_data['customer'] = $source->customer;
		}

		if ( $source->source ) {

			$get_secondary_source = $order->get_meta( '_wfocu_stripe_source_id', true );
			$post_data['source']  = ( '' !== $get_secondary_source ) ? $get_secondary_source : $source->source;
		}
		WFOCU_Core()->log->log( "Stripe Request Data:" . print_r( $post_data, true ) ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

		return apply_filters( 'wc_stripe_generate_payment_request', $post_data, $order, $source );
	}

	public function maybe_modify_3ds_prams( $threds_data, $order ) {

		$order->update_meta_data( '_wfocu_stripe_source_id', $threds_data['three_d_secure']['card'] );
		$order->save();

		return $threds_data;
	}

	/**
	 * Get the wc-api URL to redirect to
	 *
	 * @param string $action checkout action, either `set_express_checkout or `get_express_checkout_details`
	 *
	 * @return string URL
	 * @since 2.0
	 */
	public function get_callback_url( $action ) {
		return add_query_arg( 'action', $action, WC()->api_request_url( 'wfocu_stripe' ) );
	}

	/**
	 * Maybe Handle PayPal Redirection for 3DS Checkout
	 * Let our hooks modify the order received url and redirect user to offer page.
	 *
	 * @param $response
	 * @param WC_Order $order
	 */
	public function maybe_handle_redirection_stripe( $response, $order ) {

		if ( false === $this->is_enabled() ) {
			WFOCU_Core()->log->log( 'Do not initiate redirection for stripe: Stripe is disabled' );

		}

		/**
		 * Validate if its a redirect checkout call for the stripe
		 * Validate if funnel initiation happened.
		 */
		if ( 1 === did_action( 'wfocu_front_init_funnel_hooks' ) && 1 === did_action( 'wc_gateway_stripe_process_redirect_payment' ) ) {
			$get_url = $order->get_checkout_order_received_url();
			wp_redirect( $get_url );
			exit();
		}

	}

	public function maybe_log_process_redirect() {
		WFOCU_Core()->log->log( 'Entering: ' . __CLASS__ . '::' . __FUNCTION__ );
	}

	/**
	 * @param WC_Order $order
	 * @param Integer $transaction
	 */
	public function add_stripe_payouts_to_new_order( $order, $transaction ) {
		$fee = WFOCU_Core()->data->get( 'wfocu_stripe_fee' );
		$net = WFOCU_Core()->data->get( 'wfocu_stripe_net' );

		$currency = WFOCU_Core()->data->get( 'wfocu_stripe_currency' );
		WC_Stripe_Helper::update_stripe_currency( $order, $currency );
		WC_Stripe_Helper::update_stripe_fee( $order, $fee );
		WC_Stripe_Helper::update_stripe_net( $order, $net );
		$order->save_meta_data();
	}

	/**
	 * Handling refund offer request from admin
	 *
	 * @throws WC_Stripe_Exception
	 */
	public function process_refund_offer( $order ) {
		$refund_data = $_POST;  // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

		$txn_id = isset( $refund_data['txn_id'] ) ? $refund_data['txn_id'] : '';
		$amnt   = isset( $refund_data['amt'] ) ? $refund_data['amt'] : '';

		$order_currency = WFOCU_WC_Compatibility::get_order_currency( $order );

		$request  = array();
		$response = false;

		if ( ! is_null( $amnt ) && class_exists( 'WC_Stripe_Helper' ) ) {
			$request['amount'] = WC_Stripe_Helper::get_stripe_amount( $amnt, $order_currency );
		}

		$request['charge'] = $txn_id;

		WFOCU_Core()->log->log( 'Stripe offer refund request data' . print_r( $request, true ) );  // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

		if ( class_exists( 'WC_Stripe_API' ) ) {
			$response = WC_Stripe_API::request( $request, 'refunds' );
		}

		WFOCU_Core()->log->log( 'WFOCU Stripe Offer refund response: ' . print_r( $response, true ) );  // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

		if ( ! empty( $response->error ) || ! $response ) {
			return false;
		} else {
			/**
			 * Lets try and update sripe transaction amounts in the Databse
			 */
			$this->get_wc_gateway()->update_fees( $order, $response->balance_transaction );

			return isset( $response->id ) ? $response->id : true;
		}
	}

	/**
	 * Adding custom note if offer amount is not caputered yet
	 *
	 * @param $order
	 * @param $amnt
	 * @param $refund_id
	 * @param $offer_id
	 * @param $refund_reason
	 */
	public function wfocu_add_order_note( $order, $amnt, $refund_id, $offer_id, $refund_reason ) {
		$captured = WFOCU_WC_Compatibility::get_order_data( $order, '_stripe_charge_captured' );
		if ( isset( $captured ) && 'yes' === $captured ) {
			parent::wfocu_add_order_note( $order, $amnt, $refund_id, $offer_id, $refund_reason );
		} else {
			/* translators: 1) dollar amount 2) transaction id 3) refund message */
			$refund_note = sprintf( __( 'Pre-Authorization Released %1$s <br/>Offer: %2$s(#%3$s) %4$s', 'woofunnels-upstroke-one-click-upsell' ), $amnt, get_the_title( $offer_id ), $offer_id, $refund_reason );
			$order->add_order_note( $refund_note );
		}
	}

	/**
	 *  Creating transaction test/URL
	 *
	 * @param $transaction_id
	 * @param $order_id
	 *
	 * @return string
	 */
	public function get_transaction_link( $transaction_id, $order_id ) {

		$testmode = $this->get_wc_gateway()->testmode;

		if ( $transaction_id ) {
			if ( $testmode ) {
				$view_transaction_url = sprintf( 'https://dashboard.stripe.com/test/payments/%s', $transaction_id );
			} else {
				$view_transaction_url = sprintf( 'https://dashboard.stripe.com/payments/%s', $transaction_id );
			}
		}

		if ( ! empty( $view_transaction_url ) && ! empty( $transaction_id ) ) {
			$return_url = sprintf( '<a href="%s">%s</a>', $view_transaction_url, $transaction_id );

			return $return_url;
		}

		return $transaction_id;
	}

	public function maybe_flag_has_intent_secret( $result, $order_id ) {
		// Only redirects with intents need to be modified.
		if ( isset( $result['intent_secret'] ) ) {
			$this->has_intent_secret = $result['intent_secret'];
		}

		return $result;
	}

	public function modify_successful_payment_result_for_upstroke( $result, $order_id ) {
		// Only redirects with intents need to be modified.
		if ( false === $this->has_intent_secret ) {
			return $result;
		}

		if ( false === $this->should_tokenize() ) {
			return $result;
		}

		// Put the final thank you page redirect into the verification URL.
		$verification_url = add_query_arg( array(
			'order' => $order_id,
			'nonce' => wp_create_nonce( 'wc_stripe_confirm_pi' ),
		), WC_AJAX::get_endpoint( 'wc_stripe_verify_intent' ) );

		// Combine into a hash.
		$redirect                = sprintf( '#confirm-pi-%s:%s', $this->has_intent_secret, rawurlencode( $verification_url ) );
		$this->has_intent_secret = false;

		return array(
			'result'   => 'success',
			'redirect' => $redirect,
		);
	}


	/**
	 * Get payment source from an order. This could be used in the future for
	 * a subscription as an example, therefore using the current user ID would
	 * not work - the customer won't be logged in :)
	 *
	 * Not using 2.6 tokens for this part since we need a customer AND a card
	 * token, and not just one.
	 *
	 * @param object $order
	 *
	 * @return object
	 * @since 3.1.0
	 * @version 4.0.0
	 */
	public function prepare_order_source( $order = null ) {
		$stripe_customer = new WC_Stripe_Customer();
		$stripe_source   = false;
		$token_id        = false;
		$source_object   = false;

		if ( $order ) {
			$order_id = $order->get_id();

			$stripe_customer_id = get_post_meta( $order_id, '_stripe_customer_id', true );

			if ( $stripe_customer_id ) {
				$stripe_customer->set_id( $stripe_customer_id );
			}

			$source_id = $order->get_meta( '_stripe_source_id', true );

			// Since 4.0.0, we changed card to source so we need to account for that.
			if ( empty( $source_id ) ) {
				$source_id = $order->get_meta( '_stripe_card_id', true );

				$order->update_meta_data( '_stripe_source_id', $source_id );

				if ( is_callable( array( $order, 'save' ) ) ) {
					$order->save();
				}
			}

			if ( $source_id ) {
				$stripe_source = $source_id;
				$source_object = WC_Stripe_API::retrieve( 'sources/' . $source_id );
			} elseif ( apply_filters( 'wc_stripe_use_default_customer_source', true ) ) {
				/*
				 * We can attempt to charge the customer's default source
				 * by sending empty source id.
				 */
				$stripe_source = '';
			}
		}
		WFOCU_Core()->log->log('Order #' . WFOCU_WC_Compatibility::get_order_id( $order ) . ': Stripe fallback stripe source created' );
		return (object) array(
			'token_id'      => $token_id,
			'customer'      => $stripe_customer ? $stripe_customer->get_id() : false,
			'source'        => $stripe_source,
			'source_object' => $source_object,
		);
	}
}

WFOCU_Gateway_Integration_Stripe::get_instance();
