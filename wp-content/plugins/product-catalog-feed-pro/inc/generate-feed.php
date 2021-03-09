<?php
$wpwoofeed_wpml_langs='';
$wpwoofeed_wpml_debug='';
require_once dirname(__FILE__).'/common.php';
require_once "Encode.php";
function wpwoofeed_currency_code ($lang){
    global $wpwoofeed_wpml_debug,$woocommerce_wpml;
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code:".$lang);
    if( empty($lang)) return "";
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code: lang not empty");
    if (!WoocommerceWpwoofCommon::isActivatedWMPL() ) return "";
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code: wpml presents");
   
    if ( empty($woocommerce_wpml->settings)
        || empty($woocommerce_wpml->settings['enable_multi_currency'])
    ) return "";
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code: wpml wpcl enabled");

    if(WPWOOF_DEBUG){
        wpwoofStoreDebug($wpwoofeed_wpml_debug,"settings");
        wpwoofStoreDebug($wpwoofeed_wpml_debug,$woocommerce_wpml->settings['currency_options']);
    }
    foreach( $woocommerce_wpml->settings['currency_options'] as $currency => $val){
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,$val['languages']);
        if( !empty( $val['languages'][strtolower($lang)] ) ){
            if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code found:".$currency);
            return $currency;
        }else if( !empty( $val['languages'][strtoupper($lang)] ) ){
            if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code found:".$currency);
            return $currency;
        }
    }
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_currency_code: lang not present");
    return "";
}
function wpwoofeed_currency_rate ($code){
    if( empty($code)) return false;
    if (!WoocommerceWpwoofCommon::isActivatedWMPL() ) return false;
    global $woocommerce_wpml;
    if( empty($woocommerce_wpml->settings)
        || empty($woocommerce_wpml->settings['enable_multi_currency'])
    ) return false;
    global $woocommerce_wpml;
    foreach( $woocommerce_wpml->settings['currency_options'] as $currency => $val){
        if($currency == $code  ){
            if(empty($val['rate'])) return false;
            return $val['rate'];
        }
    }
    return false;
}
/* fix WPML BUG  in cron_schedule */
function wpwoofeed_query_vars_filter( $vars ){
    global $wpwoofeed_wpml_debug,$wpwoofeed_wpml_langs;   
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_query_vars_filter START:\n".print_r($vars,true));
    $vars =  preg_replace("/t\.language\_code \= \'\w{2}\'+/", ' t.language_code IN ' . $wpwoofeed_wpml_langs, $vars);
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_query_vars_filter FINAL:\n".print_r($vars,true));
    remove_filter('posts_where', 'wpwoofeed_query_vars_filter');    
    return $vars;
}
function wpwoofeed_get_id($product){
    if(empty($product)) return null;
    if( method_exists(  $product, 'get_id') ) return $product->get_id();
    if( property_exists($product, 'ID'    ) ) return $product->ID;
    if( property_exists($product, 'id'    ) ) return $product->id;
    return null;
}
function wpwoofeed_generate_feed($data, $type = 'xml', $filepath, $info = array()){
    global $woocommerce, $woocommerce_wpml;
    global $sitepress,$wpwoofeed_wpml_langs,$wpwoofeed_wpml_debug;
    global $woocommerce_wpwoof_common;
    global $store_info;
    global $wpwoofeed_settings;
    global $wpwoofeed_type;
    global $wp_query, $wpdb, $post, $_wp_using_ext_object_cache;    

    $feedStatus = $woocommerce_wpwoof_common->get_feed_status($data['edit_feed']);
   
    $upload_dir = wpwoof_feed_dir($data['feed_name'], $type );
    $filepath = $upload_dir['path'];
    $wpwoofeed_wpml_debug = $filepath.".log";

    wp_schedule_single_event( time() + 60, 'wpwoof_generate_feed', array( $data['edit_feed'] ) );
    if( empty( $feedStatus["type"] ) ) $feedStatus["type"]=$type; //started
    if ( time() - $feedStatus['time'] < 60 ) {
        if(WPWOOF_DEBUG){           
            wpwoofStoreDebug($wpwoofeed_wpml_debug,"FEED IN PROGRESS:");
        }
        return true;
    }
    if(WPWOOF_DEBUG){  
        trace("feedStatus:");
        trace($feedStatus);
    }
    
    $type = $feedStatus["type"];  
    
   
    if(WPWOOF_DEBUG){
        if(empty($feedStatus['products_left'])  ){ 
            @unlink($wpwoofeed_wpml_debug);
        }
        wpwoofStoreDebug($wpwoofeed_wpml_debug,"START GENERATE:");
    }

    $feed_data = $data;
    

    try {
        if (!empty($data['edit_feed'])) {
            $feed_data['status_feed'] = 'starting';
            wpwoof_update_feed(serialize($feed_data), $feed_data['edit_feed'],true);
        }
        $aExistsProds = array();

        $currencyCode="";
        $currencyRate=1.0;
        if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
            if(WPWOOF_DEBUG){
                wpwoofStoreDebug($wpwoofeed_wpml_debug,"WPML s active");
            }
            if(WPWOOF_DEBUG){               
                /*trace($data); */              
            }
            if (empty($data['feed_use_lang'])) $data['feed_use_lang'] = ICL_LANGUAGE_CODE;
            $general_lang = ICL_LANGUAGE_CODE;
            $sitepress->switch_lang($data['feed_use_lang'],true);
           
            if(WPWOOF_DEBUG){
                wpwoofStoreDebug($wpwoofeed_wpml_debug,"FEED SETTINGS LANG:".$data['feed_use_lang']);
            }
            if($data['feed_use_lang']=='all'){
                $wpwoofeed_wpml_langs=' (';
                $zp="";
                $aLanguages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
                foreach($aLanguages as $lang){
                    $wpwoofeed_wpml_langs.=$zp."'".$lang['language_code']."'";
                    $zp=",";
                }
                $wpwoofeed_wpml_langs.=") ";

            }else{
                $wpwoofeed_wpml_langs=" ('".$data['feed_use_lang']."') ";
            }
            
            if(!empty($data['feed_use_currency'])){
                    /* Custom setting currency */
                    $currencyCode = $data['feed_use_currency'];
                    $currencyRate = wpwoofeed_currency_rate($data['feed_use_currency']);
                    $client_currency = $woocommerce_wpml->multi_currency->get_client_currency();
                    //$woocommerce_wpml->multi_currency->set_client_currency( $currencyCode );
                    if(WPWOOF_DEBUG){
                        wpwoofStoreDebug($wpwoofeed_wpml_debug, "client_currency:".$client_currency."|".$currencyCode);
                    }
            } else if( empty($data['feed_use_currency']) ) {
                    /* setting currency to language */
                    $currencyCode = wpwoofeed_currency_code($data['feed_use_lang']);
                    $currencyRate = wpwoofeed_currency_rate($currencyCode);
            }
            if(!$currencyRate)  $currencyRate=1.0;
            
            if(WPWOOF_DEBUG){
                wpwoofStoreDebug($wpwoofeed_wpml_debug,"TO SQL:".$wpwoofeed_wpml_langs);
                wpwoofStoreDebug($wpwoofeed_wpml_debug,"CURRENCY:".$currencyCode);
                wpwoofStoreDebug($wpwoofeed_wpml_debug,"RATE:".$currencyRate);
            }


        }

        $currencyCode = !$currencyCode ? get_woocommerce_currency() : $currencyCode;
        if(WPWOOF_DEBUG){ wpwoofStoreDebug($wpwoofeed_wpml_debug,"CURRENCY FINAL:".$currencyCode);  }
        $feed_name = sanitize_text_field($data['feed_name']);
        $added_time = $data['added_time'];
        $data = array_merge(array(
            'feed_filter_sale' => 'all',
            'feed_filter_stock' => 'all',
            'feed_variable_price' => 'small',
        ), $data);
        $remove_variations = !empty($data['feed_remove_variations']);
        $enclosure = '"';
        $delimiter = "\t";
        $filepath.=".wpwoof.tmp";
        $feedStatus['file']=$filepath;
        $filePresent = file_exists( $filepath ) ? true : false;
        $filep = false;
        if ($type == 'csv') {
            
            $filep = fopen($filepath, "a+");
            if(!is_writable($filepath)) throw new Exception("Can not write to '".$filepath."' file.");


            $info = array_merge(array('delimiter' => 'tab', 'enclosure' => 'double'), $info);
            $delimiter = $info['delimiter'];
            if ($delimiter == 'tab') {
                $delimiter = "\t";
            }
            $enclosure = $info['enclosure'];
            if ($enclosure == "double")
                $enclosure = chr(34);
            else if ($enclosure == "single")
                $enclosure = chr(39);
            else
                $enclosure = '"';
            $batch = 0;
        } else if( !empty($data['feed_type']) && $data['feed_type']!= "adsensecustom"    ) {
            $filep = fopen($filepath, "a+");
            if(!is_writable($filepath)) throw new Exception("Can not write to '".$filepath."' file.");
        }


        $wpwoofeed_type = $type;
        $wpwoofeed_settings = $data;


        $field_rules = wpwoof_get_product_fields();
        $all_fields = $data['field_mapping'];
        $fields = array();
        /* Filter fields only for feed type */
        if (count($all_fields)) foreach ($all_fields as $fld => $val) {

            if (@in_array($data['feed_type'], $field_rules[$fld]['feed_type'])) {
                $fields[$fld] = $val;
            }
        }

        $categories = array();
        if (!empty($data['feed_category'])) {
            foreach ($data['feed_category'] as $key => $category_id) {
                $categories[] = $category_id;
            }
        }

        $store_info                     = new stdClass();
        $store_info->feed_type          = (!empty($data['feed_type'])) ? $data['feed_type'] : 'all'; /*'facebook'*/
        $store_info->site_url           = home_url('/');
        $store_info->feed_url_base      = home_url('/');
        $store_info->blog_name          = get_option('blogname');
        $store_info->charset            = get_option('blog_charset');
        $store_info->currency           = ( !empty($data['field_mapping']['remove_currency']) ) ? '' : $currencyCode;
        $store_info->default_currency   = get_woocommerce_currency();
        $store_info->currencyRate       = $currencyRate;
        $store_info->weight_units       = get_option('woocommerce_weight_unit');
        $store_info->base_country       = $woocommerce->countries->get_base_country();
        $store_info->taxes              = $wpdb->get_results( "SELECT tax_rate_country as shcode, `tax_rate_class` as `class`, `tax_rate_id` as `id`,`tax_rate` as `rate`, `tax_rate_name` as `name` FROM {$wpdb->prefix}woocommerce_tax_rates  Order By tax_rate_class, tax_rate_country ",ARRAY_A );
        $store_info                     = apply_filters('wpwoof_store_info', $store_info);
        $store_info->currency_list      = ( is_object($woocommerce_wpml) && method_exists ( $woocommerce_wpml->multi_currency , 'get_currencies' ) )  ? $woocommerce_wpml->multi_currency->get_currencies('include_default = true') : null;
        $store_info->item_group_id      =  null;

        /* GET PRICE FORMAT DATA FROM WC CONFIG */
        $store_info->woocommerce_price_decimal_sep =  get_option('woocommerce_price_decimal_sep',',');
        $store_info->woocommerce_price_display_suffix = get_option('woocommerce_price_display_suffix',''); 
        $store_info->woocommerce_price_num_decimals  = get_option('woocommerce_price_num_decimals','2'); 
        $store_info->woocommerce_price_thousand_sep  = get_option('woocommerce_price_thousand_sep','.'); 
        $store_info->woocommerce_price_round  = "disabled";//"down" 
        $store_info->woocommerce_price_rounding_increment = 1;
        if(WPWOOF_DEBUG){ 
            wpwoofStoreDebug($wpwoofeed_wpml_debug,"SETS PRICE ROUND FOR:".$store_info->currency);
        }
        
        if ( WoocommerceWpwoofCommon::isActivatedWMPL() && $store_info->currency 
             && is_object($woocommerce_wpml) 
             //&&  method_exists ( $woocommerce_wpml , 'settings' )              
             && !empty($woocommerce_wpml->settings["currency_options"][$store_info->currency])
             && $woocommerce_wpml->settings["currency_options"][$store_info->currency]['rounding']!="disabled"
             
             ){
                if(WPWOOF_DEBUG){                     
                    wpwoofStoreDebug($wpwoofeed_wpml_debug,"INITS IN IF PRICE ROUND FOR:".$store_info->currency);  
                }
                $currSettings = $woocommerce_wpml->settings["currency_options"][$store_info->currency];
                $store_info->woocommerce_price_decimal_sep   = !empty($currSettings['decimal_sep']) ? $currSettings['decimal_sep']  : $store_info->woocommerce_price_decimal_sep;
                $store_info->woocommerce_price_num_decimals  = !empty($currSettings['num_decimals']) ? $currSettings['num_decimals'] : $store_info->woocommerce_price_num_decimals;
                $store_info->woocommerce_price_thousand_sep  = !empty($currSettings['thousand_sep']) ? $currSettings['thousand_sep'] : $store_info->woocommerce_price_thousand_sep;
                $store_info->woocommerce_price_round = !empty($currSettings['rounding']) ? $currSettings['rounding'] : $store_info->woocommerce_price_round;
                $store_info->woocommerce_price_rounding_increment= !empty($currSettings['rounding_increment']) ? $currSettings['rounding_increment'] : $store_info->woocommerce_price_rounding_increment;
       
        }
        
        if(WPWOOF_DEBUG){ trace($store_info->taxes); }

        if ( isset( $data['feed_use_lang'] ) ) {
            $store_info->feed_language = $data['feed_use_lang'];
        }

        $store_info->feed_url = $store_info->feed_url_base;

        if (!empty($store_info->base_country) && substr('US' == $store_info->base_country, 0, 2)) {
            $US_feed = true;
            $store_info->US_feed = true;
        } else {
            $store_info->US_feed = false;
        }

        $columns = array();

        $wpwoof_feed = -1;
        $wpwoof_term = $categories;
        unset($categories);

        $tax_query = array();

        if (empty($data['feed_category_all']) && $wpwoof_term) {

            $uncategorized = in_array(0, $wpwoof_term) ? true : false;
            if ($uncategorized) {
                unset($wpwoof_term[array_search(0, $wpwoof_term)]);
            }
            $categories_tax = array();
            // Get the products in all the categories
            if (count($wpwoof_term) > 0) {
                $categories_tax[] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $wpwoof_term,
                );

            }
            // Get the products from the Uncategorized

            if ($uncategorized) {
                if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
                    $sitepress->switch_lang('all',true);
                }
                $terms = get_terms('product_cat', 'orderby=name&order=ASC&hide_empty=1');

                $array_terms = array();
                if (!empty($terms) && count($terms) > 0) {

                    foreach ($terms as $_term) {
                        $array_terms[] = $_term->slug;
                    }


                    $categories_tax['relation'] = 'OR';
                    $categories_tax[] = array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $array_terms,
                        'operator' => 'NOT IN'
                    );
                }
                if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
                    $sitepress->switch_lang($data['feed_use_lang'],true);
                }
            }

            if (!empty($categories_tax) && is_array($categories_tax) && count($categories_tax) > 0) {
                $tax_query[] = $categories_tax;
            }
            unset($categories_tax);
        }
        unset($wpwoof_feed, $wpwoof_term);

        if (!empty($data['feed_filter_tags'])) {
            $tags = explode(',', $data['feed_filter_tags']);
            $tags_arr = array();
            foreach ($tags as $tag) {
                $term = get_term_by('name', $tag, 'product_tag');
                if ($term === false) {
                    $term = get_term_by('slug', $tag, 'product_tag');
                }
                if ($term !== false) {
                    $tags_arr[] = $term->term_id;
                }
            }
            if (!empty($tags_arr)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_tag',
                    'field' => 'id',
                    'terms' => $tags_arr,
                    'operator' => 'IN'
                );
            }
        }

        if ($data['feed_filter_sale'] == 'sale') {
            $args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
        } elseif ($data['feed_filter_sale'] == 'notsale') {
            $args['post__not_in'] = array_merge(array(0), wc_get_product_ids_on_sale());
        }

        if (!empty($data['feed_filter_product_type']) && is_array($data['feed_filter_product_type'])) {
            $product_types = wc_get_product_types();
            if (count($data['feed_filter_product_type']) < count($product_types)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_type',
                    'field' => 'slug',
                    'terms' => $data['feed_filter_product_type'],
                    'operator' => 'IN'
                );
            }
        }
        $meta_query = array();
        $feed_filter_stock = '';
        if ($data['feed_filter_stock'] == 'instock' || $data['feed_filter_stock'] == 'outofstock') {
            $feed_filter_stock = $data['feed_filter_stock'];
            $meta_query[] = array(
                'key' => '_stock_status',
                'value' => $data['feed_filter_stock'],
                'operator' => '='
            );
        }

        $args['tax_query'] = $tax_query;
        $meta_query['relation'] = 'AND';
        $args['meta_query'] = $meta_query;
        //$args['meta_query'] = $meta_query;

        unset($tax_query);
        if($feedStatus['products_left'] && count($feedStatus['products_left'])>0 ){            
            $args = array(                
                'post__in' => $feedStatus['products_left']
             );
           
        } 

        $args['post_type'] = 'product';
        $args['post_status'] = 'publish';
        $args['fields'] = 'ids';
        $args['order'] = 'ASC';
        $args['orderby'] = 'ID';
        $args['posts_per_page'] = -1;//110;
        //$args['paged'] = 1;
        $wpwoof_limit = false;

        $output_count = 0;
        $data = '';
        //$query_to_update = "UPDATE {$wpdb->postmeta} SET meta_value=%s WHERE meta_key=%s AND post_id IN ";
        if (!$filePresent  && $store_info->feed_type != "adsensecustom" && $type == 'xml') {
            $header = '';
            $item = '';
            $footer = '';

            $header .= "<?xml version=\"1.0\" encoding=\"" . $store_info->charset . "\" ?>\n";
            $header .= "<rss xmlns:g=\"http://base.google.com/ns/1.0\" version=\"2.0\">\n";
            $header .= "  <channel>\n";
            $header .= "    <title><![CDATA[" . $store_info->blog_name . " Products]]></title>\n";
            $header .= "    <link><![CDATA[" . $store_info->site_url . "]]></link>\n";
            $header .= "    <description>WooCommerce Product List RSS feed</description>\n";
            fwrite($filep, $header);
            fflush($filep);
            unset($header);
        }

        
      
        if (WoocommerceWpwoofCommon::isActivatedWMPL()) {  
            /* fix WPML BUG  in cron_schedule */
            add_filter('posts_where', 'wpwoofeed_query_vars_filter', 99);
        }
      

        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"WP_QUERY: args:".print_r($args,true));
        
        $products = new WP_Query($args);

        if(WPWOOF_DEBUG) trace("SQL:".$products->request); 

        if(empty($feedStatus['total_products'])) {
            $feedStatus['total_products']=$products->post_count;
        }
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"WP_QUERY: products:".print_r($products,true));

        $args['posts_per_page'] = -1;
        $pages  = $products->max_num_pages;
        if(empty($pages)){            
            $pages  = ceil($products->post_count/100.0);
        }
        $columns_name = false;

        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PAGES:".$pages );
        

        /* before render fields need check tax value include tax or not to price*/
        $wpwoofeed_settings["tax_rate_tmp_data"] = '';

        if (isset($fields['tax']['value'])) {
            $prices_include_tax = get_option("woocommerce_prices_include_tax");
            $tax_based_on       = get_option("woocommerce_tax_based_on");
            /*
                prices_include_tax:yes
                tax_based_on: shipping
                fields['tax']['value']:true
                $wpwoofeed_settings["tax_rate_tmp_data"]: target:DE-31
            */

            switch ($fields['tax']['value']) {
                case "false" : /* Exclude */
                    if ($prices_include_tax == "yes") {
                        $wpwoofeed_settings["tax_rate_tmp_data"] = "whithout";
                    }
                    break;
                case 'true':/* Include */
                    if ($prices_include_tax == "yes") {
                        if ($tax_based_on == 'shipping' || $tax_based_on == 'billing') {
                            //то сначала отнимаем от цены величину налога BaseLocation, а затем прибавляем к базовой цене величину налога TargetCountry - и выводим в фид.
                            $wpwoofeed_settings["tax_rate_tmp_data"] = "target:" . $fields['tax_countries']['value'];
                        } else { /* base */
                            //Еcли установлено "Shop base address" - то ничего не добавляем, берем цену как есть и выводим в фид (потому что цена уже введена с учетом локального налоша).

                        }
                    } else {
                        if ($tax_based_on == 'shipping' || $tax_based_on == 'billing') {
                            //Если установлено "Customer shipping/billing address" 
                            //- то прибавляем к цене величину налога TargetCountry - и выводим в фид.
                            $wpwoofeed_settings["tax_rate_tmp_data"] = "addtarget:" . $fields['tax_countries']['value'];
                        } else {
                            // Ели установлено "Shop base address" - то прибавляем к цене 
                            // величину налога BaseLocation (вчера вроде видел в твоем коде 
                            // $store_info->base_country) 
                            // - и выводим в фид. Т.е. настройки вукоммерса не оверрайдим, 
                            // если нужно выводить с налогом - то выводим с тем налогом, 
                            // который настроен в вукоммерсе.
                            $wpwoofeed_settings["tax_rate_tmp_data"] = "addbase";
                            /** Если налога нет в цене */
                            if(get_option("woocommerce_prices_include_tax")=="no" ) {
                                $wpwoofeed_settings["tax_rate_tmp_data"] = "addtarget:" . $fields['tax_countries']['value'];
                            }


                        }
                    }
                    break;
            }
        }
       
        $feedStatus['products_left'] = $products->posts;
        if(WPWOOF_DEBUG){ 
            trace("feedStatus['products_left']");
            trace($feedStatus['products_left']);
        }
      

        /*for ($i = 1; $i <= $pages; $i++) {*/
           
            
            if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"CURRENT PAGE:".$i );           
            $products = ( isset($products->posts) && is_array($products->posts) ) ? $products->posts : $products->get_posts();
            if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"POSTS:".print_r($products,true) );
            
           
            
            
            $aExistsProds = $feedStatus["parsed_product_ids"];
            
            foreach ($products as $id_post => $post) {
                   
                $product = wpwoofeed_load_product($post);
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRODUCT:".wpwoofeed_get_id($product));
                $data = "";
                $wpwoofeed_settings["product_tmp_data"] = array("product"=>$product);
               
                $prices =  wpwoofeed_generate_prices_for_product( $product );
               
                /*
                $prices = new stdClass();

                $price = $product->get_regular_price();
                $prices->sale_price = null;
                $prices->regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                $prices->sale_price = $sale_price;
                */
                
                //for SVI gallery plugin
                $prod_images = method_exists($product,'get_gallery_image_ids') ? $product->get_gallery_image_ids() : null;

                $wpwoofeed_settings["prod_images"] = null;
                if($prod_images && count($prod_images)>0){
                    foreach($prod_images as $key=>$val){
                            $woosvi_slug = get_post_meta($val, 'woosvi_slug', true);
                            if($woosvi_slug){
                                $prod_images[$key] = array($val,$woosvi_slug);
                            }else{
                                unset($prod_images[$key]);
                            }
                    }

                    if(count($prod_images)>0){
                        $wpwoofeed_settings["prod_images"] = $prod_images;
                    }

                }


              
                
                
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRODUCT PRICE");
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,$prices);
                if ($sale_price != '') {
                    $prices->sale_price = $sale_price;
                }
                $parent_prices = $prices;

                // $prices->sale_price = null;
                // $prices->regular_price = null;
                //guard for multilanguage plugin
                if (in_array(wpwoofeed_get_id($product), $aExistsProds)) continue;
                $_var = get_post_meta( wpwoofeed_get_id( $product ), 'wpfoof-explude-product', true );
                if( !empty($_var)) continue;
                
                

                if ($product->has_child() && !$remove_variations) {
                    $fields['item_group_id']['define'] = wpwoofeed_get_id($product);
                }else{
                    $fields['item_group_id']['define'] = null;
                }

                $store_info->item_group_id = $fields['item_group_id']['define'];
                if($store_info->feed_type == "adsensecustom" ) unset($fields['item_group_id']['define']);
                if(WPWOOF_DEBUG){
                   
                    wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICES START:".print_r($parent_prices,true));
                }
                $children_count  = 0;
                $children_output = 0;
                if ($product->has_child()) {
                    $is_parent = true;
                    $children=null;
                    if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
                        $aRS = $wpdb->get_results ( "select id from ".$wpdb->posts." where post_parent='".wpwoofeed_get_id($product)."' and post_type='product_variation' and post_status='publish'", ARRAY_A);
                        $children= array();
                        foreach($aRS as $row) $children[]=$row['id'];                        
                    } else {
                        $children = $product->get_children(); 
                    }
                    $children_count = count( $children );
                    $children_output = 0;

                    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRODUCT:".wpwoofeed_get_id($product)." HAS CHILDREN");
                    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,$children);
                    $my_counter = 0;
                    $first_child = true;
                    $aChildPrices = array();
                    $wpwoofeed_settings["product_tmp_data"]["taxproduct"]  = $product;
                    
                    foreach ($children as $child) {
                        $child = wpwoofeed_product_get_child($product,$child); 
                        if (!$child) continue;
                        
                        if (    $feed_filter_stock == 'instock' && !$child->is_in_stock()) continue;
                        if (    $feed_filter_stock == 'outofstock' && $child->is_in_stock() ) continue;
                       /* block for parent product price */
                        if (WPWOOF_DEBUG) {
                            trace("===============================================");
                            wpwoofStoreDebug($wpwoofeed_wpml_debug, "CHILD ID:" . wpwoofeed_get_id($child) . "|" . $child->get_id());
                            wpwoofStoreDebug($wpwoofeed_wpml_debug,"CHILD STOCK STATUS:".( $child->is_in_stock() ? "TRUE" : "FALSE" )."NEED:".$feed_filter_stock);
                            trace("CHILD ID:" . wpwoofeed_get_id($child));
                        }
                        $children_output++;
                        $child_prices = wpwoofeed_generate_prices_for_product($child);
                        $aChildPrices[wpwoofeed_get_id($child)] = $child_prices;
                        if (WPWOOF_DEBUG) {
                            trace("CHILD PRICES:" . print_r($child_prices, true));
                        }

                        if ($first_child && $child_prices->regular_price > 0) {
                            $first_child_prices = $child_prices;
                            $first_child = false;
                        }
                        if ( (!$prices->regular_price || $is_parent) 
                              && ($child_prices->regular_price > 0)) {
                            $prices = $child_prices;                           
                            $parent_prices = $prices;
                            $wpwoofeed_settings["product_tmp_data"]["taxproduct"]  = $child;
                            $is_parent     = false;
                            if (WPWOOF_DEBUG) {
                                trace("567:CHILD PARENT PRICES:" . print_r($parent_prices, true));
                            }
                        } else {
                            if ($wpwoofeed_settings['feed_variable_price'] == 'big') {
                                if (($child_prices->regular_price > 0) 
                                   && ( empty($prices->regular_price) 
                                      ||  $child_prices->regular_price > $prices->regular_price 
                                      || $is_parent 
                                      )){
                                    $is_parent     = false;
                                    $prices = $child_prices;
                                    $wpwoofeed_settings["product_tmp_data"]["taxproduct"]  = $child;
                                    $parent_prices = $prices;
                                    if (WPWOOF_DEBUG) {
                                        trace("CHILD".$child->get_id()." PRICES BIG for(ID:".$product->get_id()."):" . print_r($child_prices, true));
                                    }
                                }
                            } else if ($wpwoofeed_settings['feed_variable_price'] == 'first') {
                                $is_parent     = false;
                                $prices = $first_child_prices;
                                $wpwoofeed_settings["product_tmp_data"]["taxproduct"]  = $child;
                                $parent_prices = $prices;
                                if (WPWOOF_DEBUG) {
                                    trace("CHILD".$child->get_id()." PRICES first for(ID:".$product->get_id()."):" . print_r($parent_prices, true));
                                }
                            } else if ( ($child_prices->regular_price > 0) && ($child_prices->regular_price < $prices->regular_price || $is_parent)) {
                                $is_parent     = false;
                                $prices = $child_prices;
                                $wpwoofeed_settings["product_tmp_data"]["taxproduct"]  = $child;
                                $parent_prices = $prices;
                                if (WPWOOF_DEBUG)  trace("CHILD".$child->get_id()." PRICES small for(ID:".$product->get_id()."):" . print_r($parent_prices, true));
                            }
                        }

                        /* end block for parent product price */
                    }                   

                    if (!$remove_variations && $children_output>0 && $children_count>0 ) {
                        foreach ($children as $child) {
                            $child = wpwoofeed_product_get_child($product,$child);
                            if (!$child) continue;
                            if (in_array( wpwoofeed_get_id( $child ), $aExistsProds)) continue;
                            if (    $feed_filter_stock == 'instock' && !$child->is_in_stock()) continue;
                            if (    $feed_filter_stock == 'outofstock' && $child->is_in_stock() ) continue;
                            $my_counter++;
                            $child_prices = $aChildPrices[wpwoofeed_get_id($child)];
                            $wpwoofeed_settings["product_tmp_data"]['prices'] = (empty($child_prices->regular_price)) ? $parent_prices : $child_prices;
                            $data = wpwoofeed_item($fields, $child);
                            if ($store_info->feed_type != "adsensecustom" && $type == 'xml') {
                                if ($data) fwrite($filep, $data);
                            } else {
                                $unsetColNum=0;
                                if (!$columns_name) {
                                    $columns = $data[0];
                                    $header = array();
                                    $ColNum=0;
                                    $ColTaxNum=0;
                                    $ColTaxCNum=0;
                                    if($columns) foreach ($columns as $column_name => $value) {
                                        $ColNum++;
                                        if ($store_info->feed_type == "adsensecustom"){
                                            if( $column_name=='item_group_id' ) {
                                                unset($columns['item_group_id']);
                                                $unsetColNum = $ColNum;
                                            }else if($column_name=='tax'){
                                                unset($columns['tax']);
                                                $ColTaxNum = $ColNum;
                                            }else if($column_name=='tax_countries'){
                                                unset($columns['tax_countries']);
                                                $ColTaxCNum = $ColNum;
                                            }
                                            else   $header[] = $column_name;
                                        } else     $header[] = $column_name;
                                    }
                                    if(!$filePresent && $columns) {
                                        fputcsv($filep, $header, $delimiter, $enclosure);
                                        $columns_name = true;
                                    }
                                }
                                $fields_item = $data[1];

                                if($unsetColNum>0){
                                    unset($fields_item[$unsetColNum-1]);
                                }
                                if($ColTaxNum>0){
                                    unset($fields_item[$ColTaxNum-1]);
                                }
                                if($ColTaxCNum>0){
                                    unset($fields_item[$ColTaxCNum-1]);
                                }
                                $fld_vals=array();
                                foreach($header as $fld){
                                    $fld_vals[$fld] = isset($fields_item[$fld]) ? $fields_item[$fld] : "";
                                }

                                if ($fields_item && count($fld_vals)>0) {
                                    fputcsv($filep, $fld_vals, $delimiter, $enclosure);
                                }
                            }
                            
                            array_push($feedStatus["parsed_product_ids"],wpwoofeed_get_id($child));
                            $feedStatus['parsed_products']++;                
                           
                            if (($key = array_search(wpwoofeed_get_id($child), $feedStatus['products_left'])) !== false) {                  
                                unset($feedStatus['products_left'][$key]);
                            }
                            $aExistsProds[] = wpwoofeed_get_id($child);
                            $woocommerce_wpwoof_common->upadte_feed_status($feed_data['edit_feed'],$feedStatus,1);
                           
                           
                            $output_count++;
                        }
                    }

                } else {
                    if ($product->is_type('bundle')) {
                        $parent_prices->sale_price = $product->get_bundle_price();
                        $parent_prices->regular_price = $product->get_bundle_regular_price();
                        $wpwoofeed_settings["product_tmp_data"]['poduct'] = $product;
                    }
                    $output_count++;
                }
                if(WPWOOF_DEBUG){
                    wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRODUCT:".wpwoofeed_get_id($product));
                    wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICES FINISH:".print_r($parent_prices,true));
                }
                $wpwoofeed_settings["product_tmp_data"]['prices']  = $parent_prices;

                
                if (
                    ($children_count>0 && $children_output>0 || $children_count==0 )
                    && (
                        !$product->is_type('bundle') && !$product->is_type('grouped') && !$product->is_type('variable')
                        ||
                        $product->is_type('bundle') && (!empty($wpwoofeed_settings['feed_bundle_show_main']) || !isset($wpwoofeed_settings['feed_bundle_show_main']))
                        ||
                        $product->is_type('grouped') && (!empty($wpwoofeed_settings['feed_group_show_main']) || !isset($wpwoofeed_settings['feed_group_show_main']))
                        ||
                        ( $product->is_type('variable') || $product->is_type('subscription_variation')  )
                        && (!empty($wpwoofeed_settings['feed_variation_show_main'])  || !isset($wpwoofeed_settings['feed_variation_show_main']))
                    )

                ) {
                   
                   $data = wpwoofeed_item($fields, $product);
                    
                } else {
                    $data = "";
                }


                if ($store_info->feed_type != "adsensecustom" && $type == 'xml') {
                    if ($data) fwrite($filep, $data);
                } else {
                    $unsetColNum=0;
                    if (!$columns_name) {
                        $columns = $data[0];

                        $header = array();
                        $ColNum=0;
                        $ColTaxNum=0;
                        $ColTaxCNum=0;
                        if($columns) foreach ($columns as $column_name => $value) {
                            $ColNum++;
                            if ($store_info->feed_type == "adsensecustom"){
                                if( $column_name=='item_group_id' ) {
                                    unset($columns['item_group_id']);
                                    $unsetColNum = $ColNum;
                                }else if($column_name=='tax'){
                                    unset($columns['tax']);
                                    $ColTaxNum = $ColNum;
                                }else if($column_name=='tax_countries'){
                                    unset($columns['tax_countries']);
                                    $ColTaxCNum = $ColNum;
                                } else $header[] = $column_name;
                            } else     $header[] = $column_name;
                        }

                        if(!$filePresent && $columns) {
                            //echo "<pre>".print_r($header,true)."</pre>";
                            fputcsv($filep, $header, $delimiter, $enclosure);
                            $columns_name = true;
                        }
                    }
                    $fields_item = $data[1];

                    if($unsetColNum>0){
                        unset($fields_item[$unsetColNum-1]);
                    }
                    if($ColTaxNum>0){
                        unset($fields_item[$ColTaxNum-1]);
                    }
                    if($ColTaxCNum>0){
                        unset($fields_item[$ColTaxCNum-1]);
                    }
                    $fld_vals=array();
                    foreach($header as $fld){
                        $fld_vals[$fld] = isset($fields_item[$fld]) ? $fields_item[$fld] : "";
                    }

                    if ($fields_item && count($fld_vals)>0) {
                        fputcsv($filep, $fld_vals, $delimiter, $enclosure);
                    }
                }
                
                $feedStatus['parsed_products']++;                
                array_push($feedStatus["parsed_product_ids"],$post);
                if (($key = array_search($post, $feedStatus['products_left'])) !== false) {                  
                    unset($feedStatus['products_left'][$key]);
                }
                $aExistsProds[] = wpwoofeed_get_id($product);
                $woocommerce_wpwoof_common->upadte_feed_status($feed_data['edit_feed'],$feedStatus);
            
            }
