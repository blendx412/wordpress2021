<?php
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
include('../../../wp-load.php');

function get_orders_by_custom_date() {
	date_default_timezone_set('Europe/London');

	$yesterday_date = date("m/d/Y", time() - 60 * 60 * 24);
	$yesterday_date_start = $yesterday_date . ' 00:00:00';
	$yesterday_date_end = $yesterday_date . ' 23:59:59';

	$order_statuses = wc_get_order_statuses();
	unset($order_statuses['wc-refunded']);
	$query_args = array(
		'post_type'      => array('shop_order'),
		'post_status'    => array_keys( $order_statuses ),
		'posts_per_page' => -1,
		'date_query' => array(
		'after'     => $yesterday_date_start,
		'before'    => $yesterday_date_end,
		),
	);
	$customer_orders = get_posts( $query_args );

	return $customer_orders;
}

function get_all_order_items_with_data() {
	$orders = get_orders_by_custom_date();
	$orderItems = [];
	foreach ($orders as $key => $v) {
		$order = wc_get_order($v->ID);
		$order_id = $order->get_id();

		$count = 0;
		foreach ($order->get_items() as $keyItem => $item) {
			$product = wc_get_product($item->get_product_id());
			$item_id = $item->get_product_id();
			$item_sku = $product->get_sku();
			$item_title = $product->get_title();
			$item_quantity = $item->get_quantity();

			$orderItems[$keyItem]['id'] = $item_id;
			$orderItems[$keyItem]['sku'] = $item_sku;
			$orderItems[$keyItem]['title'] = $item_title;
			$orderItems[$keyItem]['quantity'] = $item_quantity;

			if (get_post_meta( $item['variation_id'], '_sku', true )) {
				$item_variation_sku = get_post_meta( $item['variation_id'], '_sku', true );
				$orderItems[$keyItem]['variation_sku'] = $item_variation_sku;
			}
			$count++;
		}
	}

	return $orderItems;
}

function sumarize_all_order_items() {
	$orderItems = get_all_order_items_with_data();

	$combinedItems = array();

	foreach ($orderItems as $values) {
		// Define key
		$key = $values['sku'];
		if ($values['variation_sku'])
			$key = $values['variation_sku'];
		// Assign to the new array using all of the actual values
		$combinedItems[$key][] = $values;
	}

	return $combinedItems;
}

$sumarizedItems = sumarize_all_order_items();

foreach ($sumarizedItems as $key => $value) {
	$quantity = 0;
	foreach ($value as $keyValue => $item) {
		$quantity = $quantity + $item['quantity'];
	}
	$sep = ',';
	$delimiter = ':';
	echo $value[0]['title'].$delimiter.$value[0]['sku'].$delimiter.$value[0]['id'].$delimiter.$value[0]['variation_sku'].$delimiter.$quantity.$sep;
}
