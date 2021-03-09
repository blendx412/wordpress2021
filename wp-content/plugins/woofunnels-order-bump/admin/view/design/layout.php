<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
$product = WFOB_Common::get_bump_products( WFOB_Common::get_id() );

if ( count( $product ) > 0 ) {
	foreach ( $product as $product_key => $data ) {

		$wc_product = wc_get_product( $data['id'] );
		if ( ! $wc_product instanceof WC_Product ) {
			return;
		}

		$type = $wc_product->get_type();
		$qty  = absint( $data['quantity'] );
		if ( isset( $data['variable'] ) && isset( $data['default_variation'] ) ) {
			$variation_id = absint( $data['default_variation'] );
			if ( $variation_id > 0 ) {
				$wc_product = WFOB_Common::wc_get_product( $variation_id );
				$wc_product = WFOB_Common::set_product_price( $wc_product, $data );
			}
		} else {
			$wc_product = WFOB_Common::set_product_price( $wc_product, $data );
		}

		$price_data = apply_filters( 'wfob_product_switcher_price_data', [], $wc_product );
		if ( empty( $price_data ) ) {
			$price_data['regular_org'] = $wc_product->get_regular_price( 'edit' );
			$price_data['price']       = $wc_product->get_price( 'edit' );
		}
		$price_data['regular_org'] *= $qty;
		$price_data['price']       *= $qty;

		$price_data = WFOB_Common::get_product_price_data( $wc_product, $price_data );
		?>
        <div class="wfob_bump_r_outer_wrap">
            <div class="wfob_wrapper wfob_bump wfob_clear">
                <div class="wfob_outer">
                    <div class="wfob_Box">
                        <div class="wfob_bgBox_table">
                            <div class="wfob_bgBox_tablecell wfob_check_container">
								<span v-if="model.header_enable_pointing_arrow" class="wfob_checkbox_blick_image_container wfob_bk_blink_wrap">
									<span v-if="model.point_animation=='1'" class="wfob_checkbox_blick_image"><img src="<?php echo WFOB_PLUGIN_URL; ?>/assets/img/arrow-blink.gif"></span>
									<span v-if="model.point_animation=='0'" class="wfob_checkbox_blick_image"><img src="<?php echo WFOB_PLUGIN_URL; ?>/assets/img/arrow-no-blink.gif"></span>
								</span>
                                <input type="checkbox" id="" data-value="" class="wfob_checkbox wfob_bump_product">
                                <label for="" class="wfob_title" v-html="<?php echo 'model.product_' . $product_key . '_title'; ?>"></label>
                            </div>
                            <div class="wfob_bgBox_tablecell wfob_price_container" v-if="model.enable_price=='1'">
                                <div class="wfob_price">
									<?php
									if ( $price_data['price'] > 0 && ( absint( $price_data['price'] ) !== absint( $price_data['regular_org'] ) ) ) {
										echo wc_format_sale_price( $price_data['regular_org'], $price_data['price'] );
									} else {
										echo wc_price( $price_data['price'] );
									}
									?>
                                </div>
                            </div>
                        </div>
                        <div class="wfob_contentBox wfob_clear">
                            <div class="wfob_pro_img_wrap" v-if="<?php echo 'model.product_' . $product_key . '_featured_image'; ?>">
								<?php
								$img_src = $wc_product->get_image();
								echo $img_src;
								?>
                            </div>
                            <div class="wfob_pro_txt_wrap">
                                <div class="wfob_product_description" v-html="<?php echo 'model.product_' . $product_key . '_description'; ?>"></div>
								<?php
								if ( isset( $data['variable'] ) ) {
									printf( "<a href='#' class='wfob_qv-button var_product' qv-id='%d' qv-var-id='%d'>%s</a>", 0, 0, __( 'Choose an option', 'woocommerce' ) );
								}
								?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}
?>
