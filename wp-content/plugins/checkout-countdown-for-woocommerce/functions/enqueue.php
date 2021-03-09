<?php

/**
 * Load the CCFWOO Javascript.
 */
function ccfwoo_core_enqueue_scripts() {

	wp_enqueue_style( 'ccfwoo-style', plugin_dir_url( __FILE__ ) . '../assets/checkout-countdown.min.css', array(), ccfwoo_setup( 'version' ) );

	if ( ccfwoo_get_option( 'bar_position', false, 'top' ) === 'top' ) {
		$position = 'position: relative;';
	} else {
		$position  = 'position: fixed;';
		$position .= 'bottom: 0;';
	}

	$inline_css = sprintf(
		'.checkout-countdown-wrapper.checkout-countdown-bar {
	color: %s;
	background-color: %s;
	  %s
}',
		ccfwoo_get_option( 'top_banner_font_color' ),
		ccfwoo_get_option( 'top_banner_background_color' ),
		$position
	);

	 wp_add_inline_style( 'ccfwoo-style', $inline_css );

	// Load Javascript and Access settings as variables.
	wp_enqueue_script( 'ccfwoo-countdown', plugin_dir_url( __FILE__ ) . '../assets/checkout-countdown.min.js', array(), ccfwoo_setup( 'version' ), true );

	$data = array(
		'ccfwoo_minutes'              => ccfwoo_get_option( 'countdown_time' ),
		'top_banner_font_color'       => ccfwoo_get_option( 'top_banner_font_color' ),
		'top_banner_background_color' => ccfwoo_get_option( 'top_banner_background_color' ),
		'countdown_text'              => _x( ccfwoo_get_option( 'countdown_text' ), 'Counting text.',  'checkout-countdown-for-woocommerce'),
		'expired_text'                => _x( ccfwoo_get_option( 'expired_text' ), 'Expired Countdown text', 'checkout-countdown-for-woocommerce'),
		'banner_message_text'         => _x( ccfwoo_get_option( 'banner_message_text' ), 'Banner text before counting', 'checkout-countdown-for-woocommerce'),
		'enable_banner_message'       => ccfwoo_get_option( 'enable_banner_message' ),
		'leading_zero'                => ccfwoo_get_option( 'leading_zero', false, false ),
		'cart_count'                  => ccfwoo_get_cart_content(), // Check the number of products in cart
		'countdown_locations'         => is_array( ccfwoo_get_option( 'countdown_locations' ) ) ? ccfwoo_get_option( 'countdown_locations' ) : array(),
		'loading_html'                => ccfwoo_loading_html(),
	);

	wp_localize_script(
		'ccfwoo-countdown',
		'ccfwooLocal',
		$data
	);

	do_action( 'ccfwoo_enqueue_scripts' );
}

add_action( 'wp_enqueue_scripts', 'ccfwoo_core_enqueue_scripts', 20 );
