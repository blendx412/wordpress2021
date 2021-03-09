<?php 



define('JACKPOT_PRICE', 60);
define('JACKPOT_TIME', 7); // minutes
define('JACKPOT_PAGE_ID', 477664);


/**
 * Unhook and remove WooCommerce default emails.
 */
add_action( 'woocommerce_email', 'unhook_those_pesky_emails' );

function unhook_those_pesky_emails( $email_class ) {

		// Processing order emails
		remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
		

}

function register_primary_order_status() {
    register_post_status( 'wc-primary-order', array(
        'label'                     => 'Primary order',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Primary order (%s)', 'Primary order (%s)' )
    ) );
}
add_action( 'init', 'register_primary_order_status' );

function add_primary_to_order_statuses( $order_statuses ) {
 
    $new_order_statuses = array();
 
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
 
        $new_order_statuses[ $key ] = $status;
 
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-primary-order'] = 'Primary order';
        }
    }
 
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_primary_to_order_statuses' );


add_action( 'woocommerce_order_status_processing', 'custom_processing');

function custom_processing($order_id){
	
	
	
	global $woocommerce, $wpdb;
	
	$jackpot_order_id =  $woocommerce->session->jackpot_order;
	$jackpot_order = wc_get_order( $jackpot_order_id );		
	
	$order = wc_get_order( $order_id );
	if($order->get_payment_method() == 'cod'){
		
	
			$order->update_status( 'primary-order' );
			
	
	}	
}	



//	function returns time between order create date and now in minutes
//	dates in UTC

function hivista_order_time_diff($order, $type='m'){	
	
	if(!empty($order)){
		$order_date = $order->get_date_created();
		$now = strtotime('now');
		$order_time_diff = $now - get_gmt_from_date($order_date, "U");
		
		if($type == 'm'){	
			return floor(abs($order_time_diff/60));
		}elseif($type == 's'){
			return floor($order_time_diff);
		}		
	}else{
		return 1000;
	}		
}


add_filter( 'woocommerce_get_price_html', 'jackpot_get_price_html', 10, 2 );
function jackpot_get_price_html( $price, $that ){
	global $woocommerce;
	$order_id =  $woocommerce->session->jackpot_order;
	$order = wc_get_order( $order_id );	
	if($order){
		if($order->get_payment_method() == 'cod' && $order->get_status() == 'primary-order' &&  hivista_order_time_diff($order) < JACKPOT_TIME && (is_page('jackpot-products') || is_product() || is_cart() || is_checkout() || $_REQUEST['action'] == 'add_jackpot_product') ){
			$price = '<span class="woocommerce-Price-amount amount">'.JACKPOT_PRICE.'<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span></span>';			
		}	
	}
	
	return $price;	
}


add_filter('woocommerce_product_get_price', 'jackpot_price', 99, 2 );
add_filter('woocommerce_product_get_regular_price', 'jackpot_price', 99, 2 );
// Variations 
add_filter('woocommerce_product_variation_get_regular_price', 'jackpot_price', 99, 2 );
add_filter('woocommerce_product_variation_get_price', 'jackpot_price', 99, 2 );		

add_filter('woocommerce_variation_prices_price', 'jackpot_variation_prices', 99, 3 );
add_filter('woocommerce_variation_prices_regular_price', 'jackpot_variation_prices', 99, 3 );


function jackpot_variation_prices($price, $variation, $product){
	global $woocommerce;
	$order_id =  $woocommerce->session->jackpot_order;
	$order = wc_get_order( $order_id );	
	//is_page('jackpot-products') || 
	if($order){
		if($order->get_payment_method() == 'cod' && $order->get_status() == 'primary-order' &&  hivista_order_time_diff($order) < JACKPOT_TIME && ( is_cart() || is_checkout() || $_REQUEST['action'] == 'add_jackpot_product'  || $_REQUEST['action'] == 'remove_jackpot_product')   ) $price = JACKPOT_PRICE;			
	}
	
	return $price;		
}
function jackpot_price($price, $product){
	global $woocommerce;
	$order_id =  $woocommerce->session->jackpot_order;	
	$order = wc_get_order( $order_id );			
	//is_page('jackpot-products') || 
	if($order){
		if($order->get_payment_method() == 'cod' && $order->get_status() == 'primary-order' &&  hivista_order_time_diff($order) < JACKPOT_TIME && ( is_cart() || is_checkout() || $_REQUEST['action'] == 'add_jackpot_product' || $_REQUEST['action'] == 'remove_jackpot_product') ) $price = JACKPOT_PRICE;			
	}
	
	return $price;			
}
	
