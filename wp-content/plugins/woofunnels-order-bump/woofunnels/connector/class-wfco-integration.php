<?php

abstract class WFCO_Integration {

	public static $GET = 1;
	public static $POST = 2;
	public static $DELETE = 3;
	public static $PUT = 4;
	public static $PATCH = 5;

	public $native_integration = false;

	public $default_merge_tags = array( 'rand:5', 'email' );
	public $autobot_int_slug = '';
	public $is_direct_integration = false;
	public $is_setting_required = true;

	public $nice_name = null;
	public $integration_settings = null;
	public $is_setting = true;

	public function get_merge_tags() {

	}
}