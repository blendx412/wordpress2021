<?php

//checks if a product has fancy product enabled
function wcbv_enabled( $product_id ) {

	$_product = wc_get_product( $product_id );

	$wcbv_meta = get_post_meta( $product_id, '_wcbv', true );

	if( empty($wcbv_meta) ) {

		//check if wpml plugin is activated
		global $sitepress;
		if($sitepress && method_exists($sitepress, 'get_original_element_id')) {
			$product_id = $sitepress->get_original_element_id($product_id, 'post_product');
		}

	}

	if ($_product) {
	    if ($_product->is_type('variable')) {
		    return true;
	    }
	}
	if (is_page_template('template-landingpage.php') && get_field('product_type') == true) {
		return true;
	}

	return false;

}

function wcbv_parse_to_int( $value ) {

	if($value == 'yes') { return 1; }
	else if($value == 'no') { return 0; }
	else if($value == '') { return 0; }
	else { return $value; }

}

function wcbv_get_label_option( $name, $default ) {

	if( defined('ICL_LANGUAGE_CODE') )
		$name .= '_' . ICL_LANGUAGE_CODE;

	return get_option( $name, $default );

}

function wcbv_not_empty( $value ) {

	$value = gettype($value) === 'string' ? trim($value) : $value;
	return $value == '0' || !empty($value);

}


?>