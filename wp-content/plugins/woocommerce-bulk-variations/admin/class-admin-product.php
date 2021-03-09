<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV_Admin_Product') ) {

	class WCBV_Admin_Product {

		public function __construct() {

			add_filter( 'product_type_options', array( &$this, 'add_product_type_option' ) );
			add_filter( 'woocommerce_product_data_tabs', array( &$this, 'add_product_data_tab' ) );
			add_action( 'woocommerce_product_data_panels', array( &$this, 'add_product_data_panel' ) );
			add_action( 'woocommerce_process_product_meta', array( &$this, 'save_custom_fields' ), 10, 2 );

		}

		//add checkbox to enable wcbv for a product
		public function add_product_type_option( $types ) {

			$types['wcbv'] = array(
				'id' => '_wcbv',
				'wrapper_class' => 'show_if_wcbv show_if_variable',
				'label' => __( 'Bulk Variations', 'radykal' ),
				'description' => __( 'Enable Bulk Variations.', 'radykal' )
			);

			return $types;

		}

		//the tab in the data panel
		public function add_product_data_tab( $tabs ) {

			$tabs['wcbv'] = array(
				'label'  => __( 'Bulk Variations', 'radykal' ),
				'target' => 'wcbv_data',
				'class'  => array( 'hide_if_wcbv' ),
			);

			return $tabs;

		}

		//custom panel in the product post to set some options
		public function add_product_data_panel() {

			global $wpdb, $post;

			$options = WCBV_Admin_Settings::get_product_meta_options();
			$custom_fields = get_post_custom($post->ID);
			$stored_options = array();

			foreach( $options as $key => $value) {

				$option_key = 'wcbv_'.$key;
				if( isset($custom_fields[$option_key]) ) {
					$stored_options[$key] = $custom_fields[$option_key][0];
				}
				else {
					$stored_options[$key] = '';
				}

			}

			require_once(WCBV_PLUGIN_ADMIN_DIR.'/views/html-admin-meta-box.php');

		}

		//save all custom fields from WCBV form
		public function save_custom_fields( $post_id, $post ) {

			update_post_meta( $post_id, '_wcbv', isset( $_POST['_wcbv'] ) ? 'yes' : 'no' );

			if( isset($_POST['wcbv_position']) ) {

				foreach( WCBV_Admin_Settings::get_product_meta_options() as $key => $value) {

					$option_key = 'wcbv_'.$key;
					if( isset($_POST[$option_key]) )
						update_post_meta( $post_id, $option_key, $_POST[$option_key] );
					else
						update_post_meta( $post_id, $option_key, '' );

				}

			}

		}

	}
}

new WCBV_Admin_Product();

?>