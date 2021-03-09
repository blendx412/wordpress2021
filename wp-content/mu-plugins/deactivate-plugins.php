<?php
/**
 * @package deactivate-plugins
 * @version 1.0
 *
 * Plugin Name: Deactivate Plugins
 * Plugin URI: http://wordpress.org/extend/plugins/#
 * Description: Plugin deactivation for specific page
 * Author: Rok Premrl
 * Version: 1.0
 * Author URI: https://ms3.si/
 */

$request_uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$is_admin = strpos( $request_uri, '/wp-admin/' );
if( false === $is_admin ){
	// filter active plugins
	add_filter( 'option_active_plugins', function( $plugins ){
		global $request_uri;
		$is_thankyou_page = strpos( $request_uri, '/order-received/' ); 
		// change elements according to your needs
		$myplugins = array( 
			"pixelyoursite-pro/pixelyoursite-pro.php", 
			"pixelyoursite-super-pack/pixelyoursite-super-pack.php",
			"woofunnels-order-bump/woofunnels-order-bump.php",
			"woofunnels-upstroke-multiple-products/woofunnels-upstroke-multiple-products.php",
			"woofunnels-upstroke-one-click-upsell/woofunnels-upstroke-one-click-upsell.php",
			"woofunnels-upstroke-reports/woofunnels-upstroke-reports.php",
		);
		if( $is_thankyou_page ){
			$plugins = array_diff( $plugins, $myplugins );
			//var_dump($plugins);
		}
		return $plugins;
	} );
}