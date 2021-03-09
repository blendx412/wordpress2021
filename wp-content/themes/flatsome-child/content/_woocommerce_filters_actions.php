<?php

remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
add_action( 'woocommerce_review_order_before_submit', 'woocommerce_order_review', 20 );

// Display discounts for 2/3 products
add_action( 'woocommerce_single_product_summary', 'additional_simple_add_to_cart', 25 );
function additional_simple_add_to_cart() {
    global $product;
    $quantity_type = (get_field('quantity_type')) ? get_field('quantity_type') : 'kom';
    $get1 = $product->get_price();
    $get2 = $product->get_price() - round($product->get_price()*0.2, 2);
    $get3 = $product->get_price() - round($product->get_price()*0.3, 2);

    $description1 = '<strong>1x</strong> '.$get1.'kn/'.$quantity_type;
    $description2 = '<strong>2x</strong> '.$get2.'kn/'.$quantity_type;
    $description3 = '<strong>3x</strong> '.$get3.'kn/'.$quantity_type;
    if (get_field('different_buttons') == true) {
        $get2 = ($get1 - round($get1*0.43))*2;
        $get3 = ($get1 - round($get1*0.57))*3;
        $description1 = '<strong>1x</strong> '.get_field('buttons_text').'<br> '.$get1.'kn';
        $description2 = '<strong>2x</strong> '.get_field('buttons_text').'<br> '.$get2.'kn';
        $description3 = '<strong>3x</strong> '.get_field('buttons_text').'<br> '.$get3.'kn';
    }
	
	
	
		echo "
	<style>
	
	.tab-panels .entry-content { padding-bottom: 20px; }
	
	.tab-panels .entry-content a { font-weight: bold; text-decoration: underline; }
	
	.quantity  {
		display: none;
	}
	
	 #izbira1 , #izbira2, #izbira3 {
        width: 31%;
        padding: 10px 3px !important;
        font-size: 14px !important;
		margin-left: 1% !important;
		margin-right: 1% !important;
		border-radius: 0 !important;
      }
	  .izbira-active {
        background-color: #fff6e1 !important;
    color: #000 !important;
        border: 1px solid #f3ca66 !important;
}

	#izbira1 { position: relative;}
	
	#izbira1:before { 
	
position: absolute;
    content: 'TOP ODABIR';
    top: -24px;
    left: -1px;
    right: 0px;
    width: 100.7%;
    font-size: 11px;
    font-weight: 700;
    font-style: normal;
    font-stretch: normal;
    letter-spacing: normal;
    text-align: center;
    background-color: #f3ca66;
    color: #232f3e;
    padding: 1px 0 1px 0!important;
    text-align: center;
    text-transform: uppercase;
	}
	
	.kolicina-buttons {
	margin-top: 30px !important;
	}
	
	.wcbv-variations .select2Buttons li a {
		border-radius: none;
	}
	
	.wcbv-variations .picked {
	background: rgb(255 246 225) !important;
    border-color: #f3ca66 !important;
    color: black !important;
	border-radius: 0 !important;
	}
	
	.wcbv-variations .select2Buttons a:hover {
	background: rgb(255 246 225) !important;
    border-color: #f3ca66 !important;
    color: black !important;
	border-radius: 0 !important;
	}
	
	.wcbv-variations .select2Buttons li a {
border-radius: 0 !important;
padding: 5px !important;
	}
	
	.wcbv-variations .remove-filter {
		display: none;
	}
	
	.wcbv-variations  .select-buttons li:first-child a {
		background: white !important;
		padding-left: 0 !important;
    margin-left: -5px !important;
	}
	
	.wcbv-variations  .select-buttons li:first-child a  {
		background: white !important;
	}
	
	.wcbv-total-price {
		display: none !important;
	}
	
	.product-summary .user-ratings {
  
    text-align: left  !important;
	}
	
	
	.new-product-price {
   
	}
	
	</style>
	";

    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $currency_symbol = get_woocommerce_currency_symbol();
    $currency_symbol_data = ' data-currency="' . $currency_symbol . '"';

    if ($product->is_type('variable' )) {
        $variations = $product->get_available_variations();
        $regular_price = $variations[0]['display_regular_price'];
        $sale_price = $variations[0]['display_price'];
    }
    $regular_price_data = ' data-regular-price="' . $regular_price . '"';

    $new_price = '<div class="new-product-price" '. $currency_symbol_data .' '. $regular_price_data .'><div class="regular-price">Redovna cijena:<br>'.wc_price($regular_price).'</div>';
    if ($sale_price) {
        $new_price .= '<div class="sale-price">Cijena na popustu:<br>'.wc_price($sale_price).'</div></div>';
    } else {
        $new_price = '<div class="new-product-price" '. $currency_symbol_data .' '. $regular_price_data .'><div class="sale-price">Redovna cijena:<br>'.wc_price($regular_price).'</div></div>';
    }

        
    if(is_jackpot_product($product->get_id())){	
	
		$price_diff = $old_price - JACKPOT_PRICE;
		?>

		<p class="row-prise">
		<!--<span class="old-price"><?php echo $old_price.$currency_symbol; ?></span>-->
		<span class="new-price"><?php echo JACKPOT_PRICE.$currency_symbol;?></span>
		
		<!--<span class="bottom">Prihranite <?php echo $price_diff.$currency_symbol;?></span>-->
		</p>	
	
	<?php 
	}else{
		//echo $new_price;
	}
	
		$new_price .= '<div style="display: block; text-align: center;    font-weight: bold;    color: red;" class="sale-price"> + Besplatna dostava</div>';


    echo $new_price;

    $shippingDate = get_shipping_date();
    $shippingIcon = '<i class="fa fa-truck"></i>';
    $orderNowtext = '<p class="order-now-text">'. $shippingIcon .' Naručite danas i primite pošiljku u <span style="color:#80c455">'.$shippingDate['day_text'].' '.$shippingDate['day_date'].'</span></p>';
    //echo $orderNowtext;

    echo '<div class="qty">Količina: </div>';
    echo '<div class="kolicina-buttons">';

    echo '<button data-price="'.$get1.'" id="izbira3">'.$description1.'</button>';

    echo '<button data-price="'.$get2.'" id="izbira1">'.$description2.'</button>';

    echo '<button data-price="'.$get3.'" id="izbira2">'.$description3.'</button>';
    echo'</div>';
}

