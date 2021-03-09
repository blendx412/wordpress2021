<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('WCBV_Admin') ) {

	class WCBV_Admin {

		public function __construct() {

			require_once(WCBV_PLUGIN_ADMIN_DIR . '/class-admin-scripts-styles.php' );
			require_once(WCBV_PLUGIN_ADMIN_DIR.'/class-admin-settings.php');

			add_action( 'admin_init', array( &$this, 'init_admin' ) );
			add_action( 'admin_notices',  array( &$this, 'display_admin_notices' ) );

		}

		public function init_admin() {

			if( array_key_exists('woocommerce', $GLOBALS) == false) {
				return;
			}

			//add capability to administrator
			$role = get_role( 'administrator' );
			$role->add_cap( WooCommerce_Bulk_Variations::CAPABILITY );

			require_once(WCBV_PLUGIN_ADMIN_DIR . '/class-admin-product.php' );

		}

		public function display_admin_notices() {

			global $woocommerce;

			if( !function_exists('get_woocommerce_currency') ): ?>
		    <div class="error">
		        <p><?php _e( '<a href="http://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a> is required for WooCommerce Bulk Variations plugin!', 'radykal' ); ?></p>
		    </div>
		    <?php endif;

			if( class_exists('Fancy_Product_Designer') && version_compare(Fancy_Product_Designer::VERSION, WooCommerce_Bulk_Variations::FPD_MIN_VERSION, '<')) : ?>
			<div class="error">
		        <p><?php _e( 'Fancy Product Designer V'.WooCommerce_Bulk_Variations::FPD_MIN_VERSION.' is at least needed to use it with WooCommerce Bulk Variations!', 'radykal' ); ?></p>
		    </div>
		    <?php endif;

		}
	}
}

new WCBV_Admin();

?>