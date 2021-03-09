	<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>




<style>

	.widget_shopping_cart .quantity {
	display: none !important;
}

#chat-widget-container {
	display: none !important;
}


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

	.page-checkout-simple .woocommerce-thankyou-order-received{
		text-align: center;
		font-weight: 700;
		font-size: 1.2em;
		padding: 0.7em 0 0;
	}
	.page-checkout-simple .woocommerce-customer-details{display: none;}
	.page-checkout-simple .order_details.woocommerce-thankyou-order-details{
		background:#f8f8f8 url(<?php echo get_stylesheet_directory_uri();?>/images/gray-triangle.jpg) repeat-x;
		list-style: none;
		margin: 0;
		padding:15px 0;
		position: relative;
		font-size:12px;
		text-transform: uppercase;
	}
	.page-checkout-simple .order_details.woocommerce-thankyou-order-details:after{
		content: "";
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		background:url(<?php echo get_stylesheet_directory_uri();?>/images/gray-triangle.jpg) repeat-x;
		transform: rotate(180deg);
		height: 9px;
	}
	.page-checkout-simple .order_details li{
		border-bottom: 1px dotted #e3e3e3;
		padding:5px 10px;
		margin: 0;
	}
	.page-checkout-simple .order_details li strong{text-transform: none;}
	.page-checkout-simple .order_details+p{font-weight: 700;}
	.page-checkout-simple .woocommerce-order-details .woocommerce-order-details__title{
		font-size: 1em;
		padding:4px 14px;
		background: #ffa401;
		border: 1px solid #ffa401;
		position: relative;
	}
	.page-checkout-simple .woocommerce-order-details .woocommerce-order-details__title:after{
		content:"";
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		border-width:5px 10px;
		border-color: transparent  transparent  transparent #000;
		border-style: solid;
		right:5px;
		transition: all 0.3s linear;
	}
	.page-checkout-simple .woocommerce-order-details .woocommerce-order-details__title.open:after{
		transform:translateY(0%) translateX(-50%) rotate(90deg);
	}
	.page-checkout-simple .woocommerce-order .orange-btn{
		background: #e73d38;
		color: #fff;
		position: relative;
		padding:6px 50px;
		margin-left:auto;
		margin-right:auto;
		width: 530px;
		max-width: 100%;
	}
	.page-checkout-simple .woocommerce-order .orange-btn:before,
	.page-checkout-simple .woocommerce-order .orange-btn:after{
		content:"";
		position: absolute;
		top: 50%;
		background: url(<?php echo get_stylesheet_directory_uri();?>/images/arrow-up.png) no-repeat;
		width: 12px;
		height: 23px;
		transform: translateY(-50%);
	}
	.page-checkout-simple .woocommerce-order .orange-btn:before{left:28px;}
	.page-checkout-simple .woocommerce-order .orange-btn:after{right:28px;}
	.page-checkout-simple .s-text{
		margin: 0 0 13px;
		font-size: 1.25em;
	}
	#countdown{
		width: 300px;
		padding: 6px 0;
		max-width: 100%;
		background: #fdb51d;
		border: 1px solid #fdb51d;
		border-radius: 5px;
		margin: 0 auto 5em;
		font-size: 16px;
		line-height: 1.1;
	}
	#countdown .countdown-row{
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
	#countdown .countdown-row .countdown-section:first-child{padding: 0 20px 0 0;position: relative;}
	#countdown .countdown-row .countdown-section:first-child:after{
		content:":";
		position: absolute;
		top: 0;
		right:5px;
		font-weight: 700;
		font-size:3em;
	}
	#countdown .countdown-row .countdown-section .countdown-amount{
		font-weight: 700;
		font-size:3em;
		margin: 0;
	}
	
	html {
		background: white;
	}
	
	#canvas {
		cursor: pointer;
		margin-top: 10px;
	}
	
	@media screen and (max-width: 767px) { 
		.thankyou-tekst-my {
			font-size: 14px !important;
			    margin-bottom: 0 !important;
		}
		
		
		.make-smaller li {
			font-size: 80% !important;
		}
		
		.make-smaller p {
			font-size: 80% !important;
			margin-bottom: 0 !important;
		}
		
		
		.button-up {
			
				font-size: 12px !important;
				line-height: 1.4 !important;
				padding: 6px 30px !important;
				margin-bottom: 0 !important;
		}
		
		.button-up:before { 
				left: 5px !important;
				background: url(/hr/wp-content/themes/flatsome-child/images/arrow-down.png) no-repeat  !important;
		}
		
		.button-up:after { 
				right: 5px !important;
				background: url(/hr/wp-content/themes/flatsome-child/images/arrow-down.png) no-repeat  !important;
		}
	}

	@media screen and (min-width: 768px) { 
				
		.button-up {
			
				
				line-height: 1.2 !important;
			
				margin-bottom: 0 !important;
		}
		
		.button-up:before { 
			
				background: url(/hr/wp-content/themes/flatsome-child/images/arrow-down.png) no-repeat  !important;
		}
		
		.button-up:after { 
				
				background: url(/hr/wp-content/themes/flatsome-child/images/arrow-down.png) no-repeat  !important;
		}
	}
	
	.navodila{
		font-size: 13px;
		/* padding-top: 0px; */
		padding-bottom: 5px;
		padding: 0 20px;
	}
	
	.thankyou-tekst-my{
		margin-bottom: 5px;
		font-size: 13px;
	}

	.navodila-heading{
		margin-bottom: 5px;
		text-align: left !important;
	}

	ul.woocommerce-order-overview {
		padding: 0px !important;
	}

	ul.woocommerce-order-overview:after {
		display: none;
	}

	ol {
		margin-bottom: 0px;
	}

	ol li a{
		text-decoration: underline;
	}
	
	.page-checkout-simple{
    padding-top: 0;
}
.make-smaller .shipping{
	text-align: center;
	font-weight: 700;
	padding: 4px 14px;
    background: #EEFEF0;
    border: 1px solis #EEFEF0;
	font-size: 13px;
}
.date-underline{
	text-decoration: underline;
}
.bold-list{
	font-weight: 600;
}
	
