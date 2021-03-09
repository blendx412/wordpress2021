<?php


add_action( 'wp_footer', function () {
	?>
    <script>
        (function ($) {
            var bump_all_added = false;
            var first_click = false;
            $(document.body).on('updated_checkout', function () {
                setTimeout(function () {
                    if (first_click) {
                        return;
                    }
                    $('.wfob_bump_product').eq(0).trigger('click');

                }, 200);
            });
            $(document.body).on('click', '.wfob_bump_product', function () {

                first_click = true;
            });

            $(document.body).on('wfob_bump_trigger', function () {
                if (bump_all_added) {
                    return;
                }
                var bump = $('.wfob_bump_product');

                if (bump.length > $('.wfob_bump_product:checked').length) {

                    $('.wfob_bump_product').not(':checked,.wfob_choose_variation').eq(0).trigger('click');
                } else {
                    bump_all_added = true;
                }
            });
        })(jQuery);

    </script>
	<?php

} );


add_filter( 'woocommerce_add_to_cart_redirect', 'bbloomer_redirect_checkout_add_cart' );
 
function bbloomer_redirect_checkout_add_cart() {
   return wc_get_checkout_url();
}



/**
 * Add svg support
 *
 */
add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes) {
      global $wp_version;
      if( $wp_version == '4.7' || ( (float) $wp_version < 4.7 ) ) {
      return $data;
    }
    $filetype = wp_check_filetype( $filename, $mimes );
      return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
    ];
}, 10, 4 );

function ns_mime_types( $mimes ){
   $mimes['svg'] = 'image/svg+xml';
   return $mimes;
}
add_filter( 'upload_mimes', 'ns_mime_types' );

function ns_fix_svg() {
  echo '<style type="text/css">.attachment-266x266, .thumbnail img { width: 100% !important; height: auto !important;} </style>';
}
add_action( 'admin_head', 'ns_fix_svg' );


if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Vsebinske nastavitve',
        'menu_title'	=> 'Vsebinske nastavitve',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));

}


/**
 * Remove all java scripts.
 */
function se_remove_all_scripts() {
    global $wp_scripts;
    if ( is_page_template( ' template-landing_dizajn_1.php' ) || is_page_template( 'template-lpb2.php' ) ) {
        $wp_scripts->queue = array();
    }
}

//add_action( 'wp_print_scripts', 'se_remove_all_scripts', 99 );

/**
 * Remove all style sheets.
 */
function se_remove_all_styles() {
    global $wp_styles;
  if ( is_page_template( 'template-lpb2.php' ) ||  is_page_template( 'template-ladning-dvojne.php' ) || is_page_template( 'template-lbp2variacije.php' )) {
        $wp_styles->queue = array();
    }
}

add_action( 'wp_print_styles', 'se_remove_all_styles', 99 );

/**
		 * Enhanced Ecommerce Google Analytics compatibility
		 */

			add_action( 'wp_loaded', function () {
				if ( class_exists( 'Enhanced_Ecommerce_Google_Analytics' ) ) {
					global $wp_filter;
					foreach ( $wp_filter['woocommerce_thankyou']->callbacks as $key => $val ) {
						if ( 10 !== $key ) {
							continue;
						}
						foreach ( $val as $innerkey => $innerval ) {
							if ( isset( $innerval['function'] ) && is_array( $innerval['function'] ) ) {
								if ( is_a( $innerval['function']['0'], 'Enhanced_Ecommerce_Google_Analytics_Public' ) ) {
									$Enhanced_Ecommerce_Google_Analytics = $innerval['function']['0'];
									remove_action( 'woocommerce_thankyou', array( $Enhanced_Ecommerce_Google_Analytics, 'ecommerce_tracking_code' ) );
									break;
								}
							}
						}
					}
				}
			}, 0 );


// Helpers init
include(STYLESHEETPATH . '/content/_helpers.php');
// Config init
include(STYLESHEETPATH . '/content/_config.php');
// Woocommerce Filters and Actions
include(STYLESHEETPATH . '/content/_woocommerce_filters_actions.php');
// Woocommerce Shortcodes
include(STYLESHEETPATH . '/content/_shortcodes.php');

include(STYLESHEETPATH . '/content/_jackpot.php');

function mistermega_adding_scripts() {


wp_register_script('whatsapp-product-name', get_stylesheet_directory_uri() . '/js/whatsapp-product-name.js?dsfds4ds2', array('jquery'), true);

wp_enqueue_script('whatsapp-product-name');

wp_register_script('countdown-js', get_stylesheet_directory_uri() . '/js/countdown.js', array('jquery'), true);

wp_enqueue_script('countdown-js');

}

add_action( 'wp_enqueue_scripts', 'mistermega_adding_scripts' );

/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return 'https://mrmaks.eu/hr';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

//add awesome icons

add_action( 'wp_enqueue_scripts', 'enqueue_load_fa' );
function enqueue_load_fa() {
wp_enqueue_style( 'load-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}

//remove reviews on product page Jan B.-29.8.2018
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
    function wcs_woo_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}

//add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_echo_qty_front_add_cart' );

function bbloomer_echo_qty_front_add_cart() {
 echo '<div class="qty">
Količina: </div>';
}

add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );

function change_woocommerce_order_number( $order_id ) {
    $prefix = 'MAXHR-';
    $new_order_id = $prefix . $order_id;
    return $new_order_id;
}