// Split cart items
function split_product_individual_cart_items( $cart_item_data, $product_id ){
    $product = wc_get_product($product_id);
    $price = $product->get_price();

    $unique_cart_item_key = uniqid();
    $cart_item_data['unique_key'] = $unique_cart_item_key;
    if (isset($_REQUEST['item-quantity'])) {
        $items_quantity = $_REQUEST['item-quantity'];
        $cart_item_data['item-quantity'] = $items_quantity;
    }

    $cart_item_data['item_price'] = getDiscountPrice($items_quantity, $price);

    return $cart_item_data;
}

add_filter( 'woocommerce_add_cart_item_data', 'split_product_individual_cart_items', 10, 2 );

add_filter( 'woocommerce_is_sold_individually', '__return_true' );

function before_calculate_totals($cart_obj) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    // Iterate through each cart item
    foreach( $cart_obj->get_cart() as $key=>$value ) {
        $items_quantity = $value['item-quantity'];
        $price = $value['data']->get_price();
        $discountPrice = getDiscountPrice($items_quantity, $price);

        $value['item_price'] = $discountPrice;
        $value['data']->set_price(($discountPrice));
    }
}
//add_action( 'woocommerce_before_calculate_totals', 'before_calculate_totals', 10, 1 );

function remove_cart_item($cart_item_key, $cart) {

    $cart = WC()->cart->cart_contents;
    foreach( $cart as $cart_item_id=>$cart_item ) {
        $actual_price = $cart_item['data']->get_price();
        $price = $cart_item['item_price'];
        //epr($price);
        //die();
        $cart_item['item-quantity'] = $cart_item['item-quantity'] - 1;
        $quantity = $cart_item['item-quantity'];

        if ($quantity == 1)
            $cart_item['item_price'] = $actual_price;
        if ($quantity == 2)
            $cart_item['item_price'] = $actual_price - round($price*0.3,2);
        WC()->cart->cart_contents[$cart_item_id] = $cart_item;
    }
    WC()->cart->set_session();

};
//add_action( 'woocommerce_cart_item_removed', 'remove_cart_item', 100, 2 );

function getDiscountPrice($quantity, $actualPrice) {
    if ($quantity == 1)
        $price = $actualPrice;
    if ($quantity == 2)
        $price = $actualPrice - round($actualPrice*0.2,2);
    if ($quantity == 3)
        $price = $actualPrice - round($actualPrice*0.3,2);

    return $price;
}

// Checkout page additional field
//add_action('woocommerce_review_order_before_payment', 'custom_checkout_field');

function custom_checkout_field($checkout) {

    echo '<h3>Zavarovanje paketa</h3>';
    echo '<input type="checkbox">';
}

/*
 * Order insurance
 */
