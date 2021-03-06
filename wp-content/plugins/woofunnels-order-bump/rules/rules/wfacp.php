<?php

/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 18/9/18
 * Time: 10:43 AM
 */

class wfob_Rule_wfacp_page extends wfob_Rule_Base {

	public function __construct() {
		parent::__construct( 'wfacp_page' );
	}

	public function get_possible_rule_operators() {

		$operators = array(
			'any' => __( 'matched any of', 'woofunnels-order-bump' ),
		);

		return $operators;
	}

	public function get_possible_rule_values() {
		$result                  = array();
		$Woofunnel_transient_obj = WooFunnels_Transient::get_instance();
		$pages                   = $Woofunnel_transient_obj->get_transient( 'wfacp_publish_posts', WFACP_SLUG );

		if ( is_array( $pages ) && count( $pages ) > 0 ) {
			unset( $pages[0] );
			foreach ( $pages as $page ) {
				$result[ $page['id'] ] = $page['name'];
			}
		}

		return $result;
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		global $post;

		$wfacp_id = 0;
		if ( ! is_null( $post ) && ! wp_doing_ajax() ) {
			$wfacp_id = $post->ID;
		}
		if ( count( WFOB_Core()->public->posted_data ) > 0 && isset( WFOB_Core()->public->posted_data['_wfacp_post_id'] ) ) {
			$wfacp_id = absint( WFOB_Core()->public->posted_data['_wfacp_post_id'] );
		}
		if ( 0 == $wfacp_id && isset( $_REQUEST['wfacp_id'] ) ) {
			$wfacp_id = absint( $_REQUEST['wfacp_id'] );
		}
		$result = false;
		if ( isset( $rule_data['condition'] ) && isset( $rule_data['operator'] ) ) {
			if ( 'any' == $rule_data['operator'] && in_array( $wfacp_id, $rule_data['condition'] ) ) {
				$result = true;
			}
		}

		return $this->return_is_match( $result, $rule_data );
	}

}
