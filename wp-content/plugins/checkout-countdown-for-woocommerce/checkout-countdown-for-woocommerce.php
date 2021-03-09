<?php
/**
 * Plugin Name:       Checkout Countdown for WooCommerce
 * Description:       A flexible WooCommerce cart/checkout countdown to help improve cart conversion.
 * Version:           3.0.3
 * Author:            Puri.io
 * Author URI:        https://puri.io/
 * Text Domain:       checkout-countdown-for-woocommerce
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'ccfwoo_setup' ) ) {

	function ccfwoo_core_activation() {

		do_action( 'ccfwoo_core_activation' );

	}
	register_activation_hook( __FILE__, 'ccfwoo_core_activation' );


	/**
	 * Setup our config
	 *
	 * @param  string $option select a specific option.
	 * @param  string $pro true or false.
	 * @return returns the setup array or specify key, or pro key.
	 */
	function ccfwoo_setup( $option = false, $pro = false ) {
		// Get the current plugin version.
		$version = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
		$version = $version['Version'];
		// Setup our plugin.
		$setup = array(
			'name'        => 'Checkout Countdown',
			'prefix'      => 'ccfwoo',
			'slug'        => basename( __FILE__, '.php' ),
			'admin_page'  => 'checkout-countdown',
			'version'     => $version,
			'text-domain' => 'checkout-countdown-for-woocommerce',
			'path'        => __FILE__,
		);

		$setup = apply_filters( $setup['prefix'] . '_extend_setup', $setup );

		if ( ! empty( $option ) ) {
			if ( true === $pro ) {
				return ! empty( $setup['pro'][ $option ] ) ? $setup['pro'][ $option ] : false;
			}
			return $setup[ $option ];
		}
		return $setup;
	}


	/**
	 * Init Everyting
	 */
	function ccfwoo_core_init() {

		do_action( 'ccfwoo_before_core_loaded' );

		$enable_countdown = ccfwoo_get_option( 'enable' );

		if ( $enable_countdown === 'on' ) {
			require_once plugin_dir_path( __FILE__ ) . 'functions/functions.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/enqueue.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/shortcode.php';
		}

		// Setup our admin page with Banana, contains class Checkout_Countdown_Main();
		require_once dirname( __FILE__ ) . '/settings/settings.php';

		new Checkout_Countdown_Main( ccfwoo_setup() );

		do_action( 'ccfwoo_after_core_loaded' );
	}

	add_action( 'plugins_loaded', 'ccfwoo_core_init', 70 );

	/**
	 * Get the value of a settings field
	 *
	 * @param string $option settings field name.
	 * @param string $section the section name this field belongs to.
	 * @param string $default default text if it's not found.
	 *
	 * @return mixed
	 */

	function ccfwoo_get_option( $option, $section = false, $default = '' ) {

		$section = $section === false ? 'ccfwoo_general_section' : $section;

		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}
		return $default;
	}

	function ccfwoo_admin_notifications() {

		$compatability = apply_filters( 'ccfwoo_extend_setup', array() );

		if ( isset( $compatability['pro'] ) && isset( $compatability['pro']['version'] ) ) {

			if ( version_compare( $compatability['pro']['version'], '3.0.0' ) < 0 ) {
				$class   = 'notice notice-error';
				$message = __( 'Update required to Checkout Countdown Pro 3.0+ or downgrade to Checkout Countdown Free 2.4.4', 'checkout-countdown-for-woocommerce' );
				$button  = '<a href="https://puri.io/blog/checkout-countdown-3-0-release-notes/" target="_blank">Read why in our release notes.</a>
			';

				printf( '<div class="%1$s"><p>%2$s - %3$s</p></div>', esc_attr( $class ), esc_html( $message ), $button );
			}
		}
	}


	add_action( 'admin_notices', 'ccfwoo_admin_notifications' );

}// end Class
