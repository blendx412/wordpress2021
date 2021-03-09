<?php
/*
Plugin Name: WooCommerce Bulk Variations
Plugin URI: https://fancyproductdesigner.com
Description: Let your customers add multiple variations at once to the cart.
Version: 1.0.5
Author: fancyproductdesigner.com
Author URI: https://fancyproductdesigner.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (!defined('WCBV_PLUGIN_DIR'))
    define( 'WCBV_PLUGIN_DIR', dirname(__FILE__) );

if (!defined('WCBV_PLUGIN_ROOT_PHP'))
    define( 'WCBV_PLUGIN_ROOT_PHP', dirname(__FILE__).'/'.basename(__FILE__)  );

if (!defined('WCBV_PLUGIN_ADMIN_DIR'))
    define( 'WCBV_PLUGIN_ADMIN_DIR', dirname(__FILE__) . '/admin' );

if( !class_exists('WooCommerce_Bulk_Variations') ) {

	class WooCommerce_Bulk_Variations {

		const VERSION = '1.0.5';
		const FPD_MIN_VERSION = '3.6.1';
		const CAPABILITY = "edit_wcbv";
		const DEBUG = false; //load unminified js files in frontend

		public function __construct() {

			require_once( WCBV_PLUGIN_DIR.'/inc/functions.php' );
			require_once( WCBV_PLUGIN_DIR.'/admin/class-admin.php' );
			require_once( WCBV_PLUGIN_DIR.'/inc/class-scripts-styles.php' );

			add_action( 'plugins_loaded', array( &$this,'plugins_loaded' ) );
			add_action( 'init', array( &$this, 'init') );

		}

		public function plugins_loaded() {

			load_plugin_textdomain( 'radykal', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

		}

		public function init() {

			require_once( WCBV_PLUGIN_DIR.'/inc/class-frontend-product.php' );

		}

	}
}

new WooCommerce_Bulk_Variations();

?>