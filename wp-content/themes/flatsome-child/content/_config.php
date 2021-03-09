<?php
add_action( 'wp_enqueue_scripts', 'enqueue_load_styles' );
function enqueue_load_styles() {

	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/css/new.css?v=16' );
	wp_enqueue_style( 'owl-carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css' );
	wp_enqueue_script('landingpages', get_stylesheet_directory_uri() . '/js/landingpages.js', array(), '', 'all');

	wp_enqueue_script('owl-carousel', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array(), '', 'all');
}

function analytics_hook() {

	if(get_post_meta($order_id, 'jackopot', true) == 1 && $pagename == 'zakljucek-nakupa'){  ?>
		<!--  NO PIXED CODE HERE for JACKPOT ORDER <?php echo $order_id; ?>  -->
	
		<?php }else{	?>
	
		
<?php } ?>
	
		
<!-- Facebook Pixel Code 
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '166768947205154');
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none" 
       src="https://www.facebook.com/tr?id=166768947205154&ev=PageView&noscript=1"/>
</noscript>
 End Facebook Pixel Code -->
	
	
	<!-- Global site tag (gtag.js) - Google Analytics   -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-7MFXXELRLR"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-7MFXXELRLR');
	</script>


	
	


	<?php
}
add_action('wp_head', 'analytics_hook');
