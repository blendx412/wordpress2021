<!DOCTYPE html>
<!--[if lte IE 9 ]>
<html class="ie lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>

	<?php wp_head(); ?>
</head>
<!-- loading -->

<body <?php body_class(); ?>>

<div id="main-content" class="site-main">

	<div style="padding-top: 0;" id="main" class="page-checkout-simple">

		<div id="content" role="main">
		
<!--	
<section style="padding-top: 10px; padding-bottom: 10px; margin-bottom: 15px; background: white;">
<div class="container">
	<div class="row">
		<div style="    width: 100%;" class="col-md-12 text-center">
			<a style="display: block; margin: 0 auto;" href="https://mistermega.hr/" title="Mister Mega" rel="home"> 
				<img style="max-width: 80px; height: auto; width: 70px; " src="/wp-content/uploads/2020/01/Logo.png" class="header_logo header-logo" alt="Mister Mega" >
			</a>
		</div>
	</div>
</div>
</section>
-->
		
		
			<div class="container">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php /*
					<div class="cart-header text-left medium-text-center">
						<?php //get_template_part( 'template-parts/header/partials/element', 'logo' ); ?>
						<?php
						global $woocommerce;
						function flatsome_checkout_breadcrumb_class($endpoint){
							$classes = array();
							if($endpoint == 'cart' && is_cart() ||
								$endpoint == 'checkout' && is_checkout() && !is_wc_endpoint_url('order-received') ||
								$endpoint == 'order-received' && is_wc_endpoint_url('order-received')) {
								$classes[] = 'current';
							} else{
					      $classes[] = 'hide-for-small';
					    }
							return implode(' ', $classes);
						}
						?>

						<nav class="breadcrumbs checkout-breadcrumbs text-left medium-text-center lowercase is-large">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="<?php echo flatsome_checkout_breadcrumb_class('cart'); ?>">
								<span class="step">1</span>
								<?php _e('Kosár', 'flatsome'); ?>
							</a>
							<hr>
							<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="<?php echo flatsome_checkout_breadcrumb_class('checkout') ?>">
								<span class="step">2</span>
								<?php _e('Megrendelés', 'flatsome'); ?>
							</a>
							<hr>
							<a href="#" class="no-click <?php echo flatsome_checkout_breadcrumb_class('order-received'); ?>">
								<span class="step">3</span>
								<?php _e('Vásárlás véglegesítése', 'flatsome'); ?>
							</a>
						</nav>

					</div>
					*/ ?>
					<?php wc_print_notices(); ?>
					<?php the_content(); ?>

				<?php endwhile; // end of the loop. ?>
			</div><!-- end .container -->
		</div><!-- end #content -->

	</div>

	<div class="focused-checkout-footer">
		<?php get_template_part( 'template-parts/footer/footer', 'absolute' ); ?>
	</div>

</div><!-- #main-content -->

</div><!-- #wrapper -->

<!-- back to top -->
<?php wp_footer(); ?>

</body>
</html>