add_action( 'woocommerce_review_order_before_payment', 'add_insurance_to_checkout', -1 );
function add_insurance_to_checkout( ) {

   
   	echo '<h5 class="insure-package-title">'.get_field('title').'</h5>';
    echo '<div id="insurance">';
	echo '<div id="insurance2">';
   
	echo '</div>';
	
	
    woocommerce_form_field( 'add_insurance_box', array(
        'type'          => 'checkbox',
        'class'         => array('add_insurance_box'),
        'label'         => "Osiguranje pošiljke! <span class='woocommerce-Price-amount amount'>7 kn<span class='woocommerce-Price-currencySymbol'></span></span>",
        'placeholder'   => __(''),
		  'default' => 1 ,
		 'value'  => true, 
        ), WC()->checkout->get_value( 'add_insurance_box' ));

echo '<div id="insurance3">';
    echo '<span class="insurance-desc">'.get_field('description').'</span>';
    echo '</div>';
	echo '</div>';
	
	
   
}

add_action( 'wp_footer', 'woocommerce_add_insurance_box' );
function woocommerce_add_insurance_box() {
    if (is_checkout()) {
    ?>
    <script type="text/javascript">
    jQuery( document ).ready(function( $ ) {
        $('#add_insurance_box').click(function(){
            jQuery('body').trigger('update_checkout');
        });
    });
    </script>
    <?php
    }
}

add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' );
function woo_add_cart_fee( $cart ){

    if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
        return;
    }

    if ( isset( $_POST['post_data'] ) ) {
        parse_str( $_POST['post_data'], $post_data );
    } else {
        $post_data = $_POST; // fallback for final checkout (non-ajax)
    }

    if (isset($post_data['add_insurance_box'])) {
        $page_id = 62;
        $extracost = get_field('cart_cost', $page_id);
        WC()->cart->add_fee( get_field('cart_cost_title', $page_id), $extracost );
    }

}

function countdown_landing( $atts ) {
    $html = '<div class="counter">
				<div class="col-3">
					<div class="counter-box">
						<div id="days"></div>
						<div class="title">
							DAYS
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="counter-box">
						<div id="hours"></div>
						<div class="title">
							HOURS
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="counter-box">
						<div id="minutes"></div>
						<div class="title">
							MINUTES
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="counter-box">
						<div id="seconds"></div>
						<div class="title">
							SECONDS
						</div>
					</div>
				</div>
			</div>';

	return $html;
}
add_shortcode( 'countdown_shortcode_landing', 'countdown_landing' );

// Add extra class to body
add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {

    $extraClass = get_field('extra_class');

    $classes[] = $extraClass;

    return $classes;

}

function action_woocommerce_before_order_notes( $checkout ) {
    ?>
    <script type="text/javascript">
        jQuery( document ).ready(function( $ ) {
            $('.woocommerce-checkout #display-comments').change(function() {
                $(".woocommerce-checkout #order_comments_field").slideToggle();
            });
        });
    </script>
    <div class="display-comments-box">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <input id="display-comments" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="display-comments"> <span>KOMENTAR?</span>
        </label>
    </div>
    <?php
};

// add the action
add_action( 'woocommerce_before_order_notes', 'action_woocommerce_before_order_notes', 10, 1 );

// Display message Curently viewing product
function action_woocommerce_after_add_to_cart_button() {
    $number = rand(7,15);
    ?>
    <p class="currently-viewing-product" style="color: #f13131;margin-top: -15px;font-weight: normal;font-size: 16px"><i class="fa fa-search" aria-hidden="true"></i> Trenutno <?php echo $number; ?> osoba gleda ovaj proizvod!</p>
      <p class="currently-viewing-product" style="color: #212121;margin-top: -10px;font-size: 14px;    font-weight: 500;"><i style="margin-left: 4px; margin-right: 4px;" class="fa fa-exclamation" aria-hidden="true"></i>
    Proizvod nije dostupan u fizičkim trgovinama! </p>
	
	<div class="customer_rate_text">
            <div class="customer_rate_text_child">
                <img style="width:50px; height: auto;margin-right: 0.5em;" src="https://mrmaks.si/wp-content/themes/flatsome-child/images/tretja.png">
                <p>Obavijest o isporuci<br>
                    <span>
					Pokušavamo osigurati dostavu što je prije moguće, tako da se paketi obično šalju isti dan. Unatoč tome, zbog dostavljačkih usluga, može postojati kratka vremenska odgoda, tako da ako ne primite paket u zadanom periodu, molimo Vas kontaktirajte nas na <a style="color: black; font-weight: bold; text-decoration: underline;" href="mailto:info@mrmaks.eu">info@mrmaks.eu</a>. Hvala na razumijevanju!


					
</span>
                </p>
            </div>
        </div>
		
		<style>
		
				@media only screen and (max-width: 800px) {
  .product-tabs li {
    max-width: 33.2% !important;
  }
  
  .product-info  {
	  padding-bottom: 0 !important;
  }
  .product-main {
	   padding-bottom: 0 !important;
  }
}
		
.customer_rate_text {
  width: 100%;
  display: flex;
  background-color: #dcf1ff;
  border-radius: 2px;
  margin-bottom: 10px;
}
.customer_rate_text div {
    display: flex;
    background-color: #dcf1ff;
    padding: 10px;
    max-width: 100%;
    align-items: center;
}
.customer_rate_text p {
    font-weight: 700;
    margin-bottom: 0;
    font-size: 14px;
    line-height: 1.4;
}
.customer_rate_text span {
    font-weight: 400;color: black;
    font-size: 12px;
}
		</style>

   <?php
};

