<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV_Scripts_Styles') ) {

	class WCBV_Scripts_Styles {

		public static $add_script = false;
		private $js_ext = '.min';

		public function __construct() {

			$this->js_ext = WooCommerce_Bulk_Variations::DEBUG ? '' : '.min';

			add_action( 'wp_enqueue_scripts',array( &$this,'enqueue_styles' ) );
			add_action( 'wp_footer', array(&$this, 'footer_handler') );

		}

		//includes scripts and styles in the frontend
		public function enqueue_styles() {

			global $post;

			//only enqueue css and js files when necessary
			if( isset($post->ID) && wcbv_enabled($post->ID) ) {

				if( get_option('wcbv_include_select2', 'yes') === 'yes' )
					wp_enqueue_style(
						'wcbv-select2',
						plugins_url('/assets/css/select2.min.css', WCBV_PLUGIN_ROOT_PHP),
						false,
						'4.0.5'
					);

				wp_enqueue_style(
					'wcbv-css',
					plugins_url('/assets/css/woo-bulk-variations.css', WCBV_PLUGIN_ROOT_PHP),
					false,
					WooCommerce_Bulk_Variations::VERSION
				);

			}

		}

		public function footer_handler() {

			if( self::$add_script ) {

				if( get_option('wcbv_include_select2', 'yes') === 'yes' )
					wp_enqueue_script(
						'wcbv-select2',
						plugins_url('/assets/js/select2.full'.$this->js_ext.'.js', WCBV_PLUGIN_ROOT_PHP),
						array('jquery'),
						'4.0.5'
					);

				wp_enqueue_script(
					'wcbv-js',
					plugins_url('/assets/js/woo-bulk-variations.js', WCBV_PLUGIN_ROOT_PHP),
					array('jquery'),
					WooCommerce_Bulk_Variations::VERSION
				);

			}

		}

	}

}

new WCBV_Scripts_Styles();
?>
