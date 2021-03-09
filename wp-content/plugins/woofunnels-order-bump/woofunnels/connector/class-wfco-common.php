<?php

class WFCO_Common {

	public static $ins = null;

	public static $http;

	public static $integrations_saved_data = array();

	public static function init() {

		add_action( 'wp_loaded', array( __CLASS__, 'get_integration_data' ) );
		add_action( 'plugins_loaded', [ __CLASS__, 'wfco_add_tables' ], 8.1 );
	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Create required tables
	 */
	public static function wfco_add_tables() {
		global $wpdb;
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$max_index_length = 191;

		$co_integrationSQL = "CREATE TABLE {$wpdb->prefix}wfco_integrations (
		  ID bigint(20) unsigned NOT NULL auto_increment,
		  last_sync datetime NOT NULL default '0000-00-00 00:00:00',
		  integration_slug varchar(255) default NULL,
		  status tinyint(1) not null default 0 COMMENT '1 - Active 2 - Inactive',
		  PRIMARY KEY  (ID),		  
		  KEY integration_slug (integration_slug($max_index_length)),
		  KEY status (status)
		) $collate;";

		$co_integration_metaSQL = "CREATE TABLE {$wpdb->prefix}wfco_integrationmeta (
		  ID bigint(20) unsigned NOT NULL auto_increment,
		  wfco_integration_id bigint(20) unsigned NOT NULL default '0',
		  meta_key varchar(255) default NULL,
		  meta_value longtext,
		  PRIMARY KEY  (ID),
		  KEY wfco_integration_id (wfco_integration_id),
		  KEY meta_key (meta_key($max_index_length))
		) $collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $co_integrationSQL );
		dbDelta( $co_integration_metaSQL );

	}


	public static function http() {
		if ( self::$http == null ) {
			self::$http = new WP_Http();
		}

		return self::$http;
	}

	/**
	 * Send remote call
	 *
	 * @param $api_url
	 * @param $data
	 * @param string $method_type
	 *
	 * @return array|mixed|null|object|string
	 */
	public static function send_remote_call( $api_url, $data, $method_type = 'post' ) {
		if ( 'get' == $method_type ) {
			$httpPostRequest = self::http()->get( $api_url, array(
					'body'      => $data,
					'sslverify' => false,
					'timeout'   => 30,
				) );
		} else {
			$httpPostRequest = self::http()->post( $api_url, array(
					'body'      => $data,
					'sslverify' => false,
					'timeout'   => 30,
				) );
		}

		if ( isset( $httpPostRequest->errors ) ) {
			$response = null;
		} elseif ( isset( $httpPostRequest['body'] ) && '' != $httpPostRequest['body'] ) {
			$body     = $httpPostRequest['body'];
			$response = json_decode( $body, true );
		} else {
			$response = 'No result';
		}

		return $response;
	}


	/**
	 * Save integration data
	 *
	 * @param $data
	 * @param $slug
	 * @param $status
	 *
	 * @return int
	 */
	public static function save_integration_data( $data, $slug, $status ) {
		global $wpdb;
		$settings                     = array();
		$settings['last_sync']        = current_time( 'mysql', 1 );
		$settings['integration_slug'] = $slug;
		$settings['status']           = $status;

		WFCO_Model_Integrations::insert( $settings );
		$integration_id = WFCO_Model_Integrations::insert_id();
		foreach ( $data as $key => $val ) {
			$meta_data                        = array();
			$meta_data['wfco_integration_id'] = $integration_id;
			$meta_data['meta_key']            = $key;
			$meta_data['meta_value']          = maybe_serialize( $val );
			WFCO_Model_Integrationmeta::insert( $meta_data );
		}

		return $integration_id;

	}

	/**
	 * get integration data for global access
	 */
	public static function get_integration_data() {
		$response = array();
		$temp_arr = array();
		$response = WFCO_Model_Integrations::get_results( 'SELECT * FROM {table_name}' );
		foreach ( $response as $integration ) {
			$temp_arr[ $integration['integration_slug'] ]['id']        = $integration['ID'];
			$temp_arr[ $integration['integration_slug'] ]['last_sync'] = $integration['last_sync'];
			$temp_arr[ $integration['integration_slug'] ]['status']    = $integration['status'];

			$settings_meta = WFCO_Model_Integrationmeta::get_rows( true, array( $integration['ID'] ) );
			foreach ( $settings_meta as $meta ) {
				$temp_arr[ $integration['integration_slug'] ][ $meta['meta_key'] ] = self::get_metakey_value( $settings_meta, $meta['meta_key'] );
			}
		}
		self::$integrations_saved_data = $temp_arr;
	}

	public static function get_metakey_value( $all_meta, $meta_key, $primary_id = null, $primary_key_name = null ) {
		$value = null;
		foreach ( $all_meta as $value1 ) {
			if ( ! is_null( $primary_id ) ) {
				if ( $value1[ $primary_key_name ] == $primary_id ) {
					if ( $meta_key == $value1['meta_key'] ) {
						$value = maybe_unserialize( $value1[ $meta_key ] );
						break;
					}
				}
			}

			if ( $meta_key == $value1['meta_key'] ) {
				$value = maybe_unserialize( $value1['meta_value'] );
				break;
			}
		}

		return $value;
	}


	/**
	 * update integration data
	 *
	 * @param array $new_data
	 */
	public static function update_integration_data( $new_data = array(), $integration_id = 0 ) {
		global $wpdb;
		$data = array();

		$data['last_sync'] = current_time( 'mysql', 1 );
		$where['ID']       = $integration_id;
		WFCO_Model_Integrations::update( $data, $where );

		$sql_query = "DELETE from {table_name} where wfco_integration_id = %d";
		$sql_query = $wpdb->prepare($sql_query,$integration_id);
		WFCO_Model_Integrationmeta::delete_multiple( $sql_query );

		foreach ( $new_data as $key => $val ) {
			$meta_data                        = array();
			$meta_data['wfco_integration_id'] = $integration_id;
			$meta_data['meta_key']            = $key;
			$meta_data['meta_value']          = maybe_serialize( $val );
			WFCO_Model_Integrationmeta::insert( $meta_data );
		}
	}

	public static function is_load_admin_assets( $screen_type = 'single' ) {
		$screen = get_current_screen();
		if ( 'all' === $screen_type ) {
			if ( filter_input( INPUT_GET, 'page' ) == 'connector' ) {

				return true;
			}
		} elseif ( 'listing' == $screen_type ) {

		} elseif ( 'all' === $screen_type || 'builder' == $screen_type ) {
			if ( filter_input( INPUT_GET, 'page' ) == 'connector' && filter_input( INPUT_GET, 'edit' ) > 0 ) {
				return true;
			}
		} elseif ( 'all' === $screen_type || 'settings' == $screen_type ) {
			if ( filter_input( INPUT_GET, 'page' ) == 'connector' && filter_input( INPUT_GET, 'tab' ) == 'settings' ) {
				return true;
			}
		}

		return apply_filters( 'wfco_enqueue_scripts', false, $screen_type, $screen );
	}

	public static function array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = iterator_to_array( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ), false );

		return $result;
	}

	public static function pr( $arr ) {
		echo '<pre>';
		print_r( $arr );
		echo '</pre>';
	}

	public static function slugify_classname( $class_name ) {
		$classname = sanitize_title( $class_name );
		$classname = str_replace( '_', '-', $classname );

		return $classname;
	}

	/**
	 * Recursive Un-serialization based on   WP's is_serialized();
	 *
	 * @param $val
	 *
	 * @return mixed|string
	 * @see is_serialized()
	 */
	public static function unserialize_recursive( $val ) {
		//$pattern = "/.*\{(.*)\}/";
		if ( is_serialized( $val ) ) {
			$val = trim( $val );
			$ret = unserialize( $val );
			if ( is_array( $ret ) ) {
				foreach ( $ret as &$r ) {
					$r = self::unserialize_recursive( $r );
				}
			}

			return $ret;
		} elseif ( is_array( $val ) ) {
			foreach ( $val as &$r ) {
				$r = self::unserialize_recursive( $r );
			}

			return $val;
		} else {
			return $val;
		}

	}

	public static function get_option() {

		return;
	}

	public static function get_current_trigger() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'connector' && isset( $_GET['status'] ) ) {
			return $_GET['status'];
		}

		return 'all';
	}

	public static function active_class( $trigger_slug ) {

		if ( self::get_current_trigger() == $trigger_slug ) {
			return 'current';
		}

		return '';
	}

	public static function string2hex( $string ) {
		$hex = '';
		for ( $i = 0; $i < strlen( $string ); $i ++ ) {
			$hex .= dechex( ord( $string[ $i ] ) );
		}

		return $hex;
	}

	public static function maybe_filter_boolean_strings( $options ) {
		$cloned_option = $options;
		foreach ( $options as $key => $value ) {

			if ( is_object( $options ) ) {

				if ( $value === 'true' || $value === true ) {

					$cloned_option->$key = true;
				}

				if ( $value === 'false' || $value === false ) {
					$cloned_option->$key = false;
				}
			} elseif ( is_array( $options ) ) {

				if ( $value === 'true' || $value === true ) {

					$cloned_option[ $key ] = true;
				}
				if ( $value === 'false' || $value === false ) {
					$cloned_option[ $key ] = false;
				}
			}
		}

		return $cloned_option;

	}

	public static function is_add_on_exist( $add_on = 'MultiProduct' ) {
		$status = false;
		if ( class_exists( 'WFCO_' . $add_on ) ) {
			$status = true;
		}

		return $status;
	}

	public static function get_date_format() {
		return get_option( 'date_format', '' ) . ' ' . get_option( 'time_format', '' );
	}

	public static function after( $needle, $inthat ) {
		if ( ! is_bool( strpos( $inthat, $needle ) ) ) {
			return substr( $inthat, strpos( $inthat, $needle ) + strlen( $needle ) );
		}
	}

	public static function before( $needle, $inthat ) {
		return substr( $inthat, 0, strpos( $inthat, $needle ) );
	}

	public static function between( $needle, $that, $inthat ) {
		return self::before( $that, self::after( $needle, $inthat ) );
	}

	public static function clean_ascii_characters( $content ) {

		if ( '' == $content ) {
			return $content;
		}

		$content = str_replace( '%', '_', $content );
		$content = str_replace( '!', '_', $content );
		$content = str_replace( '\"', '_', $content );
		$content = str_replace( '#', '_', $content );
		$content = str_replace( '$', '_', $content );
		$content = str_replace( '&', '_', $content );
		$content = str_replace( '(', '_', $content );
		$content = str_replace( ')', '_', $content );
		$content = str_replace( '(', '_', $content );
		$content = str_replace( '*', '_', $content );
		$content = str_replace( ',', '_', $content );
		$content = str_replace( '', '_', $content );
		$content = str_replace( '.', '_', $content );
		$content = str_replace( '/', '_', $content );

		return $content;
	}

	/**
	 * Function to get timezone string based on specified offset
	 *
	 * @param $offset
	 *
	 * @return string
	 */
	public static function get_timezone_by_offset( $offset ) {
		switch ( $offset ) {
			case '-12':
				return 'GMT-12';
				break;
			case '-11.5':
				return 'Pacific/Niue'; // 30 mins wrong
				break;
			case '-11':
				return 'Pacific/Niue';
				break;
			case '-10.5':
				return 'Pacific/Honolulu'; // 30 mins wrong
				break;
			case '-10':
				return 'Pacific/Tahiti';
				break;
			case '-9.5':
				return 'Pacific/Marquesas';
				break;
			case '-9':
				return 'Pacific/Gambier';
				break;
			case '-8.5':
				return 'Pacific/Pitcairn'; // 30 mins wrong
				break;
			case '-8':
				return 'Pacific/Pitcairn';
				break;
			case '-7.5':
				return 'America/Hermosillo'; // 30 mins wrong
				break;
			case '-7':
				return 'America/Hermosillo';
				break;
			case '-6.5':
				return 'America/Belize'; // 30 mins wrong
				break;
			case '-6':
				return 'America/Belize';
				break;
			case '-5.5':
				return 'America/Belize'; // 30 mins wrong
				break;
			case '-5':
				return 'America/Panama';
				break;
			case '-4.5':
				return 'America/Lower_Princes'; // 30 mins wrong
				break;
			case '-4':
				return 'America/Curacao';
				break;
			case '-3.5':
				return 'America/Paramaribo'; // 30 mins wrong
				break;
			case '-3':
				return 'America/Recife';
				break;
			case '-2.5':
				return 'America/St_Johns';
				break;
			case '-2':
				return 'America/Noronha';
				break;
			case '-1.5':
				return 'Atlantic/Cape_Verde'; // 30 mins wrong
				break;
			case '-1':
				return 'Atlantic/Cape_Verde';
				break;
			case '+1':
				return 'Africa/Luanda';
				break;
			case '+1.5':
				return 'Africa/Mbabane'; // 30 mins wrong
				break;
			case '+2':
				return 'Africa/Harare';
				break;
			case '+2.5':
				return 'Indian/Comoro'; // 30 mins wrong
				break;
			case '+3':
				return 'Asia/Baghdad';
				break;
			case '+3.5':
				return 'Indian/Mauritius'; // 30 mins wrong
				break;
			case '+4':
				return 'Indian/Mauritius';
				break;
			case '+4.5':
				return 'Asia/Kabul';
				break;
			case '+5':
				return 'Indian/Maldives';
				break;
			case '+5.5':
				return 'Asia/Kolkata';
				break;
			case '+5.75':
				return 'Asia/Kathmandu';
				break;
			case '+6':
				return 'Asia/Urumqi';
				break;
			case '+6.5':
				return 'Asia/Yangon';
				break;
			case '+7':
				return 'Antarctica/Davis';
				break;
			case '+7.5':
				return 'Asia/Jakarta'; // 30 mins wrong
				break;
			case '+8':
				return 'Asia/Manila';
				break;
			case '+8.5':
				return 'Asia/Pyongyang';
				break;
			case '+8.75':
				return 'Australia/Eucla';
				break;
			case '+9':
				return 'Asia/Tokyo';
				break;
			case '+9.5':
				return 'Australia/Darwin';
				break;
			case '+10':
				return 'Australia/Brisbane';
				break;
			case '+10.5':
				return 'Australia/Lord_Howe';
				break;
			case '+11':
				return 'Antarctica/Casey';
				break;
			case '+11.5':
				return 'Pacific/Auckland'; // 30 mins wrong
				break;
			case '+12':
				return 'Pacific/Wallis';
				break;
			case '+12.75':
				return 'Pacific/Chatham';
				break;
			case '+13':
				return 'Pacific/Fakaofo';
				break;
			case '+13.75':
				return 'Pacific/Chatham'; // 1 hr wrong
				break;
			case '+14':
				return 'Pacific/Kiritimati';
				break;
			default:
				return 'UTC';
				break;
		}
	}

	/**
	 * Function to get timezone string by checking WordPress timezone settings
	 * @return mixed|string|void
	 */
	public static function wc_timezone_string() {

		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) ) {
			return $timezone;
		}

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
			return 'UTC';
		}

		// get timezone using offset manual
		return self::get_timezone_by_offset( $utc_offset );
	}

	/**
	 * Check is_connector_page
	 *
	 * @param string $section
	 *
	 * @return bool
	 */
	public static function is_connector_page( $section = '' ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'connector' && '' == $section ) {
			return true;
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'connector' && isset( $_GET['section'] ) && $_GET['section'] == $section ) {
			return true;
		}

		return false;
	}

	/**
	 * Generate random string
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function generateRandomString( $length = 5 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
		}

		return $randomString;
	}

	/**
	 * Get all merge tags from string
	 *
	 * @param $text
	 *
	 * @return array|null
	 */
	public static function get_merge_tags_from_text( $text ) {
		$merge_tags = null;
		preg_match_all( '/\{{(.*?)\}}/', $text, $more_merge_tags );
		if ( is_array( $more_merge_tags[1] ) && count( $more_merge_tags[1] ) > 0 ) {
			$merge_tags = $more_merge_tags[1];
		}

		return $merge_tags;
	}

	public static function get_single_integration_data( $integration_slug, $meta_key = null ) {
		$data_to_return = [];
		if ( is_null( $meta_key ) ) {
			if ( isset( WFCO_Common::$integrations_saved_data[ $integration_slug ] ) ) {
				$data_to_return = WFCO_Common::$integrations_saved_data[ $integration_slug ];

				return $data_to_return;
			}
		}

		if ( isset( WFCO_Common::$integrations_saved_data[ $integration_slug ][ $meta_key ] ) ) {
			$data_to_return = WFCO_Common::$integrations_saved_data[ $integration_slug ][ $meta_key ];
		}

		return $data_to_return;
	}

	public static function get_call_object($connector_slug, $call_slug){
		return WFCO_Load_Integrations::$integrations_resources[ $connector_slug ][ $call_slug ];
	}

}