/*
            $args['paged']++;
            $products = new WP_Query($args);
        }
*/
        if (isset($wpwoofeed_settings["product_tmp_data"])) unset($wpwoofeed_settings["product_tmp_data"]);
        if (isset($wpwoofeed_settings["tax_rate_tmp_data"])) unset($wpwoofeed_settings["tax_rate_tmp_data"]);
        if (isset($wpwoofeed_settings['aBaseTax_tem_val'])) unset($wpwoofeed_settings['aBaseTax_tem_val']);
        if (isset($wpwoofeed_settings['atax_tem_val'])) unset($wpwoofeed_settings['atax_tem_val']);

        if ($store_info->feed_type != "adsensecustom" && $type == 'xml') {
            $footer = '';

            $footer .= "  </channel>\n";
            $footer .= "</rss>";
            fwrite($filep, $footer);
            unset($footer);
        }

        fclose( $filep );
        rename ( $filepath , str_replace(".wpwoof.tmp","",$filepath ) );
        $woocommerce_wpwoof_common->delete_feed_status($feed_data['edit_feed']);        
        wp_clear_scheduled_hook( 'wpwoof_generate_feed',  array( $feed_data['edit_feed'] )  );

        if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
            $sitepress->switch_lang($general_lang,true);

        }

        if(!empty($feed_data['edit_feed'])) {
            $feed_data['status_feed'] = 'finished';
            wpwoof_update_feed(serialize($feed_data), $feed_data['edit_feed'],true);
        }
       

    }catch(Exception $e){
        $feed_data['status_feed'] = 'error: '.$e->getMessage();
        if(!empty($feed_data['edit_feed'])) {
            wpwoof_update_feed(serialize($feed_data), $feed_data['edit_feed'],true);
        }
        update_option("wpwoofeed_errors",$feed_data['status_feed'] );
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"ERROR:".$e->getMessage());
        return false;
    }
    if(WPWOOF_DEBUG){ 
        exit;
        trace('GENERATED');
       
    }
    
    return true;
}
function wpwoofeed_item( $fields, $product ) {
    if( empty($fields) || empty($product) )
        return '';

    global $wpwoofeed_type;
    global $woocommerce;
    global $woocommerce_wpwoof_common;
    global $store_info;
    global $wpwoofeed_settings;
    if(empty($wpwoofeed_settings['feed_type'])) $wpwoofeed_settings['feed_type']='all';
    $item = '';

    $field_rules = wpwoof_get_product_fields();


    if( 'xml' == $wpwoofeed_type ) {
        $item    = "    <item>" . "\n";
    } else if( 'csv' == $wpwoofeed_type ) {
        $columns = array();
        $values = array();
    }
    $req_identifier_exists = 0;


    foreach ($fields as $tag => $field) {
        if (  $tag == 'identifier_exists') continue;
        if ( $wpwoofeed_settings['feed_type'] == 'google' 
             && ($tag == 'mpn' || $tag=='gtin')
            &&
            (isset($fields['identifier_exists']) 
            &&  
            $fields['identifier_exists']['value']=='false')) {
            continue;
        }




        if( ( $tag=='mpn' || $tag=='gtin' )  ) {
            $req_identifier_exists++;
        }

        if( $tag == 'product_type'
            && !empty( $wpwoofeed_settings['feed_google_category'])
            && $wpwoofeed_settings['feed_google_category'] != '' ) {
            if( 'xml' == $wpwoofeed_type ) {
                $item .="        <g:google_product_category>".wpwoofeed_text($wpwoofeed_settings['feed_google_category'])."</g:google_product_category>" . "\n";
            } else if('csv' == $wpwoofeed_type) {
                $columns['google_product_category'] = 1;
                $values['google_product_category'] = htmlspecialchars($wpwoofeed_settings['feed_google_category'],ENT_QUOTES);
            }

        }


        /* New block for skiping empty fields  */
        $isEmpty=true;
        if (count($field)) { foreach($field as $fldata) { if(!empty($fldata)) { $isEmpty=false; break; } } }
        else if( !isset($field_rules[$tag]['needcheck']) || $field_rules[$tag]['needcheck'] != true ){
            continue;
        }

        if( $isEmpty && (!isset($field_rules[$tag]['needcheck']) || $field_rules[$tag]['needcheck'] != true) ){
            continue;
        }
        /* END New block for skiping empty fields  */


        $extra_param = 0;

        if( strpos($tag, 'custom_label_') !== false ) {
            $func = 'custom_label';
        } else if( strpos($tag, 'additional_image_link') !== false ) {
            $func = 'additional_image_link';
            $extra_param = str_replace('additional_image_link_', '', $tag);
        } else {
            $func = $tag;
        }
        $func = trim($func);


        $tagvalue = '';
        $field['rules'] = isset($field_rules[$tag]) ? $field_rules[$tag] : '';
        if( ! empty($field_rules[$tag]['additional_options']) && is_array($field_rules[$tag]['additional_options']) ) {
            foreach($field_rules[$tag]['additional_options'] as $adopt_key => $adopt_val) {
                $field[$adopt_key] = (isset( $field[$adopt_key] ) ? $field[$adopt_key] : $adopt_val);
            }
        }
        try{


            if(($tag=='price' || $tag=='sale_price') && !empty($wpwoofeed_settings["tax_rate_tmp_data"])) {
                $field['tax_rate']=$wpwoofeed_settings["tax_rate_tmp_data"];
            }

            $tagvalue = function_exists('wpwoofeed_' . str_replace(" ","",$func))
                ? call_user_func( 'wpwoofeed_' . str_replace(" ","",$func), $product, $item, $field, $tag)
                : wpwoofeed_custom_user_function($product, $item, $field, $tag);


        } catch (Exception $e) {
            echo $e->getMessage();
            $tagvalue = '';
        }


        if( strpos($tag, 'additional_image_link') !== false ) {
            $tag = 'additional_image_link';
        }
        if( 'xml' == $wpwoofeed_type ) {
            if( $tagvalue && !empty($tagvalue) ) {
                $item .="        <g:" . $tag .">".$tagvalue."</g:" . $tag .">" . "\n";
            }


        } else if('csv' == $wpwoofeed_type) {                      
            
                $columns[$tag] = 1;  
                if(!empty($tagvalue)){
                    $tgval = ($tag == 'additional_image_link') ? str_replace(",","%2C",$tagvalue) : $tagvalue;
                    $values[$tag] =  ( !empty($values[$tag] ) ? $values[$tag]."," : "" ) . $tgval ;
                }    

           
            
        }

    }






    if( $wpwoofeed_settings['feed_type'] == 'google'
        && (!$req_identifier_exists || (isset($fields['identifier_exists'])
                && $fields['identifier_exists']['value']=='false')) ){
        if( 'xml' == $wpwoofeed_type ) {
            $item .="        <g:identifier_exists>false</g:identifier_exists>" . "\n";
        } else if('csv' == $wpwoofeed_type) {
            $columns['identifier_exists'] = 1;
            $values['identifier_exists'] = 'false';
        }
    }



    if( 'xml' == $wpwoofeed_type ) {
        $item .= "    </item>" . "\n";
        return $item;
    } else if('csv' == $wpwoofeed_type) {
        return array($columns, $values);
    }
}
function wpwoofeed_product_get_property($proper,$product){
    if(empty($product)) return null;    
    return version_compare( WC_VERSION, '3.0', '>=' ) ? $product->{"get_".$proper}() : $product->$proper;
}
function wpwoofeed_product_get_child($product,$child_id){
    return version_compare( WC_VERSION, '3.0', '>=' ) ?  wc_get_product($child_id) : $product->get_child($child_id);
}