</style>


<div class="woocommerce-order">
	
	<?php
	//global $wp_query;
	//echo '<pre>';
	//print_r($wp_query);
	//echo '</pre>';
	?>
	

	<?php if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="thankyou-tekst-my woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			
			<!--
			<p style="text-align: center; margin-top: -20px;    margin-bottom: 15px;"><img style="max-width: 80px; height: auto;" src="/wp-content/uploads/2020/01/Logo.png"></p>
			-->
			
			<div class="make-smaller">

			<div class="navodila">
			<?php $date = get_shipping_date();  ?>
			<?php $date2 = get_shipping_date2();  ?>
			<p class="shipping">Predviđeni datum isporuke: <span class="date-underline"><span><?php echo $date['day_text'] . " " .  $date['day_date']; ?></span> - <span><?php echo $date2['day_text'] . " " .  $date2['day_date']; ?></span></span>.</p>

				<p class="navodila-heading">Nakon primitka paketa primit ćete e-mail na e-mail adresu koju ste unijeli prilikom naručivanja, gdje ćete naći:</p>
					<ol>
						<li class="bold-list">Račun</li>
						<li>Link za <a href="https://manuals.d4web.eu/hr/?brand=mrmaks" target="_blank" class="bold-list">upute</a> (kliknite na LINK gdje piše "Uputstva za korištenje" i iskoristite kod proizvoda kako biste našli upute). Ako ne možete naći upute, kontaktirajte nas na <span  class="bold-list">info@mrmaks.eu</span>.</li>
						<li>Upute za korištenje se mogu skinuti odmah klikom na link <a href="https://manuals.d4web.eu/hr/?brand=mrmaks" target="_blank" class="bold-list">"ovdje - upute”.</a></li>
						

					</ol>
			</div>
			
			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<li class="woocommerce-order-overview__total total">
					<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>
			
			</div>

		<?php endif; ?>


		<div class="<?php if ( $order->has_status( 'failed' ) ) : ?><?php else: ?> make-smaller <?php endif; ?>">
		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
		</div>
		

		<?php 		

		$skip = false;
		
		$skip_variable = $_GET['skip'];
		
		if( $skip_variable == "1" ) {
			$skip = true;
		}



		
		if( $skip == false && $order->get_payment_method() == 'cod' &&  hivista_order_time_diff($order) < 10 && $order->get_status() == 'primary-order'){?>
		
		<?php
		global $woocommerce;
		//echo '<pre>';
		//print_r($woocommerce->session);
		//echo '</pre>';
		
		//var_dump("kolo");
		
		$woocommerce->session->jackpot_order = $order->get_id();
		?>
		
			<div style="text-align:center;">
				<a href="javascript:void(0);" onClick="calculatePrize();" class="button orange-btn button-up"  id="startWill">Zavrtite kolo sreće!</a>
				<p><canvas id="canvas" class="tutCanvas" width="350" height="350">Canvas not supported</canvas></p>
				<a href="javascript:void(0);" onClick="calculatePrize();" class="button orange-btn" id="startWill">Zavrtite kolo sreće!</a>
				<p class="s-text"><strong>Zavrtite kolo sreće i osvojite posebnu nagradu!</strong></p>
				<p>Pažnja: Samo jedna vrtnja po osobi!</p>
				<div id="countdown"></div>
			</div>
		<div class="full-message" style="background: url(<?php echo get_stylesheet_directory_uri();?>/images/ozadje.png);">
			<div class="holder">
				<div class="text">
					<h2 style="color: white;">mega JACKPOT otključan!</h2>
					<p style="color: white;"><strong>Svi su proizvodi dostupni po ekskluzivnoj cijeni: </strong></p>
					<strong  style="color: white;" class="price">60 kn</strong>
					<span style="color: white;">po proizvodu!</span>
				</div>
			</div>
			
		</div>
			
		<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri();?>/js/Winwheel.js'></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/css/jquery.countdown.css">
				
		<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.plugin.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.countdown.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri();?>/js/jquery.countdown-sl.js"></script>
			
		<script>
		jQuery(document).ready(function(){
			console.log('load');
			
			var shortly = new Date(); 
			shortly.setSeconds(shortly.getSeconds() + <?php echo JACKPOT_TIME*60;?>); 
			//console.log(shortly.getSeconds());
			//jQuery('#countdown').countdown({until: shortly, compact: true, format: 'MS', description: '', onExpiry: countdownExpiry}); 
			jQuery('#countdown').countdown({until: shortly, format: 'MS', onExpiry: countdownExpiry, padZeroes: true}); 
			
			jQuery("#canvas").click(function(){
				calculatePrize();				
			});	
			
			jQuery(".full-message").click(function(){					
				window.location.href = '<?php echo get_permalink(JACKPOT_PAGE_ID);?>';
			});	
		});	
		
			function countdownExpiry(){
				jQuery("#startWill").prop("disabled", true);	
				jQuery("#startWill").hide();					
			}	
		
				let theWheel = new Winwheel({
					'numSegments'    : 8,
					'outerRadius'    : 175,
					'drawMode'       : 'image',
					'pointerAngle'   : 90,
					'segments'       :
					[
					   {'fillStyle' : '#eae56f', 'text' : '20% popusta'},
					   {'fillStyle' : '#89f26e', 'text' : '30% popusta'},
					   {'fillStyle' : '#7de6ef', 'text' : 'Jackpot', 'textFontSize' : 26},
					   {'fillStyle' : '#e7706f', 'text' : '1% popusta'},
					   {'fillStyle' : '#eae56f', 'text' : 'Besplatni proizvod'},
					   {'fillStyle' : '#89f26e', 'text' : '10% popusta'},
					   {'fillStyle' : '#7de6ef', 'text' : '5% popusta'},
					   {'fillStyle' : '#e7706f', 'text' : 'Ništa'}
					],
					'animation' :
					{
						'type'          : 'spinToStop',
						'duration'      : 4,
						'spins'         : 8,
						'callbackAfter' : 'drawTriangle()',
						'callbackFinished' : alertPrize
					}
				});
			 
				// Function with formula to work out stopAngle before spinning animation.
				// Called from Click of the Spin button.
				function calculatePrize()
				{
					// This formula always makes the wheel stop somewhere inside prize 3 at least
					// 1 degree away from the start and end edges of the segment.
					//let stopAt = (91 + Math.floor((Math.random() * 43)))
					
					let stopAt = 91
			 
					// Important thing is to set the stopAngle of the animation before stating the spin.
					theWheel.animation.stopAngle = stopAt;
			 
					// May as well start the spin from here.
					theWheel.startAnimation();
				}
				 
				// Create new image object in memory.
				let loadedImg = new Image();
	 
				// Create callback to execute once the image has finished loading.
				loadedImg.onload = function()
				{
					theWheel.wheelImage = loadedImg;    // Make wheelImage equal the loaded image object.
					theWheel.draw();                    // Also call draw function to render the wheel.
				}
	 
				// Set the image source, once complete this will trigger the onLoad callback (above).
				loadedImg.src = "<?php echo get_stylesheet_directory_uri();?>/images/will.png?d";			 
			 
				// Usual pointer drawing code.
				drawTriangle();
			 
				function drawTriangle()
				{
					// Get the canvas context the wheel uses.
					let ctx = theWheel.ctx;
			 
					ctx.strokeStyle = '#cc0000';     // Set line colour.
					ctx.fillStyle   = '#cc0000';     // Set fill colour.
					ctx.lineWidth   = 0;
					ctx.beginPath();              // Begin path.
					ctx.moveTo(327, 175);           // Move to initial position.
					ctx.lineTo(347, 163);           // Draw lines to make the shape.
					ctx.lineTo(347, 185);
					ctx.lineTo(327, 175);
					ctx.stroke();                 // Complete the path by stroking (draw lines).
					ctx.fill();                   // Then fill.
				}
				
				function alertPrize(indicatedSegment)
				{
					// Do basic alert of the segment text.
					jQuery("#startWill").hide();	
					jQuery(".full-message").show();
					//alert("You have won " + indicatedSegment.text + "\n Redirection.... ");
					setTimeout(function(){ window.location.href = '<?php echo get_permalink(JACKPOT_PAGE_ID);?>'; }, 2500);
				}
			
			</script>
		<?php }else{ /* ?>
			No  Wheel
			<br>order ID: <?php echo $order->get_id();?>
			<br>payment_method <?php echo $order->get_payment_method();?>
			<br>time_diff <?php echo hivista_order_time_diff($order);?>
			<br>order status <?php echo $order->get_status();?>	
		<?php */ }?>	


	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>
	

</div>
