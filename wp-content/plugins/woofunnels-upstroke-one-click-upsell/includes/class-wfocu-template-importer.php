<?php

class WFOCU_Template_Importer {

	private static $ins = null;

	public function __construct() {
		add_action( 'wfocu_template_selected', [ $this, 'maybe_import_data' ], 10, 4 );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function maybe_import_data( $template_group, $template, $offer, $offer_settings ) {

		if ( $template_group === 'custom_page' ) {
			return;
		}
		$group_instance = WFOCU_Core()->template_loader->get_group( $template_group );

		$group_instance->update_template( $template, $offer, $offer_settings );
	}

}


if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'importer', 'WFOCU_Template_Importer' );
}