function wpwoofeed_product_get_title($product){
    return version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_title() : $product->post->post_title;
}
function wpwoofeed_product_get_excerpt($product){
    if( version_compare( WC_VERSION, '3.0', '>=' ) ){
        $post = get_post(wpwoofeed_get_id($product));
        return $post->post_excerpt;
    } else {
        return $product->post->post_excerpt;
    }
}
function wpwoofeed_product_get_short_description($product){
    return  apply_filters('the_content', version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_short_description() : $product->short_description );
}
function wpwoofeed_product_get_description($product){       
     return  apply_filters('the_content', version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_description() : $product->post->post_content );
}
function wpwoofeed_product_get_type($product){
    return version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_type() : $product->product_type;
}
function wpwoofeed_item_group_id($product,$item, $field, $tag){
     
    if( ( in_array(   wpwoofeed_product_get_type($product) , array('variation','subscription_variation') )
            || $product->has_child() )
        && !empty($field['define'])  ) {
        return $field['define'];/*( isset($product->parent_id) && !empty($product->parent_id) ) ? $product->parent_id : wpwoofeed_get_id( $product );*/
    }else {
        return '';
    }
}
function get_maim_product(){
    global $wpwoofeed_settings;
    return (!empty($wpwoofeed_settings["product_tmp_data"]["product"]) )  ? $wpwoofeed_settings["product_tmp_data"]["product"] : null;
}

