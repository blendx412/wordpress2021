<?php

class WFOCU_Compatibility_With_FBA {

	public function __construct() {

		add_action( 'wfocu_front_init_funnel_hooks', array( $this, 'prevent_fba_fulfilment' ) );

		add_action( 'woocommerce_thankyou', array( $this, 'maybe_execute_fulfilment' ) );

	}


	public function is_enable() {
		if ( true === class_exists( 'NS_FBA' ) ) {
			return false;
		}

		return true;
	}

	public function prevent_fba_fulfilment() {

		if ( class_exists( 'NS_FBA' ) ) {
			$fba = NS_FBA::get_instance();
			remove_action( 'woocommerce_payment_complete', array( $fba->outbound, 'send_fulfillment_order' ) );

		}

	}

	public function maybe_execute_fulfilment( $order_id ) {

		$get_order     = wc_get_order( $order_id );
		$if_funnel_ran = $get_order->get_meta( '_wfocu_funnel_id', true );
		if ( ! empty( $if_funnel_ran ) && class_exists( 'NS_FBA' ) ) {
			$fba = NS_FBA::get_instance();
			$fba->outbound->send_fulfillment_order( $order_id );
		}

	}


}

WFOCU_Plugin_Compatibilities::register( new WFOCU_Compatibility_With_FBA(), 'fba' );



