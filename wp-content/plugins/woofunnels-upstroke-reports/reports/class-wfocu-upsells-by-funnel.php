<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstroke Admin Report - upstroke by funnel
 *
 * Find the upsells accepted from funnels
 *
 * @package        WooCommerce Upstroke
 * @subpackage    WC_Report_Upsells_By_Funnel
 * @category    Class
 */
class WC_Report_Upsells_By_Funnel extends WP_List_Table {

	private $totals;
	private $funnels_deleted = false;

	/**
	 * WC_Report_Upsells_By_Funnel constructor.
	 */
	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Funnel', 'woofunnels-upstroke-reports' ),
			'plural'   => __( 'Funnels', 'woofunnels-upstroke-reports' ),
		) );
	}

	/**
	 * No funnels found text.
	 */
	public function no_items() {
		esc_html_e( 'No funnels found.', 'woofunnels-upstroke-reports' );
	}

	/**
	 * No offer found or empty funnel_id text.
	 */
	public function funnels_deleted() {
		esc_html_e( '*These funnel(s) is/are deleted.', 'woofunnels-upstroke-reports' );
	}

	/**
	 * Output the report.
	 */
	public function output_report() {

		$this->prepare_items();
		echo '<div id="poststuff" class="woocommerce-reports-wide">';
		$this->display();
		if ( $this->funnels_deleted ) {
			$this->funnels_deleted();
		} ?>
        <style>
            .wp-list-table.funnels td.offer_details.column-offer_details a {
                left: 15%;
                position: relative;
            }
        </style>
		<?php
		echo '</div>';

	}

	/**
	 * @param object $funnel_data
	 * @param string $column_name
	 *
	 * @return string|void
	 */
	public function column_default( $funnel_data, $column_name ) {

		switch ( $column_name ) {

			case 'funnel_name':
				return $funnel_data['funnel_title'];

			case 'offers_viewed':
				return $funnel_data['offers_viewed'];

			case 'offers_accepted':
				return $funnel_data['offers_accepted'];

			case 'offers_rejected':
				return $funnel_data['offers_rejected'];

			case 'offers_pending':
				return $funnel_data['offers_pending'];

			case 'conversion_rate':
				return $funnel_data['conversion_rate'];

			case 'upsells':
				return wc_price( $funnel_data['upsells'] );

			case 'offer_details':
				return '<a href="' . admin_url( 'admin.php?page=wc-reports&tab=upsells&report=upsells_by_funnel&funnel_id=' . $funnel_data['funnel_id'] ) . '">' . sprintf( __( 'View', 'woofunnels-upstroke-reports' ) ) . '</a>';
		}

		return '';
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'funnel_name'     => __( 'Funnels', WFOCU_Admin_Reports::$slug ),
			'offers_viewed'   => sprintf( __( 'Offers Viewed %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers viewed from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'offers_accepted' => sprintf( __( 'Offers Accepted %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers accepted from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'offers_rejected' => sprintf( __( 'Offers Rejected %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers rejected from this funnel', 'woofunnels-upstroke-reports' ) ) ),
			'offers_pending'  => sprintf( __( 'Offers Pending  %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers pending (Failed + Expired) from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'conversion_rate' => sprintf( __( 'Conversion Rate %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Conversion Rate.', 'woofunnels-upstroke-reports' ) ) ),
			'upsells'         => sprintf( __( 'Total Upsells  %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total upsells through this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'offer_details'   => sprintf( __( 'Offer Details  %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Offer-wise details for this funnel.', 'woofunnels-upstroke-reports' ) ) ),
		);

		return $columns;
	}

	/**
	 * Prepare funnels list items.
	 */
	public function prepare_items() {
		global $wpdb;
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$current_page          = absint( $this->get_pagenum() );
		$per_page              = absint( apply_filters( 'wcur_reports_funnels_per_page', 20 ) );
		$offset                = absint( ( $current_page - 1 ) * $per_page );
		$limit                 = $per_page;

		$this->totals = self::get_data();
		$this->items  = array();

		$funnels       = WFOCU_Core()->track->query_results( array(
			'data'           => array(
				'ID' => array(
					'type'     => 'post_data',
					'function' => 'DISTINCT',
					'name'     => 'funnel_id',
				),
			),
			'where'          => array(
				array(
					'key'      => 'posts.post_type',
					'value'    => 'wfocu_funnel',
					'operator' => '=',
				),
			),
			'query_type'     => 'get_results',
			'join_object_id' => true,
			'limit'          => $limit,
			'offset'         => $offset,
		) );
		$funnels_ids   = wp_list_pluck( $funnels, 'funnel_id' );
		$funnels_query = 'SELECT DISTINCT (event_meta.meta_value) FROM `' . $wpdb->prefix . 'wfocu_event_meta` as event_meta  WHERE event_meta.meta_key = ' . "'_funnel_id'";
		$ev_funnels    = $wpdb->get_col( $funnels_query );  //db call ok; no-cache ok; WPCS: unprepared SQL ok.

		$all_funnels = array_unique( array_merge( $funnels_ids, $ev_funnels ) );

		rsort( $all_funnels );
		foreach ( is_array( $all_funnels ) ? $all_funnels : array() as $key => $funnel_id ) {
			$this->items[ $key ]['funnel_id'] = $funnel_id;

			$funnel_deleted = empty( get_the_title( $funnel_id ) );
			if ( $funnel_deleted ) {
				$this->funnels_deleted = true;
			}

			$this->items[ $key ]['funnel_title'] = $funnel_deleted ? sprintf( __( 'Funnel ID: %s*', 'woofunnels-upstroke-reports' ), $funnel_id ) : get_the_title( $funnel_id );

			$funnel_events = WFOCU_Core()->track->query_results( array(
				'data'       => array(
					'action_type_id' => array(
						'type'     => 'col',
						'function' => '',
						'name'     => 'action_id',
					),
					'object_id'      => array(
						'type'     => 'col',
						'function' => '',
						'name'     => 'objects',
					),
					'value'          => array(
						'type'     => 'col',
						'function' => '',
						'name'     => 'value',
					),
				),
				'where'      => array(),
				'where_meta' => array(
					array(
						'type'       => 'meta',
						'meta_key'   => '_funnel_id', //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						'meta_value' => $funnel_id, //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
						'operator'   => '=',
					),
				),
				'query_type' => 'get_results',
				'nocache'    => true,
			) );

			$offers_viewed = $offers_accepted = $offers_rejected = $offers_failed = $offers_expired = $upsells = 0.00;
			foreach ( $funnel_events as $events ) {
				switch ( $events->action_id ) {
					case '2':
						$offers_viewed ++;
						break;
					case '4':
						$offers_accepted ++;
						break;
					case '6':
						$offers_rejected ++;
						break;
					case '9':
						$offers_failed ++;
						break;
					case '7':
						$offers_expired ++;
						break;
					case '5':
						$upsell  = ( ! empty( $events->value ) && $events->value > 0 ) ? $events->value : 0;
						$upsells = floatval( $upsells ) + floatval( $upsell );
						break;
					default:
						break;
				}
			}

			$this->items[ $key ]['offers_viewed']   = $offers_viewed;
			$this->items[ $key ]['offers_accepted'] = $offers_accepted;
			$this->items[ $key ]['offers_rejected'] = $offers_rejected;
			$this->items[ $key ]['offers_failed']   = $offers_failed;
			$this->items[ $key ]['offers_expired']  = $offers_expired;
			$this->items[ $key ]['upsells']         = $upsells;

			$this->items[ $key ]['offers_pending'] = $this->items[ $key ]['offers_expired'] + $this->items[ $key ]['offers_failed'];
			$divisor                               = $this->items[ $key ]['offers_accepted'] + $this->items[ $key ]['offers_pending'] + $this->items[ $key ]['offers_rejected'];

			$this->items[ $key ]['conversion_rate'] = ( $divisor > 0 ) ? wc_format_decimal( ( $this->items[ $key ]['offers_accepted'] / ( $divisor ) ) * 100, 2 ) . '%' : wc_format_decimal( '0.00' ) . '%';
		}

		/**
		 * Pagination.
		 */
		$this->set_pagination_args( array(
			'total_items' => $this->totals['funnel_count'],
			'per_page'    => $per_page,
			'total_pages' => ceil( $this->totals['funnel_count'] / $per_page ),
		) );

	}

	/**
	 * Gather totals for funnels
	 *
	 * @param array $args
	 *
	 * @return int
	 */
	public static function get_data( $args = array() ) {

		$funnels = WFOCU_Core()->track->query_results( array(
			'data'           => array(
				'ID' => array(
					'type'     => 'post_data',
					'function' => 'DISTINCT',
					'name'     => 'funnel_id',
				),
			),
			'where'          => array(
				array(
					'key'      => 'posts.post_type',
					'value'    => 'wfocu_funnel',
					'operator' => '=',
				),
			),
			'query_type'     => 'get_results',
			'join_object_id' => true,
		) );

		$funnels_totals['funnel_count'] = count( wp_list_pluck( $funnels, 'funnel_id' ) );

		return $funnels_totals;

	}
}
