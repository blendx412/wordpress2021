<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @author woofunnels
 * @package WooFunnels
 */
class WooFunnels_Upstroke_Multiple_Products_Support {

	protected static $instance;
	public $validation = true;
	public $is_submitted;
	protected $encoded_basename = '';

	/**
	 * @var WooFunnels_License_check
	 */
	public $license_instance;
	public $full_name = '';
	public $is_license_needed = true;

	/**
	 * WooFunnels_Upstroke_Multiple_Products_Support constructor.
	 */
	public function __construct() {

		$this->encoded_basename = sha1( WFOCU_MP_PLUGIN_BASENAME );
		$this->full_name        = WFOCU_MP_FULL_NAME;
		add_filter( 'woofunnels_plugins_license_needed', array( $this, 'add_license_support' ), 10 );
		add_action( 'init', array( $this, 'init_licensing' ), 12 );

		add_action( 'woofunnels_licenses_submitted', array( $this, 'process_licensing_form' ) );
		add_action( 'woofunnels_deactivate_request', array( $this, 'maybe_process_deactivation' ) );
		add_filter( 'plugin_action_links_' . WFOCU_MP_PLUGIN_BASENAME, array( $this, 'plugin_actions' ) );
		add_filter( 'woofunnels_default_reason_' . WFOCU_MP_PLUGIN_BASENAME, function () {
			return 1;
		} );

		add_filter( 'woofunnels_default_reason_default', function () {
			return 1;
		} );


		add_action( 'woofunnels_upstroke_multiple_products_support_license_check', array( $this, 'license_check' ) );

	}

	/**
	 * @return WooFunnels_Upstroke_Multiple_Products_Support
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @param $plugins
	 *
	 * @return mixed
	 */
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
			'product_version'   => WFOCU_MP_VERSION,
			'product_status'    => $status,
			'license_expiry'    => $renew,
			'product_file_path' => $this->encoded_basename,
			'existing_key'      => $license,
		);

		return $plugins;
	}

	/**
	 * Initialzing licensing
	 */
	public function init_licensing() {
		if ( class_exists( 'WooFunnels_License_check' ) && $this->is_license_needed ) {
			$this->license_instance = new WooFunnels_License_check( $this->encoded_basename );

			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFOCU_MP_PLUGIN_BASENAME,
					'plugin_name' => $this->full_name,
					//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFOCU_MP_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->start_updater();
			}
		}

	}

	/**
	 * @param $posted_data
	 * Shows lincence key form
	 */
	public function process_licensing_form( $posted_data ) {

		if ( isset( $posted_data['license_keys'][ $this->encoded_basename ] ) && '' !== $posted_data['license_keys'][ $this->encoded_basename ] ) {
			$key = $posted_data['license_keys'][ $this->encoded_basename ]['key'];
			//	$email = $posted_data['license_keys'][ $this->encoded_basename ]['email'];
			$data = array(
				'plugin_slug' => WFOCU_MP_PLUGIN_BASENAME,
				'plugin_name' => $this->full_name,
				//'email'       => $email,

				'license_key' => $key,
				'product_id'  => $this->full_name,
				'version'     => WFOCU_MP_VERSION,
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
		if ( isset( $posted_data['filepath'] ) && $posted_data['filepath'] == $this->encoded_basename ) {
			$plugins = WooFunnels_License_check::get_plugins();
			if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
				$data = array(
					'plugin_slug' => WFOCU_MP_PLUGIN_BASENAME,
					'plugin_name' => $this->full_name,
					//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
					'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
					'product_id'  => $this->full_name,
					'version'     => WFOCU_MP_VERSION,
				);
				$this->license_instance->setup_data( $data );
				$this->license_instance->deactivate_license();
				wp_safe_redirect( 'admin.php?page=' . $posted_data['page'] . '&tab=' . $posted_data['tab'] );
			}
		}
	}

	/**
	 * Checking license
	 */
	public function license_check() {
		$plugins = WooFunnels_License_check::get_plugins();
		if ( isset( $plugins[ $this->encoded_basename ] ) && count( $plugins[ $this->encoded_basename ] ) > 0 ) {
			$data = array(
				'plugin_slug' => WFOCU_MP_PLUGIN_BASENAME,
				//	'email'       => $plugins[ $this->encoded_basename ]['data_extra']['license_email'],
				'license_key' => $plugins[ $this->encoded_basename ]['data_extra']['api_key'],
				'product_id'  => $this->full_name,
				'version'     => WFOCU_MP_VERSION,
			);
			$this->license_instance->setup_data( $data );
			$this->license_instance->license_status();
		}
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

	/**
	 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support
	 *
	 * @param array $links array of existing links
	 *
	 * @return array modified array
	 */
	public function plugin_actions( $links ) {
		$links['deactivate'] .= '<i class="woofunnels-slug" data-slug="' . WFOCU_MP_PLUGIN_BASENAME . '"></i>';

		return $links;
	}
}

/**
 * Creating isntance of the class
 */
WooFunnels_Upstroke_Multiple_Products_Support::get_instance();
