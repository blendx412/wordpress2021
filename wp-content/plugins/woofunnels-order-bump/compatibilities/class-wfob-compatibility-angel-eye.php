<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WFOB_Compatibility_With_Angel_Eye {
	public function __construct() {
		add_filter( 'wfob_skip_order_bump', [ $this, 'check_angel_eye_checkout_enable' ] );

	}

	/**
	 * @param $status  bool
	 * @param $instance WFACP_public
	 */
	public function check_angel_eye_checkout_enable( $status ) {
		if ( ! is_admin() ) {
			$paypal_express_checkout = WC()->session->get( 'paypal_express_checkout' );
			if ( isset( $paypal_express_checkout ) ) {
				$status = true;
			}
		}

		return $status;
	}
}

WFOB_Plugin_Compatibilities::register( new WFOB_Compatibility_With_Angel_Eye(), 'angel_eye' );
