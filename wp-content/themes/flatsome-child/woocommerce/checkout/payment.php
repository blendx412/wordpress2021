<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
	<h3 style="     color: #212121 !important; font-size: 1em  !important; text-transform: uppercase !important;   font-weight: 700 !important; font-weight: bold !important;" >Način plaćanja</h3>
	
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

	

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

			<?php wc_get_template( 'checkout/terms.php' ); ?>
		
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>


	</div>
	
		<div class="down_under">
            <div class="zaupanjevkvaliteto">


                
                        <div class="row checkout-page">
                            <div class="col-md-3 col-sm-3 col-xs-3 paddingicon_none">
                                <img class="lazy-img icon_shop loaded" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/prva.png" data-ll-status="loaded">
                                <p class="under_icon_text">Sigurna kupnja</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 paddingicon_none">
                                <img class="lazy-img icon_shop loaded" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/druga.png" data-ll-status="loaded">
                                <p class="under_icon_text">100% zadovoljnih kupaca</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 paddingicon_none">
                                <img class="lazy-img icon_shop loaded" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/tretja.png" data-ll-status="loaded">
                                <p class="under_icon_text">Besplatna dostava</p>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3 paddingicon_none">
                                <img class="lazy-img icon_shop loaded" src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/cetrta.png" data-ll-status="loaded">
                                <p class="under_icon_text">Plaćanje prilikom preuzimanja</p>
                            </div>
                        </div>

                        
            </div>
        </div>
	
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