function wpwoofeed_meta( $product, $item, $field, $id ) {
    $attribute = str_replace('wpwoofmeta_', '', $field['value']);
    $tagvalue = get_post_meta( wpwoofeed_get_id($product), $attribute, true);
    $tagvalue = is_array($tagvalue) ? '' : $tagvalue;
    $tagvalue = $tagvalue;

    $tagvalue = wpwoofeed_text($tagvalue, false);
    return $tagvalue;
}
function wpwoofeed_attr( $product, $item, $field, $id ) {
    if( isset( $field['define'] ) && isset($field['define']['option']) )
        $taxonomy = $field['define']['option'];
    else
        $taxonomy = $field['value'];
    $taxonomy = str_replace('wpwoofattr_', '', $taxonomy);
    $taxonomy = str_replace('wpwoofdefa_', '', $taxonomy);



    $tagvalue = '';
    if( strpos($taxonomy, 'pa_') !== false && wpwoofeed_product_get_type($product) == 'variation' ) {
        $txnmy = str_replace('pa_', '', $taxonomy);
        $attributes = $product->get_variation_attributes();

        foreach ($attributes as $attribute => $attribute_value) {
            if( strpos($attribute, $txnmy) !== false ) {
                $attribute_value =  $attribute_value;
                return wpwoofeed_text($attribute_value, false);
            }
        }
    }


    $product = get_maim_product();
    $the_terms = wp_get_post_terms( wpwoofeed_get_id($product), $taxonomy, array( 'fields' => 'names' ));


    $tagvalue = '';
    if( !is_wp_error($the_terms) && !empty($the_terms) ) {
        $tagvalue = implode(" > ",$the_terms);
        /*
        foreach ($the_terms as $term) {
            $tagvalue .= $term.', ';
        }
        $tagvalue = rtrim($tagvalue, ', ');
        */
    }
    return wpwoofeed_text($tagvalue);
}

function wpwoofeed_xml_has_error($message) {
    global $xml_has_some_error;
    if( ! $xml_has_some_error && !empty($message) ) {
        add_action( 'admin_notices', create_function( '', 'echo "'.$message.'";' ), 9999 );
        $xml_has_some_error = true;
    }
}
function wpwoofeed_custom_user_function( $product, $item, $field, $id, $data = array() ) {
    global $wpwoofeed_settings;
    $value = isset($field['value']) ? $field['value'] : '' ;
    
    $tagvalue = '';
    if( empty($value) ) {
        return '';
    } elseif( strpos($value, 'wpwoofdefa_condition') !== false ) {
        return '';
    } elseif( strpos($value, 'wpwoofdefa_brand') !== false ) {
        $post_type = "product";
        $taxonomy_names = get_object_taxonomies( $post_type );
        $exist_brand = false;
        foreach( $taxonomy_names as $taxonomy_name ) {
            if( ($taxonomy_name != 'product_cat') && ($taxonomy_name != 'product_tag') && ($taxonomy_name != 'product_type')
                && ($taxonomy_name != 'product_shipping_class') ) {

                if( strpos($taxonomy_name, "brand") !== false ) {
                    $value = 'wpwoofattr_'.$taxonomy_name;
                    $field['value'] = $value;
                    $exist_brand = true;
                    break;
                }
            }
        }
        if( ! $exist_brand ) {
            return '';
        }
    } else if( isset($wpwoofeed_settings['expand_more_images']) && empty($wpwoofeed_settings['expand_more_images']) &&  stripos($id,"additional_image_link")!==false ){
        return "";
    }

    $data = array_merge(array(
        'wpwoofdefa' => true,
        'wpwoofmeta' => true,
        'wpwoofattr' => true,
    ), $data);


    if( strpos($value, 'wpwoofdefa_') !== false && $data['wpwoofdefa'] ){
        return wpwoofeed_tagvalue($value, $product, $id, $field);
    } else if( strpos($value, 'wpwoofmeta_') !== false && $data['wpwoofmeta'] ){
        return wpwoofeed_meta($product, $item, $field, $id);
    } else if( strpos($value, 'wpwoofattr_') !== false && $data['wpwoofattr'] ){
        return wpwoofeed_attr($product, $item, $field, $id);
    }

    return false;
}

