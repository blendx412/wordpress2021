<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

/**
		 * Enhanced Ecommerce Google Analytics compatibility
		 */
		
			add_action( 'wp_loaded', function () {
				if ( class_exists( 'Enhanced_Ecommerce_Google_Analytics' ) ) {
					global $wp_filter;
					foreach ( $wp_filter['woocommerce_thankyou']->callbacks as $key => $val ) {
						if ( 10 !== $key ) {
							continue;
						}
						foreach ( $val as $innerkey => $innerval ) {
							if ( isset( $innerval['function'] ) && is_array( $innerval['function'] ) ) {
								if ( is_a( $innerval['function']['0'], 'Enhanced_Ecommerce_Google_Analytics_Public' ) ) {
									$Enhanced_Ecommerce_Google_Analytics = $innerval['function']['0'];
									remove_action( 'woocommerce_thankyou', array( $Enhanced_Ecommerce_Google_Analytics, 'ecommerce_tracking_code' ) );
									break;
								}
							}
						}
					}
				}
			}, 0 );

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */

