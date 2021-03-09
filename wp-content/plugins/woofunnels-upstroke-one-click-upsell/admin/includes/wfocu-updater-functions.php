<?php
if ( ! function_exists( 'wfocu_rest_update_missing_gateways' ) ) {
	function wfocu_rest_update_missing_gateways() {
		global $wpdb;
		/**
		 * get all the orders which still do not have payment_gateway updated.
		 */
		$query_select = $wpdb->prepare( "SELECT order_id from `" . $wpdb->prefix . "wfocu_session` WHERE `gateway` = %s", '' );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.

		$orders = $wpdb->get_results( $query_select, ARRAY_A );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.

		if ( $orders && is_array( $orders ) && count( $orders ) > 0 ) {

			$get_unique_orders = array_unique( wp_list_pluck( $orders, 'order_id' ) );
			$post__in          = implode( ',', $get_unique_orders );
			/**
			 * get payment_gateways for existing orders
			 */
			$query_select_2 = $wpdb->prepare( "SELECT meta_value,post_id from `" . $wpdb->prefix . "postmeta` WHERE `meta_key` = %s AND `post_id` IN ($post__in)", '_payment_method' );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.
			$results        = $wpdb->get_results( $query_select_2, ARRAY_A );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.
			if ( is_array( $results ) && count( $results ) > 0 ) {
				foreach ( $results as $result ) {
					$order_id = $result['post_id'];
					$gateway  = $result['meta_value'];
					/**
					 * Update session table for rest of the orders
					 */
					$query = $wpdb->prepare( "UPDATE`" . $wpdb->prefix . "wfocu_session` SET `gateway` = %s WHERE `order_id` = %s", $gateway, $order_id );


					$wpdb->query( $query );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.
				}
			}


		}
	}
}


if ( ! function_exists( 'wfocu_maybe_update_sessions_on_2_0' ) ) {


	/**
	 * @hooked `shutdown`
	 * As we moved to DB version 1.0 with the new table structure.
	 * We need to ensure the data for the upsell gross amount get stored in the order meta.
	 *
	 */
	function wfocu_maybe_update_sessions_on_2_0() {


		global $wpdb;

		/**
		 * Fetch payment method and creation time for the order where upsell got accepted.
		 */
		$query = $wpdb->prepare( "SELECT b.meta_value as 'payment_method', a.meta_value as 'total', a.post_id as 'order_id', c.post_date as 'created_date'
FROM `" . $wpdb->prefix . "postmeta` a 
INNER JOIN `" . $wpdb->prefix . "postmeta` b 
INNER JOIN `" . $wpdb->prefix . "posts` c 
ON a.post_id = b.post_id AND a.post_id = c.ID
WHERE a.`meta_key` LIKE %s AND a.`meta_value` != '' AND b.meta_key LIKE %s  
ORDER BY `order_id` ASC", '_wfocu_upsell_amount', '_payment_method' );

		$results = $wpdb->get_results( $query, ARRAY_A );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.

		if ( is_array( $results ) && count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$order_id   = $result['order_id'];
				$upsell_val = $result['total'];
				$gateway    = $result['payment_method'];
				$timestamp  = $result['created_date'];

				/**
				 * Update session table with the data
				 */
				$query = $wpdb->prepare( "UPDATE`" . $wpdb->prefix . "wfocu_session` SET `gateway` = %s,`timestamp` = %s, `total` = %s WHERE `order_id` = %s", $gateway, $timestamp, $upsell_val, $order_id );


				$wpdb->query( $query );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.
			}


		}


	}
}