function jackpot_add_to_cart_redirect( $url ) {
	global $woocommerce;
	$jackpot_order_id =  $woocommerce->session->jackpot_order;
	$jackpot_order = wc_get_order( $jackpot_order_id );			
	if($jackpot_order){
		if($jackpot_order->get_payment_method() == 'cod' && $jackpot_order->get_status() == 'primary-order' &&  hivista_order_time_diff($jackpot_order) < JACKPOT_TIME){			
			$url = get_permalink( JACKPOT_PAGE_ID ); 
		}
	}		

	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'jackpot_add_to_cart_redirect' );	
	
	
add_filter( 'cron_schedules', 'jackpot_add_every_three_minutes' );
function jackpot_add_every_three_minutes( $schedules ) {
    $schedules['every_three_minutes'] = array(
            'interval'  => 180,
            'display'   => __( 'Every 3 Minutes', 'textdomain' )
    );
    return $schedules;
}


if ( ! wp_next_scheduled( 'jackpot_add_every_three_minutes' ) ) {
    wp_schedule_event( time(), 'every_three_minutes', 'jackpot_add_every_three_minutes' );
}

// Hook into that action that'll fire every three minutes
add_action( 'jackpot_add_every_three_minutes', 'every_three_minutes_event_func' );
function every_three_minutes_event_func() {
    global $wpdb;
	
	//echo '<h3>update jackpot order</h3>';
	
	//wp_mail('tyurinp@gmail.com', 'jackpot cron', '');
	
	$jackpot_orders = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order' AND post_status = 'wc-primary-order' ");
	
	//echo "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order' AND post_status = 'wc-primary-order' ";
	//echo '<pre>';
	//print_r($jackpot_orders);
	//echo '</pre>';
	
	if(!empty($jackpot_orders)){
		foreach($jackpot_orders as $jackpot_order_id){
				
				$jackpot_order = wc_get_order( $jackpot_order_id ); 
				//echo '<br>'.hivista_order_time_diff($jackpot_order);
				if( hivista_order_time_diff($jackpot_order) > JACKPOT_TIME){
					//echo '<br>Update Jackpot Order ID: '.$jackpot_order_id;
					$wpdb->query("UPDATE ".$wpdb->posts." SET post_status = 'wc-processing' WHERE ID = ".$jackpot_order_id);
					
					$allmails = WC()->mailer()->emails;
					$email = $allmails['WC_Email_Customer_Processing_Order'];
					$email->trigger( $jackpot_order_id );
					$jackpot_order->add_order_note( '"Order Processing" Email Resent 2' );
					
					//add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );

			   
							

				}
		}
	}
	
	if($_REQUEST['action'] == 'update_jackpot_order') exit();
}

add_action('init', 'hivista_init');
function hivista_init(){

		if($_REQUEST['action'] == 'update_jackpot_order'){
	
			every_three_minutes_event_func();
		}
}

function is_jackpot_product($product_id){
	global $woocommerce;
	$is_jp = false;	
	
	$jackpot_order_id =  $woocommerce->session->jackpot_order;
	$jackpot_order = wc_get_order( $jackpot_order_id );		
	
	if(!empty($jackpot_order) && $jackpot_order->get_status() == 'primary-order' &&  hivista_order_time_diff($jackpot_order) < JACKPOT_TIME  && has_term('jackpot', 'product_cat', $product_id)) $is_jp = true;
	
	return  $is_jp;
}	