add_action( 'woocommerce_after_add_to_cart_button', 'action_woocommerce_after_add_to_cart_button', 10, 0 );

function action_woocommerce_review_order_before_submit() {
    global $woocommerce;

    $total_price =  $woocommerce->cart->total;
    $cart_items = $woocommerce->cart->get_cart();
    $fees = WC()->cart->get_fees();

    ?>
    <h3>4. SAŽETAK NABAVKE</h3>
    <div class="cart-box" style="font-size: 14px">
        <div>
            <div class="column col-3"><h4>Ukupni iznos</h4></div>
            <div class="column col-2"><?php echo wc_price($total_price); ?></div>
        </div>
        <div class="cart-totals">
            <h4>Naručeni proizvodi</h4>
            <?php
            foreach($cart_items as $item => $values) {
                $_product =  wc_get_product( $values['data']->get_id());
                $price = get_post_meta($values['product_id'] , '_price', true);
                ?>
                <div class="column col-3"><?php echo $values['quantity'] . 'x ' . $_product->get_title(); ?></div>
                <div class="column col-2"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $item ); ?></div>
            <?php
            }
            ?>
        </div>
        <div>
            <h4>Pretplaćene usluge</h4>
            <div>
                <div class="column col-3">Dostava</div>
                <div class="column col-2">Besplatna</div>
            </div>
            <?php if ($fees): ?>
                <?php foreach ($fees as $key => $fee): ?>
                    <div>
                        <div class="column col-3"><?php echo $fee->name; ?></div>
                        <div class="column col-2"><?php echo wc_price($fee->amount); ?></div>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
};

// add the action
//add_action( 'woocommerce_review_order_before_submit', 'action_woocommerce_review_order_before_submit', 10, 0 );

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

// woocommerce_single_product_summary callback
function action_woocommerce_single_product_summary( $array, $int ) {
      $stars_url = 'https://mrmaks.si/wp-content/uploads/2018/02/stars_new.png';
    $nr_ratings = rand(50,99);
    ?>

    <div class="user-ratings">
        <img src="<?php echo $stars_url; ?>">
        <span><?php echo $nr_ratings; ?> ocjena kupaca</span>
    </div>
	
	<span class="custom_top_seller_tag" style="background-color:#15950b">
           Velika potražnja!        
	</span>
<span class="custom_top_seller_tag" style="background-color:red">
           Besplatna dostava
	</span>
	
	<style>
	
	.product-summary .user-ratings img {
    max-width: 150px !important;
}
	
	.flickity-prev-next-button { opacity: 1; }
	.flickity-prev-prev-button { opacity: 1; }
	.flickity-prev-next-button svg { backround: white; }
	
	.flickity-button-icon { 
		background: white;
		padding: 5px 8px 5px 5px;
	}
	
	.product-main {
    padding: 15px 0 15px 0 !important;
}
	
	.product-short-description {
		margin-top: 13px !important;
	}
	
	.single-product .custom_top_seller_tag {
    font-size: small;
    color: #fff;
    background-color: red;
    border-radius: 15px;
    padding: 5px 15px;
    margin: 0 0 5px;
   
    display: inline-block;
}
	</style>


    <?php
}

add_action( 'woocommerce_single_product_summary', 'action_woocommerce_single_product_summary', 10, 2 );

// Shortcode for displaying product price
function product_price_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => null
    ), $atts, 'product_price' );

    if ( empty( $atts[ 'id' ] ) ) {
        return '';
    }

    $product = wc_get_product( $atts['id'] );

    if ( ! $product ) {
        return '';
    }

    return $product->get_price_html();
}

