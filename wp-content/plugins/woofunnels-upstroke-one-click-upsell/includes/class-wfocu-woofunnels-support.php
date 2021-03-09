<?php

class WFOCU_WooFunnels_Support {

	public static $_instance = null;
	/** Can't be change this further, as is used for license activation */
	public $full_name = 'Upstroke: WooCommerce One Click Upsell';
	public $is_license_needed = true;
	/**
	 * @var WooFunnels_License_check
	 */
	public $license_instance;
	public $slug = 'woofunnels-upstroke-one-click-upsell';
	public $encoded_basename = '';

	/**
	 * WFOCU_WooFunnels_Support constructor.
	 */
	public function __construct() {

		$this->encoded_basename = sha1( WFOCU_PLUGIN_BASENAME );

		add_action( 'wfocu_page_right_content', array( $this, 'wfocu_options_page_right_content' ), 10 );
		add_action( 'admin_menu', array( $this, 'add_menus' ), 80.1 );
		add_filter( 'woofunnels_plugins_license_needed', array( $this, 'add_license_support' ), 10 );
		add_action( 'init', array( $this, 'init_licensing' ), 12 );
		add_action( 'woofunnels_licenses_submitted', array( $this, 'process_licensing_form' ) );
		add_action( 'woofunnels_deactivate_request', array( $this, 'maybe_process_deactivation' ) );

		if ( ! wp_next_scheduled( 'woofunnels_wfocu_license_check' ) ) {
			wp_schedule_event( time(), 'daily', 'woofunnels_wfocu_license_check' );
		}
		add_action( 'woofunnels_wfocu_license_check', array( $this, 'license_check' ) );
		add_action( 'admin_init', array( $this, 'maybe_handle_license_activation_wizard' ), 1 );
		add_filter( 'woofunnels_default_reason_' . WFOCU_PLUGIN_BASENAME, function () {
			return 1;
		} );
		add_filter( 'woofunnels_default_reason_default', function () {
			return 1;
		} );

		add_filter( 'woofunnels_global_tracking_data', array( $this, 'add_data_to_tracking' ), 10, 1 );
		add_action( 'bwf_maybe_push_optin_notice_state_action', array( $this, 'wfocu_try_push_notification_for_optin' ), 10 );

		add_filter( 'woofunnels_show_reset_tracking', '__return_true', 999 );

		add_filter( 'bwf_needs_order_indexing', '__return_true', 999 );
	}

