<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WFOB_Compatibility_With_Paypal_Express {
	private $is_checkout = false;

	public function __construct() {
		add_filter( 'wfob_skip_order_bump', [ $this, 'check_ppec_checkout_enable' ], 10, 2 );
	}

	/**
	 * @param $status  bool
	 * @param $instance WFACP_public
	 */
	public function check_ppec_checkout_enable( $status ) {
		if ( function_exists( 'wc_gateway_ppec' ) ) {
			if ( wc_gateway_ppec()->checkout->has_active_session() ) {
				$status = true;
			}
		}

		return $status;
	}
}

WFOB_Plugin_Compatibilities::register( new WFOB_Compatibility_With_Paypal_Express(), 'ppec' );
