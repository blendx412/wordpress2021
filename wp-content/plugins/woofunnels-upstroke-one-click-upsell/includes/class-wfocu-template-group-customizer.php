<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WFOCU_Template_Group_Customizer extends WFOCU_Template_Group {

	public function get_templates() {
		return apply_filters( 'wfocu_templates_group_customizer', [ 'sp-classic', 'sp-vsl' ] );
	}


	public function is_visible() {

	}

	public function get_nice_name() {
		return __( 'Customizer', 'woofunnels-upstroke-one-click-upsell' );
	}


	public function local_templates() {
		$template = array(
			'sp-classic' => array(
				'path'        => WFOCU_TEMPLATE_DIR . '/sp-classic/template.php',
				'name'        => __( 'Product Upsell', 'woofunnels-upstroke-one-click-upsell' ),
				'thumbnail'   => WFOCU_PLUGIN_URL . '/admin/assets/img/templates/product-upsell-style-1.svg',
				'preview_url' => 'https://templates.buildwoofunnels.com/upstroke/offer/product-upsell-template/',

				'is_multiple' => false,
			),
			'sp-vsl'     => array(
				'path'        => WFOCU_TEMPLATE_DIR . '/sp-vsl/template.php',
				'name'        => __( 'VSL Upsell', 'woofunnels-upstroke-one-click-upsell' ),
				'thumbnail'   => WFOCU_PLUGIN_URL . '/admin/assets/img/templates/vsl-upsell.svg',
				'preview_url' => 'https://templates.buildwoofunnels.com/upstroke/offer/vsl-upsell-template/',
				'is_multiple' => false,
			),
		);

		return $template;
	}

	public function get_edit_link() {
		return admin_url( 'customize.php' ) . '?wfocu_customize=loaded&offer_id={{offer_id}}&funnel_id={{funnel_id}}&url={{step_url}}&return={{return}}';
	}

	public function get_preview_link() {
		return add_query_arg( [
			'p'               => '{{offer_id}}',
			'wfocu_customize' => 'loaded',
			'offer_id'        => '{{offer_id}}',
			'funnel_id'       => '{{funnel_id}}',
		], site_url() );
	}

	public function set_up_template() {

		$get_customizer_instance = WFOCU_Core()->customizer;
		$get_customizer_instance->load_template( WFOCU_Core()->template_loader->offer_data );
		WFOCU_Core()->template_loader->current_template = $get_customizer_instance->get_template_instance();
		if ( false === is_null( WFOCU_Core()->template_loader->current_template ) ) {
			$get_customizer_instance->get_template_instance()->set_offer_data( WFOCU_Core()->template_loader->offer_data );

			WFOCU_Core()->template_loader->current_template->set_data( WFOCU_Core()->template_loader->product_data );
		}
	}

	public function maybe_get_template( $template = '' ) {
		/**
		 * Loads a assigned template
		 */
		do_action( 'wfocu_front_template_after_validation_success' );
		WFOCU_Core()->template_loader->current_template->set_data( WFOCU_Core()->template_loader->product_data );

		WFOCU_Core()->template_loader->current_template->get_view();
		die();
	}
}

WFOCU_Core()->template_loader->register_group( new WFOCU_Template_Group_Customizer, 'customizer' );