//add_action( 'woocommerce_before_add_to_cart_button', 'additional_simple_add_to_cart', 5 );
/*
function additional_simple_add_to_cart() {
    global $product;
    $get1 = $product->get_price();
    $get2 = $product->get_price() - $product->get_price()*0.2;
    $get3 = $product->get_price() - $product->get_price()*0.3;
    // Only for simple product type
    //if( ! $product->is_type('simple ') ) return;
echo '<div class="kolicina-buttons">';

    echo '<button id="izbira3">1 za<br> '.(int)$get1.'Kn/kom<br><span class="izbira-popust-1">-0 %</span></button>';

    echo '<button id="izbira1">2 za<br> '.(int)$get2.'Kn/kom<br><span class="izbira-popust">-20 %</span></button>';

    echo '<button id="izbira2">3 za<br> '.(int)$get3.'Kn/kom<br><span class="izbira-popust">-30 %</span></button>';
echo'</div>';
}
*/




//add_action( 'woocommerce_after_add_to_cart_button', 'content_after_addtocart_button' );

function content_after_addtocart_button() {
?>
    <div class="after-addtocart" style="text-align:center">
        <div class="row small-columns-4">
            <div class="col">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/quality.jpg">
                <span>KVALITETA<br>KUPNJE</span>
            </div>
            <div class="col">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/support.jpg">
                <span>PODRŠKA<br>24/7</span>
            </div>
            <div class="col">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/garanty.jpg">
                <span>JAMSTVO ZA<br>ZADOVOLJSTVO</span>
            </div>
            <div class="col">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/images/shipping.jpg">
                <span>BRZA<br>DOSTAVA</span>
            </div>
        </div>
    </div>
<?php
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );


add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

add_filter('woocommerce_product_add_to_cart_text', 'wh_archive_custom_cart_button_text');   // 2.1 +

function wh_archive_custom_cart_button_text()
{
    return __('Oglej si več', 'woocommerce');
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'add_product_link' );
function add_product_link( $link ) {
global $product;
    echo '<form action="' . esc_url( $product->get_permalink( $product->id ) ) . '" method="get">
            <button type="submit" class="button add_to_cart_button product_type_simple">' . __('Odaberi', 'woocommerce') . '</button>
          </form>';
}


function my_custom_add_to_cart_redirect( $url ) {
	$url = WC()->cart->get_checkout_url();
	// $url = wc_get_cart_url(); // since WC 2.5.0
	return $url;
}

//add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

/*

add_action( 'woocommerce_review_order_before_submit', 'bbloomer_add_checkout_privacy_policy', 9 );

// Pogoji poslovanja

function bbloomer_add_checkout_privacy_policy() {

woocommerce_form_field( 'privacy_policy', array(
    'type'          => 'checkbox',
    'class'         => array('form-row privacy'),
    'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
    'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
    'required'      => true,
    'label'         => 'Slažem se s <a href="https://mistermega.hr/shop/uvjeti-poslovanja/">uvjetima poslovanja.</a>',
));

}

// Show notice if customer does not tick

add_action( 'woocommerce_checkout_process', 'bbloomer_not_approved_privacy' );

function bbloomer_not_approved_privacy() {
    if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
        wc_add_notice( __( 'Prije nego što nastavite, morate prihvatiti naše Uvjete i odredbe.' ), 'error' );
    }
}

*/

add_filter( 'woocommerce_shipping_package_name', 'custom_shipping_package_name' );
function custom_shipping_package_name( $name ) {
  return 'Dostava';
}


add_filter( 'woocommerce_continue_shopping_redirect', 'my_custom_continue_redirect' );

  function my_custom_continue_redirect( $url ) {
  return 'https://mrmaks.eu/hr';
  }

add_filter( 'gettext', 'change_woocommerce_return_to_shop_text', 20, 3 );

function change_woocommerce_return_to_shop_text( $translated_text, $text, $domain ) {

        switch ( $translated_text ) {

            case 'Nadaljuj z nakupovanjem' :

                $translated_text = __( 'Nazaj v trgovino', 'woocommerce' );
                break;

        }

    return $translated_text;
}

add_filter( 'the_content', 'customizing_woocommerce_description' );
function customizing_woocommerce_description( $content ) {

    // Only for single product pages (woocommerce)
    if ( is_product() ) {

        // The custom content
        $custom_content = '<p class="custom-content">' . __('<a href="#top"><button type="submit" class="back-top" href="#top">Do početka</button></a>', "woocommerce").'</p>';

        // Inserting the custom content at the end
        $content .= $custom_content;
    }
    return $content;
}


add_filter('woocommerce_sale_flash', 'woocommerce_sale_flashmessage', 10, 2);
function woocommerce_sale_flashmessage($flash){
    global $product;
    $availability = $product->get_availability();

    if ($availability['availability'] == 'Ni na zalogi') :
        $flash = '<span class="out-of-stock-label">'.__( 'SOLD', 'woocommerce' ).'</span>';

    endif;
    return $flash;
}


add_filter('gettext', 'translate_reply');
add_filter('ngettext', 'translate_reply');

function translate_reply($translated) {
$translated = str_ireplace('Dostaviti na različitu adresu?', 'DOSTAVA NA DRUGU ADRESU?', $translated);
return $translated;
}

add_action('admin_head', 'bc_disable_notice'); function bc_disable_notice() { ?> <style> .notice, .error { display: none;} .notice.pys-notice-wrapper{ display: none !important; } </style> <?php }

?>
