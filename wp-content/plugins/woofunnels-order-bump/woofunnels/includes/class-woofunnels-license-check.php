<?php

class WooFunnels_License_check {

	private $server_point = 'https://account.buildwoofunnels.com/';
	private $software_end_point = '';
	private $update_end_point = '';
	private $http = null;
	private $license_data = array();
	private $request_body = false;
	private $request_args = array(
		'timeout'   => 30,
		'sslverify' => false,
	);
	private $plugin_hash_key = '';
	private $human_name = '';
	private $version = '0.1.0';

	private $name = '';
	private $cache_key = '';
	private $default_keys = array(
		'plugin_slug'      => '',
		'email'            => '',
		'license_key'      => '',
		'product_id'       => '',
		'api_key'          => '',
		'version'          => '',
		'activation_email' => '',
	);

	public function __construct( $hash_key = '', $data = array() ) {
		//      delete_option( '_site_transient_update_plugins' );
		$this->software_end_point = add_query_arg( array(
			'wc-api' => 'am-software-api',
		), $this->server_point );
		$this->update_end_point   = add_query_arg( array(
			'wc-api' => 'upgrade-api',
		), $this->server_point );
		if ( '' !== $hash_key ) {
			$this->set_hash( $hash_key );
		}
		if ( is_array( $data ) && count( $data ) > 0 ) {
			$this->setup_data( $data );
		}
	}

