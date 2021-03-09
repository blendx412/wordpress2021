<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checked = ($gateway->chosen == true) ? 'checked' : '';

?>
<div class="cart-box <?php echo $checked; ?>">

<style>

#payment .col-1 {
	display: inline !important;
	
}

#payment .col-1 {
	display: inline-block !important;
	    width: 85% !important;
	
}

.payment_method_paypal a {
	display: none;
}

.payment_method_paypal img {
	max-height: 40px !important;
	max-height: 40px !important;
}



.payment_methods p {
    font-size: .9em !important;
    margin-bottom: 0 !important;
}


.fl-labels .form-row input:not([type="checkbox"]), .fl-labels .form-row textarea, .fl-labels .form-row select {
    height: 2.5084em !important;
    transition: padding .3s;
}

</style>

	<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">

		<div style="display: inline !important;" class="column col-1">
			<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
		</div>

		<div style="width: 80%; display: inline !important;" class="column col-3">
			<label style="font-size: .9em; font-weight: bold; display: inline;" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
				<?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
			</label>
			<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
				<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
					<?php $gateway->payment_fields(); ?>
				</div>
			<?php endif; ?>
		</div>

	<!--
		<div class="column col-2">
			<?php if ($gateway->title == 'Plačilo po povzetju'): ?>
				<img src="<?php echo get_stylesheet_directory_uri() . '/images/delivery-payment.png' ?>">
			<?php endif; ?>
			<?php if ($gateway->title == 'PLAČILO S KREDITNO KARTICO'): ?>
			
			<?php endif; ?>
			<?php if ($gateway->title == 'PLAČILO PAYPAL'): ?>
				<img src="<?php echo get_stylesheet_directory_uri() . '/images/PAYPAL.png' ?>">
			<?php endif; ?>
		</div>
		-->
	</li>
</div>