	/**
	 * @return null|WFOCU_WooFunnels_Support
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/**
	 *
	 */
	public function wfocu_options_page_right_content() {
		?>
        <div class="postbox wfocu_side_content wfocu_allow_panel_close">
            <button type="button" class="handlediv">
                <span class="toggle-indicator"></span>
            </button>
            <h3 class="hndle"><span>Must Read Links</span></h3>
            <div class="inside">
				<?php
				$support_link      = add_query_arg( array(
					'utm_source'   => 'wfocu-pro',
					'utm_medium'   => 'banner-click',
					'utm_campaign' => 'resource',
					'utm_term'     => 'support',
				), 'https://buildwoofunnels.com/support' );
				$get_familiar_link = add_query_arg( array(
					'utm_source'   => 'wfocu-pro',
					'utm_medium'   => 'text-click',
					'utm_campaign' => 'resource',
					'utm_term'     => 'getting-familiar-with-ui',
				), 'https://buildwoofunnels.com/docs/upstroke/getting-started/getting-familiar-with-ui/' );
				$first_upsell      = add_query_arg( array(
					'utm_source'   => 'wfocu-pro',
					'utm_medium'   => 'text-click',
					'utm_campaign' => 'resource',
					'utm_term'     => 'creating-first-upsell-funnel',
				), 'https://buildwoofunnels.com/docs/upstroke/getting-started/creating-first-upsell-funnel/' );
				$funnel_go_live    = add_query_arg( array(
					'utm_source'   => 'wfocu-pro',
					'utm_medium'   => 'text-click',
					'utm_campaign' => 'resource',
					'utm_term'     => 'funnel-go-live-checklist',
				), 'https://buildwoofunnels.com/docs/upstroke/getting-started/funnel-go-live-checklist/' );
				$doc_link          = add_query_arg( array(
					'utm_source'   => 'wfocu-pro',
					'utm_medium'   => 'text-click',
					'utm_campaign' => 'resource',
					'utm_term'     => 'documentation',
				), 'https://buildwoofunnels.com/docs/upstroke/' );

				?>

                <p>Before you start building the Funnels, visit these 3 important links.</p>
                <ul class="wfocu-list-dec">
                    <li><a href="<?php echo esc_url( $get_familiar_link ); ?>" target="_blank">Getting Familiar With the Interface</a></li>
                    <li><a href="<?php echo esc_url( $first_upsell ); ?>" target="_blank">Create First Upsell Funnel</a></li>
                    <li><a href="<?php echo esc_url( $funnel_go_live ); ?>" target="_blank">Important Checklist before you Go Live</a></li>
                </ul>
                <p>Unable to find answers?<br/><a href="<?php echo esc_url( $doc_link ); ?>" target="_blank">Read Documentation</a></p>
                <p>Still need Help? We will be happy to answer.</p>
                <p align="center"><a class="button button-primary" href="<?php echo esc_url( $support_link ); ?>" target="_blank">Contact Support</a></p>
            </div>
        </div>
		<?php

	}

	/**
	 * Adding WooCommerce sub-menu for global options
	 */
	public function add_menus() {
		if ( ! WooFunnels_dashboard::$is_core_menu ) {
			add_menu_page( __( 'WooFunnels', 'woofunnels-upstroke-one-click-upsell' ), __( 'WooFunnels', 'woofunnels-upstroke-one-click-upsell' ), 'manage_woocommerce', 'woofunnels', array(
				$this,
				'woofunnels_page',
			), '', 59 );
			add_submenu_page( 'woofunnels', __( 'Licenses', 'woofunnels-upstroke-one-click-upsell' ), __( 'License', 'woofunnels-upstroke-one-click-upsell' ), 'manage_woocommerce', 'woofunnels' );
			WooFunnels_dashboard::$is_core_menu = true;
		}
	}

	public function woofunnels_page() {
		if ( ! isset( $_GET['tab'] ) ) {   // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			WooFunnels_dashboard::$selected = 'licenses';
		}
		WooFunnels_dashboard::load_page();
	}

	/**
	 * License management helper function to create a slug that is friendly with edd
	 *
	 * @param type $name
	 *
	 * @return type
	 */
	public function slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function add_license_support( $plugins ) {
		$status  = 'invalid';
		$renew   = 'Please Activate';
		$license = array(
			'key'     => '',
			'email'   => '',
			'expires' => '',
		);

		$plugins_in_database = WooFunnels_License_check::get_plugins();

		if ( is_array( $plugins_in_database ) && isset( $plugins_in_database[ $this->encoded_basename ] ) && count( $plugins_in_database[ $this->encoded_basename ] ) > 0 ) {
			$status  = 'active';
			$renew   = '';
			$license = array(
				'key'     => $plugins_in_database[ $this->encoded_basename ]['data_extra']['api_key'],
				'email'   => $plugins_in_database[ $this->encoded_basename ]['data_extra']['license_email'],
				'expires' => $plugins_in_database[ $this->encoded_basename ]['data_extra']['expires'],
			);
		}

		$plugins[ $this->encoded_basename ] = array(
			'plugin'            => $this->full_name,
			'product_version'   => WFOCU_VERSION,
			'product_status'    => $status,
			'license_expiry'    => $renew,
			'product_file_path' => $this->encoded_basename,
			'existing_key'      => $license,
		);

		return $plugins;
	}