function wpwoofeed_calc_tax($price,$product){
    global $wpwoofeed_settings,$woocommerce_wpml,$woocommerce_wpwoof_common,$wpwoofeed_wpml_debug,$store_info;

    if(!$price) return 0.00;    
    
   
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_calc_tax START: ".$price."|");
    $isPriceConverted = false;
    if (   !empty($woocommerce_wpml->settings) 
        && !empty($woocommerce_wpml->settings['enable_multi_currency'])
        ){   
             $currency_options = $woocommerce_wpml->settings['currency_options'][$store_info->currency];
             if(WPWOOF_DEBUG) wpwoofStoreDebug( $wpwoofeed_wpml_debug,"wpwoofeed_calc_tax OPTIONS: CURRENCY".$store_info->currency." => ".print_r($currency_options,true) );
             //if( $currency_options['rounding'] != 'disabled' ){  
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_calc_tax WMPL:".$price."|Apply:".$woocommerce_wpml->multi_currency->prices->apply_rounding_rules($price * (  $store_info->currency != $store_info->default_currency ? $store_info->currencyRate : 1.0   ),$store_info->currency ). ' ' . $store_info->currency);      
                $price = $woocommerce_wpml->multi_currency->prices->apply_rounding_rules($price * (  $store_info->currency != $store_info->default_currency ? $store_info->currencyRate : 1.0   ),$store_info->currency );
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_calc_tax WMPL PRICE:".$price);      
                $isPriceConverted = true;
            // }
        }

    if(!$isPriceConverted){
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_calc_tax JUST: ".$price *  $store_info->currencyRate."|"); 
        $price = number_format(  $price *  $store_info->currencyRate,2,null,'');        
        $isPriceConverted = true;
    }

    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_calc_tax round price: ".$price."|");

    if(!isset($wpwoofeed_settings['tax_rate_tmp_data']) || empty($wpwoofeed_settings['tax_rate_tmp_data'])) { 
         return price_format($price);
    }
    

    $cproduct = ( $product->has_child() && isset($wpwoofeed_settings["product_tmp_data"]["taxproduct"]) ) ? $wpwoofeed_settings["product_tmp_data"]["taxproduct"] :  $product;
    
    $BaseTax = 0.0;
   
    $tax_staus = wpwoofeed_product_get_property('tax_status',$cproduct);
    $prodTaxClass = wpwoofeed_product_get_property('tax_class',$cproduct);
    
    if(WPWOOF_DEBUG){         
        trace("\nWpwoofeed_Calc_Tax PROD ID:".$product->get_id()." CPRODUCT: ".$cproduct->get_id()." => tax_staus: ".$tax_staus."|tax_class:".( empty($prodTaxClass) && $tax_staus=='taxable' ? "standart" : $prodTaxClass ) );
        trace(isset($wpwoofeed_settings['aBaseTax_tem_val']) ? $wpwoofeed_settings['aBaseTax_tem_val'] : null) ; 
     }
    
    $defCountry = get_option('woocommerce_default_country','');
    if($defCountry){
        $a = explode(":",$defCountry);
        $defCountry = $a[0];
    } 
    $atax = WC_Tax::find_rates(
            array(
                'country' => $defCountry, 
                'tax_class'=>$prodTaxClass
            )
    );
    if(WPWOOF_DEBUG){         
        trace("\nTAX DEFAULT COUNTRY:".$defCountry." | tax_class: ".$prodTaxClass."| WC_Tax::find_rates => atax:".print_r($atax,true) );       
     }

    
    $aTax = array(0=>$BaseTax);   
    if(count($atax)) 
      foreach($atax as $tax) {
        if(isset($tax['rate']) ){
              if( !empty($tax['compound']) &&  $tax['compound']=='yes'){
                $aTax[$i]+=$tax['rate']*1.0; 
                //$i++;                
              }else{                
                $BaseTax+= $tax['rate']*1.0;
                $aTax[0]=$BaseTax;
              }
        }
     }
   
    $wpwoofeed_settings['aBaseTax_tem_val']=$atax;   
    if(WPWOOF_DEBUG){ 
        trace("\nBaseTax:"); 
        trace($wpwoofeed_settings['aBaseTax_tem_val']); 
    }     
    $BaseTax = $wpwoofeed_settings['aBaseTax_tem_val'];
  
    if( $tax_staus!="taxable" || get_option('woocommerce_calc_taxes',null) != 'yes' ) {
        $BaseTax=0.0; 
        $aTax = array(0=>$BaseTax);
        if(WPWOOF_DEBUG){ 
            trace("\n no taxable  aTax:(array(0=>BaseTax-0.00))"); 
            trace( $aTax); 
        }      
    } 
    
   

    if($wpwoofeed_settings['tax_rate_tmp_data']=='addbase')  {
        foreach($aTax as $t){
            $price+=$price*$t/100.0;
        }
        if(WPWOOF_DEBUG){ 
            trace("\n addbase plus aTax:(price+=price*t/100.0 )"); 
            trace( $aTax); 
        }
        return price_format($price);
    }else if($wpwoofeed_settings['tax_rate_tmp_data']=='whithout') {

         

        $i=count($aTax)-1;
        $oprice = $price;
        while($i>-1){
            if(WPWOOF_DEBUG){
                trace("\nwhithout minus aTax:(price=price*1.0/(1.0 + aTax[i]*1.0/100.0) )"); 
                trace("\nwhithout minus aTax val".$i.":".$price."*1.0/(1.0 + ".$aTax[$i]."*1.0/100.0)=".( $price*1.0/(1.0 + $aTax[$i]*1.0/100.0) ) );
            }
            $price=$price*1.0/(1.0 + $aTax[$i]*1.0/100.0);
            $i--;
        }
        if(WPWOOF_DEBUG){ 
            
            trace( "aTax:".print_r($aTax,true)); 
            trace("\nwhithout minus orig_price=".$oprice." whithout_price:".$price."");
        }
        return price_format($price);  
        //return price_format(wc_get_price_excluding_tax($product,array("qty"=>1,"price"=>$price)));
        
    }else if(strpos($wpwoofeed_settings['tax_rate_tmp_data'],'addtarget' )!==false){
                
        $sep = explode(":",$wpwoofeed_settings['tax_rate_tmp_data']);
        if(isset($sep[1]) && !empty($sep[1]) ){            
           
                $taxRate=0.0;
                $sValTMP=$sep[1];
                $cl = $prodTaxClass;
                $country = $sValTMP;
                if(strpos($sValTMP,"-")){
                    $sValTMP = explode("-",$sep[1]);
                    $wpwoofeed_settings['atax_tem_val'] = $woocommerce_wpwoof_common->getTaxRateCountries($sValTMP[1]);
                    if(count($wpwoofeed_settings['atax_tem_val'])>0) $cl =$wpwoofeed_settings['atax_tem_val'][0]['class'];
                    $country=$sValTMP[0];

                } 
                
                if($country!='*') {                   
                     $wpwoofeed_settings['atax_tem_val'] = WC_Tax::find_rates(
                                       array("country"=>$country,
                                             "tax_class"=>$prodTaxClass
                                       ));
                    if(WPWOOF_DEBUG){ 
                       trace("\nGET TAX VALUE : country=".$country."|class=".$prodTaxClass."\n");
                       trace($wpwoofeed_settings['atax_tem_val']);
                    }
                 }    
            
            $tax_rate=0.0;
            $aTax = array(0=>$tax_rate);            
            if(count($wpwoofeed_settings['atax_tem_val'])) {
                $i=1;
                foreach($wpwoofeed_settings['atax_tem_val'] as $tax) { 
                        if(isset($tax['rate'])) {
                            if( !empty($tax['compound']) &&  $tax['compound']=='yes'){
                                $aTax[$i]+=$tax['rate']*1.0;
                               // $i++;                                
                            }else{
                                $tax_rate+=$tax['rate']*1.0;
                                $aTax[0]=$tax_rate;
                            }

                        }
                }
               
            }
            if(WPWOOF_DEBUG){ 
                trace("\n addtarget plus aTax:(price+=price*t/100.0)"); 
                trace( $aTax); 
            }
           
            foreach($aTax as $t){
                $price+=$price*$t/100.0;
            }
            
            return price_format($price);
        }
        return  price_format($price);
    }else if(strpos($wpwoofeed_settings['tax_rate_tmp_data'],'target' )!==false){
        $i=count($aTax)-1;
        while($i>-1){
            if(WPWOOF_DEBUG){ 
                trace("\nTAX target while:i=".$i."|price=".$price."|aTax[".$i."]=".print_r($aTax[$i],true));
            }            
            $price=$price*1.0/(1.0 + $aTax[$i]*1.0/100.0);
            $i--;
        }
        if(WPWOOF_DEBUG){ 
            trace("\n target minus aTax:(price*1.0/(1.0 + aTax[i]*1.0/100.0))"); 
            trace( $aTax); 
        }

        $sep = explode(":",$wpwoofeed_settings['tax_rate_tmp_data']);
        if(isset($sep[1]) && !empty($sep[1]) ){

            $sValTMP=$sep[1];
            $cl = $prodTaxClass;
            $country = $sValTMP;
            if(strpos($sValTMP,"-")){
                $sValTMP = explode("-",$sep[1]);
                $atax = $woocommerce_wpwoof_common->getTaxRateCountries($sValTMP[1]);
                if(WPWOOF_DEBUG){ trace("\nATAX:"); trace( $atax); }
                if(count($atax)>0) $cl = $atax[0]['class'];
                $country=$sValTMP[0];
            }

            if(WPWOOF_DEBUG){ 
                trace("\nWC_Tax::find_rates Query:"); 
                trace( array("country"=>$country,"tax_class"=>$cl)); }
            if($country!='*') $atax = WC_Tax::find_rates(
                       array("country"=>$country,
                       "tax_class"=>$cl));
            
            if(WPWOOF_DEBUG){ 
                trace("\nWC_Tax::find_rates:"); 
                trace( $atax); 
            }
            $tax_rate=0.0;
            $aTax = array(0=>$tax_rate); 
            if(count($atax)) {
                   $i=1;
                   foreach($atax  as $tax) { 
                           if(isset($tax['rate'])) {
                               if( !empty($tax['compound']) &&  $tax['compound']=='yes'){
                                    $aTax[$i]+=$tax['rate']*1.0;
                                    //$i++;  
                               }else{
                                    $tax_rate+=$tax['rate']*1.0;
                                    $aTax[0]=$tax_rate;
                               }
   
                        }
                    }
                
                foreach($aTax as $t){
                    $price+=$price*$t/100.0;
                }
                if(WPWOOF_DEBUG){ 
                    trace("\n target plus aTax:(price+=price*t/100.0)"); 
                    trace( $aTax); 
                }
                return price_format($price); 
            }
        }
        return  price_format($price);
    }
    return  price_format($price);





}
function wpwoofeed_id($product, $item, $field, $id){
    return wpwoofeed_custom_user_function($product, $item, $field, $id);
}
function wpwoofeed_id2($product, $item, $field, $id){
    return wpwoofeed_custom_user_function($product, $item, $field, $id);
}
/* For Google Feed */
function wpwoofeed_is_bundle($product, $item, $field, $id){
    return ( $product->is_type( 'bundle' ) /*$product->is_type( 'grouped' )*/ ) ? "TRUE" : "";
    // return wpwoofeed_custom_user_function($product, $item, $field, $id);
}
/* For Google Feed */
function wpwoofeed_adult($product, $item, $field, $id){
    return ($field['value']=="true") ? "TRUE" : "";
}
function wpwoofeed_description($product, $item, $field, $id){
   
    $desc = wpwoofeed_custom_user_function($product, $item, $field, $id);
   
    if( empty($desc) ) {
        wpwoofeed_xml_has_error('Description missing in some products');
    }
    if (!empty($field['uc_every_first'])) {       
        $desc = wpwoof_sentence_case($desc);
    }
    
    return  wpwoofeed_text($desc);
}
function wpwoofeed_description_short($product, $item, $field, $id){
    $desc = wpwoofeed_custom_user_function($product, $item, $field, $id);
    return wpwoofeed_text($desc);
}
function wpwoofeed_variation_description($product, $item, $field, $id){
    $desc = wpwoofeed_custom_user_function($product, $item, $field, $id);
    return wpwoofeed_text($desc);
}
function wpwoofeed_image_link($product, $item, $field, $id){
    $link = wpwoofeed_custom_user_function($product, $item, $field, $id);
    if( empty($link) && !empty($field['fallback image_link'])){
        $origvalue = $field['value'];
        $field['value']='wpwoofdefa_'.$field['fallback image_link'];
        $link = wpwoofeed_custom_user_function($product, $item, $field, $id);
        $field['value']=  $origvalue;
    }

    if( empty($link) ) {
        wpwoofeed_xml_has_error('Image link missing in some products');
    }
    return $link;
}

