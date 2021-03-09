<?php
/*
Template name: Page - Jackpot Products
*/


if(flatsome_option('pages_layout') != 'default') {
	
	// Get default template from theme options.
	echo get_template_part('page', flatsome_option('pages_layout'));
	return;

} else {

get_header();
do_action( 'flatsome_before_page' ); ?>

<style>

	@media only screen and (max-width: 767px) {
	  .jackpot-banner h2 {
		 font-size: 17px !important;
	  }
	  
	  .jackpot-banner p {
		    font-size: 15px;
		line-height: 1.2;
	  }
	  
	.jackpot-banner {
				padding-left: 5px !important; 
				padding-right: 5px !important;
		}
	}

.header-wrapper {
	display: none;
}


#faq  { display: none; }

.section-footer1  { display: none; }

#footer { display: none; }

.b-product {
	    width: calc(100% - 20px) !important;
}

#content {
	padding-top: 10px;
}

.jackpot-time{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    -ms-align-items: center;
    align-items: center;
    justify-content: center;
    padding-bottom:10px;
}
.jackpot-time >span{
    display: inline-block;
    vertical-align: middle;
    margin: 0 10px 0 0;
    font-weight: 700;
}
.jackpot-time #countdown{
    overflow: hidden;
}
.jackpot-time #countdown{
    width: 60px;
    padding: 6px 0;
    max-width: 100%;
    background: #ffa401;
    border: 1px solid #ffa401;
    border-radius: 5px;
    font-size: 16px;
    line-height: 1.1;
}
.jackpot-time #countdown .countdown-row{
    overflow: hidden;
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    justify-content: center;
    color: #fff;
    font-size:1em;
}
#countdown .countdown-row .countdown-section{float: none !important;width: auto !important;font-size:1em !important;}
#countdown .countdown-row .countdown-section:first-child{padding: 0 6px 0 0;position: relative;}
#countdown .countdown-row .countdown-section:first-child:after{
    content:":";
    position: absolute;
    top: 0;
    right:2px;
    font-weight: 700;
    font-size: 1em;
}
#countdown .countdown-row .countdown-section .countdown-amount{
    font-weight: 700;
    margin: 0;
    font-size: 1em;
}
.jackpot-time #countdown .countdown-period{display: none;}
.jackpot-banner{
    text-align: center;
    padding:1.7em 0;
    margin: 0 0 2em;
}
.jackpot-banner p:last-child{margin: 0;}
.page-checkout-simple .full-message{
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    display: none;
    z-index: 99999;
}
.page-checkout-simple .full-message .holder{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    -ms-align-items: center;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.page-checkout-simple .full-message .text{
    text-align: center;
    font-weight: 700;
}
.page-checkout-simple .full-message .text .price{
    font-size: 3.5em;
    display: block;
    margin: 0 0 0.3em;
    text-shadow:-6px 5px 0 rgba(0,0,0,.2)
}
.page-checkout-simple .full-message .text span:last-child{
    text-transform: uppercase;
    display: block;
}
.jackpot-products{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    -webkit-flex-wrap: wrap;
    -moz-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    -o-flex-wrap: wrap;
    flex-wrap: wrap;
}
.jackpot-products .block{
    width: 25%;
}
.jackpot-products .b-product{
    width: 208px;
    margin: 0 auto;
    padding: 0 0 35px;
}
.b-product__image{
    margin: 0 0 10px;
}
.b-product .row-prise{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    -webkit-flex-wrap: wrap;
    -moz-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    -o-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-align-items: center;
    align-items: center;
    margin: 0 0 10px;
}
.product-main .row-prise{
    overflow: hidden;
    margin: 0 0 10px;
}
.product-main .row-prise .bottom{
    background: #fee4a3;
    color: #000;
    text-align: center;
    padding: 10px 5px;
    display: block;
    width: 100%;
    border-radius:3px;
    font-size: 1em;
    letter-spacing: 0;
    line-height: 1.2;
    font-weight: 600;
    max-width:200px;
}
.product-main .row-prise .old-price,
.b-product .old-price{
    color:#72706e;
    text-decoration:line-through;
    margin: 0 10px 0 0;
    display: inline-block;
    vertical-align: middle;
    font-weight:500;
}
.product-main .row-prise .old-price{font-size: 1.3em;}
.product-main .row-prise .new-price,
.b-product .new-price{
    color:#9f1c1c;
    display: inline-block;
    vertical-align: middle;
    font-size: 1.5em;
    font-weight: 600;
}
.product-main .row-prise .new-price{color:#ed1c24;font-size: 2em;font-weight: 400;}
.b-product .btn-yellow{
    display: block;
    background: #fba718;
    border: 1px solid #fba718;
    color: #000;
    padding: 10px;
    margin: 0 0 10px;
    font-weight: 700;
    text-align: center;
    cursor: pointer;
    text-transform: none;
    font-size: 1em;
    letter-spacing: 0;
    min-height:inherit;
    line-height: 1.2;
    width: 100%;
    border-radius:3px;
    transition: all 0.3s linear;
}
.b-product .btn-yellow:hover{
    background:transparent;
    color:#fba718;
}
.b-product .btn-green{
    display: block;
    background: #6ab14c;
    border: 1px solid #6ab14c;
    color: #000;
    padding: 10px;
    margin: 0;
    font-weight: 700;
    text-align: center;
    cursor: pointer;
    text-transform: none;
    font-size: 1em;
    letter-spacing: 0;
    min-height:inherit;
    line-height: 1.2;
    width:calc(100% - 45px);
    border-radius:3px;
    transition: all 0.3s linear;
}
.b-product .btn-green:hover{
    background:#6ab14c;
    color: black;
}
.b-product .btn-close{
    background: #fb0007;
    border: 1px solid #fb0007;
    text-indent: -9999px;
    display: block;
    width:40px;
    padding: 0;
    margin: 0;
    text-align: left;
    line-height: 1.2;
    border-radius:3px;
    transition: all 0.3s linear;
    position: relative;
}
.b-product .btn-close:before,
.b-product .btn-close:after{
    content:"";
    position: absolute;
    top: 50%;
    left: 50%;
    background: #fff;
    width:11px;
    height: 2px;
    margin: -2px 0 0 -5px;
    transform: rotate(45deg);
    transition: all 0.3s linear;
}
.b-product .btn-close:after{
    transform: rotate(-45deg);
}
.b-product .btn-close:hover{
    background:transparent;
}
.b-product .btn-close:hover:before,
.b-product .btn-close:hover:after{background: #fb0007;}
.b-product .btn-gray{
    display: block;
    padding: 10px;
    margin: 0;
    text-align: center;
    background: #f0f0f0;
    border: 1px solid #f0f0f0;
    color: #585858;
    width: 100%;
    border-radius:3px;
    font-size: 1em;
    letter-spacing: 0;
    min-height:inherit;
    line-height: 1.2;
    transition: all 0.3s linear;
}
.b-product .btn-gray:hover{
    background: transparent;
}
.b-product .row-buttons{
    display: -webkit-flex;
    display: -moz-flex;
    display: -ms-flex;
    display: -o-flex;
    display: flex;
    -webkit-flex-wrap: wrap;
    -moz-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    -o-flex-wrap: wrap;
    flex-wrap: wrap;
    justify-content: space-between;
}
.woocommerce-mini-cart__buttons .btn-yellow{
    background: #fdb51d;
    border: 1px solid #fdb51d;
    color: #000;
    text-align: center;
    padding:11px 10px;
    display: block;
    margin: 0 auto;
    font-weight: 700;
    transition: all 0.3s linear;
}
.woocommerce-mini-cart__buttons .btn-yellow:hover{
    background:#fff;
    color:#fdb51d;
}
.cart-btn-yellow{
    background: #fdb51d;
    border: 1px solid #fdb51d;
    color: #000;
    text-align: center;
    width: 420px;
    padding:11px 10px 11px 70px;
    position: relative;
    display: block;
    margin: 0 auto 2em;
    font-weight: 700;
    transition: all 0.3s linear;
}
.cart-btn-yellow:hover{
    background:#fff;
    color:#fdb51d;
}
.cart-btn-yellow:before{
    content:"";
    position: absolute;
    top:-1px;
    bottom:-1px;
    left:-1px;
    width: 60px;
    background:#000 url(https://mistermega.si/wp-content/themes/flatsome-child/images/shopping-cart.svg) no-repeat 50% 50%;
    -webkit-background-size: 30px auto;
    background-size: 30px auto;
}
@media screen and (max-width: 980px) {
    .wc-sticky-product-bar .button {
        font-size: 12px;
    }
    .wc-sticky-product-bar .action-button {
        padding: 0 20px;
        margin: 0 !important;
    }
.jackpot-products .block{width: 33.33%;}
}
@media screen and (max-width: 767px) {
.jackpot-products .block{width:50%;}
.cart-btn-yellow{
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    margin: 0;
    width: 100%;
    z-index: 9;
}
.jackpot-time.sticky{
    position: sticky;
    top:0px !important;
    padding:5px 0;
    left: 0;
    right: 0;
    margin: 0 -15px;
    background: #fffaf2;
    border-top: 1px solid #eec45e;
    border-bottom: 1px solid #eec45e;
    z-index: 99;
}
.jackpot-time >span{font-size: 14px;}
.jackpot-time #countdown{font-size: 14px;}


.pp-title {
	height: 50px !important;
}

}

.jackpot-banner {
	
	background: red;
	    padding: 0.7em 0;
}


.widget_shopping_cart .quantity {
	display: none !important;
}

#chat-widget-container {
	display: none !important;
}


</style>

<div id="content" class="content-area page-wrapper" role="main">
	<div class="row row-main">
		<div class="large-12 col">
			<div class="col-inner">
				
				<?php if(get_theme_mod('default_title', 0)){ ?>
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header><!-- .entry-header -->
				<?php } ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php do_action( 'flatsome_before_page_content' ); ?>
					
						<?php
						global $woocommerce;
						//echo '<h4>Jackpot Order ID: '.$woocommerce->session->jackpot_order.'</h4>';						
						$jackpot_order_id =  $woocommerce->session->jackpot_order;
						
				
						
						if(!empty($jackpot_order_id)){						
							
						  $jackpot_order = wc_get_order($jackpot_order_id); 


									$order_key = $jackpot_order->get_order_key();
						$returnURL = site_url().'/zakljucek-nakupa/order-received/'.$jackpot_order_id.'/?key='.$order_key.'&skip=1';
							
					    //echo $returnURL;
						
						$new_url = wc_get_checkout_url();
						  
						  if($jackpot_order){	
						  
						    $item_sku = array();
						   
						   foreach ($jackpot_order->get_items() as $item) {
								$product = wc_get_product($item->get_product_id());
								$item_sku[] = $product->get_sku();
							  }
						  
							
							if($jackpot_order->get_payment_method() == 'cod' &&  hivista_order_time_diff($jackpot_order) < 10 && $jackpot_order->get_status() == 'primary-order'){						
						?>	
					
					
					<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/css/jquery.countdown.css">				
					<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.plugin.min.js"></script>
					<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.countdown.min.js"></script>
					<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.countdown-sl.js"></script>					
					<script>
						jQuery(document).ready(function(){
								
								var shortly = new Date(); 
								shortly.setSeconds(shortly.getSeconds() + <?php echo JACKPOT_TIME*60;?> - <?php echo hivista_order_time_diff($jackpot_order, 's');?>); 
								console.log('shortly '+shortly);
								jQuery('#countdown').countdown({until: shortly, format: 'MS', onExpiry: countdownExpiry, padZeroes: true}); 
								
								jQuery(window).scroll(function() {
								  var scroll = jQuery(window).scrollTop();
								  if (scroll >= 100){
									jQuery(".jackpot-time").addClass("sticky");
								  }else{
									jQuery(".jackpot-time").removeClass("sticky");
								  }
								});								
														});
						function countdownExpiry(){
							window.location.href = '/hr/?clear-cart';
						}						
					</script>		
					
					<div class="jackpot-time"><span>Ponuda ističe</span><div id="countdown"></div></div>
					
					<div class="jackpot-banner" >
						<h2 style="color: white;">Mega ponuda: Svi proizvodi po  60 kn!</h2>
						<p style="font-weight: bold; color: white";>Svi dodani proizvodi iz ove ponude naplatit će se po cijeni od 60 kn / komad. Oni će biti isporučeni u paketu zajedno s narudžbom koju ste već napravili, bez dodatnih troškova poštarine. </p>
						
					</div>
														
						<?php 
						$args = array('post_type' => 'product', 'posts_per_page' => -1, 'product_cat' => 'jackpot');
						$products = new WP_Query( $args );
						if ( $products->have_posts() ) { ?>
						 <div class="container">
							<div class="jackpot-products">							
							<?php while ( $products->have_posts() ) {
										$products->the_post();
										$product = wc_get_product(get_the_ID());
										
									    $sku = $product->get_sku();
										
										$price = $product->get_price();
										$image_id = $product->get_image_id();

										$in_cart = hivista_is_product_in_cart(get_the_ID());										
										?>
										
											<?php if( !in_array( $sku, $item_sku)  ): ?>		
										
								<div class="block">
									<div class="b-product product_<?php the_ID();?>">
										<div class="b-product__image">
											<img src="<?php echo get_thumb($image_id, 408, 408, true);?>" alt="">
										</div>
										
										<p class="pp-title" style="    margin-bottom: 5px;    height: 40px;    line-height: 1;      font-weight: bold;  color: black;"><?php echo get_the_title(); ?></p>
										
										<p class="row-prise"><span class="old-price"><?php echo $price.get_woocommerce_currency_symbol(); ?></span><span class="new-price"><?php echo JACKPOT_PRICE.get_woocommerce_currency_symbol();?></span></p>
										<button class="btn-yellow add_jackpot_product" data-product_id="<?php the_ID();?>" data-product_type="<?php echo $product->get_type();?>" data-product_url="<?php the_permalink();?>"  <?php if($in_cart) echo 'style="display:none;"';?>>Dodaj u narudžbu</button>										
										<!--<a href="<?php the_permalink();?>" class="btn-gray" <?php if($in_cart) echo 'style="display:none;"';?>>Več o izdelku</a>-->
										
										<div class="row-buttons" <?php if(!$in_cart) echo 'style="display:none;"';?>>
											<span  class="btn-green">Dodano</span>
											<button class="btn-close remove_jackpot_product" data-product_id="<?php the_ID();?>">close</button>
										</div>
									</div>
								</div>
								
										<?php endif; ?>
								
							<?php }?>							
								
							</div>	
							
							<a  id="finish" style="border: 1px solid red; background: red; color: white; text-transform: uppercase;" href="<?php echo $returnURL; ?>" class="cart-btn-yellow" >Dovršite kupnju</a>
							
							<a style="display: block; color: black;text-align: center;text-decoration: underline;" href="/hr/?clear-cart">Ne želim tu ponudu</a>
							<br/>
							
							<!--
							
							/zakljucek-nakupa/order-received/848569/?key=wc_order_GGw6VLGOXD2Oj&skip
							
							-->
			
							
						 </div>
					<script>
					jQuery(document).ready(function(){
						
						jQuery(".add_jackpot_product").click(function(){
							
							var product_type = jQuery(this).data("product_type");
							var product_url = jQuery(this).data("product_url");
							
							var cartCounter = 0;
							
							if(product_type == 'simple'){
								var product_data = {
											action: "add_jackpot_product",
											product_id: jQuery(this).data("product_id"),
											nonce: "<?php echo wp_create_nonce('add_jackpot_product');?>"
										};	

								jQuery.post("<?php echo admin_url('admin-ajax.php');?>", product_data, function(request) {						
									//alert(request.success);														
									if(request.success == "1"){
										jQuery(".product_"+request.product_id).find(".row-buttons").show();
										jQuery(".product_"+request.product_id).find(".add_jackpot_product").hide();
										jQuery(".product_"+request.product_id).find(".btn-gray").hide();
										jQuery(".widget_shopping_cart").html(request.mini_cart);
										jQuery(".woocommerce-Price-amount").html(request.cart_total);
										jQuery(".cart-icon strong").text(request.cart_count);
										
										cartCounter = request.cart_count;
										console.log(cartCounter);
										if( cartCounter > 0 ) {
											 jQuery("#finish").attr("href", "<?php echo $new_url; ?>");
										} 
										
									}else if(request.error == "1"){								
										alert(request.error_message);								
									}							
									
								}, "json");						
							}else{
								window.location.href = product_url;
							}	
							
							
							
						
							
						});

						
						jQuery(".remove_jackpot_product").click(function(){
							
							var product_data = {
										action: "remove_jackpot_product",
										product_id: jQuery(this).data("product_id"),
										nonce: "<?php echo wp_create_nonce('remove_jackpot_product');?>"
									};	

							jQuery.post("<?php echo admin_url('admin-ajax.php');?>", product_data, function(request) {						
								console.log('AJAX '+request);										
		
								if(request.success == "1"){
									jQuery(".product_"+request.product_id).find(".row-buttons").hide();
									jQuery(".product_"+request.product_id).find(".add_jackpot_product").show();
									jQuery(".product_"+request.product_id).find(".btn-gray").show();	
									jQuery(".widget_shopping_cart").html(request.mini_cart);
									jQuery(".woocommerce-Price-amount").html(request.cart_total);
									jQuery(".cart-icon strong").text(request.cart_count);		
										
									
									cartCounter = request.cart_count;
									console.log(cartCounter);
									
									if( cartCounter > 0 ) {
										 jQuery("#finish").attr("href", "<?php echo $new_url; ?>");
									} else {
										 jQuery("#finish").attr("href", "<?php echo $returnURL; ?>");
									}
									
									
								}else if(request.error == "1"){								
									alert(request.error_message);								
								}							
								
							}, "json");			


							
							
						
						});
						
					});	
					</script>	
					<?php }	 ?>
						
						
					<?php     	}else{
									echo '<h2>There is not Jackpot jet</h2>';
								}
							}
						}else{
	
							echo '<h2>There is not Jackpot jet</h2>';

						}	?>						
						

					<?php //do_action( 'flatsome_after_page_content' ); ?>
				<?php endwhile; // end of the loop. ?>
			</div><!-- .col-inner -->
		</div><!-- .large-12 -->
	</div><!-- .row -->
</div>

<?php
do_action( 'flatsome_after_page' );
get_footer();

}

?>