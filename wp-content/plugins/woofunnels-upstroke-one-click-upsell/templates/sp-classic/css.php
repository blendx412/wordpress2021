<?php
$css = $this->internal_css;
global $output, $output_media;

$output_media = $this->assign_key_to_array( $output_media, 'desktop' );
$output_media = $this->assign_key_to_array( $output_media, 'tablet' );
$output_media = $this->assign_key_to_array( $output_media, 'mobile' );

/** Product */
if ( isset( $css['pro_title_font_size'] ) && is_array( $css['pro_title_font_size'] ) && count( $css['pro_title_font_size'] ) > 0 ) {
	foreach ( $css['pro_title_font_size'] as $pro_key => $val ) {
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['tablet'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['mobile'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' );
		if ( isset( $val['desktop'] ) && ! empty( $val['desktop'] ) ) {
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' ]['font-size'] = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
		}
		if ( isset( $val['tablet'] ) && ! empty( $val['tablet'] ) ) {
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' ]['font-size'] = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
		}
		if ( isset( $val['mobile'] ) && ! empty( $val['mobile'] ) ) {
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' ]['font-size'] = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
		}
	}
}
if ( isset( $css['reg_price_fs'] ) && is_array( $css['reg_price_fs'] ) && count( $css['reg_price_fs'] ) > 0 ) {
	foreach ( $css['reg_price_fs'] as $pro_key => $val ) {
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['tablet'], '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['mobile'], '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' );
		if ( isset( $val['desktop'] ) && ! empty( $val['desktop'] ) ) {
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' ]['font-size'] = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
		}
		if ( isset( $val['tablet'] ) && ! empty( $val['tablet'] ) ) {
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' ]['font-size'] = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
		}
		if ( isset( $val['mobile'] ) && ! empty( $val['mobile'] ) ) {
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' ]['font-size'] = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
		}
	}
}
if ( isset( $css['sale_price_fs'] ) && is_array( $css['sale_price_fs'] ) && count( $css['sale_price_fs'] ) > 0 ) {
	foreach ( $css['sale_price_fs'] as $pro_key => $val ) {
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['tablet'], '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['mobile'], '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' );
		if ( isset( $val['desktop'] ) && ! empty( $val['desktop'] ) ) {
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' ]['font-size'] = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
		}
		if ( isset( $val['tablet'] ) && ! empty( $val['tablet'] ) ) {
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' ]['font-size'] = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
		}
		if ( isset( $val['mobile'] ) && ! empty( $val['mobile'] ) ) {
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' ]['font-size'] = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
		}
	}
}
if ( isset( $css['pro_title_color'] ) && is_array( $css['pro_title_color'] ) && count( $css['pro_title_color'] ) > 0 ) {
	foreach ( $css['pro_title_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-title' ]['color'] = $val;
	}
}
if ( isset( $css['reg_price_color'] ) && is_array( $css['reg_price_color'] ) && count( $css['reg_price_color'] ) > 0 ) {
	foreach ( $css['reg_price_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-regular-price' ]['color'] = $val;
	}
}
if ( isset( $css['sale_price_color'] ) && is_array( $css['sale_price_color'] ) && count( $css['sale_price_color'] ) > 0 ) {
	foreach ( $css['sale_price_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-sale-price' ]['color'] = $val;
	}
}
if ( isset( $css['pro_desc_font_size'] ) && is_array( $css['pro_desc_font_size'] ) && count( $css['pro_desc_font_size'] ) > 0 ) {
	foreach ( $css['pro_desc_font_size'] as $pro_key => $val ) {
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' );
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' );
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' );
		if ( isset( $val['desktop'] ) && ! empty( $val['desktop'] ) ) {
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' ]['font-size']  = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' ]['font-size'] = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' ]['font-size']    = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
		}
		if ( isset( $val['tablet'] ) && ! empty( $val['tablet'] ) ) {
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' ]['font-size']  = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' ]['font-size'] = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' ]['font-size']    = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
		}
		if ( isset( $val['mobile'] ) && ! empty( $val['mobile'] ) ) {
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' ]['font-size']  = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description li' ]['font-size'] = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description' ]['font-size']    = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
		}
	}
}
if ( isset( $css['pro_bg_color'] ) && is_array( $css['pro_bg_color'] ) && count( $css['pro_bg_color'] ) > 0 ) {
	foreach ( $css['pro_bg_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key );

		$output[ '.wfocu-pkey-' . $pro_key ]['background-color'] = $val;
	}
}
if ( isset( $css['pro_border_color'] ) && is_array( $css['pro_border_color'] ) && count( $css['pro_border_color'] ) > 0 ) {
	foreach ( $css['pro_border_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' ]['border-color'] = $val;
	}
}
if ( isset( $css['pro_border_width'] ) && is_array( $css['pro_border_width'] ) && count( $css['pro_border_width'] ) > 0 ) {
	foreach ( $css['pro_border_width'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' ]['border-width'] = $val . 'px';
	}
}
if ( isset( $css['pro_border_type'] ) && is_array( $css['pro_border_type'] ) && count( $css['pro_border_type'] ) > 0 ) {
	foreach ( $css['pro_border_type'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-border' ]['border-style'] = $val;
	}
}
if ( isset( $css['pro_tab_title_fs'] ) && is_array( $css['pro_tab_title_fs'] ) && count( $css['pro_tab_title_fs'] ) > 0 ) {
	foreach ( $css['pro_tab_title_fs'] as $pro_key => $val ) {
		$output_media['desktop'] = $this->assign_key_to_array( $output_media['desktop'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' );
		$output_media['tablet']  = $this->assign_key_to_array( $output_media['tablet'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' );
		$output_media['mobile']  = $this->assign_key_to_array( $output_media['mobile'], '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' );

		if ( isset( $val['desktop'] ) && ! empty( $val['desktop'] ) ) {
			$output_media['desktop'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' ]['font-size'] = $val['desktop'] . ( ( isset( $val['desktop-unit'] ) ) ? $val['desktop-unit'] : 'px' );
		}
		if ( isset( $val['tablet'] ) && ! empty( $val['tablet'] ) ) {
			$output_media['tablet'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' ]['font-size'] = $val['tablet'] . ( ( isset( $val['tablet-unit'] ) ) ? $val['tablet-unit'] : 'px' );
		}
		if ( isset( $val['mobile'] ) && ! empty( $val['mobile'] ) ) {
			$output_media['mobile'][ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' ]['font-size'] = $val['mobile'] . ( ( isset( $val['mobile-unit'] ) ) ? $val['mobile-unit'] : 'px' );
		}
	}
}
if ( isset( $css['default_tab_tcolor'] ) && is_array( $css['default_tab_tcolor'] ) && count( $css['default_tab_tcolor'] ) > 0 ) {
	foreach ( $css['default_tab_tcolor'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title' ]['color'] = $val;
	}
}
if ( isset( $css['active_tab_tcolor'] ) && is_array( $css['active_tab_tcolor'] ) && count( $css['active_tab_tcolor'] ) > 0 ) {
	foreach ( $css['active_tab_tcolor'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title.wfocu-active' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-widget-tabs .wfocu-tab-title.wfocu-active' ]['color'] = $val;
	}
}

if ( isset( $css['pro_active_tab_color'] ) && is_array( $css['pro_active_tab_color'] ) && count( $css['pro_active_tab_color'] ) > 0 ) {
	foreach ( $css['pro_active_tab_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-tabs-view-horizontal .wfocu-tabs-style-line .wfocu-tab-title:after' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-tabs-view-horizontal .wfocu-tabs-style-line .wfocu-tab-title:after' ]['background-color'] = $val;
	}
}
if ( isset( $css['pro_content_color'] ) && is_array( $css['pro_content_color'] ) && count( $css['pro_content_color'] ) > 0 ) {
	foreach ( $css['pro_content_color'] as $pro_key => $val ) {
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description ul li' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description ol li' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  p' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  ul li' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  ol li' );
		$output = $this->assign_key_to_array( $output, '.wfocu-pkey-' . $pro_key . ' .wfocu-product-attr-wrapper' );

		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description p' ]['color']     = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description ul li' ]['color'] = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-short-description ol li' ]['color'] = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  p' ]['color']                  = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  ul li' ]['color']              = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-tab-content  ol li' ]['color']              = $val;
		$output[ '.wfocu-pkey-' . $pro_key . ' .wfocu-product-attr-wrapper' ]['color']            = $val;
	}
}