add_shortcode( 'product_price', 'product_price_shortcode' );

/**
 * Add a custom product data tab
 */
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {

    // Adds the new tab
    $tabs['shipping_tab'] = array(
        'title'     => __( 'DOSTAVA', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'shipping_tab_content'
    );

    // Adds the new tab
    $tabs['waranty_tab'] = array(
        'title'     => __( 'GARANCIJA', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'waranty_tab_content'
    );
	
		$this_id = get_the_ID();
	$navodila_file = get_field("product_pdf_navodila");
	//var_dump($navodila_file);
	
	
	if( $navodila_file != null && $navodila_file != "" && $navodila_file != false ) {
    
		$tabs['manuls_tab'] = array(
			'title'     => __( 'Upute za upotrebu', 'woocommerce' ),
			'priority'  => 50,
			'callback'  => 'manuals_tab_content'
		);
	
	}

    return $tabs;

}
function shipping_tab_content() {
    // The new tab content
    echo do_shortcode('[block id="shipping-tab"]');
}
function waranty_tab_content() {
    // The new tab content
    echo do_shortcode('[block id="waranty-tab"]');
}

function manuals_tab_content() {
    // The new tab content
	$this_id = get_the_ID();
	$navodila_file = get_field("product_pdf_navodila");
	$navodila_tekst = get_field("product_pdf_navodila_tekst");
	
	echo "<a class='pp-navodila-link' target=''download href='".$navodila_file."'>Preuzmite upute za ovaj proizvod (.pdf)</a>";
	echo "<br>";
	echo "<div style='margin-top: 15px;' class='pp-navodila'>";
	echo "<style>.pp-navodila p, .pp-navodila ul, .pp-navodila li, .pp-navodila a, .pp-navodila span {font-size: 13px;}
	.pp-navodila-link {
		color: black;
		background-color: #dcf1ff;
		text-align: center;
		font-size: large;
		padding: .5em 2em .5em 1em;
		display: inline-block;
		border: 1px solid black;
	}
	.pp-navodila li { margin-bottom: 0.2em!important; }
	.pp-navodila-link:hover { transition: 0.3s; opacity: 0.75; }
	
	</style>";
	echo $navodila_tekst;
	echo "</div>";
	
}



// Display order bump on cart page
function action_woocommerce_after_cart() {
    $items = get_field('order_bump_items');
    ?>
    <div class="order-bump-cart-box" style="float: right;width: 400px">
        <?php foreach ($items as $key => $item):
            $image_src = get_the_post_thumbnail_url($item->ID);
            $_product = wc_get_product($item->ID);
            $thumbnail_ids = $_product->get_gallery_image_ids();
            $regular_price = $_product->get_regular_price();
            $sale_price = $_product->get_sale_price();
            $max_percentage = ( ( $_product->get_regular_price() - $_product->get_sale_price() ) / $_product->get_regular_price() ) * 100;

            if ($_product->is_type('variable' )) {
                $variations = $_product->get_available_variations();
                $regular_price = $variations[0]['display_regular_price'];
                $sale_price = $variations[0]['display_price'];
                $max_percentage = ( $regular_price - $sale_price ) / $regular_price * 100;
            }
            ?>
            <div class="discount">
                -<?php echo round($max_percentage); ?>%
            </div>
            <div class="owl-carousel" id="order-bump-cart">
                <div class="item">
                    <div class="img-box">
                        <img src="<?php echo $image_src; ?>">
                    </div>
                </div>
                <?php foreach ($thumbnail_ids as $thumb_key => $thumb): ?>
                    <div class="item">
                        <div class="img-box">
                            <img src="<?php echo wp_get_attachment_image_src($thumb, 'full')[0]; ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="price-button-box">
                <p class="price">
                    <span class="sale-price">
                        <?php echo wc_price($sale_price); ?>
                    </span>
                    <span class="regular-price">
                        <?php echo wc_price($regular_price); ?>
                    </span>
                </p>
                <div class="add-to-cart">
                    <a href="<?php echo site_url(); ?>/?add-to-cart=<?php echo $item->ID ?>">Košarica</a>
                </div>
            </div>
            <h4><?php echo $item->post_title; ?></h4>
            <p><?php echo wp_trim_words(get_the_excerpt($item), 20); ?></p>

        <?php endforeach; ?>

    </div>
    <script type="text/javascript">
        jQuery(document).ready(function( $ ) {
            $('#order-bump-cart').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                items: 1,
            });
        });
    </script>
    <?
};

add_action( 'woocommerce_after_cart', 'action_woocommerce_after_cart', 10, 0 );

?>
