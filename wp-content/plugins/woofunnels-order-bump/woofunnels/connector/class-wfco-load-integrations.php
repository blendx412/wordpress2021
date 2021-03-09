<?php

class WFCO_Load_Integrations {
	private static $ins = null;
	/**
	 * Saves all the main integration's object
	 * @var array
	 */
	public static $integrations = array();
	/**
	 * Saves all the action's object
	 * @var array
	 */
	public static $integrations_resources = array();
	private static $_registered_entity = array(
		'active' => array(),
	);
	private static $_registered_resource_entity = array();

	/**
	 * WFCO_Load_Integrations constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'system_load_integrations' ], 8 );
		add_action( 'plugins_loaded', [ $this, 'register_classes' ], 9 );
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|WFCO_Load_Integrations
	 */
	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Include all the Integration's files
	 */
	public static function system_load_integrations() {
		//      $integration_dir = __DIR__ . '/integrations';
		//      foreach ( glob( $integration_dir . '/*/class-*.php' ) as $_field_filename ) {
		//          $file_data = pathinfo( $_field_filename );
		//          if ( isset( $file_data['basename'] ) && 'index.php' == $file_data['basename'] ) {
		//              continue;
		//          }
		//          require_once( $_field_filename );
		//      }
		do_action( 'wfco_load_integrations' );
	}

	/**
	 * Registers every integration as a system integration
	 */
	public function register_classes() {
		$load_classes = self::get_registered_integration();
		if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
			foreach ( $load_classes as $access_key => $class ) {
				self::$integrations[ $access_key ] = $class::get_instance();
				$this->register_action_classes( $access_key );
			}
		}
	}

	/**
	 * Registers every action for every integration
	 *
	 * @param $access_key
	 */
	public function register_action_classes( $access_key ) {
		$load_resources = self::get_registered_actions();
		if ( is_array( $load_resources ) && count( $load_resources ) > 0 && isset( $load_resources[ $access_key ] ) ) {
			foreach ( $load_resources[ $access_key ] as $resource_access_key => $resource_class_array ) {
				self::$integrations_resources[ $access_key ][ $resource_access_key ] = $resource_class_array::get_instance();
			}
		}
		do_action( 'wfco_load_actions_' . $access_key );
	}

	/**
	 * Return the registered integrations
	 *
	 * @return mixed
	 */
	public static function get_registered_integration() {
		return self::$_registered_entity['active'];
	}

	/**
	 * Register the integration when the integration file is included
	 *
	 * @param $shortName
	 * @param $class
	 * @param null $overrides
	 */
	public static function register( $shortName, $class, $overrides = null ) {
		//Ignore classes that have been marked as inactive
		if ( isset( self::$_registered_entity['inactive'] ) && in_array( $class, self::$_registered_entity['inactive'] ) ) {
			return;
		}

		//Mark classes as active. Override existing active classes if they are supposed to be overridden
		$index = array_search( $overrides, self::$_registered_entity['active'] );
		if ( false !== $index ) {
			self::$_registered_entity['active'][ $index ] = $class;
		} else {
			//          self::$_registered_entity['active'][ $shortName ] = $class;
			self::$_registered_entity['active'][ sanitize_title( $class ) ] = $class;
		}

		//Mark overridden classes as inactive.
		if ( ! empty( $overrides ) ) {
			self::$_registered_entity['inactive'][] = $overrides;
		}

	}

	/**
	 * Returns the registered actions
	 *
	 * @return array
	 */
	public static function get_registered_actions() {
		return self::$_registered_resource_entity;
	}

	/**
	 * Register every action when action file is included
	 *
	 * @param $integration
	 * @param $action_name
	 */
	public static function register_actions( $integration, $action_name ) {
		self::$_registered_resource_entity[ sanitize_title( $integration ) ][ sanitize_title( $action_name ) ] = $action_name;
	}

	/**
	 * Return all the actions with group and their integrations
	 *
	 * @return array
	 */
	public static function get_all_integrations() {
		return self::$integrations_resources;
	}

	/**
	 * Return all the integrations
	 *
	 * @return array
	 */
	public static function get_integrations() {
		return self::$integrations;
	}
}

/**
 * Initiate the class as soon as it is included
 */
WFCO_Load_Integrations::get_instance();
