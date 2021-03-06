<?php
require_once("common.php");
global $woocommerce_wpwoof_common;


function wpwoof_delete_feed( $id ) {
        global $wpdb;
        wpwoof_delete_feed_file($id);
        return $wpdb->delete(
            "{$wpdb->prefix}options",
            array('option_id' => $id),
            array('%d')
        );
}

function wpwoof_update_feed( $option_value, $option_id,$flag=false,$feed_name='' ) {     
        global $wpdb;
        //wpwoof_delete_feed_file($id);
        if(!$flag) {
            $option_value = unserialize($option_value);
            if (empty($option_value['status_feed'])) {
                $option_value['status_feed'] = "";
            }

            $tmpdata = unserialize(wpwoof_get_feed($option_id));

            if (!empty($tmpdata['status_feed'])) {
                $option_value['status_feed'] = $tmpdata['status_feed'];
            }
            $option_value = serialize($option_value);
        }

        $table = "{$wpdb->prefix}options";
        $data = array('option_value'=>$option_value);
        if($feed_name) {
            $data['option_name'] = 'wpwoof_feedlist_'. $feed_name;
        }    
        //trace($data,1) ;        
        $where = array('option_id'=>$option_id);
        return $wpdb->update( $table, $data, $where);
}


function wpwoof_get_feeds( $search = "" ) {

    global $wpdb;
    $option_name="wpwoof_feedlist_";
    if( $search != '' )
    	$option_name = $search;

    $query = $wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", "%".$option_name."%");
    $result = $wpdb->get_results($query, 'ARRAY_A');

    return $result;
}

function wpwoof_get_feed( $option_id ) {
    global $wpdb;

    $query = $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_id='%s';", $option_id);
    $result = $wpdb->get_var($query);
    $result = unserialize($result);
    $result['edit_feed'] = $option_id;
    $result = serialize($result);
    return $result;
}

function wpwoof_feed_dir( $feedname, $file_type = 'xml', $feedService = 'facebook' ) {
    $feedname = str_replace(' ', '-', $feedname);
    $feedname = strtolower($feedname);
    $upload_dir = wp_upload_dir();
    $base = $upload_dir['basedir'];
    $baseurl = $upload_dir['baseurl'];

    $path = $base . "/wpwoof-feed/" . $feedService . "/" . $file_type;
    $baseurl = $baseurl . "/wpwoof-feed/" . $feedService . "/" . $file_type;
    $file = $path . "/" . $feedname . "." . $file_type;
    $fileurl = $baseurl . "/" . $feedname . "." . $file_type;
    
    return array('path' => $file, 'url'=>$fileurl, 'file' => $feedname . '.'.$file_type);        
}

function wpwoof_create_feed($feedname, $data){   
    global $wpdb;
    $upload_dir = wpwoof_feed_dir($feedname, $data['feed_type'] == "adsensecustom" ? "csv" : "xml");
    $file = $upload_dir['path'];
    $fileurl = $upload_dir['url'];
    $file_name = $upload_dir['file'];
    $data['url'] = $fileurl;  
    if( update_option('wpwoof_feedlist_' . $feedname, $data) ){
        $row = $wpdb->get_row("SELECT * FROM ".$wpdb->options." WHERE option_name = 'wpwoof_feedlist_" . $feedname. "'", ARRAY_A);
        if(empty($row['option_id'])){
            $r = $wpdb->get_row("SELECT MAX(option_id)+1 as id from ".$wpdb->options, ARRAY_A);
            $wpdb->query("update ".$wpdb->options." SET option_id='".$r['id']."' where option_name = 'wpwoof_feedlist_" . $feedname. "'");
        }        
    }





    $dir_path = str_replace( $file_name, '', $file );
    if (wpwoof_checkDir($dir_path)) {
        if(!wpwoofeed_generate_feed($data, $data['feed_type'] == "adsensecustom" ? "csv" : "xml", $path)) return false;
    }
    return $fileurl;
}

function wpwoof_checkDir($path){
    if (!file_exists($path)) {
       return wp_mkdir_p($path);
    }
    return true;
}

