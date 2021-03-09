<?php

class WFOCU_Affiliate_WP_Compatibility {

	public function __construct() {
		add_action( 'wfocu_offer_accepted_and_processed', array( $this, 'wfocu_add_affiliate_on_order' ), 10, 5 );

	}

	public function is_enable() {
		if ( defined( 'AFFILIATEWP_VERSION' ) ) {
			return true;
		}

		return false;
	}

	public function wfocu_add_affiliate_on_order( $offer_id, $package, $order, $new_order, $transaction_id ) {
		if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
			define( 'WOOCOMMERCE_CHECKOUT', true );
		}

		if ( class_exists( 'Affiliate_WP_WooCommerce' ) ) {
			$obj = new Affiliate_WP_WooCommerce;
			if ( ! empty( $new_order ) && is_object( $new_order ) ) {
				$order_id = $new_order->get_id();
				$obj->add_pending_referral( $order_id );
				$obj->mark_referral_complete( $order_id );
			} else {
				global $wpdb;
				$order_id   = $order->get_id();
				$table_name = $wpdb->prefix . 'affiliate_wp_referrals';
				$data       = array( 'status' => 'pending' );
				$where      = array(
					'context'   => 'woocommerce',
					'reference' => $order_id,
				);
				$wpdb->update( $table_name, $data, $where );

				$obj->add_pending_referral( $order_id );
				$obj->mark_referral_complete( $order_id );
			}
		}

	}
}

WFOCU_Plugin_Compatibilities::register( new WFOCU_Affiliate_WP_Compatibility(), 'wfocu_affiliate_wp' );
