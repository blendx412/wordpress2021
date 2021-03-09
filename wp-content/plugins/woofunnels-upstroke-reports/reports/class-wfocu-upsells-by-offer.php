<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstroke Admin Report - upstroke by offer
 *
 * Find the offers accepted from funnels
 *
 * @package        WooCommerce Upstroke
 * @subpackage    WC_Report_Upsells_By_Offer
 * @category    Class
 */
class WC_Report_Upsells_By_Offer extends WP_List_Table {

	private $totals;
	private $funnel_id;
	private $offers_deleted = false;

	/**
	 * WC_Report_Upsells_By_Offer constructor.
	 */
	public function __construct( $funnel_id ) {
		$this->funnel_id = $funnel_id;
		parent::__construct( array(
			'singular' => __( 'Offer', 'woofunnels-upstroke-reports' ),
			'plural'   => __( 'Offers', 'woofunnels-upstroke-reports' ),
		) );
	}

	/**
	 * No offer found or empty funnel_id text.
	 */
	public function no_items() {
		esc_html_e( 'No offer found.', 'woofunnels-upstroke-reports' );
	}

	/**
	 * No offer found or empty funnel_id text.
	 */
	public function offers_deleted() {
		esc_html_e( '*These offer(s) is/are deleted.', 'woofunnels-upstroke-reports' );
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * @param string $which
	 *
	 * @since 3.1.0
	 */
	protected function display_tablenav( $which ) {
		if ( 'top' === $which ) {
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		}
		?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php $funnel_name = empty( get_the_title( $this->funnel_id ) ) ? $this->funnel_id : get_the_title( $this->funnel_id ); ?>
			<?php if ( 'top' === $which ) {
				?>
                <span class="wfocu-funnel_name"><?php echo sprintf( __( '<strong>Funnel Name:</strong> %s', 'woofunnels-upstroke-reports' ), $funnel_name ); ?></span>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-reports&tab=upsells&report=upsells_by_funnel' ) ); ?>" class="button button-right right"><?php esc_html_e( 'Back to funnel reports', 'woofunnels-upstroke-reports' ) ?></a>
				<?php
			} ?>

			<?php if ( $this->has_items() ) : ?>
                <div class="alignleft actions bulkactions">
					<?php $this->bulk_actions( $which ); ?>
                </div>
			<?php
			endif;
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

            <br class="clear"/>
        </div>
		<?php
	}

	/**
	 * Output the report.
	 */
	public function output_report() {
		$this->prepare_items();
		echo '<div id="poststuff" class="woocommerce-reports-wide">';
		$this->display();
		if ( $this->offers_deleted ) {
			$this->offers_deleted();
		}
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

			case 'offer_name':
				return $funnel_data['offer_title'];

			case 'offer_viewed':
				return $funnel_data['offer_viewed'];

			case 'offer_accepted':
				return $funnel_data['offer_accepted'];

			case 'offer_rejected':
				return $funnel_data['offer_rejected'];

			case 'offer_pending':
				return $funnel_data['offer_pending'];

			case 'conversion_rate':
				return $funnel_data['conversion_rate'];

			case 'upsells':
				return wc_price( $funnel_data['upsells'] );
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
			'offer_name'      => __( 'Offers', 'woofunnels-upstroke-reports' ),
			'offer_viewed'    => sprintf( __( 'Offer Viewed %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers viewed from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'offer_accepted'  => sprintf( __( 'Offer Accepted %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers accepted from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'offer_rejected'  => sprintf( __( 'Offer Rejected %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers rejected from this funnel', 'woofunnels-upstroke-reports' ) ) ),
			'offer_pending'   => sprintf( __( 'Offer Pending  %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total offers pending (Failed + Expired) from this funnel.', 'woofunnels-upstroke-reports' ) ) ),
			'conversion_rate' => sprintf( __( 'Conversion Rate %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Conversion Rate.', 'woofunnels-upstroke-reports' ) ) ),
			'upsells'         => sprintf( __( 'Total Upsells  %s', 'woofunnels-upstroke-reports' ), WFOCU_Admin_Reports::wfocu_help_tip( __( 'Total upsells through this funnel.', 'woofunnels-upstroke-reports' ) ) ),
		);

		return $columns;
	}

	/**
	 * Prepare funnels list items.
	 */
	public function prepare_items() {
		$funnel_id             = $this->funnel_id;
		$this->items           = array();
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		if ( empty( $funnel_id ) || $funnel_id < 1 ) {
			return;
		}

		$steps  = get_post_meta( $funnel_id, '_funnel_steps', true );
		$steps  = ( ! empty( $steps ) && is_array( $steps ) ) ? $steps : array();
		$offers = wp_list_pluck( $steps, 'id' );

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
		) );

		$criteria      = array( 'action_id' => '2' );
		$funnel_offers = array_unique( array_merge( wp_list_pluck( wp_list_filter( $funnel_events, $criteria ), 'objects' ), $offers ) );
		sort( $funnel_offers );

		foreach ( is_array( $funnel_offers ) ? $funnel_offers : array() as $key => $offer_id ) {
			$this->items[ $key ]['offer_id'] = $offer_id;

			$offer_deleted = empty( get_the_title( $offer_id ) );
			if ( $offer_deleted ) {
				$this->offers_deleted = true;
			}
			$this->items[ $key ]['offer_title'] = $offer_deleted ? sprintf( __( 'Offer ID: %s*', 'woofunnels-upstroke-reports' ), $offer_id ) : get_the_title( $offer_id );

			$viewed   = array( 'action_id' => '2', 'objects' => $offer_id );
			$accepted = array( 'action_id' => '4', 'objects' => $offer_id );
			$rejected = array( 'action_id' => '6', 'objects' => $offer_id );
			$failed   = array( 'action_id' => '9', 'objects' => $offer_id );
			$expired  = array( 'action_id' => '7', 'objects' => $offer_id );
			$upsell   = array( 'action_id' => '4', 'objects' => $offer_id );

			$this->items[ $key ]['offer_viewed']   = count( wp_list_filter( $funnel_events, $viewed ) );
			$this->items[ $key ]['offer_accepted'] = count( wp_list_filter( $funnel_events, $accepted ) );
			$this->items[ $key ]['offer_rejected'] = count( wp_list_filter( $funnel_events, $rejected ) );
			$this->items[ $key ]['offer_failed']   = count( wp_list_filter( $funnel_events, $failed ) );
			$this->items[ $key ]['offer_expired']  = count( wp_list_filter( $funnel_events, $expired ) );
			$this->items[ $key ]['upsells']        = array_sum( wp_list_pluck( wp_list_filter( $funnel_events, $upsell ), 'value' ) );

			$this->items[ $key ]['offer_pending']   = $this->items[ $key ]['offer_expired'] + $this->items[ $key ]['offer_failed'];
			$divisor                                = $this->items[ $key ]['offer_accepted'] + $this->items[ $key ]['offer_pending'] + $this->items[ $key ]['offer_rejected'];
			$this->items[ $key ]['conversion_rate'] = ( $divisor > 0 ) ? wc_format_decimal( ( $this->items[ $key ]['offer_accepted'] / ( $divisor ) ) * 100, 2 ) . '%' : wc_format_decimal( '0.00' ) . '%';
		}
	}
}