function wpwoof_delete_feed_file($id){
    $option_id = $id;
    $feed = wpwoof_get_feed($option_id);
    $wpwoof_values = unserialize($feed);
    $feed_name = sanitize_text_field($wpwoof_values['feed_name']);
    $upload_dir = wpwoof_feed_dir($feed_name);
    $file = $upload_dir['path'];
    $fileurl = $upload_dir['url'];

    if( file_exists($file))
        unlink($file);
}

function wpwoof_refresh($message = '') {
    $settings_page = $_SERVER['REQUEST_URI'];
    if ( strpos( $settings_page, '&' ) !== false ) {
        $settings_page = substr( $settings_page, 0, strpos( $settings_page, '&' ) );
    }
    if ( ! empty( $message ) ) {
        $settings_page .= '&show_msg=true&wpwoof_message=' . $message;
    }
    if(!WPWOOF_DEBUG) header("Location:".$settings_page);
}



add_action('wp_ajax_wpwoofgtaxonmy', 'ajax_wpwoofgtaxonmy');
function ajax_wpwoofgtaxonmy(){
    error_reporting(E_ALL & ~E_NOTICE);

    $lang = 'en-US';
    $file = "http://www.google.com/basepages/producttype/taxonomy.{$lang}.txt";

    $reader = new LazyTaxonomyReader();

    $line_no = (isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null);
    $result = $reader->getDirectDescendants($line_no);
    echo json_encode($result);

    die();
}

class LazyTaxonomyReader {

    private $base = null;
    private $separator = ' > ';
    protected $lines;

    public function __construct($file='') {
        if( empty($file) )
            $file = plugin_dir_path(__FILE__) . 'google-taxonomy.en.txt';

        $this->lines = file($file, FILE_IGNORE_NEW_LINES);
        // remove first line that has version number
        if (substr($this->lines[0], 0, 1) == '#')
            unset($this->lines[0]);
    }

    public function setBaseNode($line_no) {
        if (is_null($line_no)) {
            $this->base = null;
            return;
        }
        $this->base = $this->lines[$line_no];
    }

    public function getDirectDescendants($line_no = null) {
        $this->setBaseNode($line_no);
        // select only lines that are directly below current base node
        $direct = array_filter($this->lines, array($this, 'isDirectlyBelowBase'));

        // return only last part of their names
        return array_map(array($this, 'getLastNode'), $direct);
    }

    protected function getLastNode($line) {
        if (strpos($line, $this->separator) === false) {
            // no separator present
            return $line;
        }
        // strip up to and including last separator
        return substr($line, strrpos($line, $this->separator) + strlen($this->separator));
    }

    protected function str_replace_once($search, $replace, $subject) {
        $firstChar = strpos($subject, $search);
        if ($firstChar !== false) {
            $beforeStr = substr($subject, 0, $firstChar);
            $afterStr = substr($subject, $firstChar + strlen($search));
            return $beforeStr . $replace . $afterStr;
        } else {
            return $subject;
        }
    }

    protected function isDirectlyBelowBase($line) {
        // starting text that must be present
        if (is_null($this->base)) {
            $start = '';
        } else {
            $start = $this->base . $this->separator;
        }
        if ($start !== '') {
            $starts_at_base = (strpos($line, $start) === 0);

            if (!$starts_at_base) { // starts with something different
                return false;
            }
            // remove start text AND the following separator
            $line = $this->str_replace_once($start, '', $line);
        }
        // we're direct descendants if we have no separators left on the line
        if (strpos($line, $this->separator) !== false)
            return false;

        return true;
    }

}

