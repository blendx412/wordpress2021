<?php

/**
 * Class contains all the methods & processes that belongs to the funnels/funnel
 * All the operations for the Funnels should be written here
 * Class WFOCU_Funnels
 */
class WFOCU_Funnels {

	private static $ins = null;
	private $funnel_id = 0;
	private $offers = 0;
	private $options = null;

	/**
	 * WFOCU_Funnels constructor.
	 */
	public function __construct() {

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );

		$id = filter_input( INPUT_GET, 'edit', FILTER_SANITIZE_SPECIAL_CHARS );

		if ( 'upstroke' === $page && 0 < $id ) {
			$this->set_funnel_id( $id );
		}

		add_action( 'wfocu_funnel_decided', array( $this, 'setup_funnel_options' ), 999 );
		add_action( 'init', array( $this, 'maybe_set_funnel_on_customizer' ), 1 );
		add_action( 'before_delete_post', array( $this, 'delete_funnel_permanently' ), 99, 1 );
		add_action( 'wfocu_license_activated', array( $this, 'create_default_funnels' ) );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function validate() {

		$get_query_key = filter_input( INPUT_GET, 'wfocu-key', FILTER_SANITIZE_STRING );
		$get_key       = WFOCU_Core()->data->get_funnel_key();
		if ( $get_query_key === $get_key ) {
			return true;
		}

		return false;
	}


	/**
	 * This method fires WP query and tries to get all the activated funnels
	 * After fetching funnel from the db/cache , performs `product` rule operations
	 *
	 * @param bool $force decides whether to take saved cache into account or not
	 *
	 * @return array|bool|mixed returns a bunch of funnels that will further take part in deciding the ultimate funnel
	 */
	public function setup_funnels( $force = false ) {
		$args = array(
			'post_type'        => WFOCU_Common::get_funnel_post_type_slug(),
			'post_status'      => 'publish',
			'nopaging'         => true, //phpcs:ignore WordPressVIPMinimum.Performance.NoPaging.nopaging_nopaging
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
		);

		$args = apply_filters( 'wfocu_add_control_meta_query', $args );

		$transient_data           = false;
		$woofunnels_transient_obj = WooFunnels_Transient::get_instance();

		$funnels = array();
		$key     = 'wfocu_instances';

		// handling for WPML
		if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== '' ) {
			$key .= '_' . ICL_LANGUAGE_CODE;
		}

		if ( false === $force ) {
			$transient_data = $woofunnels_transient_obj->get_transient( $key, 'upstroke' );

		}

		if ( false !== $transient_data ) {
			$funnels = $transient_data;
		} else {
			do_action( 'wfocu_before_query' );

			$query_result = new WP_Query( $args );

			if ( $query_result instanceof WP_Query && $query_result->have_posts() ) {
				$contents = $query_result->posts;

				if ( is_array( $contents ) && count( $contents ) > 0 ) {
					foreach ( $contents as $content_single ) {
						/**
						 * post instance extra checking added as some plugins may modify wp_query args on pre_get_posts filter hook
						 */
						$content_id = ( $content_single instanceof WP_Post && is_object( $content_single ) ) ? $content_single->ID : $content_single;

						do_action( 'wfocu_before_matching_rules_after_query' );

						array_push( $funnels, array(
							'id' => $content_id,
						) );

						do_action( 'wfocu_after_matching_rules_after_query' );
					}
					$woofunnels_transient_obj->set_transient( $key, $funnels, 21600, 'upstroke' );
				}
			}
			do_action( 'wfocu_after_query' );
		}