	public function woofunnels_slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function init_licensing() {
		if ( class_exists( 'WooFunnels_License_check' ) && $this->is_license_needed ) {
			$this->license_instance = new WooFunnels_License_check( $this->encoded_basename );

			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFOCU_PLUGIN_BASENAME,
					'plugin_name' => WFOCU_FULL_NAME,
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFOCU_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->start_updater();
			}
		}
	}

	public function process_licensing_form( $posted_data ) {

		if ( isset( $posted_data['license_keys'][ $this->encoded_basename ] ) ) {
			$key  = $posted_data['license_keys'][ $this->encoded_basename ]['key'];
			$data = array(
				'plugin_slug' => WFOCU_PLUGIN_BASENAME,
				'plugin_name' => WFOCU_FULL_NAME,

				'license_key' => $key,
				'product_id'  => $this->full_name,
				'version'     => WFOCU_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$this->license_instance->activate_license();
		}
	}

	/**
	 * Validate is it is for email product deactivation
	 *
	 * @param type $posted_data
	 */
	public function maybe_process_deactivation( $posted_data ) {
		if ( isset( $posted_data['filepath'] ) && $posted_data['filepath'] === $this->encoded_basename ) {
			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFOCU_PLUGIN_BASENAME,
					'plugin_name' => WFOCU_FULL_NAME,
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFOCU_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->deactivate_license();
				wp_safe_redirect( 'admin.php?page=' . $posted_data['page'] . '&tab=' . $posted_data['tab'] );
				exit;
			}
		}
	}

	public function license_check() {
		$plugins = WooFunnels_License_check::get_plugins();
		if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
			$data = array(
				'plugin_slug' => WFOCU_PLUGIN_BASENAME,
				'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
				'product_id'  => $this->full_name,
				'version'     => WFOCU_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$this->license_instance->license_status();
		}
	}

	public function is_license_present() {
		$plugins = WooFunnels_License_check::get_plugins();

		if ( ! isset( $plugins[ $this->encoded_basename ] ) ) {
			return false;
		}

		return true;

	}

	public function maybe_handle_license_activation_wizard() {

		if ( filter_input( INPUT_POST, 'wfocu_verify_license', FILTER_SANITIZE_STRING ) !== null ) {
			$data = array(
				'plugin_slug' => WFOCU_PLUGIN_BASENAME,
				'plugin_name' => WFOCU_FULL_NAME,
				'license_key' => filter_input( INPUT_POST, 'license_key', FILTER_SANITIZE_STRING ),
				'product_id'  => $this->full_name,
				'version'     => WFOCU_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$data_response = $this->license_instance->activate_license();

			if ( is_array( $data_response ) && $data_response['activated'] === true ) {
				WFOCU_Wizard::set_license_state( true );
				do_action( 'wfocu_license_activated', 'woofunnels-upstroke-one-click-upsell' );
				if ( filter_input( INPUT_POST, '_redirect_link', FILTER_SANITIZE_STRING ) !== null ) {
					wp_redirect( filter_input( INPUT_POST, '_redirect_link', FILTER_SANITIZE_STRING ) );
					exit;
				}
			} else {
				WFOCU_Wizard::set_license_state( false );
				WFOCU_Wizard::set_license_key( filter_input( INPUT_POST, 'license_key', FILTER_SANITIZE_STRING ) );

			}
		}
	}

	/**
	 * Append WooFunnels data to tracking data
	 *
	 * @param $tracking_data
	 *
	 * @return mixed
	 */
	public function add_data_to_tracking( $tracking_data ) {

		$woofunnels = array();
		/**
		 *
		 *
		 * [woofunnels] => Array
		 * (
		 * [total_funnels] =>
		 * [total_active_funnels] =>
		 * [funnel_init_count] =>
		 * [offer_accepted_count] =>
		 * [offer_rejected_count] =>
		 * [settings] =>
		 * )
		 */

		$get_funnels                 = WFOCU_Common::get_post_table_data();
		$woofunnels['total_funnels'] = $get_funnels['found_posts'];
		$items_active                = array_filter( $get_funnels['items'], function ( $var ) {

			return ( $var['status'] === 'publish' );
		} );

		$woofunnels['total_active_funnels'] = count( $items_active );

		$results = WFOCU_Core()->track->query_results( array(
			'data'       => array(
				'ID' => array(
					'type'     => 'col',
					'function' => 'COUNT',
					'name'     => 'total_count',
				),
			),
			'where'      => array(

				array(
					'key'      => 'events.action_type_id',
					'value'    => 1,
					'operator' => '=',
				),
			),
			'order_by'   => 'events.id DESC',
			'query_type' => 'get_var',
		) );

		$woofunnels['funnel_init_count'] = $results;

		$results = WFOCU_Core()->track->query_results( array(
			'data'       => array(
				'ID' => array(
					'type'     => 'col',
					'function' => 'COUNT',
					'name'     => 'total_count',
				),
			),
			'where'      => array(

				array(
					'key'      => 'events.action_type_id',
					'value'    => 4,
					'operator' => '=',
				),
			),
			'order_by'   => 'events.id DESC',
			'query_type' => 'get_var',
		) );

		$woofunnels['offer_accepted_count'] = $results;

		$results = WFOCU_Core()->track->query_results( array(
			'data'       => array(
				'ID' => array(
					'type'     => 'col',
					'function' => 'COUNT',
					'name'     => 'total_count',
				),
			),
			'where'      => array(

				array(
					'key'      => 'events.action_type_id',
					'value'    => 6,
					'operator' => '=',
				),
			),
			'order_by'   => 'events.id DESC',
			'query_type' => 'get_var',
		) );

		$woofunnels['offer_rejected_count'] = $results;

		$woofunnels['settings'] = WFOCU_Core()->data->get_option();

		$tracking_data['upstroke'] = $woofunnels;

		return $tracking_data;

	}

	public function wfocu_try_push_notification_for_optin() {

		$last_updated_time = get_option( '_wfocu_plugin_last_updated', '' );
		if ( ! WooFunnels_admin_notifications::has_notification( 'bwf_optin_notice' ) && ! empty( $last_updated_time ) && ( $last_updated_time + ( DAY_IN_SECONDS * 7 ) ) < time() ) {
			WooFunnels_admin_notifications::add_notification( array(
				'bwf_optin_notice' => array(
					'type'    => 'info',
					'content' => sprintf( '
                        <p>We\'re always building new features into UpStroke: One click Upsells, Play a part in shaping the future of UpStroke: One click Upsell and in turn benefit from new conversion-boosting updates.</p>
                        <p>Simply by allowing us to learn about your plugin usage. No sensitive information will be passed on to us. It\'s all safe & secure to say YES.</p>
                        <p><a href=\'%s\' class=\'button button-primary\'>Yes, I want to help</a> <a href=\'%s\' class=\'button button-secondary\'>No, I don\'t want to help</a> <a style="float: right;" target="_blank" href=\'%s\'>Know More</a></p> ', esc_url( wp_nonce_url( add_query_arg( array(
						'woofunnels-optin-choice' => 'yes',
						'ref'                     => filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ),
					) ), 'woofunnels_optin_nonce', '_woofunnels_optin_nonce' ) ), esc_url( wp_nonce_url( add_query_arg( 'woofunnels-optin-choice', 'no' ), 'woofunnels_optin_nonce', '_woofunnels_optin_nonce' ) ), esc_url( 'https://buildwoofunnels.com/non-sensitive-usage-tracking/?utm_source=upstroke-plugin&utm_campaign=usage-tracking&utm_medium=text&utm_term=optin' ) ),
				),
			) );
		}
	}
}

if ( class_exists( 'WFOCU_WooFunnels_Support' ) ) {
	WFOCU_Core::register( 'support', 'WFOCU_WooFunnels_Support' );
}
