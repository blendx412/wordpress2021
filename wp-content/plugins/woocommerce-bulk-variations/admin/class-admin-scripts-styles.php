<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV__Admin_Scripts_Styles') ) {

	class WCBV__Admin_Scripts_Styles {

		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles_scripts' ), 50 );

		}

		public function enqueue_styles_scripts( $hook ) {

			global $post;

			//woocommerce post types
		    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

		    	//product
		        if ( 'product' === $post->post_type ) {

		        	wp_enqueue_style( 'wcbv-admin', plugins_url('/css/admin.css', __FILE__) );
		        	wp_enqueue_script( 'wcbv-meta-box', plugins_url('/js/meta-box.js', __FILE__), false, WooCommerce_Bulk_Variations::VERSION );

		        }
		    }

		}
	}
}

new WCBV__Admin_Scripts_Styles();

?>