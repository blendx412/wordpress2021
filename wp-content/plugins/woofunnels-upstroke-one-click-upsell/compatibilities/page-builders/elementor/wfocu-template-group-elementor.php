<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WFOCU_Template_Group_Elementor extends WFOCU_Template_Group {
	public $allow_empty_template = true;
	public  $prefix = 'el';

	public function get_nice_name() {
		return __( 'Elementor', 'woofunnels-upstroke-one-click-upsell' );
	}

	public function get_slug() {
		return 'elementor';
	}

	public function load_templates() {

		$template = array_merge( $this->get_remote_templates(), $this->local_templates() );

		foreach ( $template as $temp_key => $temp_val ) {

			$temp_val = wp_parse_args( $temp_val, array(
				'path' => plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'compatibilities/page-builders/elementor/class-wfocu-template-elementor.php',
			) );

			$temp_val['thumbnail'] = WFOCU_PLUGIN_URL . '/admin/assets/img/templates/' . $this->get_template_thumbnail_name($temp_key) . '.svg';

			WFOCU_Core()->template_loader->register_template( $temp_key, $temp_val );
		}
		$this->maybe_register_empty( plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'compatibilities/page-builders/elementor/class-wfocu-template-elementor.php' );

	}

	public function local_templates() {
		$template = [];

		return $template;
	}

	public function get_edit_link() {
		return add_query_arg( [
			'post'   => '{{offer_id}}',
			'action' => 'elementor',
		], admin_url( 'post.php' ) );
	}

	public function get_preview_link() {
		return add_query_arg( [
			'p' => '{{offer_id}}',
		], site_url() );
	}


	public function update_template( $template, $offer, $offer_settings ) {

		delete_post_meta( $offer, '_elementor_data' );
		wp_update_post( [
			'ID'           => $offer,
			'post_content' => '',
		] );



		\Elementor\Plugin::$instance->db->set_is_elementor_page( $offer, true );
		if ( $this->if_current_template_is_empty( $template ) ) {
			return;
		}
		$get_template_json = WFOCU_Core()->template_retriever->get_single_template_json( $template, $this->get_slug() );

		require_once plugin_dir_path( WFOCU_PLUGIN_FILE ) . '/compatibilities/page-builders/elementor/class-wfocu-elementor-importer.php';

		$obj = new WFOCU_Importer_Elementor();
		$obj->import_single_template( $offer, $get_template_json, $offer_settings );
		$this->clear_cache($offer);
	}

	public function get_template_path( $template ) {
		return plugin_dir_path( WFOCU_PLUGIN_FILE ) . '/compatibilities/page-builders/elementor/templates/' . $template . '/template.json';
	}

	public function clear_cache($offer_id_new) {
		Elementor\Plugin::$instance->files_manager->clear_cache();
	}



}

WFOCU_Core()->template_loader->register_group( new WFOCU_Template_Group_Elementor, 'elementor' );
