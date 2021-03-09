<?php
/**
 * Checkout Form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wrapper_classes = array();
$row_classes     = array();
$main_classes    = array();
$sidebar_classes = array();

$layout = get_theme_mod( 'checkout_layout' );

if ( ! $layout ) {
	$sidebar_classes[] = 'has-border';
}

if ( $layout == 'simple' ) {
	$sidebar_classes[] = 'is-well';
}

$wrapper_classes = implode( ' ', $wrapper_classes );
$row_classes     = implode( ' ', $row_classes );
$main_classes    = implode( ' ', $main_classes );
$sidebar_classes = implode( ' ', $sidebar_classes );


if ( ! fl_woocommerce_version_check( '3.5.0' ) ) {
	wc_print_notices();
}

//do_action( 'woocommerce_before_checkout_form', $checkout );





?>

<!--
<script type="text/javascript">
jQuery( document ).ready(function( $ ) {
	$('.add-to-cart-additional').click(function(e){
		e.preventDefault();
		var item_to_add = $(this).data('add-to-cart');
		$('.'+item_to_add+' .quantity .plus').click();
	});
    $('.qty').change(function() {
    	setTimeout(function(){
    		location.reload();
    	},
    	1500);
    });
    jQuery(window).on('load', function() {
        jQuery('body').trigger('update_checkout');
    });
    /*
	$(window).on('load', function() {
		$(document).on('change', '.wfob_bump_product', function() {
			setTimeout(function() {
				location.reload();
			}, 1500);
		});
	});
	*/
});
</script>
-->

<?php
/*
if ( empty( WC()->cart->applied_coupons ) ) {
	$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
	wc_print_notice( $info_message, 'notice' );
}
*/
?>



<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');


.wc_payment_methods {
    padding: 5px 0 5px 0 !important;
}

.wc_payment_methods .cart-box {
    border: none !important;
    padding: 3px 15px 3px 15px !important;
    margin-bottom: 0px !important;
}

.woocommerce-shipping-fields { display: none; }

.woocommerce-additional-fields { display: none; }

.kl_newsletter_checkbox_field {
	display: none !important;
}


.priorit .add_insurance_box{
	background: #ffe5b5 !important;
}

.priorit #insurance3{
	background: #ffe5b53d !important;
}

#insurance .optional {
	display: none;
}

.woocommerce-shipping-fields {
	    padding-bottom: 40px;
}

#main {
	padding-top: 0 !important;

}

.focused-checkout-footer {
	display: none;
}

body {
	padding-top: 25px;
	background: white;
	font-family: 'Roboto', sans-serif !important;
}

h1,h2,h3,h4,h5, input, p, ul, li, a, button {
	font-family: 'Roboto', sans-serif !important;
}


.checkout-rating {
    background-color: #9bd7ff59;
    border: 1px solid #6fa9d1;
	padding: 13px 10px;
	text-align: center;
	margin-bottom: 5px;
}
.checkout-title {
    font-size: 29px;
	text-align: center;
}
.underline {
    text-decoration: underline;
	font-weight: bold;
}



.checkout-rating2 {
    background-color: #ffa4004a;
    border: 1px solid #ffa401;
	padding: 13px 10px;
	text-align: center;
	margin-bottom: 20px;
}
.checkout-title2 {
    font-size: 29px;
	text-align: center;
}
.underline2 {
    text-decoration: underline;
	font-weight: bold;
}



#billing_country_field, #shipping_country_field {
	display: none;
}

input[type='email'], input[type='date'], input[type='search'], input[type='number'], input[type='text'], input[type='tel'], input[type='url'], input[type='password'], textarea, select, .select-resize-ghost, .select2-container .select2-choice, .select2-container .select2-selection {

	border-color: #c9c9c9;
    font-size: 15px;
	background-color: #fff!important;
    color: #000!important;
	border: 1px solid #ccc;
    box-shadow: none;
    border-radius: 4px;
	font-weight: 400;
    padding: .4761904762em 1em;
    transition: all .2s ease-out;
    width: 100%;
    min-height: 54px;
}

#billing_address_1_field {
    display: inline-block;
    margin-right: 8px;
    width: 64%;
}
#billing_address_2_field {
    display: inline-block;
    width: 33%;
    float: right;
}

.woocommerce-billing-fields {
    padding-top: 0px;
    border-top: none !important;
}

#ship-to-different-address {
	margin-bottom: 2px;
}

#order_comments {
	font-size: 13.9px;
    line-height: 1.35;
    text-transform: uppercase;
}

body #wfob_wrap .wfob_wrapper[data-wfob-id='174089'] .wfob_bump {
    border: 1px solid #ff1923 !important;
}

#wfob_wrap .wfob_bgBox_table {
	background: #f5f5f5 !important;
    padding: 1px 15px !important;
}
#wfob_wrap .wfob_bgBox_table .wfob_bgBox_cell.wfob_img_box {
    width: auto !important;
}
#wfob_wrap .wfob_content_sec {
    padding-left: 11px !important;
    text-align: left;
	margin-left: 0px;
}