		return $funnels;

	}

	public function get_funnel_offers_admin( $funnel_id = 0 ) {

		if ( $funnel_id > 0 ) {

			$this->funnel_id = $funnel_id;
		}
		$woofunnels_cache_object = WooFunnels_Cache::get_instance();
		$cache_key               = 'wfocu_admin_data_' . $this->funnel_id;
		$get_xl_data             = $woofunnels_cache_object->get_cache( $cache_key, 'upstroke' );

		if ( $get_xl_data ) {
			return $get_xl_data;
		}
		$resp = array(
			'id'              => $this->funnel_id,
			'customize_url'   => admin_url( 'customize.php' ),
			'funnel_name'     => '',
			'funnel_desc'     => '',
			'steps'           => array(),
			'offers'          => array(),
			'upsell_downsell' => array(),
			'products'        => array(),
		);

		if ( $this->funnel_id > 0 ) {

			if ( ! empty( self::$offers ) ) {
				return self::$offers;
			}

			$post = get_post( $this->funnel_id );

			if ( ! is_null( $post ) ) {
				$resp['id']                         = $this->funnel_id;
				$resp['is_multiple_product_search'] = WFOCU_Common::is_add_on_exist();
				$resp['funnel_name']                = $post->post_title;
				$resp['funnel_desc']                = $post->post_content;
				$resp['steps']                      = $this->get_funnel_steps( $this->funnel_id );
				$resp['offers']                     = $this->get_offers( $this->funnel_id );
				$resp['upsell_downsell']            = $this->get_funnel_upsell_downsell( $this->funnel_id );

			}
			$woofunnels_cache_object->set_cache( $cache_key, $resp, 'upstroke' );
			$this->offers = $resp;

			return $this->offers;
		}

		return $resp;
	}

	public function get_funnel_steps( $funnel_id ) {

		$data = get_post_meta( $funnel_id, '_funnel_steps', true );
		if ( is_array( $data ) && count( $data ) > 0 ) {
			foreach ( $data as &$step ) {
				$step['url'] = get_permalink( $step['id'] );

				if ( $step['state'] === '1' || true === $step['state'] || 1 === $step['state'] ) {
					$step['state'] = '1';
				} else {
					$step['state'] = '0';
				}
				$step['slug'] = get_post( $step['id'] )->post_name;
			}
		}

		return apply_filters( 'get_funnel_steps', $data, $funnel_id );
	}

	public function get_offers( $funnel_id = 0 ) {
		$offer_data = array();

		$get_steps = $this->get_funnel_steps( $this->funnel_id );

		$get_steps_id = ( is_array( $get_steps ) && count( $get_steps ) > 0 ) ? wp_list_pluck( $get_steps, 'id' ) : array();

		if ( count( $get_steps_id ) === 0 ) {
			return $offer_data;
		}

		$args = array(
			'post_type'      => WFOCU_Common::get_offer_post_type_slug(),
			'posts_per_page' => - 1,
			'post_status'    => 'any',
			'post__in'       => $get_steps_id,
		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$offer_id                = get_the_ID();
				$offer_data[ $offer_id ] = WFOCU_Core()->offers->build_offer_product( WFOCU_Core()->offers->get_offer( $offer_id ), $offer_id );

			}
		}

		return $offer_data;
	}

	public function get_funnel_upsell_downsell( $funnel_id ) {
		$data = get_post_meta( $funnel_id, '_funnel_upsell_downsell', true );

		return apply_filters( 'get_funnel_offers', $data, $funnel_id );
	}

	public function get_funnel_products( $funnel_id ) {
		$data = get_post_meta( $funnel_id, '_funnel_products', true );

		return apply_filters( 'get_funnel_products', $data, $funnel_id );
	}

	public function update_funnel_products( $funnel_id, $data ) {
		if ( is_array( $data ) ) {

			$data = array_filter( $data );
		}
		update_post_meta( $funnel_id, '_funnel_products', $data );

	}

	public function save_funnel_options( $funnel_id, $data ) {
		$options = wp_parse_args( $data, $this->get_funnel_default_settings() );

		update_post_meta( $funnel_id, '_wfocu_settings', $options );
	}

	public function get_funnel_default_settings() {
		return array(
			'order_behavior'            => 'batching',
			'is_cancel_order'           => 'no',
			'offer_success_message_pop' => __( 'Congratulations! Your item has been successfully added to the order.', 'woofunnels-upstroke-one-click-upsell' ),
			'offer_failure_message_pop' => __( 'Sorry! We are unable to add this item to your order.', 'woofunnels-upstroke-one-click-upsell' ),
			'offer_wait_message_pop'    => __( 'Updating your order...', 'woofunnels-upstroke-one-click-upsell' ),
			'funnel_priority'           => '0',
			'is_tax_included'           => 'yes',
			'funnel_success_script'     => '',

		);
	}

	public function save_funnel_priority( $funnel_id, $priority = '0' ) {

		wp_update_post( array(
			'ID'         => $funnel_id,
			'menu_order' => $priority,
		) );
		WFOCU_Common::update_max_priority( $priority );
	}

	public function exclude_static_rules() {
		WFOCU_Core()->rules->excluded_rules_categories = array( 'basic' );
	}

	public function clear_exclusions() {
		WFOCU_Core()->rules->excluded_rules_categories = array();
	}

	/**
	 * hooked over `before_delete_post`
	 * Checks if we have funnel to delete, then delete all the offers and associated options as well
	 *
	 * @param $post_id current post id
	 */
	public function delete_funnel_permanently( $post_id ) {
		$get_post_type = get_post_type( $post_id );

		if ( WFOCU_Common::get_funnel_post_type_slug() === $get_post_type ) {
			$get_funnel_steps = WFOCU_Core()->funnels->get_funnel_steps( $post_id );
			if ( is_array( $get_funnel_steps ) && count( $get_funnel_steps ) > 0 ) {
				$get_ids = wp_list_pluck( $get_funnel_steps, 'id' );
				foreach ( $get_ids as $id ) {
					delete_option( 'wfocu_c_' . $id );
					wp_delete_post( $id );
				}
			}
		}
	}

	public function show_prices_including_tax( $data = array(), $key = '' ) {

		$display = apply_filters( 'wfocu_display_price_including_taxes', true, $data, $key );

		if ( false === $display ) {
			return false;
		}

		return wc_string_to_bool( $this->get_funnel_option( 'is_tax_included' ) );

	}

	public function get_funnel_option( $key = '' ) {

		if ( $key !== '' ) {
			return ( isset( $this->options[ $key ] ) ? $this->options[ $key ] : '' );
		}

		return $this->options;

	}

	public function maybe_set_funnel_on_customizer() {
		if ( WFOCU_Core()->template_loader->is_customizer_preview() ) {

			$this->set_funnel_id( filter_input( INPUT_GET, 'funnel_id', FILTER_SANITIZE_STRING ) );
			$this->setup_funnel_options();
		}
	}

	public function setup_funnel_options( $funnel_id = '' ) {
		if ( empty( $funnel_id ) ) {

			$funnel_id = WFOCU_Core()->data->get_funnel_id();

		}
		if ( empty( $funnel_id ) ) {

			$funnel_id = $this->get_funnel_id();

		}

		if ( '' !== $funnel_id && false !== $funnel_id && 0 !== $funnel_id ) {

			$options = get_post_meta( $funnel_id, '_wfocu_settings', true );

			$this->options = wp_parse_args( $options, $this->get_funnel_default_settings() );

			if ( 'admin_enqueue_scripts' === current_action() ) {
				$get_funnel = get_post( $funnel_id );
				if ( $get_funnel instanceof WP_Post ) {
					$this->options['funnel_priority'] = $get_funnel->menu_order;
				}
			}
		}

		return $this;
	}

	public function get_funnel_id() {
		return $this->funnel_id;
	}

	public function set_funnel_id( $funnel_id ) {
		$this->funnel_id = $funnel_id;

		return $this;
	}

	public function maybe_set_funnel_from_offer( $id ) {
		if ( ! empty( $this->get_funnel_id() ) ) {
			return;
		}

		$funnel_id = WFOCU_Core()->offers->get_parent_funnel( $id );

		if ( ! empty( $funnel_id ) ) {
			$this->set_funnel_id( $funnel_id );
			$this->setup_funnel_options();
		}

	}

	/**
	 * Generate default posts on license activation once from the wizard
	 */
	public function create_default_funnels() {
		$existing_funnles = get_posts( array(
			'post_type'      => 'wfocu_funnel',
			'posts_per_page' => '5',
			'post_status'    => 'any',
		) );  //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts

		$get_posts_created = get_option( 'wfocu_default_posts_created', false );
		/**
		 * bail out if user has funnels posts OR default posts are created
		 */
		if ( ! empty( $get_posts_created ) || count( $existing_funnles ) > 0 ) {
			return;
		}
		$funnels_to_create = array(
			array(
				'title'           => __( 'Sample Funnel Basic', 'woofunnels-upstroke-one-click-upsell' ),
				'status'          => 'publish',
				'offers_override' => array(
					0 => array( 'meta_override' => array( '_wfocu_setting_override' => array( 'template' => 'sp-classic' ) ) ),
				),

			),
			array(
				'title'           => __( 'Sample Funnel VSL', 'woofunnels-upstroke-one-click-upsell' ),
				'status'          => 'publish',
				'offers_override' => array(
					0 => array( 'meta_override' => array( '_wfocu_setting_override' => array( 'template' => 'sp-vsl' ) ) ),
				),
			),
		);

		foreach ( $funnels_to_create as $funnel ) {
			$this->generate_preset_funnel_data( $funnel );
		}
		update_option( 'wfocu_default_posts_created', 'yes' );

	}

	public function generate_preset_funnel_data( $data = [] ) {

		$get_default_schema = $this->get_default_funnel_schema();

		$funnel_data = wp_parse_args( $data, $get_default_schema );

		$funnel_post_type = WFOCU_Common::get_funnel_post_type_slug();
		$funnel_post_new  = array(
			'post_title'   => $funnel_data['title'],
			'post_type'    => $funnel_post_type,
			'post_status'  => $funnel_data['status'],
			'post_content' => $funnel_data['description'],
			'menu_order'   => $funnel_data['priority'],
		);

		$new_funnel_id = wp_insert_post( $funnel_post_new );

		if ( $funnel_data['offers'] && count( $funnel_data['offers'] ) > 0 ) {
			$funnel_data['meta']['_funnel_steps'] = [];
			foreach ( $funnel_data['offers'] as $key => $offer_raw ) {
				if ( isset( $data['offers_override'][ $key ] ) ) {
					$offer_raw = wp_parse_args( $data['offers_override'][ $key ], $offer_raw );
				}

				$offer_post_type = WFOCU_Common::get_offer_post_type_slug();
				$offer_post_new  = array(
					'post_title'  => $offer_raw['name'],
					'post_type'   => $offer_post_type,
					'post_name'   => sanitize_title( $offer_raw['name'] ) . '-' . time(),
					'post_status' => 'publish',
				);

				$offer_id_new = wp_insert_post( $offer_post_new );

				if ( ! is_wp_error( $offer_id_new ) && $offer_id_new ) {

					if ( isset( $offer_raw['meta_override'] ) ) {
						$offer_raw['meta'] = wp_parse_args( $offer_raw['meta_override'], $offer_raw['meta'] );
					}

					if ( isset( $offer_raw['meta']['_wfocu_setting_override'] ) ) {
						$offer_raw['meta']['_wfocu_setting'] = (object) $this->wp_parse_args( $offer_raw['meta']['_wfocu_setting_override'], $offer_raw['meta']['_wfocu_setting'] );
					}

					if ( isset( $offer_raw['parent_meta'] ) && ! empty( $offer_raw['parent_meta'] ) ) {
						global $wpdb;
						$parent_meta_all   = $offer_raw['parent_meta'];
						$sql_query_selects = [];
						$sql_query_meta    = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

						foreach ( $parent_meta_all as $meta_info ) {

							$meta_key = $meta_info->meta_key;

							/**
							 * Good to remove slashes before adding
							 */
							$meta_value = addslashes( $meta_info->meta_value );

							$sql_query_selects[] = "SELECT $offer_id_new, '$meta_key', '$meta_value'"; //db call ok; no-cache ok; WPCS: unprepared SQL ok.
						}

						$sql_query_meta .= implode( ' UNION ALL ', $sql_query_selects ); //db call ok; no-cache ok; WPCS: unprepared SQL ok.

						$wpdb->query( $sql_query_meta ); //db call ok; no-cache ok; WPCS: unprepared SQL ok.

					} else {
						foreach ( $offer_raw['meta'] as $key_meta => $meta_val ) {
							update_post_meta( $offer_id_new, $key_meta, $meta_val );
						}
					}

					update_post_meta( $offer_id_new, '_funnel_id', $new_funnel_id );
					$funnel_data['meta']['_funnel_steps'][] = array(
						'id'    => (string) $offer_id_new,
						'name'  => $offer_raw['name'],
						'type'  => $offer_raw['type'],
						'state' => $offer_raw['state'],
						'slug'  => sanitize_title( $offer_raw['name'] ) . '-' . time(),
					);

				}
			}
		}

		foreach ( $funnel_data['meta'] as $key => $meta_val ) {
			switch ( $key ) {
				case '_funnel_steps':
					update_post_meta( $new_funnel_id, $key, $funnel_data['meta']['_funnel_steps'] );
					break;
				case '_funnel_upsell_downsell':
					update_post_meta( $new_funnel_id, $key, $this->prepare_upsell_downsells( $funnel_data['meta']['_funnel_steps'] ) );
					break;
				default:
					update_post_meta( $new_funnel_id, $key, $meta_val );
			}
		}

		return $new_funnel_id;
	}

	public function get_default_funnel_schema() {
		$json = array(
			'ID'          => 'XXXX',
			'title'       => __( 'no title', 'woofunnels-upstroke-one-click-upsell' ),
			'description' => __( 'This is a sample description', 'woofunnels-upstroke-one-click-upsell' ),
			'status'      => WFOCU_SLUG . '-disabled',
			'priority'    => WFOCU_Common::get_next_funnel_priority(),
			'offers'      => array(
				0 => WFOCU_Core()->offers->get_default_offer_schema(),
			),
			'meta'        => array(
				'_wfocu_is_rules_saved'   => 'yes',
				'_wfocu_rules'            => array(),
				'_funnel_steps'           => array(),
				'_funnel_upsell_downsell' => array(),
			),
		);

		return $json;
	}

	public function wp_parse_args( $args, $defaults = '' ) {
		if ( is_object( $defaults ) ) {
			$def = get_object_vars( $defaults );
		}

		return wp_parse_args( $args, $def );
	}

	public function prepare_upsell_downsells( $steps ) {
		$upsell_downsell = array();
		if ( ! empty( $steps ) ) {
			foreach ( $steps as $key => $step ) {
				$offer_id = $step['id'];
				if ( '0' === $step['state'] ) {
					continue;
				}
				$upsell_id   = $this->get_next_upsell( $steps, $key );
				$downsell_id = $this->get_next_downsell( $steps, $key );

				if ( $downsell_id === 0 && $upsell_id > 0 ) {
					$downsell_id = $upsell_id;
				}
				$upsell_downsell[ $offer_id ]['y'] = $upsell_id;
				$upsell_downsell[ $offer_id ]['n'] = $downsell_id;
				unset( $downsell_id );
				unset( $upsell_id );
			}
		}

		return $upsell_downsell;

	}

	/**
	 * Finding the next upsell in the funnel for the respective index.
	 * We iterate over all the steps and try to find the next possible enables upsell offer.
	 *
	 * @param $steps
	 * @param $key
	 *
	 * @return int
	 */
	public function get_next_upsell( $steps, $key ) {
		$out = 0;

		if ( ! empty( $steps ) && '' !== $key ) {
			foreach ( $steps as $k => $step ) {
				if ( $k > $key ) {
					if ( $step['type'] === 'upsell' && '1' === $step['state'] ) {
						return $step['id'];
					}
				}
			}
		}

		return $out;
	}

	public function get_next_downsell( $steps, $key ) {
		$out = 0;
		$key = $key + 1;
		if ( ! empty( $steps ) && isset( $steps[ $key ] ) ) {
			/**
			 * Finding the next downsell means to find the immediate downsell or any upsell.
			 * Meaning the next followed offer will be assigned as downsell (as 'NO').
			 */
			if ( '1' === $steps[ $key ]['state'] ) {
				return $steps[ $key ]['id'];

			} else {
				$out = $this->get_next_downsell( $steps, $key );
			}
		}

		return $out;
	}

}

if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'funnels', 'WFOCU_Funnels' );
}
