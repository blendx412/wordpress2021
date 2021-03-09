<?php
/*
Plugin Name: WooCommerce Sticky Product Bar
Plugin URI: https://wordpress.org/plugins/woo-sticky-product-bar/
Description: This plugin allows you to add a sticky bar to the single product pages.
Version: 1.0.14
Author: OneTeamSoftware
Author URI: http://oneteamsoftware.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace OneTeamSoftware\Woocommerce\StickyProductBar;

// No direct file access
if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/includes/StickyProductBar.php';