add_action('wp_ajax_wpwoofcategories', 'ajax_wpwoofcategories');
function ajax_wpwoofcategories(){
    wpwoofcategories( $_POST );
    die();
}
function wpwoofcategories_wmpl($options){
    global $sitepress,$wp_version;
    $general_lang=ICL_LANGUAGE_CODE;
    $options = array_merge(array(), $options);
    $aLanguages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
    $sell_all =  ( !isset($options['feed_name']) && !isset($options['feed_category_all'])    ) ? true : ( isset($options['feed_category_all']) && $options['feed_category_all']=="-1" ? true : false );
   // trace("options['feed_category_all']:".$options['feed_category_all']);
   // trace("sell_all:".$sell_all);
   
    ?><p><b>Please select categories</b></p>
    <p class="description">You can also select multiple categories</p>
    <ul  id="lang_wpwoof_categories">
        <li><input type="checkbox" value="-1" name="feed_category_all" id="feed_category_all" class="feed_category" <?php
            if($sell_all)  echo " checked='checked' "; ?>>
            <label for="feed_category_all">All Categories</label>
        </li>
    <?php
    $array_terms = array();
    foreach($aLanguages as $lang) {
        //   $lang['language_code']; $lang['translated_name'];
        $terms = null;
        $sitepress->switch_lang($lang['language_code']);
        if (version_compare(floatval($wp_version), '4.5', '>=')) {
            $args = array(
                'taxonomy' => array('product_cat'),
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC'
            );
            $terms = get_terms($args);
        } else {
            $terms = get_terms('product_cat', 'orderby=name&order=ASC&hide_empty=0');
        }

        if (empty($options['feed_category'])) {
            $options['feed_category'] = array();
            $options['feed_category_all'] = '-1';
            $options['feed_category'][] = '0';
            foreach ($terms as $key => $term) {
                $options['feed_category'][] = $term->term_id;
            }

        }

        // Find the number of uncategorized products

        if (count($terms) > 0) {
            foreach ($terms as $_term) {
                $array_terms[] = $_term->slug;
            }
        }

        echo "<li class='language_".$lang['language_code']." language_all'><b><i>".$lang['translated_name']."</i></b></li>";
        foreach ($terms as $key => $term) {
                $haystacks = isset($options['feed_category']) ? $options['feed_category'] : array();
                $cat_key = array_search($term->term_id, $haystacks);
                $cat_id = isset($haystacks[$cat_key]) ? $haystacks[$cat_key] : -1;
                ?>
                <li class="language_<?php echo $lang['language_code']?> language_all">
                    <input type="checkbox" value="<?php echo $term->term_id; ?>" name="feed_category[]"
                           id="feed_category_<?php echo $term->term_id; ?>"
                           class="feed_category" <?php  if($sell_all)  { echo " checked='checked' "; } else { checked($term->term_id, $cat_id, true);} ?>>
                    <label for="<?php echo 'feed_category_' . $term->term_id; ?>"><?php echo $term->name; ?> &nbsp; &nbsp;
                        (<?php echo $term->count; ?>)</label>
                </li>
            <?php
         } ?>
        <?php
    }//foreach($aLanguages as $lang) {

    // Find the number of uncategorized products
    /*
    if (count($array_terms) > 0) {
        $args = array(
            'posts_per_page' => 1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $array_terms,
                    'operator' => 'NOT IN'
                )
            ),
            'post_type' => 'product',
            'fields' => 'ids',
        );
    } else {
        $args = array(
            'posts_per_page' => 1,
            'post_type' => 'product',
            'fields' => 'ids',
        );
    }
    $products_uncategorized = new WP_Query($args);
    */
    $uncategorized_checked = in_array(0, $options['feed_category']) ? " checked='checked' " : '';
    ?>
        <li>
            <input type="checkbox" value="0" name="feed_category[]" id="feed_category_0"
                   class="feed_category" <?php if($sell_all)  { echo " checked='checked' "; } else { echo $uncategorized_checked; } ?>>
            <label for="feed_category_0">Uncategorized
                products <?php /*&nbsp; &nbsp; (<?php echo $products_uncategorized->found_posts; ?>) */ ?></label>
        </li>
    </ul>
    <br>
    <div id="wpwoof-popup-bottom"><a href="#done" class="button button-secondary wpwoof-popup-done">Done</a></div>
    <?php
    $sitepress->switch_lang( $general_lang );
}
function wpwoofcategories( $options = array() ) {
    if (WoocommerceWpwoofCommon::isActivatedWMPL()) {
        wpwoofcategories_wmpl($options);
        return;
    }
    global $wp_version;
    $options = array_merge(array(), $options);
?>
    <p><b>Please select categories</b></p>
    <?php
    $terms = null;
    if( version_compare( floatval( $wp_version ), '4.5', '>=' ) ) {
        $args = array(
            'taxonomy'      => array('product_cat'),
            'hide_empty'    => false,
            'orderby'       => 'name',
            'order'         => 'ASC'
        );
        $terms =  get_terms( $args );
    }else{
        $terms =  get_terms( 'product_cat', 'orderby=name&order=ASC&hide_empty=0' );
    }





    if( empty( $options['feed_category'] ) ) {
        $options['feed_category'] = array();
        $options['feed_category_all'] = '-1';
        $options['feed_category'][] = '0';
        foreach($terms as $key => $term) {
            $options['feed_category'][] = $term->term_id;
        }
        
    }

    // Find the number of uncategorized products
    $array_terms = array();
    if (count( $terms ) > 0 ) {
        foreach( $terms as $_term ) {
            $array_terms[] = $_term->slug;
        }
        $args = array(
            'posts_per_page' => 1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $array_terms, 
                    'operator' => 'NOT IN'
                )
            ),
            'post_type' => 'product',
            'fields' => 'ids',
        );
    } else {
        $args = array(
            'posts_per_page' => 1,
            'post_type' => 'product',
            'fields' => 'ids',
        );
    }
    $products_uncategorized = new WP_Query( $args );
    $uncategorized_checked = in_array( 0, $options['feed_category'] ) ? ' checked' : '';
    ?>
    <p class="description">You can also select multiple categories</p>
    <ul>
        <li><input type="checkbox" value="-1" name="feed_category_all" id="feed_category_all" class="feed_category" <?php checked( -1, (isset($options['feed_category_all']) ? $options['feed_category_all'] : '0'), true); ?>>
        <label for="feed_category_all">All Categories</label></li>
        <?php foreach ($terms as $key => $term) { 
            $haystacks = isset($options['feed_category']) ? $options['feed_category'] : array();
            $cat_key = array_search($term->term_id, $haystacks);
            $cat_id = isset($haystacks[$cat_key]) ? $haystacks[$cat_key] : -1;
            ?>
            <li><input type="checkbox" value="<?php echo $term->term_id; ?>" name="feed_category[]" id="feed_category_<?php echo $term->term_id; ?>" class="feed_category" <?php checked( $term->term_id, $cat_id, true); ?>><label for="<?php echo 'feed_category_' . $term->term_id; ?>"><?php echo $term->name; ?> &nbsp; &nbsp; (<?php echo $term->count; ?>)</label></li> 
        <?php } ?>
        <li><input type="checkbox" value="0" name="feed_category[]" id="feed_category_0" class="feed_category" <?php echo $uncategorized_checked; ?>><label for="feed_category_0">Uncategorized products &nbsp; &nbsp; (<?php echo $products_uncategorized->found_posts; ?>)</label></li> 
    </ul>
    <br>
    <div id="wpwoof-popup-bottom"><a href="#done" class="button button-secondary wpwoof-popup-done">Done</a></div>
        
<?php
}
function wpwoof_create_csv($path, $file, $content, $columns, $info=array()) {
    $info = array_merge(array('delimiter'=>'tab', 'enclosure' => 'double' ), $info);
    if(wpwoof_checkDir($path)) {
        $fp = fopen($file, "w");
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
        if (!empty($columns) ) {
            $header = array();
            foreach ($columns as $column_name => $value) {
                $header[] = $column_name;
            }
            fputcsv($fp, $header, $delimiter, $enclosure);
        }
        if (!empty($content) ) {
            foreach ($content as $fields) {
                if( count($fields) != count($columns) )
                    continue;
                fputcsv($fp, $fields, $delimiter, $enclosure);
            }
        }
        fclose($fp);
        return true;
    } else {
        return false;
    }
}
function wpwoof_get_interval() {
    return wpwoof_product_catalog::$interval;
}
