<!DOCTYPE html>
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="ie9 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="ie8 <?php flatsome_html_classes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="<?php flatsome_html_classes(); ?>"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php
    if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ){ if(!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false){
        wp_head(); }}
    else{wp_head();}
    ?>
	
	<?php 

global $woocommerce;
	// d( $woocommerce->session->jackpot_order );
	if( is_front_page() || is_product() ) {
			if( $woocommerce->session->jackpot_order != null && $woocommerce->session->jackpot_order != false && $woocommerce->session->jackpot_order != "" ) {
				$woocommerce->session->DESTROY_SESSION();
			}	
	}
?>
	
	
	<style>
	
	.checkout-rating3 {
    background-color: #ffa4004a;
    border: 1px solid #ffa401;
	padding: 13px 10px;
	text-align: center;
	margin-bottom: 20px;
}
.checkout-title3 {
    font-size: 29px;
	text-align: center;
}
.underline3 {
    text-decoration: underline;
	font-weight: bold;
}

	
	.product_cat-hidden {
		

		
	}
	
	.select-buttons .plava {
		
		/* background: #0000ff !important; */
	}
	
		.select-buttons .roza {
		
		/*  background: #ff69b4 !important; */
	}
	
	</style>
	
	<script>
	/** 
 * Forward port jQuery.live()
 * Wrapper for newer jQuery.on()
 * Uses optimized selector context 
 * Only add if live() not already existing.
*/
if (typeof jQuery.fn.live == 'undefined' || !(jQuery.isFunction(jQuery.fn.live))) {
  jQuery.fn.extend({
      live: function (event, callback) {
         if (this.selector) {
              jQuery(document).on(event, this.selector, callback);
          }
      }
  });
}
	</script>
	
</head>

<body <?php body_class(); // Body classes is added from inc/helpers-frontend.php ?>>

<?php do_action( 'flatsome_after_body_open' ); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'flatsome' ); ?></a>

<div id="wrapper">

<?php do_action('flatsome_before_header'); ?>

<header id="header" class="header <?php flatsome_header_classes();  ?>">
   <div class="header-wrapper">
	<?php
		get_template_part('template-parts/header/header', 'wrapper');
	?>
   </div><!-- header-wrapper-->
</header>

<?php do_action('flatsome_after_header'); ?>

<main id="main" class="<?php flatsome_main_classes();  ?>">