function wpwoofeed_getParentID($product){
    if( isset($product) && in_array(wpwoofeed_product_get_type($product), array('variation','subscription_variation')) ){ 
        // isset($product->variation_id)     
        $parId = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_product_get_property('parent_id',$product) :  $product->parent_id;
        if(!$parId) {
            $parId = $product->parent->id;
        }
        return  $parId;
    }
    return null;
}

function wpwoofeed_title($product, $item, $field, $id){
    $title = wpwoofeed_custom_user_function($product, $item, $field, $id);
    if(!empty($title)) {
        if (!empty($field['uc_every_first'])) {

            $title =wpwoof_sentence_case($title);
        }
    }

    return  wpwoofeed_text($title,false);
}

function wpwoofeed_price($product, $item, $field, $id){
    global $wpwoofeed_settings;
    

    $price = wpwoofeed_custom_user_function( $product, $item, $field, $id );
    if($price>0) return $price;
   
    $price = $wpwoofeed_settings['product_tmp_data']['prices']->regular_price;

    if( $product->is_type('subscription_variation')){
        $price =  wpwoofeed_generate_prices_for_product( $product );
        $price = $price->regular_price;
    }
   
    if(WPWOOF_DEBUG) {
       trace("\nWpwoofeed_Price: PROD ID: "
             .$product->get_id()." "
             .( isset( $cproduct ) ? (" CPROD ID:".$cproduct->get_id()): "" )." PRICE:" . $price  );
    }
    
    return  wpwoofeed_calc_tax($price,$product);
}
function price_format($price){
    global $store_info,$wpwoofeed_wpml_debug,$woocommerce_wpml;   
    
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT".$price." ".$store_info->currency);
    
    
    if (   !empty($woocommerce_wpml->settings) 
        && !empty($woocommerce_wpml->settings['enable_multi_currency'])
        ){   
              $currency_options = $woocommerce_wpml->settings['currency_options'][$store_info->currency];
              if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT WMPL options=".print_r($currency_options,true));
              if($currency_options){
                return number_format(  $price  , 
                        $currency_options['num_decimals'],  
                        $currency_options['decimal_sep'], 
                        $currency_options['thousand_sep']). ' ' . $store_info->currency;      
              }          
        }
    
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT store_info->woocommerce_price_decimal_sep=".$store_info->woocommerce_price_decimal_sep."|");
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT store_info->woocommerce_price_num_decimals".$store_info->woocommerce_price_num_decimals."|");
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT store_info->woocommerce_price_thousand_sep".$store_info->woocommerce_price_thousand_sep."|");
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT store_info->woocommerce_price_round".$store_info->woocommerce_price_round."|");
        if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT store_info->woocommerce_price_rounding_increment".$store_info->woocommerce_price_rounding_increment."|");
       
    
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"PRICE_FORMAT JUST: ".$price ."|Apply:".number_format(  (float)$price, 
    (int)$store_info->woocommerce_price_num_decimals,  
    $store_info->woocommerce_price_decimal_sep, 
    $store_info->woocommerce_price_thousand_sep). ' ' . $store_info->currency);  

    return number_format(  $price  , 
                           (int)$store_info->woocommerce_price_num_decimals,  
                           $store_info->woocommerce_price_decimal_sep, 
                           $store_info->woocommerce_price_thousand_sep). ' ' . $store_info->currency;
}

function wpwoofeed_gtin($product, $item, $field, $id){
    global $wpwoofeed_settings;
    $value = wpwoofeed_custom_user_function($product, $item, $field, $id);
    if( empty($value) && !empty($wpwoofeed_settings['gtin_global']) ){
                $p = get_maim_product();
                if($p) { $value = wpwoofeed_custom_user_function($p, $item, $field, $id);}
    }   
    return  wpwoofeed_text($value,false);
}

function wpwoofeed_mpn($product, $item, $field, $id){
    global $wpwoofeed_settings;
    $value = wpwoofeed_custom_user_function($product, $item, $field, $id);
    if( empty($value) && !empty($wpwoofeed_settings['mpn_global']) ){
                $p = get_maim_product();
                if($p) { $value = wpwoofeed_custom_user_function($p, $item, $field, $id);}
    }   
    return  wpwoofeed_text($value,false);
}

function wpwoofeed_sale_price($product, $item, $field, $id){
    global $wpwoofeed_settings;
    $prices = $wpwoofeed_settings['product_tmp_data']['prices'];
    if($prices->sale_price<0.001 || $prices->sale_price==$prices->regular_price ) return '';
    return  wpwoofeed_calc_tax($prices->sale_price,$product);
}
function wpwoofeed_tax($product, $item, $field, $id){
    if(empty($field['value']) || $field['value']!="true") return "";
    return "";
}
function wpwoofeed_tax_countries($product, $item, $field, $id){
    return "";
}
function wpwoofeed_condition($product, $item, $field, $id){
    $value = $field['define'];
    $tagvalue = wpwoofeed_custom_user_function($product, $item, $field, $id);
    if( isset($value['global']) && isset($value['global'])  == 1 ){
        $tagvalue =  wpwoofeed_text($value['globalvalue'],false);
    } else {
        if( empty($tagvalue) ){
            $tagvalue =  wpwoofeed_text($value['missingvalue'],false);
        }
    }
    if( empty($tagvalue) ) {
        $tagvalue = 'new';
    } else {
        $tagvalue = str_replace(',', ' , ', $tagvalue);
        $tagvalue = ' '.$tagvalue.' ';
        $tagvalue = strtolower($tagvalue);
        if( strpos($tagvalue, ' new ') !== false ) {
            $tagvalue = 'new';
        } elseif( strpos($tagvalue, ' used ') !== false ) {
            $tagvalue = 'used';
        } elseif( strpos($tagvalue, ' refurbished ') !== false ) {
            $tagvalue = 'refurbished';
        } else {
            $tagvalue = 'new';
        }
    }
    if( empty($tagvalue) ) {
        wpwoofeed_xml_has_error('Condition missing in some products');
    }
    return $tagvalue;
}
function wpwoofeed_brand($product, $item, $field, $id){

    $value = $field['define'];
    $tagvalue = wpwoofeed_text(wpwoofeed_custom_user_function($product, $item, $field, $id));
    if( !empty($value['globalvalue']) && isset($value['global']) && isset($value['global'])  == 1 ){
        $tagvalue = wpwoofeed_text($value['globalvalue']);
    } else {
        if( empty($tagvalue) && ! empty($value['missingvalue']) ){
            $tagvalue =  wpwoofeed_text($value['missingvalue']);
        }
    }
    if($id=='brand' && empty($tagvalue) ) {
        wpwoofeed_xml_has_error('Brand missing in some products');
    }
    return $tagvalue;
}

function wpwoofeed_itemaddress($product, $item, $field, $id){
    return wpwoofeed_brand($product, $item, $field, $id);
}

function wpwoofeed_trackingtemplate($product, $item, $field, $id){
    return wpwoofeed_brand($product, $item, $field, $id);
}

function wpwoofeed_load_product( $post ) {
    if ( function_exists( 'wc_get_product' ) ) {
        // 2.2 compat.
        return wc_get_product( $post );
    } else if ( function_exists( 'get_product' ) ) {
        // 2.0 compat.
        return get_product( $post );
    } else {
        return new WC_Product( wpwoofeed_get_id($post) );
    }
}
function wpwoofeed_enforce_length($text, $length, $full_words = false){
    if ( empty($length) || strlen( $text ) <= $length ) {
        return $text;
    }

    if ( $full_words === true ) {
        $text = substr( $text, 0, $length );
        $pos = strrpos($text, ' ');
        $text = substr( $text, 0, $pos );
    } else {
        $text = substr( $text, 0, $length );
    }

    return $text;
}


function wpwoofeed_text($text, $use_cdata = true){
    global $wpwoofeed_type;
    if( ! empty($text) ) {
        $text = preg_replace("/\r\n|\r|\n/",' ',$text);

        $text = (strpos($text,"<![CDATA[")!==false) ? str_replace("<![CDATA[","",str_replace(']]>','',$text)) : $text;
        $text =  WPWOOF_Encoding::toUTF8(html_entity_decode($text));
        $text = strip_tags($text);
        $text = strip_shortcodes( do_shortcode( $text ) );
        $text = preg_replace('#\[[^\]]+\]#', '',$text); 
        if('xml' == $wpwoofeed_type) {
            $text = wp_kses_decode_entities($text);
            if( $use_cdata ) {
                $text = "<![CDATA[" . $text . "]]>";
            }else{
                $text = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', htmlspecialchars($text,ENT_QUOTES));
            }
        }
    }

    return preg_replace('/[\x{0a}-\x{1f}]/u', '*', $text);
}

function wpwoofeed_check_url($src){
    $src=trim($src);
    if(empty($src)) return '';
    return (!preg_match("~^(?:f|ht)tps?://~i", $src)) ?  home_url($src) : $src;
}

function wpfeed_thumbnail_src( $post_id = null, $size = 'post-thumbnail', $product ) {
    global $wpwoofeed_settings;
    $post_thumbnail_id = false;
    if( !empty($wpwoofeed_settings["prod_images"]) && count($wpwoofeed_settings["prod_images"])>0){

        $attributes = $product->get_attributes();
        $first_key = false;
        foreach($attributes as $attr){
            foreach($wpwoofeed_settings["prod_images"] as $key => $aimg){
                if( $first_key === false) $first_key=$key;
                if(!empty($aimg[1]) && $aimg[1]==$attr){
                    $post_thumbnail_id=$aimg[0];
                    break;
                }
            }
            if($post_thumbnail_id) break;
        }
        if(!$post_thumbnail_id) $post_thumbnail_id=$wpwoofeed_settings["prod_images"][$first_key][0];
    }
    if(!$post_thumbnail_id) $post_thumbnail_id = get_post_thumbnail_id( $post_id );
    
    if ( ! $post_thumbnail_id ) {
        return false;
    }
    list( $src ) = wp_get_attachment_image_src( $post_thumbnail_id, $size, false );

    return $src;
}

