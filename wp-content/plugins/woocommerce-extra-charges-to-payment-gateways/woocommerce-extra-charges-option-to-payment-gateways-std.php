<?php
/*
Plugin Name: WooCommerce Extra Charges To Payment Gateway (Standard)
Plugin URI: http://www.mydealstm.com
Description: You can add extra fee for any payment gateways
Version: 1.0.12.9
Author: hemsingh1
Author URI: http://www.mydealstm.com
Text Domain: woocommerce-extra-charges-to-payment-gateways
Domain Path: /languages/
*/

/**
 * Copyright (c) `date "+%Y"` hemsingh1. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//updted

class WC_PaymentGateway_Add_extra_std_Charges{
    public function __construct(){
        $this -> current_gateway_title = '';
        $this -> current_gateway_extra_charges = '';
        add_action('admin_head', array($this, 'add_form_fields'));
        
        add_action( 'woocommerce_cart_calculate_fees', array( $this, 'calculate_totals' ), 10, 1 );

        //add_action( 'woocommerce_calculate_totals', array( $this, 'calculate_totals' ), 10, 1 );
   add_action( 'wp_enqueue_scripts',array($this,'load_my_script'));
   add_action('plugins_loaded', array($this, 'load_textdomain'));
    }


function load_textdomain() {
 
     load_plugin_textdomain('woocommerce-extra-charges-to-payment-gateways', false, dirname(plugin_basename(__FILE__)) . '/languages/');
 
 }

    function load_my_script(){

        wp_enqueue_script( 'wc-add-extra-charges', $this->plugin_url() . '/assets/app.js', array('wc-checkout'), false, true );
    }
//ttttjjjj

    function add_form_fields(){
        global $woocommerce;
         // Get current tab/section
        $current_tab        = ( empty( $_GET['tab'] ) ) ? '' : sanitize_text_field( urldecode( $_GET['tab'] ) );
        $current_section    = ( empty( $_REQUEST['section'] ) ) ? '' : sanitize_text_field( urldecode( $_REQUEST['section'] ) );

        if($current_tab == 'checkout' && $current_section!='' && ($current_section=='bacs'||$current_section=='cod'||$current_section=='cheque')){
            $gateways = $woocommerce->payment_gateways->payment_gateways();
            foreach($gateways as $gateway){
                if( (strtolower(get_class($gateway))=='wc_gateway_bacs' || strtolower(get_class($gateway))=='wc_gateway_cheque' || strtolower(get_class($gateway))=='wc_gateway_cod') && strtolower(get_class($gateway))=='wc_gateway_'.$current_section){
                    $current_gateway = $gateway -> id;
                   $extra_charges_id = 'woocommerce_'.$current_gateway.'_extra_charges';
                    $extra_charges_type = $extra_charges_id.'_type';
                    $extra_charges_label = $extra_charges_id.'_label';
                    $extra_charges_threshold = $extra_charges_id.'_threshold';
            $extra_charges_maxamt = $extra_charges_id.'_maxamt';
                    $extra_charges_tax_lbl = $extra_charges_id.'_lbl';

        $extra_charges_custompct_lbl = $extra_charges_id.'_custompct_lbl';
        $extra_charges_customfix_lbl = $extra_charges_id.'_customfix_lbl';
                    if(isset($_REQUEST['save'])){
                        update_option( $extra_charges_id, $_REQUEST[$extra_charges_id] );
                        update_option( $extra_charges_type, $_REQUEST[$extra_charges_type] );
                        
                        update_option( $extra_charges_label, $_REQUEST[$extra_charges_label] );
                       
                        update_option( $extra_charges_threshold, $_REQUEST[$extra_charges_threshold] );
 update_option($extra_charges_maxamt, $_REQUEST[$extra_charges_maxamt]);
                         
                        update_option($extra_charges_custompct_lbl, $_REQUEST[$extra_charges_custompct_lbl] );

            
                        update_option( $extra_charges_customfix_lbl, $_REQUEST[$extra_charges_customfix_lbl] );

                      if(isset($_REQUEST[$extra_charges_tax_lbl])){
                        update_option( $extra_charges_tax_lbl,1);
}else{
 update_option( $extra_charges_tax_lbl, 0 );
}
                    }
                    $extra_charges = get_option( $extra_charges_id);
                    $extra_charges_cust = get_option( $extra_charges_label);
$extra_charges_maxamt_v = get_option($extra_charges_maxamt);
                    $extra_charges_type_value = get_option($extra_charges_type);
                     $extra_charges_tax  = get_option($extra_charges_tax_lbl);
 $extra_charges_custompct = get_option($extra_charges_custompct_lbl);
 $extra_charges_customfix = get_option($extra_charges_customfix_lbl);

                  if(get_option($extra_charges_threshold)> 0){

                    $extra_charges_thresh_cust = get_option($extra_charges_threshold);

}else{

                    $extra_charges_thresh_cust = 0;
}

                }
            }

            ?>
            <script>
            jQuery(document).ready(function($){
      
                $data = '<h4>Add Extra Charges</h4><table class="form-table">';
                $data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc">Extra Charges</th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_id?>" id="<?php echo $extra_charges_id?>" type="text" value="<?php echo $extra_charges?>"/>';
                $data += '<br /></fieldset></td></tr>';
                $data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc"><?php echo __('Custom label for Extra Charges (optional)', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_label?>" id="<?php echo $extra_charges_label?>" type="text" value="<?php echo $extra_charges_cust?>" placeholder="My Custom label"/>';
                $data += '<br /></fieldset></td></tr>';

//threshold

                $data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc new"><?php echo __('Threshold amount after which no extra charge is levied. (Set 0 to make extracharge applicable for all amounts)', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_threshold?>" id="<?php echo $extra_charges_threshold?>" type="text" value="<?php echo $extra_charges_thresh_cust?>" placeholder="0"/>';
                $data += '<br /></fieldset></td></tr>';


//max amt

$data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc new"><?php echo __('Maximum Extracharge (Set 0 to have no maximum limit on extracharge)', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_maxamt?>" id="<?php echo $extra_charges_maxamt?>" type="text" value="<?php echo $extra_charges_maxamt_v?>" placeholder="0"/>';
                $data += '<br /></fieldset></td></tr>';



//tax

    $data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc"><?php echo __('Include Tax', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_tax_lbl?>" id="<?php echo $extra_charges_tax_lbl?>" type="checkbox" value="<?php echo $extra_charges_tax?>" <?php echo ($extra_charges_tax==1)? 'checked=checked':''?> />';
                $data += '<br /></fieldset></td></tr>';


// tax ends
                $data += '<tr valign="top">';
                $data += '<th scope="row" class="titledesc">Extra Charges Type</th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<select name="<?php echo $extra_charges_type?>" id="sel"><option <?php if($extra_charges_type_value=="add") echo "selected=selected"?> value="add">Total Add</option>';
                $data += '<option <?php if($extra_charges_type_value=="percentage") echo "selected=selected"?> value="percentage">Total % Add</option>';
                 $data += '<option <?php if($extra_charges_type_value=="both") echo "selected=selected"?> value="both"> Both (Total Custom Fixed + Total Custom % Add) </option>';
$data += '<br /></fieldset></td></tr>';

$data += '<tr valign="top" id="cfd">';
                $data += '<th scope="row" class="titledesc"><?php echo __('Total Custom Fixed', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_customfix_lbl?>" id="<?php echo $extra_charges_customfix_lbl?>" type="text" value="<?php echo $extra_charges_customfix?>"  />';
                $data += '<br /></fieldset></td></tr>';


$data += '<tr valign="top" id="cpct">';
                $data += '<th scope="row" class="titledesc"><?php echo __('Total Custom % Add', 'woocommerce-extra-charges-to-payment-gateways');?></th>';
                $data += '<td class="forminp">';
                $data += '<fieldset>';
                $data += '<input style="" name="<?php echo $extra_charges_custompct_lbl?>" id="<?php echo $extra_charges_custompct_lbl?>" type="text" value="<?php echo $extra_charges_custompct?>"  />';



                $data += '<br /></fieldset></td></tr></table>';
                $('.form-table:last').after($data);


$('#sel').change(function() {
    var selected = $(this).val();
    if(selected == 'both'){
      $('#cfd,#cpct').show();
    }
    else{
      $('#cfd,#cpct').hide();
    }
});

 var selected2 = $('#sel').val();
    if(selected2 == 'both'){
      $('#cfd,#cpct').show();
    }
    else{
      $('#cfd,#cpct').hide();
    }




            });


</script>
<?php
}
}

//Modified functions to include fee in email
public function calculate_totals( $totals ) {
    global $woocommerce;
    $available_gateways = $woocommerce->payment_gateways->get_available_payment_gateways();
    $current_gateway = '';
    if ( ! empty( $available_gateways ) && !is_cart() ) {
           // Chosen Method
        if ( isset( $woocommerce->session->chosen_payment_method ) && isset( $available_gateways[ $woocommerce->session->chosen_payment_method ] ) ) {
            $current_gateway = $available_gateways[ $woocommerce->session->chosen_payment_method ];
        } elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
            $current_gateway = $available_gateways[ get_option( 'woocommerce_default_gateway' ) ];
        } else {
            $current_gateway =  current( $available_gateways );

        }
    }
    if($current_gateway!=''){
        $current_gateway_id = $current_gateway -> id;
        $extra_charges_id = 'woocommerce_'.$current_gateway_id.'_extra_charges';
        $extra_charges_type = $extra_charges_id.'_type';
        $extra_charges_cust = $extra_charges_id.'_label';
        $extra_charges_threshold = $extra_charges_id.'_threshold';
        $extra_charges_tax_lbl = $extra_charges_id.'_lbl';
  $extra_charges_maxamt = $extra_charges_id.'_maxamt';
        $extra_charges_custompct_lbl = $extra_charges_id.'_custompct_lbl';
        $extra_charges_customfix_lbl = $extra_charges_id.'_customfix_lbl';
        $extra_charges = (float)get_option( $extra_charges_id);
        $extra_charges_type_value = get_option($extra_charges_type);
        $extra_charges_label_value = get_option($extra_charges_cust);
        $extra_charges_threshold_value = get_option($extra_charges_threshold);
      $extra_charges_maxamt_v = get_option($extra_charges_maxamt);
        $extra_charges_tax = get_option($extra_charges_tax_lbl);
 $extra_charges_custompct = get_option($extra_charges_custompct_lbl);
 $extra_charges_customfix = get_option($extra_charges_customfix_lbl);
        if($extra_charges){
            if($extra_charges_type_value=="percentage"){
           $decimal_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
     $thousands_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );

       $t1 = ($totals -> cart_contents_total*$extra_charges)/100;
                //$totals -> cart_contents_total = $totals -> cart_contents_total + round(($totals -> cart_contents_total*$extra_charges)/100,2);
$t3 = ($totals -> cart_contents_total*0.1)/100;

            }elseif($extra_charges_type_value=="add"){
//$totals -> cart_contents_total = $totals -> cart_contents_total + $extra_charges;
$t1 =  $extra_charges;
            }else{

$t1 = $extra_charges_customfix + (($totals -> cart_contents_total* $extra_charges_custompct)/100);

}

if($extra_charges_maxamt_v > 0 && $t1 > $extra_charges_maxamt_v){
$t1 =  $extra_charges_maxamt_v;
$v1 = $extra_charges;
//$extra_charges = "Maximum extracharge applied instead of ".$v1;
$extra_charges = __('Maximum extracharge applied instead of ', 'woocommerce-extra-charges-to-payment-gateways').$v1;
}

            $this -> current_gateway_title = $current_gateway -> title;
            $this -> current_gateway_extra_charges = $extra_charges;
            $this -> current_gateway_extra_charges_type_value = $extra_charges_type_value;


   //$t5 = ($extra_charges_type_value=="percentage"? ' - '.$extra_charges.'%':'');

if($extra_charges_type_value=="percentage"){
$t5 = ' - '.$extra_charges.'%';

}elseif($extra_charges_type_value=="add"){

if(isset($extra_charges_label_value)&& strlen($extra_charges_label_value)>2){

$t5 = "   ";

}else{

$t5 =  '- '.__('Fixed', 'woocommerce-extra-charges-to-payment-gateways');
}

}else{
$t5 = ' ('.get_woocommerce_currency_symbol(get_option('woocommerce_currency')).$extra_charges_customfix. __('Fixed', 'woocommerce-extra-charges-to-payment-gateways').' + '.$extra_charges_custompct.'%'.')';
}




// $woocommerce->cart->
//$totals->add_fee( __( $this -> current_gateway_title.'  Extra Charges   '.$t5),$t1);
if(isset($extra_charges_label_value) && strlen($extra_charges_label_value)>2){
//$t6 =  $extra_charges_cust;
$t6 = $extra_charges_label_value ;
}else{
//$t6 = $extra_charges_cust;
$t6 = $this -> current_gateway_title.'  Extra Charges ';
}

if($extra_charges_tax==1){
$ta1 = true;
}else{
$ta1 = false;
}

if($extra_charges_threshold_value > 0){

if($totals -> cart_contents_total < $extra_charges_threshold_value){

$woocommerce->cart->add_fee(__($t6.$t5),$t1,$ta1);

}else{

$extra_charges = 0;
   //$t5 = ($extra_charges_type_value=="percentage"? $extra_charges.'%':'Fixed');




$woocommerce->cart->add_fee(__($t6.$t5),0,$ta1);

}

}else{

$woocommerce->cart->add_fee(__($t6.$t5),$t1,$ta1);

}


        }

    }
    return $totals;
}

//option to exclude products for extracharge 

function add_payment_gateway_extra_charges_row(){
    ?>
    <tr class="payment-extra-charge newcls">
        <th><?php echo $this->current_gateway_title . ' ' . __('Extra Charges', 'woocommerce-extra-charges-to-payment-gateways');?></th>
        <td><?php if($this->current_gateway_extra_charges_type_value=="percentage"){
            echo $this -> current_gateway_extra_charges.'%';
        }else{
         echo woocommerce_price($this -> current_gateway_extra_charges);
     }?></td>
 </tr>
 <?php
}

/**
     * Get the plugin url.
     *
     * @access public
     * @return string
     */
    public function plugin_url() {

        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }


    /**
     * Get the plugin path.
     *
     * @access public
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

}
new WC_PaymentGateway_Add_extra_std_Charges();