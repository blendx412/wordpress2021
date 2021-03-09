<?php
/*
Plugin Name: Product Catalog Feed Pro by PixelYourSite
Description: WooCommerce Products Feed for Facebook Product Catalog. You can create XML feeds for Facebook Dynamic Product Ads.
Plugin URI: http://www.pixelyoursite.com/product-catalog-facebook
Author: PixelYourSite
Author URI: http://www.pixelyoursite.com
Version: 3.3.9
WC requires at least: 3.0.0
WC tested up to: 3.4.0
*/
/* Following are used for updating plugin */


//Plugin Version
define( 'WPWOOF_VERSION', '3.3.9');
//NOTIFICATION VERSION
define( 'WPWOOF_VERSION_NOTICE', '0.0.0');
define( 'WPWOOF_NOTICE_CONTENT', '<span>Product Catalog Plugin:</span>  we\'ve made some changes in the way your WooCommerce feed works, so you need to open the Product Catalog Plugin, Edit your feed, select your TAX settings and save it.</a>');

//Plugin Update URL
define( 'WPWOOF_SL_STORE_URL', 'http://www.pixelyoursite.com' );
//Plugin Name
define( 'WPWOOF_SL_ITEM_NAME', 'Product Catalog Feed Pro' );

//Plugin Base
define( 'WPWOOF_BASE', plugin_basename( __FILE__ ) );
//Plugin PAtH
define( 'WPWOOF_PATH', plugin_dir_path( __FILE__ ) );
//Plugin URL
define( 'WPWOOF_URL', plugin_dir_url( __FILE__ ) );
//Plugin assets URL
define( 'WPWOOF_ASSETS_URL', WPWOOF_URL . 'assets/' );
//Plugin
define( 'WPWOOF_PLUGIN', 'wp-woocommerce-feed');

//Plugin
define( 'WPWOOF_DEBUG', false );


if(WPWOOF_DEBUG){
    function trace ($obj,$onexit=0){
        echo "<pre>".print_r($obj,true)."</pre>";
        if($onexit) exit();
    }
    function wpwoofStoreDebug($file,$data){
       trace(date('Y-m-d H:i:s')."\t".print_r($data,true)."\n");
       file_put_contents($file,date('Y-m-d H:i:s')."\t".print_r($data,true)."\n",FILE_APPEND);
    }

}

require_once('inc/helpers.php');
require_once('inc/generate-feed.php');
require_once('inc/admin.php');
require_once('inc/feed-list-table.php');
require_once('inc/admin_notices.php' );

class wpwoof_product_catalog {
    static $interval = '86400';
    static $schedule = array(
        '3600'  => 'hourly',
        '43200' => 'twicedaily',
        '86400' => 'daily',
        '604800' => 'weekly'
    );
    static $field_names = array(

        'wpfoof-explude-product'        =>   array(
            "title"=>'Exclude Product '       ,
            "subscription"=>'Exclude the product from feed',
            "main"=> true,
            "type"=> 'checkbox'
        ),
        'wpfoof-mpn-name'        =>   array(
            "title"=>'MPN '       ,
            "subscription"=>'Manufacturer part number',
            "type"=> 'text'
        ),
        'wpfoof-gtin-name'        =>   array(
            "title"=>'GTIN '       ,
            "subscription"=>'Global Trade Item Number(GTINs may be 8, 12, 13 or 14 digits long)',
            "type"=> 'text'
        ),
        'wpfoof-carusel-box-media-name'        =>   array(
            "title"=>'Carousel ad '       ,
            "subscription"=>'(1080X1080 recommended)',
            "size"=>"1080X1080"
        ),
        'wpfoof-box-media-name'                =>   array(
            "title"=>'Single product ad ' ,
            "subscription"=>'(1200X628 recommended)',
            "size"=>"1200X628"

        )
    );