	public function start_updater() {
		$data = $this->get_data();

		$this->version     = $data['version'];
		$this->name        = $data['plugin_slug'];
		$this->human_name  = $data['plugin_name'];
		$this->slug        = str_replace( '.php', '', basename( $data['plugin_slug'] ) );
		$this->wp_override = false;
		$this->cache_key   = md5( serialize( $this->slug ) );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );
		remove_action( 'after_plugin_row_' . $this->name, 'wp_plugin_update_row', 10 );
		add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'show_changelog' ), 25 );
	}

	public function http() {
		if ( is_null( $this->http ) ) {
			$this->http = new WP_Http();
		}

		return $this->http;
	}

	public function set_hash( $hash ) {
		if ( '' !== $hash ) {
			$this->plugin_hash_key = $hash;
		}
	}

	public function get_hash() {
		return $this->plugin_hash_key;
	}


	public function setup_data( $data ) {
		$default = array(
			'plugin_slug'      => '',
			'plugin_name'      => '',
			'email'            => '',
			'license_key'      => '',
			'product_id'       => '',
			'api_key'          => '',
			'version'          => '',
			'activation_email' => '',
			'platform'         => $this->get_domain(),
			'domain'           => $this->get_domain(),
		);
		if ( isset( $data['email'] ) ) {
			$data['activation_email'] = $data['email'];
		}
		if ( isset( $data['license_key'] ) ) {
			$data['api_key'] = $data['license_key'];
		}

		$data['instance']   = $this->pass_instance();
		$this->license_data = wp_parse_args( $data, $default );
	}

	public function reset_data() {
		$this->license_data = array(
			'plugin_slug'      => '',
			'email'            => '',
			'license_key'      => '',
			'product_id'       => '',
			'api_key'          => '',
			'version'          => '',
			'activation_email' => '',
		);
	}

	public function get_data() {
		return count( $this->license_data ) > 0 ? $this->license_data : $this->default_keys;
	}

	/**
	 * preform here license check for all installed plugin
	 */
	public function woofunnels_license_check() {

		$plugins    = $this->get_plugins();
		$all_status = array();
		if ( is_array( $plugins ) && count( $plugins ) > 0 ) {
			foreach ( $plugins as $slug => $plugin ) {
				$api_data = array(
					'plugin_slug' => $slug,
					'email'       => $plugin['data_extra']['license_email'],
					'license_key' => $plugin['data_extra']['api_key'],
					'product_id'  => $plugin['data_extra']['software_title'],
					'version'     => '0.1.0',
				);

				$this->setup_data( $api_data );
				$all_status[ $slug ] = $this->license_status();
				$this->reset_data();
			}
		}

		return $all_status;
	}

	private function build_output( $is_searilize = false ) {
		$output = $this->request_body;
		if ( ! is_wp_error( $output ) ) {
			$body = $output['body'];
			if ( '' != $body ) {
				if ( false === $is_searilize ) {
					$body = json_decode( $body, true );
					if ( $body ) {
						return $body;
					}
				} else {
					$object = maybe_unserialize( $body );
					if ( is_object( $object ) && count( get_object_vars( $object ) ) > 0 ) {
						return $object;
					}

					return false;
				}
			}
		}

		return false;
	}

	public function pass_instance() {
		$plugin    = $this->get_hash();
		$instances = self::get_plugins();
		if ( is_array( $instances ) ) {
			if ( isset( $instances[ $plugin ] ) ) {
				return $instances[ $plugin ]['instance'];
			} else {
				return md5( wp_generate_password( 12 ) );
			}
		}

		return false;
	}

	public static function get_plugins() {

		/**
		 * only runs it when we have cron call or admin dashboard call
		 */
		if ( defined( 'DOING_CRON' ) && true === DOING_CRON || ( is_admin() ) ) {
			return get_option( 'woofunnels_plugins_info', array() );
		}

		return array();


	}

	public static function update_plugins( $data ) {
		update_option( 'woofunnels_plugins_info', $data, 'no' );
	}

	/**
	 * Save plugin activation data in database
	 *
	 * @param $license_data
	 */
	private function save_license( $license_data ) {

		if ( ! empty( $license_data ) && isset( $license_data['activated'] ) && 1 == $license_data['activated'] ) {
			$slug = $this->get_hash();
			//          if ( isset( $license_data['instance'] ) ) {
			//              $slug = $license_data['instance'];
			//          }
			if ( '' !== $slug ) {
				$plugin_info          = self::get_plugins();
				$plugin_info[ $slug ] = $license_data;
				$this->update_plugins( $plugin_info );
			}
		}
	}

	/**
	 *remove plugin license from database
	 */
	private function remove_license( $license_data ) {
		if ( ! empty( $license_data ) && ( ( isset( $license_data['deactivated'] ) && 1 == $license_data['deactivated'] ) || $license_data['activated'] == 'inactive' ) ) {
			$slug = $this->get_hash();
			if ( '' !== $slug ) {
				$plugin_info = self::get_plugins();
				if ( isset( $plugin_info[ $slug ] ) ) {
					unset( $plugin_info[ $slug ] );
					self::update_plugins( $plugin_info );
				}
			}
		}
	}

	public function activate_license() {

		$parse_data            = $this->get_data();
		$parse_data['request'] = 'activation';
		$end_point_url         = add_query_arg( $parse_data, $this->software_end_point );
		//      exit($end_point_url);
		$this->request_body = $this->http()->get( $end_point_url, $this->request_args );
		$output             = $this->build_output();
		if ( false !== $output ) {

			$this->save_license( $output );
		}

		return $output;
	}

	public function deactivate_license() {
		$parse_data = $this->get_data();

		$parse_data['request'] = 'deactivation';
		$end_point_url         = add_query_arg( $parse_data, $this->software_end_point );
		//      exit( $end_point_url );
		$this->request_body = $this->http()->get( $end_point_url, $this->request_args );
		$ouput              = $this->build_output();
		if ( false !== $ouput ) {
			$this->remove_license( $ouput );
		}

		return $this->build_output();
	}

	public function license_status() {
		$parse_data            = $this->get_data();
		$parse_data['request'] = 'status';

		$end_point_url = add_query_arg( $parse_data, $this->software_end_point );
		//      exit($end_point_url );
		$this->request_body = $this->http()->get( $end_point_url, $this->request_args );
		$output             = $this->build_output();

		if ( false !== $output ) {
			if ( $output['status_check'] && 'inactive' == $output['status_check'] ) {
				$license_data                = array();
				$license_data['deactivated'] = 1;
				$this->remove_license( $license_data );
			} else {
				$activation_domain = $output['status_extra']['activation_domain'];
				$activation_domain = str_replace( [ 'https://', 'http://' ], '', $activation_domain );
				$activation_domain = trim( $activation_domain, '/' );
				if ( strpos( site_url(), $activation_domain ) === false ) {
					$license_data                = array();
					$license_data['deactivated'] = 1;
					$this->remove_license( $license_data );
				}
			}
		}

		return $output;
	}

	public function check_update_info() {
		$output = $this->check_plugin_info();

		if ( false !== $output ) {

			if ( isset( $output->errors ) ) {
				return false;
			}

			if ( version_compare( $this->version, $output->new_version, '<' ) ) {
				$parse_data            = $this->get_data();
				$parse_data['request'] = 'plugininformation';
				$end_point_url         = add_query_arg( $parse_data, $this->update_end_point );
				$this->request_body    = $this->http()->get( $end_point_url, $this->request_args );
				$out                   = $this->build_output( true );
				if ( false !== $out ) {
					$out->new_version    = $output->new_version;
					$out->package        = $output->package;
					$out->download_link  = $output->package;
					$out->access_expires = $output->access_expires;

					return $out;
				}
			}
		}

		return false;
	}

	public function check_plugin_info() {
		$parse_data = $this->get_data();

		$parse_data['request'] = 'pluginupdatecheck';
		$end_point_url         = add_query_arg( $parse_data, $this->update_end_point );
		//      exit($end_point_url);
		$this->request_body = $this->http()->get( $end_point_url, $this->request_args );

		return $this->build_output( true );
	}

	public function get_domain() {
		$domain = site_url();

		return $domain;
	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 *
	 *
	 * @param array $_transient_data Update array build by WordPress.
	 *
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {

		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass;
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $_transient_data;
		}

		if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
			return $_transient_data;
		}
		//      $version_info = $this->get_cached_version_info();
		$version_info = null;

		$output = $this->check_update_info();
		if ( false !== $output ) {

			$output->slug = $this->slug;
			$version_info = $output;
			$this->set_version_info_cache( $version_info );
		}

		if ( ! is_object( $version_info ) ) {
			return $_transient_data;
		}
		if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->version ) ) {
			if ( version_compare( $this->version, $version_info->version, '<' ) ) {
				$_transient_data->response[ $this->name ] = $version_info;
			}
			$_transient_data->last_checked           = current_time( 'timestamp' );
			$_transient_data->checked[ $this->name ] = $this->version;
		}

		return $_transient_data;
	}

	/**
	 * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
	 *
	 * @param string $file
	 * @param array $plugin
	 */
	public function show_update_notification( $file, $plugin ) {

		if ( is_network_admin() ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		if ( ! is_multisite() ) {
			return;
		}

		if ( $this->name != $file ) {
			return;
		}

		// Remove our filter on the site transient
		remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );

		$update_cache = get_site_transient( 'update_plugins' );

		$update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

		if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

			$version_info = $this->get_cached_version_info();

			if ( false === $version_info || is_null( $version_info ) ) {
				$output = $this->check_update_info();
				if ( false !== $output ) {
					$output->slug = $this->slug;
					$version_info = $output;
					$this->set_version_info_cache( $version_info );
				}
			}

			if ( ! is_object( $version_info ) ) {
				return;
			}

			if ( version_compare( $this->version, $version_info->version, '<' ) ) {

				$update_cache->response[ $this->name ] = $version_info;

			}

			$update_cache->last_checked           = current_time( 'timestamp' );
			$update_cache->checked[ $this->name ] = $this->version;

			set_site_transient( 'update_plugins', $update_cache );

		} else {

			$version_info = $update_cache->response[ $this->name ];

		}

		// Restore our filter
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->version, '<' ) ) {

			// build a plugin list row, with update notification
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
			# <tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
			echo '<tr class="plugin-update-tr" id="' . $this->slug . '-update" data-slug="' . $this->slug . '" data-plugin="' . $this->slug . '/' . $file . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt">';

			$changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

			if ( empty( $version_info->download_link ) ) {
				printf( __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'easy-digital-downloads' ), esc_html( $version_info->name ), '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">', esc_html( $version_info->version ), '</a>' );
			} else {
				printf( __( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'easy-digital-downloads' ), esc_html( $version_info->name ), '<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">', esc_html( $version_info->version ), '</a>', '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) . '">', '</a>' );
			}

			do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

			echo '</div></td></tr>';
		}
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 *
	 * @param mixed $_data
	 * @param string $_action
	 * @param object $_args
	 *
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( $_action != 'plugin_information' ) {

			return $_data;

		}

		if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {

			return $_data;

		}

		$version_info = $this->get_cached_version_info();

		if ( false === $version_info || is_null( $version_info ) ) {
			$output = $this->check_update_info();
			if ( false !== $output ) {
				$output->slug = str_replace( '.php', '', basename( $this->slug ) );
				$_data        = $output;
				$this->set_version_info_cache( $_data );
			}
		} else {
			$_data = $version_info;
		}

		// Convert sections into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
			$new_sections = array();
			foreach ( $_data->sections as $key => $value ) {
				$new_sections[ $key ] = $value;
			}

			$_data->sections = $new_sections;
		}

		// Convert banners into an associative array, since we're getting an object, but Core expects an array.
		if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
			$new_banners = array();
			foreach ( $_data->banners as $key => $value ) {
				$new_banners[ $key ] = $value;
			}

			$_data->banners = $new_banners;
		}

		return $_data;
	}

	/**
	 * Disable SSL verification in order to prevent download update failures
	 *
	 * @param array $args
	 * @param string $url
	 *
	 * @return object $array
	 */
	public function http_request_args( $args, $url ) {

		$verify_ssl = $this->verify_ssl();
		if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = $verify_ssl;
		}

		return $args;

	}


	public function show_changelog() {

		global $edd_plugin_data;

		if ( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' != $_REQUEST['edd_sl_action'] ) {
			return;
		}

		if ( empty( $_REQUEST['plugin'] ) ) {
			return;
		}

		if ( empty( $_REQUEST['slug'] ) ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die( __( 'You do not have permission to install plugin updates', 'woofunnels' ), __( 'Error', 'woofunnels' ), array(
				'response' => 403,
			) );
		}

		$data         = $edd_plugin_data[ $_REQUEST['slug'] ];
		$beta         = ! empty( $data['beta'] ) ? true : false;
		$cache_key    = md5( 'edd_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_' . $beta . '_version_info' );
		$version_info = $this->get_cached_version_info( $cache_key );

		if ( false === $version_info ) {

			$api_params = array(
				'edd_action' => 'get_version',
				'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
				'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
				'slug'       => $_REQUEST['slug'],
				'author'     => $data['author'],
				'url'        => home_url(),
				'beta'       => ! empty( $data['beta'] ),
			);

			$verify_ssl = $this->verify_ssl();
			$request    = wp_remote_post( $this->api_url, array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => $api_params,
			) );

			if ( ! is_wp_error( $request ) ) {
				$version_info = json_decode( wp_remote_retrieve_body( $request ) );
			}

			if ( ! empty( $version_info ) && isset( $version_info->sections ) ) {
				$version_info->sections = maybe_unserialize( $version_info->sections );
			} else {
				$version_info = false;
			}

			if ( ! empty( $version_info ) ) {
				foreach ( $version_info->sections as $key => $section ) {
					$version_info->$key = (array) $section;
				}
			}

			$this->set_version_info_cache( $version_info, $cache_key );

		}

		if ( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
			echo '<div style="background:#fff;padding:10px;">' . $version_info->sections['changelog'] . '</div>';
		}

		exit;
	}

	public function get_cached_version_info( $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		$cache = get_option( $cache_key );

		if ( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
			return false; // Cache is expired
		}

		return json_decode( $cache['value'] );

	}

	public function set_version_info_cache( $value = '', $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		$data = array(
			'timeout' => strtotime( '+3 hours', current_time( 'timestamp' ) ),
			'value'   => json_encode( $value ),
		);

		update_option( $cache_key, $data, 'no' );

	}

	/**
	 * Returns if the SSL of the store should be verified.
	 *
	 * @since  1.6.13
	 * @return bool
	 */
	private function verify_ssl() {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}
}
