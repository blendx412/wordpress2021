<?php


class WFOCU_MultiProductCore {


	private static $_instance = null;

	public function __construct() {
		add_filter( 'wfocu_templates_group_customizer', function ( $templates ) {
			return array_merge( $templates, [ 'mp-grid', 'mp-list' ] );
		} );
		$this->register_templates();

	}

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	public function register_templates() {
		$template = array(
			'path'        => WFOCU_MP_TEMPLATE_DIR . '/mp-grid/template.php',
			'name'        => __( 'Multi Product Grid', 'woofunnels-upstroke-one-click-upsell' ),
			'thumbnail'   => WFOCU_PLUGIN_URL . '/admin/assets/img/templates/multi-product-grid-three-column.svg',
			'preview_url' => 'https://templates.buildwoofunnels.com/upstroke/offer/multi-product-grid-template/',
			//'large_img'   => '//storage.googleapis.com/upstroke/multi-grid-full.jpg',
			'is_multiple' => true,
		);
		WFOCU_Core()->template_loader->register_template( 'mp-grid', $template );
		$template = array(
			'path'        => WFOCU_MP_TEMPLATE_DIR . '/mp-list/template.php',
			'name'        => __( 'Multi Product List', 'woofunnels-upstroke-one-click-upsell' ),
			'preview_url' => 'https://templates.buildwoofunnels.com/upstroke/offer/multi-product-list-template/',
			'thumbnail'   => WFOCU_PLUGIN_URL . '/admin/assets/img/templates/multi-product-list.svg',
			//'large_img'   => '//storage.googleapis.com/upstroke/multi-list-full.jpg',
			'is_multiple' => true,
		);

		WFOCU_Core()->template_loader->register_template( 'mp-list', $template );

	}


}