    function __construct() {
        /*if( ! empty( $_GET['pcbpys_license_deactivate'] ) ) {
            $_POST['pcbpys_license_deactivate'] = true;
        }*/
        global $xml_has_some_error;
        $xml_has_some_error = false;
        register_activation_hook(__FILE__, array(__CLASS__, 'activate'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'deactivate'));

        add_action('init', array(__CLASS__, 'init'));
        add_action('admin_init', array(__CLASS__, 'admin_init'));
        //

        // Display Fields
        add_action( 'woocommerce_product_options_general_product_data', array(__CLASS__, 'add_extra_fields'), 10);
        add_action( 'woocommerce_product_after_variable_attributes',array(__CLASS__, 'add_extra_fields_variable'), 10, 3 );
        // Save Fields
        add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_extra_fields' ), 10, 2 );
        add_action( 'woocommerce_save_product_variation', array( __CLASS__, 'save_extra_fields' ), 10, 2 );


        add_action('admin_menu', array(__CLASS__, 'admin_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));

        add_filter('cron_schedules'      , array(__CLASS__ , 'cron_schedules'    ));
        add_action('wpwoof_feed_update'  , array(__CLASS__ , 'wpwoof_feed_update'));
        add_action('wpwoof_generate_feed', array(__CLASS__ , 'do_this_generate'  ), 10, 3 );
        //////////////////////////////////////



        add_filter( 'http_request_host_is_external', array(__CLASS__, 'http_request_host_is_external'), 10, 3 );
        if( !class_exists( 'WPWOOF_Plugin_Updater' ) ) {
            include plugin_dir_path( __FILE__ ) . 'inc/plugin-updater.php';
        }
    }

    static function init() {
        self::$interval = get_option('wpwoof_schedule', '86400');
        $is_xml = ( isset($_GET['wpwoofeedxmldownload']) && wp_verify_nonce( $_GET['wpwoofeedxmldownload'], 'wpwoof_download_nonce' ) );
        $is_csv = ( isset($_GET['wpwoofeedcsvdownload']) && wp_verify_nonce( $_GET['wpwoofeedcsvdownload'], 'wpwoof_download_nonce' ) );
        if( $is_xml || $is_csv ){

            $option_id = $_GET['feed'];
            $data = wpwoof_get_feed($option_id);
            $data = unserialize($data);
            $data['edit_feed']= $option_id;
            $feedname = $data['feed_name'];
            $upload_dir = wpwoof_feed_dir($feedname, ($is_xml ? 'xml' : 'csv'));
            $file = $upload_dir['path'];
            $path = $upload_dir['path'];
            $fileurl = $upload_dir['url'];
            $file_name = $upload_dir['file'];
            if($is_csv && empty($_GET['dwnl'])) @unlink($path);
            else self::downloadFile($file,$file_name,$is_csv);
            $dir_path = str_replace( $file_name, '', $path );
            $create_csv = false;
            if(wpwoof_checkDir($dir_path)) {
              $create_csv = wpwoofeed_generate_feed($data, $is_xml ? 'xml' : 'csv', $file);
            }
            return;
        }
    }
    static function downloadFile($file,$file_name,$is_csv=false){
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            if( !$is_csv ) {
                header('Content-Type: text/xml');
            } else {
                header('Content-Type: application/octet-stream');
            }
            header('Content-Disposition: attachment; filename="'.$file_name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            if($is_csv)  @unlink($file);
            exit;
        }else if($is_xml){
            wp_die( 'Error: File not found', ( $is_xml ? 'XML Download' : 'CSV Download' ) );
            exit;
        }
    }

    static function feed_dir($feedname, $file_type='xml'){
        $feedname = str_replace(' ', '-', $feedname);
        $feedname = strtolower($feedname);
        $upload_dir = wp_upload_dir();
        $base = $upload_dir['basedir'];
        $baseurl = $upload_dir['baseurl'];
        $feedService = 'facebook';
        $path = "{$base}/wpwoof-feed/{$feedService}/{$file_type}";
        $baseurl = $baseurl . "/wpwoof-feed/{$feedService}/{$file_type}";
        $file = "{$path}/{$feedname}.{$file_type}";
        $fileurl = "{$baseurl}/{$feedname}.{$file_type}";

        return array('path' => $file, 'url'=>$fileurl, 'file' => $feedname . '.'.$file_type);
    }

    static function admin_init() {
        // retrieve our license key from the DB
        $license_key = trim( get_option( 'pcbpys_license_key' ) );
        // setup the updater
        $edd_updater = new WPWOOF_Plugin_Updater( WPWOOF_SL_STORE_URL, __FILE__, array(
            'version' 	=> WPWOOF_VERSION,      // current version number
            'license' 	=> $license_key,        // license key (used get_option above to retrieve from DB)
            'item_name' => WPWOOF_SL_ITEM_NAME, // name of this plugin
            'author' 	=> 'PixelYourSite'      // author of this plugin
        ) );



        global $wpdb,$wpwoof_values, $wpwoof_add_button, $wpwoof_add_tab, $wpwoof_message, $wpwoofeed_oldname;
        $wpwoof_values      = array();
        $wpwoof_add_button  = 'Generate the Feed';
        $wpwoof_add_tab     = 'Add New Feed';
        $wpwoof_message     = '';
        $wpwoofeed_oldname  = '';



        if( isset($_POST['check_feed_name'])){
            $feed_name = sanitize_text_field($_POST['check_feed_name']);
            header('Content-Type: application/json');
            if (! get_option('wpwoof_feedlist_' . $feed_name, false) ){
                exit( json_encode( array("status"=>"OK") ) );
            } else {
                $aExists =  Array();
                $sql = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wpwoof_feedlist_" . $feed_name . "%'";
                $res = $wpdb->get_results($sql, 'ARRAY_A');
                foreach($res as $val){
                    $aExists[]=$val['option_name'];
                }
                exit(json_encode($aExists));
            }

        }
        if ( ! isset($_REQUEST['page']) || $_REQUEST['page'] != 'wpwoof-settings' ) {
            return;
        }
        if( isset($_POST['wpwoof-status'])){

            $var = "wpwoof_status_";
            $sql = "SELECT * FROM $wpdb->options WHERE option_name LIKE '%$var%'";
            $result = $wpdb->get_results($sql, 'ARRAY_A');
            foreach($result as $key=>$val){
                $result[$key]['option_value'] = unserialize($result[$key]['option_value']);
                $result[$key]['feed_id'] = (!empty($result[$key]['option_name'])) ? str_replace($var,"",$result[$key]['option_name']) : 0 ;


            }
            header('Content-Type: application/json');
            exit(json_encode($result));
        }else if( isset($_POST['wpwoof-addfeed-submit']) ) {

            $values = $_POST;

            unset($values['wpwoof-addfeed-submit']);
            $values['added_time'] = time();
            $feed_name = sanitize_text_field($values['feed_name']);
            $values['field_mapping'] = wpwoof_feed_option_fulled($values['field_mapping']);

            if( isset($_POST['edit_feed']) && !empty($_POST['edit_feed']) ){
                if( isset($_POST['old_feed_name']) && !empty($_POST['old_feed_name'])) {
                    $oldfile = trim($_POST['old_feed_name']);
                    $oldfile = strtolower($oldfile);
                    $newfile = trim($_POST['feed_name']);
                    $newfile = strtolower($newfile);
                    if( $newfile != $oldfile ) {
                        wpwoof_delete_feed_file($_POST['edit_feed']);
                        wpwoof_update_feed(serialize($values), $_POST['edit_feed'],false,$feed_name);
                    }
                }
                $url = wpwoof_create_feed($feed_name, $values);

                $values['url'] = $url;
                $updated = wpwoof_update_feed(serialize($values), $_POST['edit_feed']);

                update_option('wpwoof_message', 'Feed Updated Successully.');
                $wpwoof_message = 'success';
            } else {

                if(update_option('wpwoof_feedlist_' . $feed_name, $values)){
                    global $wpdb;
                    $sql = "SELECT * FROM $wpdb->options WHERE option_name = 'wpwoof_feedlist_" . esc_sql( $feed_name ) . "'";
                    $result = $wpdb->get_results($sql, 'ARRAY_A');
                    if(count($result)==1 ) {
                        $values['edit_feed']=$result[0]['option_id'] ;
                        $url = wpwoof_create_feed($feed_name, $values);
                    }
                }
            }
            /* Reload the current page */
            wpwoof_refresh( $wpwoof_message );
        } else if ( isset( $_REQUEST['delete'] ) && !empty( $_REQUEST['delete'] ) ) {
            $id = $_REQUEST['delete'];
            $deleted = wpwoof_delete_feed($id);

            if( $deleted ) {
                wp_cache_flush();
                update_option('wpwoof_message', 'Feed Deleted Successully.');
                $wpwoof_message = 'success';
            } else {
                update_option('wpwoof_message', 'Failed To Delete Feed.');
                $wpwoof_message = 'error';
            }
            /* Reload the current page */
            wpwoof_refresh( $wpwoof_message );

        } else if ( isset($_REQUEST['edit']) && !empty($_REQUEST['edit']) ) {
            $option_id = $_REQUEST['edit'];
            $feed = wpwoof_get_feed($option_id);
            $wpwoof_values = unserialize($feed);
            $wpwoof_values['edit_feed']=$option_id;
            $wpwoofeed_oldname = isset($wpwoof_values['feed_name']) ? $wpwoof_values['feed_name'] : '';
            $wpwoof_add_button = 'Update the Feed';
            $wpwoof_add_tab = 'Edit Feed : ' . $wpwoof_values['feed_name'];
        } else if ( isset($_REQUEST['update']) && !empty($_REQUEST['update']) ) {
            $option_id = $_REQUEST['update'];
            $feed = wpwoof_get_feed($option_id);
            $wpwoof_values = unserialize($feed);

            $wpwoof_values['edit_feed']=$option_id;
            $feed_name = sanitize_text_field($wpwoof_values['feed_name']);
            $wpwoof_values['added_time'] = time();
            $url = wpwoof_create_feed($feed_name, $wpwoof_values);
            $wpwoof_values['url'] = $url;
            $updated = wpwoof_update_feed(serialize($wpwoof_values), $option_id);
            $wpwoof_message = '';
            if($url) {
                update_option('wpwoof_message', 'Feed Regenerated Successully.');
                $wpwoof_message = 'success';
            }


            /* Reload the current page */
            wpwoof_refresh( $wpwoof_message );
        } else if ( isset($_REQUEST['generate']) && !empty($_REQUEST['generate']) ) {
            $option_id = $_REQUEST['generate'];
            $feed = wpwoof_get_feed($option_id);
            $wpwoof_values = unserialize($feed);
            $wpwoof_values['edit_feed']=$option_id;



            $feed_name = sanitize_text_field($wpwoof_values['feed_name']);
            $url = wpwoof_create_feed($feed_name, $wpwoof_values);

            $wpwoof_message = '';
            if($url) {
                update_option('wpwoof_message', 'Feed Generated Successully.');
                $wpwoof_message = 'success';
            }


            /* Reload the current page */
            wpwoof_refresh( $wpwoof_message );
        }
    }

    static function admin_menu() {
        add_menu_page( 'Product Catalog', 'Product Catalog Pro', 'manage_options', 'wpwoof-settings', array(__CLASS__, 'menu_page_callback'), WPWOOF_URL . '/assets/img/favicon.png');
    }

    static function menu_page_callback() {
        if( isset($_POST['wpwoof_schedule_submit']) ){
            $option = $_POST['wpwoof_schedule'];


            if(!empty( self::$schedule[$option])) {
                self::$interval = $option;
                update_option('wpwoof_schedule', self::$interval);


                wp_clear_scheduled_hook('wpwoof_feed_update');
                if (!empty(self::$schedule[$option])) {
                    wp_schedule_event(time(), self::$schedule[$option], 'wpwoof_feed_update');
                }
            }
        }
        require_once('view/admin/settings.php');
    }

    static function admin_enqueue_scripts() {
        if(isset($_GET['page']) && $_GET['page'] == 'wpwoof-settings' ){
            //Admin Style
            wp_enqueue_style( WPWOOF_PLUGIN.'-style', WPWOOF_ASSETS_URL . 'css/admin.css', array(), WPWOOF_VERSION, false );
            //Admin Javascript
            wp_enqueue_script( WPWOOF_PLUGIN.'-script', WPWOOF_ASSETS_URL . 'js/admin.js', array('jquery'), WPWOOF_VERSION, false );
            wp_enqueue_script( WPWOOF_PLUGIN.'-optionTree', WPWOOF_ASSETS_URL . 'js/jquery.optionTree.js', array('jquery'), WPWOOF_VERSION, false );

            wp_localize_script( WPWOOF_PLUGIN.'-script', 'WPWOOF', array( 'ajaxurl'=> admin_url('admin-ajax.php'), 'loading' => admin_url('images/loading.gif') ) );
        }
    }





    static function cron_schedules($schedules) {
        $interval = self::$interval;

        foreach(self::$schedule as $sec => $name){
            if(!isset($schedules[$name])){
                $schedules[$name] = array(
                    'interval' => $sec*1,
                    'display' => __($name));
            }
        }

        return $schedules;
    }
    static function do_this_generate( $feed_id ) {
        if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tSTART do_this_generate\t".$feed_id."\n",FILE_APPEND);
        self::wpwoof_feed_go_update(array(0=>array("option_id"=>$feed_id)));
    }
    static function wpwoof_feed_update() {
        global $wpdb;
        $var = "wpwoof_feedlist_";
        $sql = "SELECT option_id FROM $wpdb->options WHERE option_name LIKE '".$var."%'";
        if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tSTART wpwoof_feed_update\n",FILE_APPEND);
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        self::wpwoof_feed_go_update($result);
    }

    static function wpwoof_feed_go_update($result) {
        if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tSTART wpwoof_feed_go_update\n",FILE_APPEND);
        $vFirst=null;
        $time = time();
        foreach ($result as $key => $value) {
            if(!$vFirst) {
                $vFirst=$value;
            } else {
                $time+=600;//10min
                wp_schedule_single_event( $time, 'wpwoof_generate_feed', array( $value['option_id'] ) );
                if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tSET wpwoof_generate_feed\t".$value['option_id']."\tTIME:".date("Y-m-d H:i:s",$time)."\n",FILE_APPEND);
            }
        }
        if($vFirst){
            $option_id = $vFirst['option_id'];
            if($option_id){
                if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tSTART wpwoof_feed_go_update\t".$option_id."\tTIME:".date("Y-m-d H:i:s")."\n",FILE_APPEND);

                $feed = wpwoof_get_feed($option_id);
                $wpwoof_values = unserialize($feed);
                $wpwoof_values['edit_feed']=$option_id;


                $feed_name = sanitize_text_field($wpwoof_values['feed_name']);
                $wpwoof_values['added_time'] = time();
                $url = wpwoof_create_feed($feed_name, $wpwoof_values);
                $wpwoof_values['url'] = $url;
                $updated = wpwoof_update_feed(serialize($wpwoof_values), $option_id);
                if(WPWOOF_DEBUG) file_put_contents(WPWOOF_PATH.'cron-wpfeed.log',date("Y-m-d H:i:s")."\tEND wpwoof_feed_go_update\t".print_r($updated,true)."\n",FILE_APPEND);
            }
        }
    }

    static function activate() {
        wp_schedule_event(time(), 'twicedaily', 'wpwoof_feed_update');

        $path_upload 	= wp_upload_dir();
        $path_upload 	= $path_upload['basedir'];
        $pathes = array(
            array('wpwoof-feed', 'facebook', 'xml'),
            array('wpwoof-feed', 'facebook', 'csv'),
        );
        foreach($pathes as $path) {
            $path_folder = $path_upload;
            foreach($path as $folder) {
                $path_created = false;
                if( is_writable($path_folder) ) {
                    $path_folder = $path_folder.'/'.$folder;
                    $path_created = is_dir($path_folder);
                    if( ! $path_created ) {
                        $path_created = mkdir($path_folder, 0755);
                    }
                }
                if( ! is_writable($path_folder) || ! $path_created ) {
                    self::deactivate_generate_error('Cannot create folders in uploads folder', true, true);
                    die('Cannot create folders in uploads folder');
                }
            }
        }
    }

    static function deactivate() {
        wp_clear_scheduled_hook('wpwoof_feed_update');
    }

    static function deactivate_generate_error($error_message, $deactivate = true, $echo_error = false) {
        if( $deactivate ) {
            deactivate_plugins(array(__FILE__));
        }
        if($error_message) {
            $message = "<div class='notice notice-error is-dismissible'>
            <p>" . $error_message . "</p></div>";
            if ($echo_error) {
                echo $message;
            } else {
                add_action('admin_notices', create_function('', 'echo "' . $message . '";'), 9999);
            }
        }
    }

    static function http_request_host_is_external( $allow, $host, $url ) {
        if ( $host == 'woocommerce-5661-12828-90857.cloudwaysapps.com' )
            $allow = true;
        return $allow;
    }
    static function add_extra_fields_variable($loop, $variation_data, $post){
        ?><div class="woocommerce_variable_attributes">
        <h2>Product Catalog Feed for Variable</h2>
        <?php
        self::extra_fields_box_func( $post );
        ?></div><?php
    }

    static function add_extra_fields(){
        global $post;
        ?><div class="options_group show_if_simple show_if_external show_if_variable show_if_bundle">
        <h2>Product Feed Catalog</h2>
        <?php
        self::extra_fields_box_func($post,true);
        ?></div><?php
      /*  add_meta_box( 'extra_fields', 'Product Catalog Feed Ads Images',array(__CLASS__, 'extra_fields_box_func'), 'product', 'normal', 'high'  );*/
    }



    static function extra_fields_box_func( $post ,$isMain=false){
        $post_id = (isset($post->ID)) ? $post->ID : '0';
        wp_enqueue_media();
        wp_enqueue_script( WPWOOF_PLUGIN.'-media-script', WPWOOF_ASSETS_URL . 'js/media.js', array('jquery'), WPWOOF_VERSION, false );
        wp_enqueue_style( WPWOOF_PLUGIN.'-style', WPWOOF_ASSETS_URL . 'css/admin.css', array(), WPWOOF_VERSION, false );
        wp_nonce_field( 'nonce_action', 'nonce_name' );

        foreach ( self::$field_names as $key => $val ) {
            if( !$isMain && empty($val['main']) || $isMain ){
                $value = $rawvalue = ($post_id) ? get_post_meta( $post_id, $key, true ) : '';
                $key   = esc_attr( $key );
                $value = esc_attr( $value );
                ?><p class="form-field  form-row custom_field_type"><?php
                if(empty($val['type'])){
                    $s = explode("x",$val['size']);
                    $image = ! $rawvalue ? '' : wp_get_attachment_image( $rawvalue, 'full', false, array('style' => 'display:block;margin-left:auto;margin-right:auto;max-width:30%;height:auto;') );
                    ?>
                    <span  id='IDprev-<?php echo $post_id."-".$key; ?>'class='image-preview'><?php echo ($image) ? ($image."<br/>") : "" ?></span>
                    <label for="<?php echo $post_id."-".$key; ?>-value"><?php echo $val['title'];?></label>
                    <span class="wrap wpwoof-required-value">
                    <input type='hidden' id='_value-<?php echo $post_id."-".$key; ?>'      name='wpfoof-box-media[<?php echo $post_id."-".$key?>]'   value='<?php echo $value?>' />
                    <input type='button' id='<?php echo $post_id."-".$key; ?>'   onclick="jQuery.fn.clickWPfoofClickUpload(this);"     class='button wpfoof-box-upload-button'        value='Upload' />
                    <input type='button' id='<?php echo $post_id."-".$key; ?>-remove' onclick="jQuery.fn.clickWPfoofClickRemove(this);" <?php if(empty($image)) {?>style="display:none;"<?php } ?> class='button wpfoof-box-upload-button-remove' value='Remove' />
                    </span>
                    <span class="unlock_pro_features" data-size='<?php echo esc_attr( $val['size']);?>'  id='<?php echo $post_id."-".$key; ?>-alert'>
                    </span>
                    <?php
                }//if(empty($val['type'])){
                else if($val['type']=="checkbox"){
                    ?>
                    <label for="<?php echo $post_id."-".$key; ?>-value"><?php echo $val['title'];?></label>
                    <span class="wrap wpwoof-required-value">
                    <input type='hidden'   id='value-<?php echo $post_id."-".$key; ?>'      name='wpfoof-box-media[<?php echo $post_id."-".$key?>]'   value='0' />
                    <input type='checkbox' id='_value-<?php echo $post_id."-".$key; ?>'     name='wpfoof-box-media[<?php echo $post_id."-".$key?>]'   value='1'  <?php if($value) echo "checked='true'"; ?> />
                    </span>
                    <?php
                }  else if($val['type']=="text"){
                    ?>
                    <label for="<?php echo $post_id."-".$key; ?>-value"><?php echo $val['title'];?></label>
                    <span class="wrap wpwoof-required-value">
                    <input type='text' id='_value-<?php echo $post_id."-".$key; ?>'   class='short wc_input_<?php echo $key; ?>'  name='wpfoof-box-media[<?php echo $post_id."-".$key?>]'   value='<?php echo $value; ?>' />
                    </span>
                    <?php
                }
                ?>
                <span class="woocommerce-help-tip" data-tip="<?php echo esc_attr($val['subscription']); ?>" ></span>
                </p><?php
            }//if(!$isMain && empty($val['main']) || $isMain){
        }
    }
    static function save_extra_fields( $post_id, $post ){
            /*echo "save_extra_fields:";*/


            if ( !isset( $_POST['wpfoof-box-media'] ) ) return;
            if ( ! isset( $_POST['nonce_name'] ) ) //make sure our custom value is being sent
                return;
            if ( ! wp_verify_nonce( $_POST['nonce_name'], 'nonce_action' ) ) //verify intent
                return;
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) //no auto saving
                return;
            if ( ! current_user_can( 'edit_post', $post_id ) ) //verify permissions
                return;
            /* exit(print_r($_POST,true)); */
            $new_value = array_map( 'trim', $_POST['wpfoof-box-media'] ); //sanitize

            foreach ( $new_value as $k => $v ) {
                $k = str_replace($post_id."-","",$k);
                $k = str_replace("0-","",$k);
                $old_val = get_post_meta( $post_id, $k, true);
                if($old_val!=$v || !empty($v) ) { update_post_meta( $post_id, $k, $v ); } //save
                /*else { delete_post_meta( $post_id, $k); }*/
            }

    }



}
new wpwoof_product_catalog;