function wpwoofeed_product_prices( &$feed_item, $woocommerce_product ) {

    // Grab the price of the main product.
    $prices = wpwoofeed_generate_prices_for_product( $woocommerce_product );
    // Set the selected prices into the feed item.
    $feed_item->regular_price    = $prices->regular_price;
    $feed_item->sale_price       = $prices->sale_price;

}
function wpwoofeed_generate_prices_for_product( $woocommerce_product ) {
    global $store_info, $woocommerce_wpml,$wpwoofeed_wpml_debug,$sitepress,$wpdb;

    $prices = new stdClass();
    $prices->sale_price    = null;
    $prices->regular_price  = null;


    if(WPWOOF_DEBUG) 
       wpwoofStoreDebug($wpwoofeed_wpml_debug,
                          "wpwoofeed_generate_prices_for_product". $store_info->currency." != ".$store_info->default_currency
                        );
    if(WPWOOF_DEBUG) 
         wpwoofStoreDebug($wpwoofeed_wpml_debug,"PROD ID:".wpwoofeed_get_id($woocommerce_product));

    if ( /** WPML MULTYCURRENCY BLOCK */
        WoocommerceWpwoofCommon::isActivatedWMPL() && !empty($woocommerce_wpml->settings)
        && !empty($woocommerce_wpml->settings['enable_multi_currency'])

    ) {

        $rate =  ( $store_info->currencyRate>0 ) ?  $store_info->currencyRate*1.0 : 1.0;
        if($store_info->currency==$store_info->default_currency) $rate=1.0;
        if(WPWOOF_DEBUG) 
           wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_generate_prices_for_product RATE:". $rate);
        $id = wpwoofeed_get_id($woocommerce_product);
        if( version_compare( WC_VERSION, '3.0', '<' )){
            $id = isset($woocommerce_product->variation_id) ?  $woocommerce_product->variation_id : wpwoofeed_get_id($woocommerce_product);
        }
        $genID=$id;
        $dubID = get_post_meta($id, '_wcml_duplicate_of_variation', true);
        if($store_info->currency != $store_info->default_currency && $dubID) {
            if(WPWOOF_DEBUG ) wpwoofStoreDebug($wpwoofeed_wpml_debug,"FOUND DUBLICAT ITEM (".$dubID.")");
            $id=$dubID;
        }

        //if(WPWOOF_DEBUG ) wpwoofStoreDebug($wpwoofeed_wpml_debug,"get_post_meta(".$id.")");
        $meta = get_post_meta($id);
        //if(WPWOOF_DEBUG ) wpwoofStoreDebug($wpwoofeed_wpml_debug,$meta);
        if(WPWOOF_DEBUG ) wpwoofStoreDebug($wpwoofeed_wpml_debug,"---------------------------------------");

        if ( get_post_meta($id,'_wcml_custom_prices_status',true)=='1' ){
            $prices->regular_price = get_post_meta($id,'_regular_price_'.$store_info->currency,true);
            if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_generate_prices_for_product regular_price:". $prices->regular_price);

            if( get_post_meta($id,'_wcml_schedule__'.$store_info->currency,true)=='1'){
                $dt_from = get_post_meta($id,'_sale_price_dates_from_'.$store_info->currency,true);
                $dt_to = get_post_meta($id,'_sale_price_to_from_'.$store_info->currency,true);
                if(    ( empty($dt_from) || $dt_from<=time() )
                    && ( empty($dt_to) ||   $dt_from>=time() )
                ){
                    $prices->sale_price = get_post_meta($id,'_sale_price_'.$store_info->currency,true);
                    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_generate_prices_for_product sale_price:". $prices->sale_price);
                }else{
                    $prices->sale_price = null;
                }
            }else{
                $prices->sale_price  = get_post_meta($id,'_sale_price_'.$store_info->currency,true);
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"wpwoofeed_generate_prices_for_product sale_price:". $prices->sale_price);
            }
            if( $prices->sale_price>0 )    $prices->sale_price = $prices->sale_price / $rate;
            if( $prices->regular_price>0 ) {
                $prices->regular_price = $prices->regular_price / $rate;
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, 'WCML  _price_'.$store_info->currency.' prices');
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, $prices);
                if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"================================================");
                return $prices;

            }
        }
        $id=$genID;
        if($store_info->currency != $store_info->default_currency ) {
            $querystr = "
                    SELECT * FROM " . $wpdb->postmeta . "
                    WHERE  post_id = '" . $id . "'
                    AND meta_key in ('_regular_price','_sale_price') 
                    LIMIT 2";

            $rs = $wpdb->get_results($querystr, OBJECT);
            if (WPWOOF_DEBUG) {
                wpwoofStoreDebug($wpwoofeed_wpml_debug, "DB DATA:");
                wpwoofStoreDebug($wpwoofeed_wpml_debug, $rs);
            }

                
            if ($rs) foreach ($rs as $row) {
                if (isset($row->meta_key) && $row->meta_key == "_regular_price") {
                    $prices->regular_price = $row->meta_value; //*$rate; 
                } else if (isset($row->meta_key) && $row->meta_key == "_sale_price") {
                    $prices->sale_price = $row->meta_value; //*$rate; 
                }
                if (WPWOOF_DEBUG) {
                    wpwoofStoreDebug($wpwoofeed_wpml_debug, "DB PARSED DATA:");
                    wpwoofStoreDebug($wpwoofeed_wpml_debug, $prices);
                }
            }
            if ($prices->regular_price > 0) {
                if (WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, "DIRECT DB prices");
                if (WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, "SQL:" . $querystr);
                if (WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, $prices);
                if (WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, "================================================");
                return $prices;

            }
        }

    } /*  if (  WPML MULTYCURRENCY BLOCK */


    if (WoocommerceWpwoofCommon::isActivatedWMPL()) $sitepress->switch_lang( $store_info->feed_language , true);


    $regular_price  = $woocommerce_product->get_regular_price();
    if ( '' != $regular_price ) {
        $prices->regular_price = $regular_price;
    }

    // Grab the sale price of the base product.
    $sale_price                    = $woocommerce_product->get_sale_price();
    if ( $sale_price != '' ) {
        $prices->sale_price    = $sale_price ;
    }
   
    if(  empty($prices->regular_price) && $woocommerce_product->is_type('subscription_variation')){
        $id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($woocommerce_product) : $woocommerce_product->variation_id;
        $rerular = get_post_meta( $id,'_unit_price_regular',true);
        if(!empty($rerular) && is_numeric($rerular)){
            $prices->sale_price     = get_post_meta( $id,'_unit_price_sale',true);
            $prices->regular_price  = $rerular;
        }else {
            $rerular = get_post_meta( $id,'_subscription_price',true);
            if(!empty($rerular) && is_numeric($rerular)){
                $prices->regular_price  = $rerular;
            }else {
                $rerular = get_post_meta( $id,'_subscription_sign_up_fee',true);
                if(!empty($rerular) && is_numeric($rerular)){
                    $prices->regular_price  = $rerular;
                }
            }
        } /* else  _unit_price_regular if(!empty($rerular) && is_numeric($rerular)){ */
    }
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug, 'GENERAL PRICE');
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,$prices);
    if(WPWOOF_DEBUG) wpwoofStoreDebug($wpwoofeed_wpml_debug,"================================================");


    return $prices;
}

