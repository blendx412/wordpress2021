<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;


global $woocommerce;

$total_price =  $woocommerce->cart->total;
?>

<div style="border: none; background: white; padding: 10px 0px;"class="shop_table woocommerce-checkout-review-order-table cart-box no-border">

	<style>
	
	#order_review .cart-box p {
   		font-size: 16px !important;

		} 
	
	#order_review  .cart-box.no-border {
 
    font-size: 16px !important;
}
	
	#order_review .bordr {
		border-bottom: 1px solid black;
	}
	
	#order_review h4 {
		font-size: 16px !important;
		
		
	}
	#order_review .cart_item {
		
		font-size: 16px !important;
	}
	
	#order_review span.amount {
		
		font-weight: normal !important;
	}
	
		#order_review .last-price .amount {
		font-weight: bold !important;
	}
	
	</style>
	


 

    <div class="cart-totals ">
        <?php
            do_action( 'woocommerce_review_order_before_cart_contents' );

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    ?>
                    <div class="111 <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                       <div style="width: 65% !important;" class="column col-3">        
          <div class="pp_name"><?php echo $_product->get_name(); ?>
         
          
		  <?php if( $_product->get_sku() == "IZD-PRES" ): ?>
		  
		  
	<div class="remove-gift" style="display: inline !important;" >
   <div class="remove-gift" style="display: inline !important;">
     <a id="special-remove-pres" class="remove" >×</a>  
   </div>
   </div>

		  
		  
		  
		  <?php else: ?>
		    <div class="remove-pp" style="display: inline !important;" >
			<?php
            echo apply_filters(
              'woocommerce_cart_item_remove_link',
              sprintf(
                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                esc_html__( 'Remove this item', 'woocommerce' ),
                esc_attr( $_product->get_id() ),
                esc_attr( $_product->get_sku() )
              ),
              $cart_item_key
            );
          ?>
		   </div>
          <?php endif; ?>
		  

          </div>
          <style>
          .remove-gift a { 
          display: inline !important; 
          color: #ff1923 !important; 
          padding: 0px 5px 0px 5px;
          margin-left: 1px !important;
          font-size: 14px !important;
          }
		  
		  .remove-pp a { 
          display: inline !important; 
          color: #b3b3b3 !important; 
          padding: 0px 5px 0px 5px;
          margin-left: 1px !important;
          font-size: 14px !important;
          }
		  
          </style>

      <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
      <?php echo WC()->cart->get_item_data( $cart_item ); ?>
          
</div>
                        <div style="width: 32% !important;"  class="column col-2">
                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                        </div>
                    </div>
                    <?php
                }
            }

            do_action( 'woocommerce_review_order_after_cart_contents' );
        ?>
    </div>

    <div style="margin-top: 10px;" class="2222">
        <h4 style="padding-bottom: 0px;    margin-bottom: 5px;" class="">Pretplaćene usluge</h4>
        
		<div>
            <div class="column col-3">Prevoz</div>
            <div class="column col-2"><p style="margin-bottom: 0; color:#4BB594;font-weight:bold;text-transform:uppercase;">BESPLATNA!</p></div>
        </div>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div>
                <div class="column col-3"><?php echo esc_html( $fee->name ); ?></div>
                <div class="column col-2"><?php wc_cart_totals_fee_html( $fee ); ?></div>
            </div>
        <?php endforeach; ?>

    </div>
	
	<div class="333 bordr2t last-price">
        <div class="column col-3"><h4>Ukupno</h4></div>
        <div class="column col-2"><p><?php echo wc_price($total_price); ?></p></div>
    </div>
    <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

 
</div>

<style>

.woocommerce-checkout-review-order-table .col-3 {
	width: 60% !important;
	display: inline-block !important;
    vertical-align: middle !important;
	text-align: left !important;
}

.woocommerce-checkout-review-order-table .col-2 {
	width: 38% !important;
	display: inline-block !important;
    vertical-align: middle !important;
	text-align: right !important;
}

</style>



   <script>
		jQuery( "#special-remove-pres" ).on( "click", function() {
			jQuery(".wfob_bump_product").trigger("click");
		});
	</script>

  
	<style>
	 .remove-gift a { 
	 display: inline !important; 
	 color: #ff1923 !important; 
	 padding: 0px 5px 0px 5px;
	 margin-left: 1px !important;
	 font-size: 14px !important;
	 cursor: pointer;
	 }
	 </style>



