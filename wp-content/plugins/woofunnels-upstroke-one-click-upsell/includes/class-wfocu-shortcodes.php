<?php

class WFOCU_Shortcodes {
	private static $ins = null;

	public function __construct() {

		$shortcodes = $this->get_shortcodes();

		foreach ( $shortcodes as $shortcode ) {

			add_shortcode( $shortcode, array( $this, $shortcode . '_output' ) );

		}
	}

	public function get_shortcodes() {
		return apply_filters( 'wfocu_shortcodes', array(
			'wfocu_yes_link',
			'wfocu_no_link',
			'wfocu_variation_selector_form',
			'wfocu_qty_selector',
		) );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function wfocu_yes_link_output( $atts, $html = '' ) {
		$atts = shortcode_atts( array(
			'key'   => '',
			'class' => '',
		), $atts );

		if ( '' === $atts['key'] ) {
			return __( 'Key is a required parameter in this shortcode', 'woofunnels-upstroke-one-click-upsell' );
		}
		ob_start();
		WFOCU_Core()->template_loader->add_attributes_to_buy_button();
		$attributes = ob_get_clean();

		return sprintf( '<a href="javascript:void(0);" class="%s" data-key="%s" %s>%s</a>', 'wfocu_upsell ' . $atts['class'], $atts['key'], $attributes, do_shortcode( $html ) );
	}

	public function wfocu_no_link_output( $atts, $html = '' ) {
		$atts = shortcode_atts( array(
			'key'   => '',
			'class' => '',
		), $atts );

		if ( '' === $atts['key'] ) {
			return __( 'Key is a required parameter in this shortcode', 'woofunnels-upstroke-one-click-upsell' );
		}

		return sprintf( '<a href="javascript:void(0);" class="%s" data-key="%s">%s</a>', 'wfocu_skip_offer ' . $atts['class'], $atts['key'], do_shortcode( $html ) );
	}

	public function wfocu_variation_selector_form_output( $atts ) {
		$atts = shortcode_atts( array(
			'key'   => '',
			'label' => __( 'No, thanks', 'woofunnels-upstroke-one-click-upsell' ),
			'display' => 'yes',
		), $atts );

		if ( '' === $atts['key'] ) {
			return __( 'Key is a required parameter in this shortcode', 'woofunnels-upstroke-one-click-upsell' );
		}

		$data = WFOCU_Core()->data->get( '_current_offer_data' );
		if ( false === $data ) {
			return '';
		}

		if ( ! isset( $data->products->{$atts['key']} ) ) {
			return '';
		}

		if ( ! isset( $data->products->{$atts['key']}->variations_data ) ) {
			return '';
		}
		$product_raw = array(
			'key'     => $atts['key'],
			'product' => $data->products->{$atts['key']},
			'display' => $atts['display'],
		);
		ob_start();
		WFOCU_Core()->template_loader->get_template_part( 'product/variation-form', $product_raw );

		return ob_get_clean();
	}

	public function wfocu_qty_selector_output( $atts ) {
		$atts = shortcode_atts( array(
			'key'   => '',
			'label' => __( 'Quantity', 'woocommerce' ),
		), $atts );

		if ( '' === $atts['key'] ) {
			return __( 'Key is a required parameter in this shortcode', 'woofunnels-upstroke-one-click-upsell' );
		}

		$data = WFOCU_Core()->data->get( '_current_offer_data' );

		if ( false === $data ) {
			return '';
		}
		$product_raw = array(
			'key'     => $atts['key'],
			'product' => $data->products->{$atts['key']},
			'label'   => $atts['label'],
		);

		ob_start();

		WFOCU_Core()->template_loader->get_template_part( 'qty-selector', $product_raw );

		return ob_get_clean();
	}


}

if ( class_exists( 'WFOCU_Core' ) ) {
	WFOCU_Core::register( 'shortcodes', 'WFOCU_Shortcodes' );
}
