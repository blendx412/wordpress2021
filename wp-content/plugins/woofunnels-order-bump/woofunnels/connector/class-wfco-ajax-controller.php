<?php

/**
 * Class WFCO_AJAX_Controller
 * Handles All the request came from Backend
 */
class WFCO_AJAX_Controller {

	public static function init() {
		/**
		 * Backend AJAX actions
		 */
		if ( is_admin() ) {
			self::handle_admin_ajax();
		}
	}

	public static function handle_admin_ajax() {
		add_action( 'wp_ajax_wfco_save_integration', array( __CLASS__, 'save_integration' ) );
		add_action( 'wp_ajax_wfco_sync_integration', array( __CLASS__, 'sync_integration' ) );
		add_action( 'wp_ajax_wfco_delete_integration', array( __CLASS__, 'delete_integration' ) );
		add_action( 'wp_ajax_wfco_update_integration', array( __CLASS__, 'update_integration' ) );
		add_action( 'wp_ajax_wfco_connector_install', array( __CLASS__, 'connector_install' ) );
	}

	/**
	 * Update Integration's settings
	 */
	public static function update_integration() {
		if ( empty( $_REQUEST ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}
		$resp = array();

		$resp['status'] = false;

		if ( isset( $_REQUEST['wfco_integration'] ) && '' != $_REQUEST['wfco_integration'] && isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] && wp_verify_nonce( $_REQUEST['edit_nonce'], 'wfco-integration-edit' ) ) {
			$response = WFCO_Load_Integrations::$integrations[ $_REQUEST['wfco_integration'] ]->handle_settings_form( $_REQUEST );
			if ( is_array( $response ) && count( $response ) > 0 ) {
				$resp['status']       = true;
				$resp['id']           = $response['id'];
				$resp['data_changed'] = $response['data_changed'];
				$resp['redirect_url'] = add_query_arg( array(
					'page' => 'connector',
				), admin_url( 'admin.php' ) );

				wp_send_json( $resp );
			} else {
				$resp['status'] = false;
				$resp['msg']    = $response;
				wp_send_json( $resp );
			}
		}
	}

	/**
	 * save Integration's settings
	 */
	public static function save_integration() {
		if ( empty( $_REQUEST ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$resp = array();

		$resp['status'] = false;

		if ( isset( $_REQUEST['wfco_integration'] ) && '' != $_REQUEST['wfco_integration'] && wp_verify_nonce( $_REQUEST['_wpnonce'], 'wfco-integration' ) ) {
			$response = WFCO_Load_Integrations::$integrations[ $_REQUEST['wfco_integration'] ]->handle_settings_form( $_REQUEST );
			if ( (int) $response > 0 ) {
				$resp['status']                = true;
				$resp['id']                    = $response;
				$resp['redirect_url']          = add_query_arg( array(
					'page' => 'connector',
				), admin_url( 'admin.php' ) );
				$resp['is_direct_integration'] = false;

				if ( isset( $_REQUEST['wfco_integration_type'] ) && 'direct' == $_REQUEST['wfco_integration_type'] ) {
					$resp['is_direct_integration'] = true;
				}

				wp_send_json( $resp );
			} else {
				$resp['status'] = false;
				$resp['msg']    = $response;
				wp_send_json( $resp );
			}
		}
	}

	/**
	 * sync Integration's settings
	 */
	public static function sync_integration() {
		if ( empty( $_REQUEST ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$resp = array();

		$resp['status'] = false;

		if ( isset( $_REQUEST['slug'] ) && '' != $_REQUEST['slug'] && isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] && wp_verify_nonce( $_REQUEST['sync_nonce'], 'wfco-integration-sync' ) ) {

			$id = WFCO_Load_Integrations::$integrations[ $_REQUEST['slug'] ]->handle_settings_form( $_REQUEST );

			if ( count( $id ) > 0 ) {
				$resp['status']       = true;
				$resp['id']           = $id['id'];
				$resp['data_changed'] = $id['data_changed'];
				$resp['redirect_url'] = add_query_arg( array(
					'page' => 'connector',
				), admin_url( 'admin.php' ) );
				wp_send_json( $resp );
			}
		}
	}

	/**
	 * Delete Integration
	 */
	public static function delete_integration() {
		if ( empty( $_REQUEST ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}
		global $wpdb;
		$resp = array();

		$resp['status'] = false;

		if ( isset( $_REQUEST['id'] ) && '' != $_REQUEST['id'] && wp_verify_nonce( $_REQUEST['delete_nonce'], 'wfco-integration-delete' ) ) {

			$integration_id      = $_REQUEST['id'];
			$integration_details = WFCO_Model_Integrations::get( $_REQUEST['id'] );
			$connector_slug      = $integration_details['integration_slug'];
			$sql_query = "DELETE from {table_name} where wfco_integration_id = %d";
			$sql_query = $wpdb->prepare($sql_query,$integration_id);
			WFCO_Model_Integrationmeta::delete_multiple( $sql_query );
			WFCO_Model_Integrations::delete( $integration_id );
			do_action( 'connector_disconnected', $connector_slug, true );

			$resp['status'] = true;

			$resp['redirect_url'] = add_query_arg( array(
				'page' => 'connector',
			), admin_url( 'admin.php' ) );
			wp_send_json( $resp );

		}
	}

	public static function connector_install() {
		if ( empty( $_REQUEST ) ) {
			wp_send_json_error( new \WP_Error( 'Bad Request' ) );
		}

		$resp = array();

		$resp['status'] = false;

		if ( isset( $_REQUEST['connector_slug'] ) && '' != $_REQUEST['connector_slug'] && wp_verify_nonce( $_REQUEST['install_nonce'], 'wfco-integration-install' ) ) {

			$connector_slug          = $_REQUEST['connector_slug'];
			$all_integration         = WFCO_Admin::get_available_connectors();
			$connector_download_link = $all_integration[ $connector_slug ]['source'];
			$connector_plugin_file   = $all_integration[ $connector_slug ]['file'];
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..
			//includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
			include_once( ABSPATH . 'wp-admin/includes/file.php' );
			include_once( ABSPATH . 'wp-admin/includes/misc.php' );
			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			$upgrader    = new Plugin_Upgrader();
			$result      = $upgrader->install( $connector_download_link );
			$resp['msg'] = __( 'There was some error. Please try again later.', 'woofunnels-autobot-automation' );

			if ( $result ) {
				$resp['status'] = true;
				try{
					$activation_result = activate_plugin( $connector_plugin_file );
					if ( is_wp_error( $activation_result ) ) {
						throw new Exception();
					}

					$resp['msg'] = __( 'Plugin installed and activated successfully.', 'woofunnels-autobot-automation' );

				}
				catch (Exception $error){
					$resp['msg'] = __( 'Plugin installed successfully. Please activate plugin from plugins screen', 'woofunnels-autobot-automation' );
				}
			}

			$resp['redirect_url'] = add_query_arg( array(
				'page' => 'connector',
			), admin_url( 'admin.php' ) );

			wp_send_json( $resp );

		}
	}

}

WFCO_AJAX_Controller::init();
