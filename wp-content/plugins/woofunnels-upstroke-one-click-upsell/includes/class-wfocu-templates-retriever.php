<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class to retrieve templates json file
 * Class WFOCU_Templates_Retriever
 */
class WFOCU_Templates_Retriever {

	/** @var null */
	private static $ins = null;

	/** @var array $supported page builders */
	protected $supported_builders = array();

	/**
	 * WFOCU_Templates_Retriever constructor.
	 */
	public function __construct() {

		$this->supported_builders = array( 'elementor' );
		//add_action( 'plugins_loaded', array( $this, 'wfocu_process_builder_templates' ), 25 );
	}

	/**
	 * @return WFOCU_Templates_Retriever|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Creating and retrieving  templates json
	 */
	public function wfocu_process_builder_templates() {
		$group = filter_input( INPUT_GET, 'get_templates', FILTER_SANITIZE_STRING );

		if ( ! empty( $group ) && in_array( $group, $this->supported_builders, true ) ) {
			$this->get_detailed_template( $group );
		}

		$get_single_template = filter_input( INPUT_GET, 'get_single_template', FILTER_SANITIZE_STRING );
		$group               = filter_input( INPUT_GET, 'group', FILTER_SANITIZE_STRING );
		if ( ! empty( $get_single_template ) && ! empty( $group ) ) {
			$this->get_single_template_json( $get_single_template, $group );
		}
	}

	/**
	 * Retrieving main json file for all template details
	 *
	 * @param $template
	 *
	 * @return array|mixed|object|null
	 */
	public function get_detailed_template( $group ) {

		$group_templates = array();
		if ( empty( $group ) || ! in_array( $group, $this->supported_builders, true ) ) {
			return $group_templates;
		}

		$templates_json_file = file_get_contents( $this->get_template_path( $group ) . 'template_details.json' );

		$group_templates = $this->get_group_template_details( $templates_json_file, $group );

		return $group_templates;

	}

	/**
	 * Getting template path inside plugin
	 *
	 * @param $group
	 *
	 * @return string
	 */
	public function get_template_path( $group ) {
		return WFOCU_PLUGIN_DIR . '/compatibilities/page-builders/' . $group . '/templates/';
	}

	/**
	 * Extracting single template group templates array from the all group templates detailed json
	 *
	 * @param $template_details_json
	 * @param $group
	 *
	 * @return array
	 */
	public function get_group_template_details( $templates_json, $group ) {
		$group_templates = array();
		if ( ! empty( $templates_json ) && ! is_array( $templates_json ) ) {
			$temp_arr = json_decode( $templates_json, ARRAY_A );

			foreach ( $temp_arr as $temp_slug => $temp_details ) {
				if ( $group === $temp_details['group'] ) {
					$group_templates[ $temp_slug ] = $temp_details;
				}
			}
		}

		return $group_templates;
	}

	/**
	 * Retrieving single template json
	 *
	 * @param $get_template
	 * @param $group
	 *
	 * @return array|bool|string|WP_Error
	 */
	public function get_single_template_json( $get_template, $group ) {

		$template_json = file_get_contents( $this->get_template_path( $group ) . $get_template . '/' . $get_template . '.json' );

		return $template_json;
	}
}

if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'template_retriever', 'WFOCU_Templates_Retriever' );
}
