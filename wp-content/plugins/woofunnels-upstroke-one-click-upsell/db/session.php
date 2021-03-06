<?php

/**
 * Track events in Caldera Forms
 *
 * @package WFOCU
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class WFOCU_DB_Session extends WFOCU_DB_Base {

	/**
	 * Primary fields
	 *
	 * @since 1.3.5
	 *
	 * @var array
	 */

	public $_session_key = 0;
	protected $primary_fields = array(
		'order_id'  => array(
			'%s',
			'strip_tags',
		),
		'email'     => array(
			'%s',
			'strip_tags',
		),
		'total'     => array(
			'%s',
			'strip_tags',
		),
		'gateway'   => array(
			'%s',
			'strip_tags',
		),
		'timestamp' => array(
			'%s',
			'strip_tags',
		),


	);


	/**
	 * Name of table
	 *
	 * @since 1.3.5
	 *
	 * @var string
	 */
	protected $table_name = 'wfocu_session';

	/**
	 * Class instance
	 *
	 * @since 1.3.5
	 *
	 * @var WFOCU_DB_Track
	 */
	private static $instance;

	/**
	 * Setup the actions to track upon
	 *
	 * @since 1.3.5
	 */
	protected function __construct() {
		add_action( 'wfocu_funnel_init_event', array( $this, 'funnel_start' ), 998, 4 );
	}


	/**
	 * Get class instance
	 *
	 * @since 1.3.5
	 *
	 * @return \WFOCU_DB_Track
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}


	public function funnel_start( $funnel_id, $order_id, $email, $gateway = '' ) {

		$recorded = $this->create( array(
			'order_id'  => $order_id,
			'email'     => $email,
			'total'     => '',
			'gateway'   => $gateway,
			'timestamp' => current_time( 'mysql' ),
		) );


		/*
		 * Record session key to record further
		 */
		$this->set_session_id( $recorded );

	}

	public function get_session_id() {
		return WFOCU_Core()->data->get( 'session_db_key', '' );
	}

	public function set_session_id( $id ) {
		$this->_session_key = $id;
		WFOCU_Core()->data->set( 'session_db_key', $id );
		WFOCU_Core()->data->save();
	}

	/**
	 * Delete an entry from DB
	 *
	 * @since 1.3.5
	 *
	 * @param int $id ID of entry
	 *
	 * @return bool
	 */
	public function delete( $id ) {
		global $wpdb;
		$deleted = $wpdb->delete( $this->get_table_name(), array( 'ID' => $id ) );   //db call ok; no-cache ok; WPCS: unprepared SQL ok.
		if ( false !== $deleted ) {
			WFOCU_Core()->track->delete( $id );

			return true;
		}
	}


	/**
	 * Getting session id by order id
	 *
	 * @param $order_id
	 *
	 * @return array
	 */
	public function get_session_id_by_order_id( $order_id ) {
		$session_id = WFOCU_Core()->track->query_results( array(
			'data'          => array(
				'id' => array(
					'type'     => 'col',
					'function' => '',
					'name'     => 'sess_id',
				),
			),
			'where'         => array(
				array(
					'key'      => 'events.order_id',
					'value'    => $order_id,
					'operator' => '=',
				),
			),
			'query_type'    => 'get_var',
			'session_table' => true,
		) );

		return $session_id;

	}

}

if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'session_db', 'WFOCU_DB_Session' );
}