.wfob_title:hover {
    color: #ff0000 !important;
}

.wfob_pro_img_wrap {
    width: 70px;
    float: left;
    margin-right: 15px;
    margin-top: 0;
    border: 1px solid #e7e7e7;
    height: auto;
}

.wfob_pro_img_wrap img {
	margin-top: 0px;
}

.wfob_price_container {
	text-align: right !important;

}

#wfob_wrap .wfob_wrapper .wfob_bump {
    border: 1px solid #ff1922 !important;
    border-radius: 0 !important;
}

@media only screen and (max-width: 414px) {
	.wfob_price_container {
		display: none !important;
	}

	.wfob_check_container {
		padding-top: 6px !important;
		padding-bottom: 5px !important;
	}
}


#insurance .woocommerce-Price-amount {
    float: right;
    padding-right: 11px;
}

#insurance {
	border: 1px solid #c2c2c2;
}

#insurance3 {
	padding: 10px;
}

.add_insurance_box {
	background: #f5f5f5 !important;
        padding: 9px 15px 1px 15px !important;
		margin-bottom: 0;
}
.insurance-desc {

    font-size: 15px;
    line-height: 1.5;
}



#order_review {
	padding: 0 14px;
    background: #f5f5f5;
    border: 1px solid #c8c7c8;
}

#order_review thead {
	display: none;
}

.wc_payment_methods {
	padding: 14px;
    background: #f5f5f5;
    border: 1px solid #c8c7c8;

}


#order_review {
	background: white;
	padding: 0;
	border: none;
}

#payment #order_review_heading {
	margin-bottom: 0;
}

.woocommerce-checkout-review-order .order-total th, .woocommerce-checkout-review-order .order-total td {
    padding: 7px 0 !important;
}

table.shop_table a.remove {
	display: none !important;
}

.cqoc_product_name {
	    margin-left: -6px !important;
}

.woocommerce-checkout-review-order .order-total {
    background-color: white !important;
}

.woocommerce-checkout-review-order .order-total th, .woocommerce-checkout-review-order .order-total td {
	border: none !important;
}

.woocommerce-terms-and-conditions-wrapper p {
	line-height: 1.3 !important;
    margin-top: 8px !important;
    font-weight: 500  !important;
	color: #666 !important;
}

.woocommerce-terms-and-conditions-wrapper p label {
	 font-weight: 500  !important;
	color: #666 !important;
}

.woocommerce-checkout #payment .place-order .button {
    font-size: 1.41575em;
    padding-bottom: 0;
    width: 100%;
    padding-top: 12px !important;
    white-space: pre-wrap !important;
    background: #ff6600 !important;
    background-color: #ff6600 !important;
    color: white !important;
    border: 1px solid black;
	line-height: 1.6em !important;
	margin-bottom: 0 !important;
	padding-bottom: 10px;	   
    margin-top: 0 !important;

}


#billing_city_field {
	
	width: 48% !important;
    float: left !important;
margin-right: 4% !important;
display: inline-block !important;
clear: none !important;
}


#billing_postcode_field {
	
	width: 48%  !important;
    float: right !important;
	display: inline-block !important;
	clear: none !important;
}


.woocommerce-checkout button#place_order:before {
    background: url("/wp-content/themes/flatsome-child/woocommerce/checkout/vozicek-glava-12.svg") no-repeat scroll 50%/51% auto #232f3e;
    content: "";
    display: inline-block;
    color: #fff;
    position: relative;
    top: -12px;
    vertical-align: middle;
    width: 85px;
    height: 62px;
        left: -27px;
    float: left;
    margin-bottom: -12px;
    padding: 0;
	display: none;
}

@media only screen and (max-width: 400px) {
  .woocommerce-checkout button#place_order:before {
    display: none !important;
  }
  .woocommerce-checkout #payment .place-order .button {
      padding-bottom: 10px;
  }
}

.woocommerce-checkout .zaupanjevkvaliteto {
    margin: 20px auto 0;
}
.col-xs-3 {
    width: 25%;
}
.paddingicon_none {
    padding-left: 0!important;
    padding-right: 0!important;
}
img.icon_shop {
    max-width: 68px;
	    display: block;
    margin: 0 auto;
}
.checkout-page .paddingicon_none .under_icon_text {
    color: #000;
    font-weight: 400;
    text-align: center !important;
    font-size: .9em;
    padding: 10px;
	line-height: 1.3;
}

@media only screen and (max-width: 400px) {
  .checkout-page .paddingicon_none .under_icon_text {

    line-height: 1.3 !important;
    font-weight: bold !important;
	padding: 10px 4px !important;
  }

}