function jacpot_checkout_template_redirect(){
	if(is_checkout() || is_cart()){
		
		global $woocommerce, $wpdb;
	
		$jackpot_order_id =  $woocommerce->session->jackpot_order;
		$jackpot_order = wc_get_order( $jackpot_order_id );				
		
		if(!empty($jackpot_order) && $jackpot_order->get_status() == 'primary-order'){
			
			
			 $items = $woocommerce->cart->get_cart();
			 

			if(!empty($items)){
				foreach($items as $cart_item_key => $values){
					
					$item_id = $jackpot_order->add_product($values['data'], $values['quantity'], array(
																					'variation' => $values['variation'],
																					'totals' => array(
																								'subtotal' => $values['line_subtotal'],
																								'subtotal_tax' => $values['line_subtotal_tax'],
																								'total' => $values['line_total'],
																								'tax' => $values['line_tax'],
																								'tax_data' => $values['line_tax_data'] // Since 2.2
																							)
																					)
							);
	
				}
	
				// Remove Jackpot Order is from session	
				$woocommerce->session->jackpot_order = '';
				unset($woocommerce->session->jackpot_order);
			
				// Update Jackpot Order ofter add new order items
				$jackpot_order->calculate_totals();
			
				// Add metadata for Jackpot Order
				$jackpot_order->update_meta_data( 'jackopot', true );
				$jackpot_order->save();
				
				update_post_meta($jackpot_order_id, 'jackopot', true);
			
				// Change Jackpot Order status dilectly in DB
				$wpdb->query("UPDATE ".$wpdb->posts." SET post_status = 'wc-processing' WHERE ID = ".$jackpot_order_id);	
				
				
					$allmails = WC()->mailer()->emails;
					$email = $allmails['WC_Email_Customer_Processing_Order'];
					$email->trigger( $jackpot_order_id );
					$jackpot_order->add_order_note( '"Order Processing" Email Resent 3' );
				
				
				//$email_notifications['WC_Email_Customer_Processing_Order']->trigger( $jackpot_order_id );
								
	
				$order_key = get_post_meta( $jackpot_order_id, '_order_key', true);
			
				$review_order_url = wc_get_checkout_url().'/order-received/'.$jackpot_order_id.'/?key='.$order_key;
			
				$woocommerce->cart->empty_cart(); 
			
				wp_redirect( $review_order_url );
				exit();			
			}
				
		}		
	}
}
add_action( 'template_redirect', 'jacpot_checkout_template_redirect' );	


function get_thumb($attach_id, $width, $height, $crop = false) {


	if (is_numeric($attach_id)) {

		//echo 'is_numeric';
		$image_src = wp_get_attachment_image_src($attach_id, 'full');
		//print_r($image_src);
		$file_path = get_attached_file($attach_id);

	} else {

		$imagesize = getimagesize($attach_id);
		$image_src[0] = $attach_id;
		$image_src[1] = $imagesize[0];
		$image_src[2] = $imagesize[1];
		$file_path = $_SERVER["DOCUMENT_ROOT"].str_replace(get_bloginfo('siteurl'), '', $attach_id);

	}

	$file_info = pathinfo($file_path);
	$extension = '.'. $file_info['extension'];


	//print_r($file_info);
	// image path without extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

	//echo $file_path;
	//echo $cropped_img_path;
	// if file size is larger than the target size

	if ($image_src[1] > $width || $image_src[2] > $height) {
		// if resized version already exists
		if (file_exists($cropped_img_path)) {
			return str_replace(basename($image_src[0]), basename($cropped_img_path), $image_src[0]);
		}

		if (!$crop) {
			// calculate size proportionaly
			//echo 'calculate size proportionaly';
			$proportional_size = wp_constrain_dimensions($image_src[1], $image_src[2], $width, $height);
			$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
			// if file already exists
			if (file_exists($resized_img_path)) {
				return str_replace(basename($image_src[0]), basename($resized_img_path), $image_src[0]);
			}
		}

		// resize image if no such resized file
		//echo $file_path;
		$new_img_path = image_resize($file_path, $width, $height, $crop);
		if( is_wp_error( $new_img_path ) ) {
			$error_message = $new_img_path->get_error_message();
			if($error_message == "File doesn't exist?"){
				$new_img_path = image_resize(strtolower($file_path), $width, $height, $crop);
			}else{
				//echo $new_img_path->get_error_message();
			}
		}		
		//echo $new_img_path;
		$new_img_size = getimagesize($new_img_path);
		return str_replace(basename($image_src[0]), basename($new_img_path), $image_src[0]);
	}
	// return without resizing
	return $image_src[0];
}

function hivista_is_product_in_cart($product_id){
	global $woocommerce;
	$in_cart = false;
  
	foreach($woocommerce->cart->get_cart() as $cart_item ) {
      $product_in_cart = $cart_item['product_id'];
      if ( $product_in_cart == $product_id ) $in_cart = true;
	}	
	return $in_cart;
}	

add_action('wp_ajax_add_jackpot_product', 'add_jackpot_product_callback');
add_action('wp_ajax_nopriv_add_jackpot_product', 'add_jackpot_product_callback');

