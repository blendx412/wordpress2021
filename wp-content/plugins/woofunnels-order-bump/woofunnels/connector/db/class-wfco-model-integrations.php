<?php

class WFCO_Model_Integrations extends WFCO_Model {
	static $primary_key = 'ID';

	private static function _table() {
		global $wpdb;
		$tablename = strtolower( get_called_class() );
		$tablename = str_replace( 'wfco_model_', 'wfco_', $tablename );

		return $wpdb->prefix . $tablename;
	}

	public static function count_rows( $dependency = null ) {
		global $wpdb;
		$table_name = self::_table();
		$sql        = 'SELECT COUNT(*) FROM ' . $table_name;

		if ( isset( $_GET['status'] ) && 'all' !== $_GET['status'] ) {
			$status = $_GET['status'];
			$status = ( 'active' == $status ) ? 1 : 2;
			$sql    = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE status = %d", $status );
		}

		return $wpdb->get_var( $sql );
	}
}