@media only screen and (min-width: 850px) {
  .my-col2 {

      width: 100% !important;
	  padding-left: 60px !important;
  }
  
  
}


@media only screen and (max-width: 850px) {
	#order_review {
	  margin-top: 30px !important;
  }
  
  #billing_city_field {
	
	width: 100% !important;
    float: left !important;
margin-right: 0% !important;
display: inline-block !important;
clear: none !important;
}


#billing_postcode_field {
	
	width: 100%  !important;
    float: right !important;
	display: inline-block !important;

}

@media only screen and (max-width: 767px) {
	body #wfob_wrap .wfob_pro_img_wrap {
		width: 60px !important;
		float: left  !important;
		margin-right: 13px !important;
	}
}

@media only screen and (max-width: 450px) {
	.cart-box.no-border {
		margin-left: 0px !important;
		margin-right: 0px !important;
}
}

.checkout-rating3 {
    background-color: #ffa4004a;
    border: 1px solid #ffa401;
    padding: 13px 10px;
    text-align: center;
    margin-bottom: 40px;
}

</style>



<div class="koda">
<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

	<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></p>

	<p class="kodap form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<button type="submit" class="kodapb button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
	</p>

	<div class="clear"></div>
</form>
</div>
<style>
.koda .woocommerce-info { font-size: 14px !important; color: black !important; background: white!important; margin: 0 0 5px 0; padding: 0; }
.koda .message-container { color: black!important; }
.koda .message-container  a{ color: black!important; }
@media screen and (min-width: 992px) {
  .kodap {
	margin-right: 1% !important;
  }
  .kodapb {
        min-height: 52px;
		margin-top: 1px;
  }
}
@media screen and (max-width: 992px) {
  .kodapb {
		font-size: 14px !important;
  }
}

</style>




<form name="checkout" method="post" class="checkout woocommerce-checkout <?php echo esc_attr( $wrapper_classes ); ?>" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>
	
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>


		<div style="width: 100%;" class="col2-set" id="customer_details">
	
			<div class="col-2">
	
					<div class="checkout-rating tooltip tooltipstered">
					<img src="/hr/wp-content/themes/flatsome-child/woocommerce/checkout/smiley-1-svg.png">
					<strong>Odličan izbor!</strong> Ostali kupci su ocijenili vaše izabrane proizvode ocjenom od <span class="underline"><span class="review-number">9.8</span> do 10!</span>
					</div>

			

				<div id="customer_details">
					<div class="clear">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="clear">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

			


		</div><!-- large-7 -->

			<div  style="" class="col-2 my-col2">

			<!-- custom obvestilo na checkout -->
			<?php 	
			global $woocommerce;
			foreach($woocommerce->cart->get_cart() as $key => $val ) {
				$_product = $val['data'];
				
				if( get_field("pc_spremeni_datum_dostave", $_product->id) == true ): ?>
					
					
				
					<?php $date = get_field("pc_cas_dostave_od", $_product->id );  $date  = str_replace("/",".",$date);  ?>
					<?php $date2 = get_field("pc_cas_dostave_do", $_product->id ); $date2 = str_replace("/",".",$date2); ?>	
					
					<div class="checkout-rating4 tooltip2 tooltipstered2">
					<span style="display: block; margin-bottom: 5px;"><?php echo get_field("pc_tekst_opomba_checkout", $_product->id ); ?></span><span class="underline2"><span class="review-number2"><?php echo $date; ?></span></span> - <span class="underline2"><span class="review-number2"><?php echo $date2; ?></span></span></span>.
					</div>	
					
						<style>
						.checkout-rating4 {
							background-color: #9bd7ff59;
							border: 1px solid #6fa9d1;
							padding: 7px 8px;
							text-align: center;
							margin-bottom: 8px;
							font-size: 14px;  
							line-height: 1.2;
						}
						.underline2 {
							font-weight: bold;
							text-decoration: underline;
						}
						@media only screen and (max-width: 850px) {
						  .checkout-rating4  {
							margin-top: 40px !important;
							margin-bottom: -20px !important;
						  }
						}
						@media only screen and (min-width: 850px) {
							#order_review {
								margin-top: 0px !important;
							}
						}
						
						</style>
					
					
				<?php endif; } ?>
		
		
			<!-- custom obvestilo na checkout -->
		
							
						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

						<div style="margin-top: -20px;" id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

					
					
				
					
			</div><!-- row -->
			
				</div><!-- row -->
	
		<?php endif; ?>
	
</form>


 <script type="text/javascript">
    jQuery( document ).ready(function( $ ) {
		var my_url = jQuery(location).attr('href');
		if ( my_url.indexOf("removed") >= 0) { }
		else {
		setTimeout(function(){
			if (  !jQuery('.wfob_bump_product').is(':checked')  ) {
            jQuery('.wfob_bump_product').click();
			//jQuery('body').trigger('update_checkout');
			}
			},
    	800);
		
		}
    });
</script>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
