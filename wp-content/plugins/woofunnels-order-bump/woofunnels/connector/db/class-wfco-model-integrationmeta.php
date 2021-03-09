<?php

class WFCO_Model_Integrationmeta extends WFCO_Model {
	static $primary_key = 'ID';

	private static function _table() {
		global $wpdb;
		$tablename = strtolower( get_called_class() );

		$tablename = str_replace( 'wfco_model_', 'wfco_', $tablename );

		return $wpdb->prefix . $tablename;
	}

	public static function get_rows( $only_query = false, $integration_ids = array() ) {
		global $wpdb;

		$table_name = self::_table();

		if ( $only_query ) {
			// For Fetching the meta of integrations
			$integrationCount        = count( $integration_ids );
			$stringPlaceholders      = array_fill( 0, $integrationCount, '%s' );
			$placeholdersintegration = implode( ', ', $stringPlaceholders );
			$sql_query               = "SELECT wfco_integration_id, meta_key, meta_value FROM $table_name WHERE wfco_integration_id IN ($placeholdersintegration)";
			$sql_query               = $wpdb->prepare( $sql_query, $integration_ids );
		}

		$result = $wpdb->get_results( $sql_query, ARRAY_A );

		return $result;
	}
}
