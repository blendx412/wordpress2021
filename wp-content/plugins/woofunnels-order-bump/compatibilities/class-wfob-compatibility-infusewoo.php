<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WFOB_Compatibility_With_Active_InfuseWooPro {
	public function __construct() {

		/* checkout page */
		add_action( 'wfob_product_switcher_price_data', [ $this, 'price_data' ], 1, 2 );
		add_action( 'wp_footer', [ $this, 'wp_footer' ] );

	}

	/**
	 * @param $price
	 * @param $pro WC_Product
	 *
	 * @return mixed
	 */
	public function price_data( $price, $pro ) {
		if ( defined( 'INFUSEDWOO_PRO_VER' ) ) {
			$infusionsoft_sub = $pro->get_meta( 'infusionsoft_sub' );
			if ( ! empty( $infusionsoft_sub ) && ( $infusionsoft_sub = absint( $infusionsoft_sub ) ) > 0 ) {

				$infusionsoft_trial       = $pro->get_meta( 'infusionsoft_trial' );
				$infusionsoft_sign_up_fee = $pro->get_meta( 'infusionsoft_sign_up_fee' );

				if ( $infusionsoft_trial > 0 ) {
					$price['regular_org'] = $pro->get_regular_price();
					if ( $infusionsoft_sign_up_fee > 0 ) {
						$price['price'] = $infusionsoft_sign_up_fee;
					} else {
						$price['price'] = 0;
					}
				}
			}
		}

		return $price;
	}

	public function wp_footer() {
		if ( defined( 'INFUSEDWOO_PRO_VER' ) ) {
			?>
            <script>
                window.addEventListener('load', function () {
                    (function ($) {

                        $(document.body).on('wfob_bump_trigger', function () {
                            $(document.body).trigger('update_checkout');
                        });


                    })(jQuery);
                });

            </script>
			<?php
		}
	}
}

WFOB_Plugin_Compatibilities::register( new WFOB_Compatibility_With_Active_InfuseWooPro(), 'infusewoopro' );
