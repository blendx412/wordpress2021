<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * This file is to initiate WooFunnel core and to run some common methods and decide which XL core should run
 */

if ( ! class_exists( 'WooFunnel_Loader' ) ) {
	class WooFunnel_Loader {

		public static $plugins = array();
		public static $loaded = false;
		public static $version = '1.6.1';
		public static $ultimate_path = '';

		public static function include_core() {

			$get_configuration = self::get_the_latest();

			if ( false === self::$loaded && $get_configuration && is_array( $get_configuration ) && isset( $get_configuration['class'] ) ) {

				if ( is_callable( array( $get_configuration['class'], 'load_files' ) ) ) {
					self::$version       = $get_configuration['version'];
					self::$ultimate_path = $get_configuration['plugin_path'] . '/woofunnels/';
					self::$loaded        = true;
					call_user_func( array( $get_configuration['class'], 'load_files' ) );
				}
			}

		}

		public static function register( $configuration ) {
			array_push( self::$plugins, $configuration );
		}

		public static function get_the_latest() {
			$get_all = self::$plugins;
			uasort( $get_all, function ( $a, $b ) {
				if ( version_compare( $a['version'], $b['version'], '=' ) ) {
					return 0;
				} else {
					return ( version_compare( $a['version'], $b['version'], '<' ) ) ? - 1 : 1;
				}
			} );

			$get_most_recent_configuration = end( $get_all );

			return $get_most_recent_configuration;
		}

	}
}

class WooFunnel_WFOB {

	public static $version = '1.6.1';

	public static function register() {
		$configuration = array(
			'basename'    => plugin_basename( WFOB_PLUGIN_FILE ),
			'version'     => self::$version,
			'plugin_path' => dirname( WFOB_PLUGIN_FILE ),
			'class'       => __CLASS__,
		);
		WooFunnel_Loader::register( $configuration );

	}

	public static function load_files() {
		$get_global_path = dirname( WFOB_PLUGIN_FILE ) . '/woofunnels/';

		if ( false === @file_exists( $get_global_path . 'includes/class-woofunnels-api.php' ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'WooFunnels Core should be present in folder \'woofunnels\' in order to run this properly. ' ), self::$version );
			die( 0 );
		}

		/**
		 * Loading Core XL Files
		 */
		require_once $get_global_path . 'includes/class-woofunnels-api.php';
		require_once $get_global_path . 'includes/class-woofunnels-admin-notifications.php';
		require_once $get_global_path . 'includes/class-woofunnels-opt-in-manager.php';
		require_once $get_global_path . 'includes/class-woofunnels-addons.php';
		require_once $get_global_path . 'includes/class-woofunnels-licenses.php';
		require_once $get_global_path . 'includes/class-woofunnels-support.php';
		require_once $get_global_path . 'includes/class-woofunnels-process.php';
		require_once $get_global_path . 'includes/class-woofunnels-deactivation.php';
		require_once $get_global_path . 'includes/class-woofunnels-dashboard-loader.php';
		require_once $get_global_path . 'includes/class-woofunnels-cache.php';
		require_once $get_global_path . 'includes/class-woofunnels-transients.php';
		require_once $get_global_path . 'includes/class-woofunnels-file-api.php';
		require_once $get_global_path . 'includes/class-woofunnels-license-check.php';
		require_once $get_global_path . 'includes/class-woofunnels-notifications.php';
		require_once $get_global_path . 'includes/class-bwf-logger.php';

		do_action( 'woofunnels_loaded', $get_global_path );
	}
}

WooFunnel_WFOB::register();


















