<?php
/**
 * Single product short description
 *
 * @author  Automattic
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="product-short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>



<style>

.checkout-rating4 {
    background-color: #9bd7ff59;
    border: 1px solid #6fa9d1;
	padding: 7px 8px;
	text-align: center;
	margin-bottom: 15px;
      font-size: 15px;
}

.underline2 {
	font-weight: bold;
	text-decoration: underline;
}

@media only screen and (max-width: 850px) {
  .garancija-teskt {
    text-align: center;
  }
}

</style>


<?php $date = get_shipping_date();  ?>
<?php $date2 = get_shipping_date2();  ?>
					
<div class="checkout-rating4 tooltip2 tooltipstered2">
					<!--<strong>Ilość dostępnych przedmiotów szybko się kończy ze względu na duże zapotrzebowanie! </strong>-->
					Naručite odmah i primit ćete sljedeći proizvod: <br/><span class="underline2"><span class="review-number2"><?php echo $date['day_text'] . " " .  $date['day_date']; ?></span></span> - <span class="underline2"><span class="review-number2"><?php echo $date2['day_text'] . " " .  $date2['day_date']; ?></span></span></span>.
</div>
<p style="color: black; font-size: 14.5px; line-height: 1.3;" class="garancija-teskt" >
<b>Jamstvo od 14 dana.</b> 
<span style="font-size: 13px;"><br/>Ako niste zadovoljni proizvodom, Vratit ćemo vam novac. <span>
<a style="font-size: 13px; color: black;text-decoration: underline;" href="#faq"><b>Više:</b></a></p>			