<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WFCO_Admin {

	private static $ins = null;
	public $admin_path;
	public $admin_url;
	public $section_page = '';
	public $should_show_shortcodes = null;

	public function __construct() {
		define( 'WFCO_PLUGIN_FILE', __FILE__ );
		define( 'WFCO_PLUGIN_DIR', __DIR__ );
		define( 'WFCO_PLUGIN_URL', untrailingslashit( plugin_dir_url( WFCO_PLUGIN_FILE ) ) );
		$this->admin_path = WFCO_PLUGIN_DIR;
		$this->admin_url  = WFCO_PLUGIN_URL;

		include_once( $this->admin_path . '/class-wfco-integration.php' );
		include_once( $this->admin_path . '/class-wfco-call.php' );
		include_once( $this->admin_path . '/class-wfco-load-integrations.php' );
		include_once( $this->admin_path . '/class-wfco-common.php' );
		include_once( $this->admin_path . '/class-wfco-ajax-controller.php' );
		include_once( $this->admin_path . '/class-wfco-model.php' );
		include_once( $this->admin_path . '/class-wfco-db.php' );

		WFCO_Common::init();

		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 90 );

		/**
		 * Admin enqueue scripts
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 99 );

		/**
		 * Admin footer text
		 */
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 9999, 1 );
		add_filter( 'update_footer', array( $this, 'update_footer' ), 9999, 1 );
		add_action( 'in_admin_header', array( $this, 'maybe_remove_all_notices_on_page' ) );
		//      add_action( 'admin_init', array( $this, 'check_db_version' ) );

	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public static function get_available_connectors() {
		$available_connectors = array(
			'wfco_activecampaign_integration' => array(
				'name'            => 'Active Campaign',
				'connector_class' => 'wfco_activecampaign_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/active-campaign.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-activecampaign.zip',
				'file'            => 'woofunnels-connector-activecampaign/woofunnels-connector-activecampaign.php',
			),
			'wfco_drip_integration'           => array(
				'name'            => 'Drip',
				'connector_class' => 'wfco_drip_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/drip.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-drip.zip',
				'file'            => 'woofunnels-connector-drip/woofunnels-connector-drip.php',
			),
//			'wfco_infusionsoft_integration'   => array(
//				'name'            => 'Infusionsoft',
//				'connector_class' => 'wfco_infusionsoft_core',
//				'image'           => 'https://s3.amazonaws.com/woofunnels/infusion-soft.png',
//				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-infusionsoft.zip',
//				'file'            => 'woofunnels-connector-infusionsoft/woofunnels-connector-infusionsoft.php',
//			),
			'wfco_ontraport_integration'      => array(
				'name'            => 'Ontraport',
				'connector_class' => 'wfco_ontraport_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/ontraport.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-ontraport.zip',
				'file'            => 'woofunnels-connector-ontraport/woofunnels-connector-ontraport.php',
			),
			'wfco_convertkit_integration'     => array(
				'name'            => 'Convertkit',
				'connector_class' => 'wfco_convertkit_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/convertkit.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-convertkit.zip',
				'file'            => 'woofunnels-connector-convertkit/woofunnels-connector-convertkit.php',
			),
			'wfco_mailchimp_integration'      => array(
				'name'            => 'Mailchimp',
				'connector_class' => 'wfco_mailchimp_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/mailchimp.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-mailchimp.zip',
				'file'            => 'woofunnels-connector-mailchimp/woofunnels-connector-mailchimp.php',
			),
//			'wfco_twilio_integration'         => array(
//				'name'            => 'Twilio',
//				'connector_class' => 'wfco_twilio_core',
//				'image'           => 'https://s3.amazonaws.com/woofunnels/twillio.png',
//				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-twilio.zip',
//				'file'            => 'woofunnels-connector-twilio/woofunnels-connector-twilio.php',
//			),
			'wfco_slack_integration'          => array(
				'name'            => 'Slack',
				'connector_class' => 'wfco_slack_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/slack.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-slack.zip',
				'file'            => 'woofunnels-connector-slack/woofunnels-connector-slack.php',
			),
			'wfco_zapier_integration'         => array(
				'name'            => 'Zapier',
				'connector_class' => 'wfco_zapier_core',
				'image'           => 'https://s3.amazonaws.com/woofunnels/zapier.png',
				'source'          => 'https://s3.amazonaws.com/woofunnels/connector/woofunnels-connector-zapier.zip',
				'file'            => 'woofunnels-connector-zapier/woofunnels-connector-zapier.php',
			),

		);
		$available_connectors = apply_filters( 'wfco_connectors_loaded', $available_connectors );

		return $available_connectors;
	}

	public function get_admin_url() {
		return plugin_dir_url( WFCO_PLUGIN_FILE ) . 'admin';
	}

	public function register_admin_menu() {

		add_submenu_page( 'woofunnels', __( 'Connector', 'woofunnels' ), __( 'Connector', 'woofunnels' ), 'manage_woocommerce', 'connector', array(
				$this,
				'connector_page',
			) );
	}

	public function admin_enqueue_assets() {
		if ( is_admin() ) {

		}
		/**
		 * Load Funnel Builder page assets
		 */
		/*if ( WFCO_Common::is_load_admin_assets( 'builder' ) ) {
			wp_enqueue_style( 'wfco-funnel-bg', $this->admin_url . '/assets/css/wfco-funnel-bg.css', array(), WFCO_VERSION_DEV );
			wp_enqueue_style( 'woofunnels-opensans-font', '//fonts.googleapis.com/css?family=Open+Sans', array(), WFCO_VERSION_DEV );
		}*/

		/**
		 * Including izimodal assets
		 */
		if ( WFCO_Common::is_load_admin_assets( 'all' ) ) {

			if ( $this->is_connector_page() ) {
				wp_enqueue_style( 'wfco-sweetalert2-style', $this->admin_url . '/assets/css/sweetalert2.css', array(), WooFunnel_Loader::$version );
				wp_enqueue_style( 'wfco-izimodal', $this->admin_url . '/assets/css/iziModal/iziModal.css', array(), WooFunnel_Loader::$version );
				wp_enqueue_style( 'wfco-toast-style', $this->admin_url . '/assets/css/toast.min.css', array(), WooFunnel_Loader::$version );

				wp_enqueue_script( 'wfco-sweetalert2-script', $this->admin_url . '/assets/js/sweetalert2.js', array( 'jquery' ), WooFunnel_Loader::$version, true );
				wp_enqueue_script( 'wfco-izimodal', $this->admin_url . '/assets/js/iziModal/iziModal.js', array(), WooFunnel_Loader::$version );
				wp_enqueue_script( 'wfco-toast-script', $this->admin_url . '/assets/js/toast.min.js', array( 'jquery' ), WooFunnel_Loader::$version, true );

				wp_enqueue_script( 'wc-backbone-modal' );
			}
		}
		/* if ( WFCO_Common::is_load_admin_assets( 'settings' ) ) {
			wp_enqueue_script( 'jquery-tiptip' );
		} */

		/**
		 * Including Connector assets on all connector pages.
		 */
		if ( WFCO_Common::is_load_admin_assets( 'all' ) ) {
			//          wp_enqueue_script( 'wc-backbone-modal' );
			wp_enqueue_style( 'wfco-admin', $this->admin_url . '/assets/css/wfco-admin.css', array(), WooFunnel_Loader::$version );
			wp_enqueue_script( 'wfco-admin-ajax', $this->admin_url . '/assets/js/wfco-admin-ajax.js', array(), WooFunnel_Loader::$version );
			wp_enqueue_script( 'wfco-admin', $this->admin_url . '/assets/js/wfco-admin.js', array(), WooFunnel_Loader::$version );
			wp_enqueue_script( 'wfco-admin-sub', $this->admin_url . '/assets/js/wfco-admin-sub.js', array(), WooFunnel_Loader::$version );
		}
		$data = array(
			'ajax_nonce'            => wp_create_nonce( 'wfcoaction-admin' ),
			'plugin_url'            => plugin_dir_url( WFCO_PLUGIN_FILE ),
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'admin_url'             => admin_url(),
			'ajax_chosen'           => wp_create_nonce( 'json-search' ),
			'search_products_nonce' => wp_create_nonce( 'search-products' ),
			'integrations_pg'       => admin_url( 'admin.php?page=connector&tab=integrations' ),
			'oauth_nonce'           => wp_create_nonce( 'wfco-integration' ),
			'oauth_integrations'    => $this->get_oauth_integration(),
			'texts'                 => $this->js_text(),
		);
		wp_localize_script( 'wfco-admin', 'wfcoParams', $data );

	}

	public function js_text() {
		$data = array(
			'text_copied'             => __( 'Text Copied', 'woofunnels' ),
			'sync_title'              => __( 'Sync Integration', 'woofunnels' ),
			'sync_text'               => __( 'All the data of this Integration will be Synced.', 'woofunnels' ),
			'sync_wait'               => __( 'Please Wait...', 'woofunnels' ),
			'sync_progress'           => __( 'Sync in progress...', 'woofunnels' ),
			'sync_success_title'      => __( 'Integration Synced', 'woofunnels' ),
			'sync_success_text'       => __( 'We have detected change in the integration during syncing. Please Re-save your Automations/Campaign.', 'woofunnels' ),
			'oops_title'              => __( 'Oops', 'woofunnels' ),
			'oops_text'               => __( 'There was some error. Please try again later.', 'woofunnels' ),
			'delete_int_title'        => __( 'There was some error. Please try again later.', 'woofunnels' ),
			'delete_int_text'         => __( 'There was some error. Please try again later.', 'woofunnels' ),
			'update_int_prompt_title' => __( 'Integration Updated', 'woofunnels' ),
			'delete_int_prompt_title' => __( 'Delete Connector', 'woofunnels' ),
			'delete_int_prompt_text'  => __( 'All the action, tasks, logs of this connector will be deleted.', 'woofunnels' ),
			'delete_int_wait_title'   => __( 'Please Wait...', 'woofunnels' ),
			'delete_int_wait_text'    => __( 'Disconnecting the connector ...', 'woofunnels' ),
			'delete_int_success'      => __( 'Connector Disconnected', 'woofunnels' ),
			'update_btn'              => __( 'Update', 'woofunnels' ),
			'update_btn_process'      => __( 'Updating...', 'woofunnels' ),
			'connect_btn_process'     => __( 'Connecting...', 'woofunnels' ),
			'install_success_title'   => __( 'Connector Installed Successfully', 'woofunnels-autobot-automation' ),
			'connect_success_title'   => __( 'Connected Successfully', 'woofunnels-autobot-automation' ),
		);

		return $data;
	}

	public function get_oauth_integration() {
		$oauth_integrations = array();
		$all_integration    = WFCO_Admin::get_available_connectors();

		if ( is_array( $all_integration ) && count( $all_integration ) > 0 ) {
			foreach ( $all_integration as $source_slug => $integration ) {
				if ( class_exists( $integration['connector_class'] ) ) {
//					if ( isset( WFCO_Common::$integrations_saved_data[ $source_slug ] ) ) {
						$connector = $integration['connector_class']::get_instance();
						if ( isset( $connector->is_oauth ) && true == $connector->is_oauth ) {
							$oauth_integrations[] = $source_slug;
						}
//					}
				}
			}
		}

		return $oauth_integrations;
	}

	public function connector_page() {
		if ( isset( $_GET['page'] ) && 'connector' === $_GET['page'] ) {

			if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'settings' ) {
				include_once( $this->admin_path . '/view/global-settings.php' );
			} elseif ( isset( $_GET['section'] ) && $_GET['section'] == 'settings' ) {
				include_once( $this->admin_path . '/view/connector-global-settings.php' );
			} else {
				include_once( $this->admin_path . '/view/connector-admin.php' );
			}
		}

	}

	public function is_connector_page( $section = '' ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'single_connector' && '' == $section ) {
			return true;
		}
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'connector' && '' == $section ) {
			return true;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'connector' && isset( $_GET['section'] ) && $_GET['section'] == $section ) {
			return true;
		}

		return false;
	}

	public function admin_footer_text( $footer_text ) {
		/*	if ( WFCO_Common::is_load_admin_assets( 'builder' ) ) {
				return '';
			} */

		return $footer_text;
	}

	public function update_footer( $footer_text ) {
		/*	if ( WFCO_Common::is_load_admin_assets( 'builder' ) ) {
				return '';
			} */

		return $footer_text;
	}

	/**
	 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support
	 *
	 * @param array $links array of existing links
	 *
	 * @return array modified array
	 */
	public function plugin_actions( $links ) {
		$links['deactivate'] .= '<i class="woofunnels-slug" data-slug="' . WFCO_PLUGIN_BASENAME . '"></i>';

		return $links;
	}

	public function tooltip( $text ) {
		?>
        <span class="wfco-help"><i class="icon"></i><div class="helpText"><?php echo $text; ?></div></span>
		<?php
	}

	/**
	 * Remove all the notices in our dashboard pages as they might break the design.
	 */
	public function maybe_remove_all_notices_on_page() {
		if ( isset( $_GET['page'] ) && 'connector' == $_GET['page'] && isset( $_GET['section'] ) ) {
			remove_all_actions( 'admin_notices' );
		}
	}


	public function check_db_version() {

		$get_db_version = get_option( '_wfco_db_version', '0.0.0' );

		if ( version_compare( WFCO_DB_VERSION, $get_db_version, '>' ) ) {

			//needs checking
			global $wpdb;
			include_once plugin_dir_path( WFCO_PLUGIN_FILE ) . 'db/tables.php';
			$tables = new WFCO_DB_Tables( $wpdb );

			$tables->add_if_needed();

			update_option( '_wfco_db_version', WFCO_DB_VERSION, true );
		}

	}

}

$should_include = apply_filters( 'wfco_include_connector', false );

if ( $should_include ) {
	WFCO_Admin::get_instance();
}