function add_jackpot_product_callback(){

	global $wpdb, $woocommerce;
	
	$request = array('success'=>0, 'error'=>0, 'error_message' =>'');
	$error = false;
	
	if ( !wp_verify_nonce( $_REQUEST['nonce'], 'add_jackpot_product' )) {
		$error = true;
		$request['error'] = 1;
		$request['error_message'] .= 'Sequrity error';
	}
	
	$product_id = absint($_REQUEST['product_id']);
	$request['product_id'] = $product_id;
	
	if ( $error === false ) {
		
		$woocommerce->cart->add_to_cart( $product_id );
	
		ob_start();	
		woocommerce_mini_cart();		
		$mini_cart = ob_get_contents();
		$mini_cart = preg_replace("!<p class=\"woocommerce-mini-cart__buttons buttons\">(.*?)</p>!si","<p class=\"woocommerce-mini-cart__buttons buttons\"><a href=\"".wc_get_checkout_url()."\" class=\"btn-yellow\" >Zaklujuči nakup</a></p>", $mini_cart);		
		$request['mini_cart'] = $mini_cart;
		ob_get_clean();	
		
		$request['cart_total'] = $woocommerce->cart->get_cart_total();	
		$request['cart_count'] = $woocommerce->cart->get_cart_contents_count();		
		 
		$request['success'] = 1;

	}
	
	echo json_encode($request);	
	exit();
}

add_action('wp_ajax_remove_jackpot_product', 'remove_jackpot_product_callback');
add_action('wp_ajax_nopriv_remove_jackpot_product', 'remove_jackpot_product_callback');

function remove_jackpot_product_callback(){

	global $wpdb, $woocommerce;
	
	$request = array('success'=>0, 'error'=>0, 'error_message' =>'');
	$error = false;
	
	if ( !wp_verify_nonce( $_REQUEST['nonce'], 'remove_jackpot_product' )) {
		$error = true;
		$request['error'] = 1;
		$request['error_message'] .= 'Sequrity error';
	}
	
	$product_id = absint($_REQUEST['product_id']);
	$request['product_id'] = $product_id;
	
	if ( $error === false ) {

		foreach($woocommerce->cart->get_cart() as $cart_item_key=>$cart_item ) {
			$product_in_cart = $cart_item['product_id'];
			if ( $product_in_cart == $product_id )  $woocommerce->cart->remove_cart_item( $cart_item_key );
		}		
		
		ob_start();	
		woocommerce_mini_cart();		
		$mini_cart = ob_get_contents();
		$mini_cart = preg_replace("!<p class=\"woocommerce-mini-cart__buttons buttons\">(.*?)</p>!si","<p class=\"woocommerce-mini-cart__buttons buttons\"><a href=\"".wc_get_checkout_url()."\" class=\"btn-yellow\" >Zaklujuči nakup</a></p>", $mini_cart);		
		$request['mini_cart'] = $mini_cart;
		ob_get_clean();	
		
		$request['success'] = 1;
		
		$request['cart_total'] = $woocommerce->cart->get_cart_total();	
		$request['cart_count'] = $woocommerce->cart->get_cart_contents_count();		
		
	}
	
	echo json_encode($request);	
	exit();
}


// ADDING A CUSTOM COLUMN TITLE TO ADMIN ORDER LIST
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 12, 1 );
function custom_shop_order_column($columns)
{
    // Set "Actions" column after the new colum
    $action_column = $columns['order_actions']; // Set the title in a variable
    unset($columns['order_actions']); // remove  "Actions" column


    //add the new column "Platform"
    $columns['order_platform'] = '<span>'.__( 'Kolo srece','woocommerce').'</span>'; // title

    // Set back "Actions" column
    $columns['order_actions'] = $action_column;

    return $columns;
}

// ADDING THE DATA FOR EACH ORDERS BY "Platform" COLUMN
add_action( 'manage_shop_order_posts_custom_column' , 'custom_order_list_column_content', 10, 2 );
function custom_order_list_column_content( $column, $post_id )
{

    // HERE get the data from your custom field (set the correct meta key below)
    $platform = get_post_meta( $post_id, 'jackopot', true );
    if( empty($platform)) $platform = '';

    switch ( $column )
    {
        case 'order_platform' :
            echo '<span>'.$platform.'</span>'; // display the data
            break;
    }
}

// check for clear-cart get param to clear the cart, append ?clear-cart to any site url to trigger this
add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
	if ( isset( $_GET['clear-cart'] ) ) {
		global $woocommerce;
		$woocommerce->cart->empty_cart();
	}
}


?>