function wpwoofeed_variation_is_visible($variation) {
    if ( method_exists( $variation, 'variation_is_visible' ) ) {
        return $variation->variation_is_visible();
    }
    $visible = true;

    // Published == enabled checkbox
    if ( 'publish' !=  get_post_status ( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($variation) :  $variation->variation_id ) ) {
        $visible = false;
    }
    // Out of stock visibility
    elseif ( 'yes' == get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $variation->is_in_stock() ) {
        $visible = false;
    }
    // Price not set
    elseif ( $variation->get_price() === '' ) {
        $visible = false;
    }
    return $visible;
}
function wpwoofeed_tagvalue($tag, $product, $tagid, $tag_option = array()){
    global $wpwoofeed_settings;
    global $store_info;
    global $woocommerce_wpwoof_common, $wpwoofeed_wpml_debug;
    $field_rules = wpwoof_get_product_fields();

    $tag = str_replace('wpwoofdefa_', '', $tag);



    if( strpos($tagid, 'additional_image_link') !== false ) {
        $image_position = str_replace('additional_image_link_', '', $tagid);
        $image_position = (int) $image_position - 1;
    } else if( strpos($tag, 'additional_image_link') !== false ) {
        $image_position = str_replace('additional_image_link_', '', $tag);
        $image_position = (int) $image_position - 1;
    }
    if(  strpos($tag, 'additional_image_link') !== false ){        
        $tag = 'additional_image_link';
    }

    $length = false;
    if( isset($field_rules[$tag][$store_info->feed_type.'_len']) && $field_rules[$tag][$store_info->feed_type.'_len'] != false ){
        $length = $field_rules[$tag][$store_info->feed_type.'_len'];
    }


    switch ($tag) {
        case 'id':
            $return = wpwoofeed_get_id( $product );
            if( in_array( wpwoofeed_product_get_type($product) ,array('variation','subscription_variation') ) ) {
                $return =  version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
            }
            $return = wpwoofeed_enforce_length( $return, $length );
            return $return;
        case 'item_group_id':
            return $store_info->item_group_id;

        case 'availability':
            if( $product->is_in_stock() ) {
                $stock = 'in stock';
            } else {
                $stock = 'out of stock';
            }
            return $stock;
            break;
        case 'description':
            $description = '';
            if( in_array( wpwoofeed_product_get_type($product) , array('variation','subscription_variation') ) ){
                $product_id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
                if(!isset($wpwoofeed_settings['feed_use_variation_data']) ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])) {
                    $description = apply_filters('the_content', get_post_meta($product_id, '_variation_description', true) );
                }
                if( empty($description) ) {
                    $mainpr      = get_maim_product();
                    $description = ($mainpr) ? wpwoofeed_product_get_description( $mainpr ) : '';
                }
            }
            if( empty($description) ) {
                $description = wpwoofeed_product_get_description( $product );
            }
           
            $description = wpwoofeed_text(wpwoofeed_enforce_length($description, $length, true));            
            return $description;
            break;

        case 'description_short':

            $short_description = wpwoofeed_product_get_excerpt($product);
            if( empty($short_description) ) $short_description = wpwoofeed_product_get_short_description( $product );
            if( in_array( wpwoofeed_product_get_type($product) , array('variation','subscription_variation') ) ){
                $mainpr            = get_maim_product();
                $short_description = ($mainpr) ?  wpwoofeed_product_get_short_description($mainpr) : '';
            }

            return wpwoofeed_text(wpwoofeed_enforce_length($short_description, $length, true));

            break;
        case 'variation_description':
            $variation_description = '';
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) ){
                $product_id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
                $variation_description = apply_filters('the_content', get_post_meta($product_id, '_variation_description', true) );
                if(empty($variation_description)){
                    $mainpr                = get_maim_product();
                    $variation_description = ($mainpr) ? wpwoofeed_product_get_description ($mainpr) : '';
                }
            }

            $variation_description = wpwoofeed_text(wpwoofeed_enforce_length( $variation_description, $length, true ));


            return $variation_description;

        case 'use_custom_attribute':
            $attribute_value = '';
            if( isset( $wpwoofeed_settings['field_mapping'][$tagid]['custom_attribute'] ) ) {
                $custom_attribute = $wpwoofeed_settings['field_mapping'][$tagid]['custom_attribute'];
                $taxonomy = strtolower($custom_attribute);
                if( !empty($taxonomy) && in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )) {
                    $attributes = $product->get_variation_attributes();
                    foreach ($attributes as $attribute => $attribute_value) {
                        $attribute = strtolower($attribute);
                        if( strpos($attribute, $taxonomy) !== false ) {
                            $attribute_value = wpwoofeed_text($attribute_value, false);
                            return $attribute_value;
                        }
                    }
                }
            }
            return $attribute_value;

        case 'image_link':
            $lnk ='';
            $link = wpwoofeed_check_url(wpfeed_thumbnail_src( wpwoofeed_getParentID($product),null,$product ));
            if ( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )   && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )  ){
                $lnk = wpwoofeed_check_url(wpfeed_thumbnail_src(version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id ,null,$product));
                //$wpwoofeed_settings["prod_images"]

            }else if( !in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )  ) {
                $lnk = wpwoofeed_check_url(wpfeed_thumbnail_src( wpwoofeed_get_id( $product ),null,$product ));
            }
            return ($lnk ) ? wpwoofeed_text($lnk) : wpwoofeed_text($link);

        case 'product_image':
            $link = wpwoofeed_check_url(wpfeed_thumbnail_src( wpwoofeed_getParentID($product), 'shop_single',$product ));

            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )  && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )
            ){
                $link = wpwoofeed_check_url(wpfeed_thumbnail_src(version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id, 'shop_single',$product));
                if(empty($link)) $link = wpwoofeed_check_url(wpfeed_thumbnail_src( wpwoofeed_getParentID($product), 'shop_single',$product));
                return wpwoofeed_text($link);
            }else if( !in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) ) {
                $image = wpwoofeed_text(wpwoofeed_check_url(wpfeed_thumbnail_src( wpwoofeed_get_id( $product ), 'shop_single',$product)));
            }
            return ($image ) ? wpwoofeed_text($image) : wpwoofeed_text($link);

        case 'yoast_seo_product_image':
            $data = '';
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) &&
                ( !isset($wpwoofeed_settings['feed_use_variation_data']) ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )
            ){
                $data =  get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id, '_yoast_wpseo_opengraph-image', true );
            }

            if(empty($data)) {
                $mainpr      = get_maim_product();
                $data = ($mainpr) ?  get_post_meta( wpwoofeed_get_id($mainpr ),'_yoast_wpseo_opengraph-image', true ) : '';
            }

            if($data ){
                $src = wpwoofeed_check_url( $data );
                return   ($src && !empty($src)) ? $src : '' ;
            }
            return '';

        case 'wpfoof-mpn-name':
        case 'wpfoof-gtin-name':
                $data =  "";
                if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )  &&
                    ( !isset($wpwoofeed_settings['feed_use_variation_data'])
                        ||
                        !empty($wpwoofeed_settings['feed_use_variation_data']) )
                ){                    
                    $data =  get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id, $tag, true );
                } else {
                    $data = get_post_meta( wpwoofeed_get_id( $product ), $tag, true );
                }
    

                return $data;

        case 'wpfoof-box-media-name':
        case 'wpfoof-carusel-box-media-name':
            
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )  &&
                ( !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )
            ){
                $data =  get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id, $tag, true );
            }

            if(!$data){
                $mainpr      = get_maim_product();
                $data = ($mainpr) ?  get_post_meta( wpwoofeed_get_id($mainpr ), $tag, true ) :'';
            }

            if($data) {
                $data=wp_get_attachment_image_src($data, 'full', false );
                $src = wpwoofeed_check_url( $data[0] );
                return   ($src && !empty($src)) ? $src : '' ;
            }
            return '';

        case 'mashshare_product_image':
            $data = '';
            if( in_array(wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) &&
                (!isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )
            ){
                $data = get_post_meta( ( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id) ,'mashsb_og_image',true);
            }
            if(!$data) {
                $mainpr      = get_maim_product();
                $data = get_post_meta( wpwoofeed_get_id($mainpr ),'mashsb_og_image',true);
            }


            if($data ){
                $data=wp_get_attachment_image_src($data, 'post-thumbnail', false );
                $src = wpwoofeed_check_url( $data[0] );
                return  wpwoofeed_check_url( ($src && !empty($src)) ? $src : '' ) ;
            }


            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) &&
                (!isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data']) )
            ){
                $data = get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id,'mashsb_pinterest_image',true);
            }
            if(!$data) {
                $mainpr      = get_maim_product();
                $data = get_post_meta( wpwoofeed_get_id($mainpr ),'mashsb_pinterest_image',true);
            }

            if($data){
                $data = wp_get_attachment_image_src($data, 'post-thumbnail', false );
                $src  = wpwoofeed_check_url( $data[0] );
                return  wpwoofeed_check_url( ($src && !empty($src)) ? $src : '' );
            }

            return '';

        case 'link':
            $url = get_permalink( wpwoofeed_get_id( $product ) );
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )  ) {
                $wc_product = new WC_Product_Variation(version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id);
                $url = $wc_product->get_permalink();
                unset($wc_product);
            }
            $url = wpwoofeed_text($url);
            return $url;

        case 'title':


            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) 
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $title = wpwoofeed_product_get_title($product);
            }

            if( empty($title) ) {
                $mainpr = get_maim_product();
                $title  = ($mainpr) ?  wpwoofeed_product_get_title($mainpr) : '';
            }

            $title = wpwoofeed_enforce_length( $title, $length, true );
            return $title;
        case 'site_name':
            return wpwoofeed_enforce_length( $store_info->blog_name, $length, true );
            break;
        case 'mpn':
            $data = $product->get_sku();
            if( wpwoofeed_product_get_type($product)=='subscription_variation' ) {
                $data = get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id,'_sku');
            }                    
            return wpwoofeed_text($data,false);
            break;
        case 'condition':
            break;
        case 'brand':
            break;
        case 'additional_image_link':
            $tagvalue = '';
            $imgIds = version_compare( WC_VERSION, '3.0', '>=' ) ?   $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
            
            if(!count($imgIds)){
                $product = get_maim_product();
                if($product) $imgIds = version_compare( WC_VERSION, '3.0', '>=' ) ?   $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
            } 
            if(WPWOOF_DEBUG) {
                wpwoofStoreDebug($wpwoofeed_wpml_debug, 'IMAGES:'); 
                wpwoofStoreDebug($wpwoofeed_wpml_debug,  $imgIds); 
            }
            $images = array();
            if (count($imgIds)) {
                foreach ($imgIds as $key => $value) {
                    if ($key < 9) {
                        $images[$key] = wpwoofeed_check_url(wp_get_attachment_url($value));
                    }
                }
            }
            if ($images && is_array($images) ) {
                if( isset( $images[$image_position] ) )
                    $tagvalue.= $images[$image_position] . ',';
            }
            $tagvalue = rtrim($tagvalue, ',');
            $tagvalue = wpwoofeed_text($tagvalue);
            
            return $tagvalue;
            break;

        case 'product_type':

            $prId =  wpwoofeed_getParentID($product);
            if(!$prId) $prId = wpwoofeed_get_id( $product );
            $categories = wp_get_object_terms( $prId, 'product_cat');

            $categories_string = array();
            if( ! is_wp_error($categories) ) {
                foreach($categories as $cat) {
                    $categories_string[] = $cat->name;
                }
            }
            $categories_string = implode(' > ', $categories_string);
            $categories_string = wpwoofeed_enforce_length( $categories_string, $length, true );

            $categories_string = wpwoofeed_text($categories_string);

            return $categories_string;
            break;
        case 'product_type_normal':
            $product_type = wpwoofeed_product_get_type($product);
            $product_type = wpwoofeed_text($product_type);
            return $product_type;
            break;
        case 'sale_price_effective_date':
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) )
                $product_id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
            else
                $product_id = wpwoofeed_get_id( $product );
            $from = get_post_meta($product_id, '_sale_price_dates_from', true);
            $to = get_post_meta($product_id, '_sale_price_dates_to', true);

            if (!empty($from) && !empty($to)) {
                $from = date_i18n('Y-m-d\TH:iO', $from);
                $to = date_i18n('Y-m-d\TH:iO', $to);
                $date = "$from" . "/" . "$to";
            } else {
                $date = "";
            }
            $tagvalue = $date;

            $tagvalue = wpwoofeed_text($tagvalue, false);
            return $tagvalue;
            break;
        case 'shipping':
            $shipping = $product->get_shipping_class();
            $shipping = wpwoofeed_text($shipping, false);
            return $shipping;
            break;
        case 'shipping_weight':

       
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_weight();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_weight() : '';
            }

            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_weight_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            break;

        case 'shipping_length':

   
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) 
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_length();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_length() : '';
            }

            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            }
            return '';

        case 'shipping_width':
           if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_width();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_width() : '';
            }

            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            return $tagvalue;

        case 'shipping_height':
            if(   in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_height();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_height() : '';
            }

            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            return $tagvalue;
            break;
        case 'size':
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_dimensions();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_dimensions() : '';
            }

            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_size_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            }
            return '';

        case 'length':
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_length();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_length() : '';
            }
            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            }
            return '';

        case 'width':
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_width();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_width() : '';
            }


            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            return $tagvalue;

        case 'height':
       
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_height();
            }

            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_height() : '';
            }
            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_dimension_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            return $tagvalue;
            break;
        case 'weight':
       
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tagvalue = $product->get_weight();
            }
            if( empty($tagvalue) ) {
                $mainpr = get_maim_product();
                $tagvalue  = ($mainpr) ?  $mainpr->get_weight() : '';
            }


            if( !empty($tagvalue) ) {
                $unit = get_option( 'woocommerce_weight_unit' );
                $tagvalue = $tagvalue . ' ' . esc_attr($unit);
                $tagvalue = wpwoofeed_text($tagvalue, false);
                return $tagvalue;
            } else {
                return '';
            }
            break;
        case 'tags':

            $tags_string = array();
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $tags = wp_get_object_terms( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id , 'product_tag');

                if( ! is_wp_error($tags) ) {
                    foreach($tags as $tag) {
                        $tags_string[] = $tag->name;
                    }
                }
            }
            if( count($tags_string)==0 ) {
                $mainpr = get_maim_product();
                if ($mainpr) {
                    $tags = wp_get_object_terms( wpwoofeed_get_id( $mainpr ), 'product_tag');
                    if( ! is_wp_error($tags) ) {
                        foreach($tags as $tag) {
                            $tags_string[] = $tag->name;
                        }
                    }
                }
            }

            $tags_string = implode(', ', $tags_string);
            $tags_string = wpwoofeed_enforce_length( $tags_string, $length, true );
            $tags_string = wpwoofeed_text($tags_string, false);
            return $tags_string;
            break;
        case 'custom_label_':
            break;
        case 'stock_quantity':           
            $qty = $product->get_stock_quantity();
            return ($qty>0) ? $qty : "0";
            break;
        case 'average_rating':
            $average_rating = '';
          
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $average_rating = $product->get_average_rating();
            }
            if( empty($average_rating) ) {
                $mainpr = get_maim_product();
                $average_rating  = ($mainpr) ?  $mainpr->get_average_rating() : '';
            }

            return $average_rating;
            break;
        case 'total_rating':
            $total_rating = '';
            
            if(  in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') )
                && (
                    !isset($wpwoofeed_settings['feed_use_variation_data'])
                    ||
                    !empty($wpwoofeed_settings['feed_use_variation_data'])
                )
            ){
                $total_rating = $product->get_rating_count();
            }
            if( empty($total_rating) ) {
                $mainpr = get_maim_product();
                $total_rating  = ($mainpr) ?  $mainpr->get_rating_count() : '';
            }


            return $total_rating;
            break;
        case 'sale_start_date':
        
            if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) )
                $product_id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
            else
                $product_id = wpwoofeed_get_id( $product );
            $from = get_post_meta($product_id, '_sale_price_dates_from', true);

            if (!empty($from)) {
                $tagvalue = date_i18n('Y-m-d\TH:iO', $from);
            } else {
                $tagvalue = "";
            }
            $tagvalue = wpwoofeed_text($tagvalue, false);
            return $tagvalue;
            break;
        case 'sale_end_date':
           if( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) )
                $product_id = version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id;
            else
                $product_id = wpwoofeed_get_id( $product );
            $to = get_post_meta($product_id, '_sale_price_dates_to', true);

            if (!empty($to)) {
                $tagvalue = date_i18n('Y-m-d\TH:iO', $to);
            } else {
                $tagvalue = "";
            }
            $tagvalue = wpwoofeed_text($tagvalue, false);
            return $tagvalue;
            break;
        case 'price':
            return wpwoofeed_price($product, null, null, $tagid);
            break;
        case 'sale_price':
            return wpwoofeed_sale_price($product, null, null, $tagid);
            break;

        default:
            return '';
            break;
    }
    return '';
}
function wpwoofeed_product_is_excluded($woocommerce_product){
    $excluded = false;
    // Check to see if the product is set as Hidden within WooCommerce.
    if ( 'hidden' == $woocommerce_product->visibility ) {
        $excluded = true;
    }
    // Check to see if the product has been excluded in the feed config.
    if ( $tmp_product_data = wpwoofeed_get_product_meta( $woocommerce_product, 'woocommerce_wpwoof_data' ) ) {
        $tmp_product_data = maybe_unserialize( $tmp_product_data );
    } else {
        $tmp_product_data = array();
    }
    if ( isset ( $tmp_product_data['exclude_product'] ) ) {
        $excluded = true;
    }

    return apply_filters( 'wpwoof_exclude_product', $excluded, wpwoofeed_get_id( $woocommerce_product ), 'facebook');
}
function wpwoofeed_get_product_meta( $product, $field_name ) {
    if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) >= 0 ) {
        // even in WC >= 2.0 product variations still use the product_custom_fields array apparently        
        if ( $product->variation_id && isset( $product->product_custom_fields[ '_' . $field_name ][0] ) && $product->product_custom_fields[ '_' . $field_name ][0] !== '' ) {
            return $product->product_custom_fields[ '_' . $field_name ][0];
        }
        // use magic __get
        return $product->$field_name;
    } else {
        // variation support: return the value if it's defined at the variation level
        if ( in_array( wpwoofeed_product_get_type($product), array('variation','subscription_variation') ) ) {
            if ( ( $value = get_post_meta( version_compare( WC_VERSION, '3.0', '>=' ) ? wpwoofeed_get_id($product) : $product->variation_id, '_' . $field_name, true ) ) !== '' ) {
                return $value;
            }
            // otherwise return the value from the parent
            return get_post_meta( wpwoofeed_get_id( $product ), '_' . $field_name, true );
        }
        // regular product
        return isset( $product->product_custom_fields[ '_' . $field_name ][0] ) ? $product->product_custom_fields[ '_' . $field_name ][0] : null;
    }

}
