<?php
require_once dirname(__FILE__).'/../../inc/common.php';
require_once dirname(__FILE__).'/../../inc/countries.php';

class FeedFBGooglePro {
    /* FeedFBGooglePro - class for optimazing fields render in this view */

    private function _isValidAsArray($d){
       return (count($d) && is_array($d)) ? true : false;
    }

    public function showAttributes($attributes){
        $sResult = "";
        if( $this->_isValidAsArray($attributes) ){
            foreach ($attributes as $attr => $val){
                $sResult.=" ".$attr."='".str_replace('\'', '\\\'', $val)."' ";
            }
        }
        return $sResult;

    }
    public function showCustomOprions($aCcustom,$selval="") {
        $sResult = "";
        if ($this->_isValidAsArray($aCcustom)) {
            foreach ($aCcustom as $text => $val) {
                $sResult .= "<option " . ( ($selval==$val) ? " selected='selected' " :  "")  . " value='" . htmlspecialchars($val, ENT_QUOTES) . "'>" . htmlspecialchars($text, ENT_QUOTES) . "</option>";
            }
        }
        return $sResult;
    }

    public function addCssForFeed( $feed_type ) {
            $sCssClass="";
            if ($this->_isValidAsArray($feed_type)) {
                foreach ($feed_type as $ftp) $sCssClass .= " stl-" . $ftp;
            }
        return $sCssClass;
    }
    
    public function buidCountryValues($field,$fieldkey){
        $sResult="";
       
        if(count($field['custom']) ) {
            global $wpwoof_values;
            $val = (empty($wpwoof_values['field_mapping']['tax_countries']['value'])) ? "" : $wpwoof_values['field_mapping']['tax_countries']['value'];
            $selected = false; //(empty($wpwoof_values['field_mapping']['tax_countries']['value']));
            if($val && strpos($val,"-")!==false){
                $id = (!$selected) ? explode("-",$wpwoof_values['field_mapping']['tax_countries']['value']) : "";
                $id = (is_array($id) &&  count($id)>1) ? $id[1] : 0;
            }else{
                $id = $val;
            }
           
            $sResult.="<select id='ID".$fieldkey."' name='field_mapping[".$fieldkey."][value]' >";
            $sResult.="<option ".( !$id ? " selected " : "" )." value='' >" . 	__('select', 'woocommerce_wpwoof') ."</option>";
            $tax_class = "-1";
            $sCloseOptGroup = "";
            $aExistsCountries = array();
            $sGlobalResult="";
            foreach ($field['custom'] as $shcode){               
                if( empty($shcode['shcode']) && $shcode['rate'] && !$sGlobalResult) {
                    $sGlobalResult .= "<option ";                    
                    //if (!$selected ) { $sGlobalResult .= " selected "; }
                    $sGlobalResult .= " value='*'>";
                    $sGlobalResult .= "* - Global";//  ( ($shcode['name']) ?  $shcode['name'] : "Global" ) . " (".$shcode['rate'].") " .  "</option>";
                }               
                if(!in_array($shcode['shcode'],$aExistsCountries) && !empty($shcode['shcode']) ){
                $aExistsCountries[]=$shcode['shcode'];                
                $sResult .= "<option ";                    
                if (!$selected && $id==$shcode['id'] || $id==$shcode['shcode'] ) {
                        $sResult .= " selected ";
                        $selected==true;
                    }
                    $sResult .= " value='" . htmlspecialchars($shcode['shcode'], ENT_QUOTES) . "'>";
                    $sResult .= WpWoof_get_feed_pro_countries($shcode['shcode']) . "</option>";                    
                    
                }
             }
             if(!count($aExistsCountries)){
                $sResult .= $sGlobalResult;
             }


            /*
            foreach ($field['custom'] as $shcode){
                if($tax_class!=$shcode['class']){
                    $aExistsCountries[$shcode['class']] = array();
                    $sResult .=$sCloseOptGroup."<optgroup label='".strtoupper( $shcode['class']=="" ? "standart-rate" : $shcode['class'] )."'>";
                    $sCloseOptGroup.="</optgroup>";
                    $tax_class=$shcode['class'];
                }
                if(!in_array($shcode['shcode'],$aExistsCountries[$shcode['class']])){
                $aExistsCountries[$shcode['class']][]=$shcode['shcode'];
                $shcode['shcode'] = empty($shcode['shcode']) ? '*': $shcode['shcode'];
                    $sResult .= "<option ";
                    
                    if (!$selected && $id==$shcode['id']) {
                        $sResult .= " selected ";
                    }
                    $sResult .= " value='" . htmlspecialchars($shcode['shcode']."-".$shcode['id'], ENT_QUOTES) . "'>";
                    $sResult .= WpWoof_get_feed_pro_countries($shcode['shcode']).( $shcode['shcode']=="*" && $shcode['rate'] ? (" ".$shcode['name']." ( ".$shcode['rate']." ) ") : ""  ). "</option>";                    
                    
                }
             }
             */
            $sResult.=$sCloseOptGroup."</select>";
        }
        
        return $sResult;    
    }
}

$oFeedFBGooglePro = new FeedFBGooglePro();
?><div class="wpwoof-box">
    <div class="wpwoof-addfeed-top">

        <div class="addfeed-top-field"><p>
            <label class="addfeed-top-label addfeed-bigger">Feed Name</label>
            <span class="addfeed-top-value">
                <input type="text" id="idFeedName" name="feed_name" value="<?php echo isset($wpwoof_values['feed_name']) ? $wpwoof_values['feed_name'] : ''; ?>" />
                <?php if( !empty($wpwoofeed_oldname) ) { ?>
                    <input type="hidden" name="old_feed_name" value="<?php echo $wpwoofeed_oldname; ?>" style="display:none" />
                <?php } ?>
            </span>
        </p></div>
        <div class="addfeed-top-field"><p>
            <label class="addfeed-top-label addfeed-bigger">Feed Type</label>
            <span class="addfeed-top-value">
                <select id="ID-feed_type" name="feed_type" onchange="toggleFeedField(this.value);">
                    <option <?php if(isset($wpwoof_values['feed_type'])) { selected( "facebook", $wpwoof_values['feed_type'], true); } ?> value="facebook">Facebook Product Catalog</option>
                    <option <?php if(isset($wpwoof_values['feed_type'])) { selected( "google", $wpwoof_values['feed_type'], true); } ?> value="google">Google Merchant</option>
                    <option <?php if(isset($wpwoof_values['feed_type'])) { selected( "adsensecustom", $wpwoof_values['feed_type'], true); } ?> value="adsensecustom">Google Adwords Remarketing Custom</option>
                </select>
            </span>
        </p></div>
    </div>
    <?php
    if ( WoocommerceWpwoofCommon::isActivatedWMPL() ) {?>
        <hr class="wpwoof-break" />
        <h4 class="wpwoofeed-section-heading"><?php  echo __('Feed Language Settings', 'woocommerce_wpwoof'); ?></h4>
        <div class="wpwoof-addfeed-top">
        <div class="addfeed-top-field wpwoof-open-popup-wrap" >
        <p>
            <label class="addfeed-top-label"><?php  echo __('Include multilingual products', 'woocommerce_wpwoof'); ?></label>
                <span class="addfeed-top-value">
                        <select onchange="setLanguageToFeed(this.value)" name="feed_use_lang">
                            <?php
                            $sel = (!empty($wpwoof_values['feed_use_lang'])) ? $wpwoof_values['feed_use_lang'] : ICL_LANGUAGE_CODE;
                            /* ICL_LANGUAGE_CODE; */
                            ?>
                            <option value="all" <?php if($sel=='all') echo "selected='selected'" ?> ><?php  echo __('All Languages', 'woocommerce_wpwoof'); ?></option>
                            <?php
                            $aLanguages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
                            foreach($aLanguages as $lang){
                                ?><option value="<?php echo $lang['language_code']; ?>" <?php if($sel==$lang['language_code']) echo "selected='selected'" ?>><?php echo (!empty($lang['translated_name']) ? $lang['translated_name'] : $lang['language_code']); ?></option><?php
                            }
                            ?>
                        </select>
                </span>
        </p>
        <p class="description"><span></span><span>Select to include items for choosen language / All languages.</span></p>
        </div>
        </div>
        <?php
        global $woocommerce_wpml;
        if(isset($woocommerce_wpml) && is_object($woocommerce_wpml) && method_exists ( $woocommerce_wpml->multi_currency , 'get_currencies' ) ) { ?>
            <hr class="wpwoof-break"/>
            <h4 class="wpwoofeed-section-heading"><?php echo __('Feed Currency Settings', 'woocommerce_wpwoof'); ?></h4>
            <div class="wpwoof-addfeed-top">
            <div class="addfeed-top-field wpwoof-open-popup-wrap">
                <p>
                    <label class="addfeed-top-label"><?php echo __('Currency', 'woocommerce_wpwoof'); ?></label>
                <span class="addfeed-top-value">
                        <select name="feed_use_currency">
                            <?php
                            $sel = (!empty($wpwoof_values['feed_use_currency'])) ? $wpwoof_values['feed_use_currency'] : false;

                            $aCurrencies = $woocommerce_wpml->multi_currency->get_currencies('include_default = true');
                            foreach ($aCurrencies as $currency => $cur_data) {
                                ?>
                                <option
                                value="<?php echo $currency; ?>" <?php
                                if ($sel == $currency ) { echo "selected='selected'"; }
                                ?>><?php echo $currency; ?></option><?php
                            }
                            ?>
                        </select>
                </span>
                </p>
                <p class="description"><span></span><span>Select currency for feed.</span></p>
            </div>
            </div><?php
           }
       } ?>
    <hr class="wpwoof-break" />
    <h4 class="wpwoofeed-section-heading">Feed Options (PRO)</h4>

    <div class="wpwoof-addfeed-top">

        <div class="addfeed-top-field wpwoof-open-popup-wrap">
            <p>
                <label class="addfeed-top-label">Filter by Category</label>
                <span class="addfeed-top-value">
                    <a href="#chose_categories" class="wpwoof-button wpwoof-button-blue wpwoof-open-popup" id="wpwoof-select-categories">Chose WooCommerce Categories for this Feed</a>
                </span>
            </p>

            <div class="wpwoof-popup-wrap" style="display: none;">
                <div class="wpwoof-popup-bg"></div>
                <div class="wpwoof-popup">
                <div class="wpwoof-popup-close" tabindex="0" title="Close"></div>
                    <div class="wpwoof-popup-form" >
                        <div id="wpwoof-popup-categories" class="wpwoof-popup-body">
                            <?php wpwoofcategories( $wpwoof_values ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="addfeed-top-field" >
            <p>
                <label class="addfeed-top-label">Filter by Tags</label>
                <span class="addfeed-top-value">
                    <textarea name="feed_filter_tags"><?php echo (empty($wpwoof_values['feed_filter_tags']) ? '' : $wpwoof_values['feed_filter_tags']); ?></textarea>
                </span>
            </p>
            <p class="description"><span></span><span>Add multiple tags separated by comma.</span></p>
        </div>

        <div class="addfeed-top-field" >
            <p>
                <label class="addfeed-top-label">Filter by Sale</label>
                <span class="addfeed-top-value">
                    <select name="feed_filter_sale">
                        <option <?php if(isset($wpwoof_values['feed_filter_sale'])) { selected( "all", $wpwoof_values['feed_filter_sale'], true); } ?> value="all">All Products</option>
                        <option <?php if(isset($wpwoof_values['feed_filter_sale'])) { selected( "sale", $wpwoof_values['feed_filter_sale'], true); } ?> value="sale">Only products on sale</option>
                        <option <?php if(isset($wpwoof_values['feed_filter_sale'])) { selected( "notsale", $wpwoof_values['feed_filter_sale'], true); } ?> value="notsale">Only products not on sale</option>
                    </select>
                </span>
            </p>
            <p class="description"><span></span><span>Select all products, only sale products or only non sale products.</span></p>
        </div>

        <div class="addfeed-top-field wpwoof-open-popup-wrap">
            <p>
                <label class="addfeed-top-label">Filter by Product type</label>
                <span class="addfeed-top-value">
                    <a href="#chose_product_type" class="wpwoof-button wpwoof-button-blue wpwoof-open-popup" id="wpwoof-select-product_type">Chose WooCommerce Product type for this Feed</a>
                </span>
            </p>

            <div class="wpwoof-popup-wrap" style="display: none;">
                <div class="wpwoof-popup-bg"></div>
                <div class="wpwoof-popup">
                <div class="wpwoof-popup-close" tabindex="0" title="Close"></div>
                    <div class="wpwoof-popup-form" >
                        <div id="wpwoof-popup-type" class="wpwoof-popup-body">
                            <p><b>Please select product types</b></p>
                            <ul>
                            <?php
                            $is_empty_product_type = true;
                            if( ! empty($wpwoof_values['feed_filter_product_type']) && 
                                is_array($wpwoof_values['feed_filter_product_type']) && 
                                count($wpwoof_values['feed_filter_product_type']) > 0 ) 
                            {
                                $is_empty_product_type = false;
                            }
                            foreach ( wc_get_product_types() as $value => $label ) {
                                $selected = true;
                                if( ! $is_empty_product_type ) {
                                    $selected = in_array($value, $wpwoof_values['feed_filter_product_type']);
                                }
                                echo '<li><label class="wpwoof_checkboxes_top"><input type="checkbox" name="feed_filter_product_type[]" value="' . esc_attr( $value ) . '" ' . 
                                ( $selected ? 'checked' : '' ) .'>' . esc_html( $label ) . '</label></li>';
                            }
                            ?>
                            </ul>
                            <div id="wpwoof-popup-bottom"><a href="javascript:void(0);" class="button button-secondary wpwoof-popup-done">Done</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="addfeed-top-field" >
            <p>
                <label class="addfeed-top-label">Filter by Stock</label>
                <span class="addfeed-top-value">
                    <select name="feed_filter_stock">
                        <option <?php if(isset($wpwoof_values['feed_filter_stock'])) { selected( "all", $wpwoof_values['feed_filter_stock'], true); } ?> value="all">All Products</option>
                        <option <?php if(isset($wpwoof_values['feed_filter_stock'])) { selected( "instock", $wpwoof_values['feed_filter_stock'], true); } ?> value="instock">Only in stock</option>
                        <option <?php if(isset($wpwoof_values['feed_filter_stock'])) { selected( "outofstock", $wpwoof_values['feed_filter_stock'], true); } ?> value="outofstock">Only out of stock</option>
                    </select>
                </span>
            </p>
        </div>

        <hr class="wpwoof-break wpwoof-break-small"/>

        <div class="addfeed-top-field" >
            <p>
                <label class="addfeed-top-label">Variable Product Price</label>
                <span class="addfeed-top-value">
                    <select name="feed_variable_price">
                        <option <?php if(isset($wpwoof_values['feed_variable_price'])) { selected( "small", $wpwoof_values['feed_variable_price'], true); } ?> value="small">Smaller Price</option>
                        <option <?php if(isset($wpwoof_values['feed_variable_price'])) { selected( "big", $wpwoof_values['feed_variable_price'], true); } ?> value="big">Bigger Price</option>
                        <option <?php if(isset($wpwoof_values['feed_variable_price'])) { selected( "first", $wpwoof_values['feed_variable_price'], true); } ?> value="first">First Variation Price</option>
                    </select>
                </span>
            </p>
            <p class="description"><span></span><span>Select which price to be use for main product when there are variations.</span></p>
        </div>

        <hr class="wpwoof-break wpwoof-break-small"/>

        <div class="addfeed-top-field" >
            <p>
                <label class="addfeed-top-label" for="feed_remove_variations">Remove product variations</label>
                <span class="addfeed-top-value">
                    <input type="checkbox" value="1" id="feed_remove_variations" name="feed_remove_variations"<?php if( ! empty($wpwoof_values['feed_remove_variations']) ) echo ' checked '; ?>>
                </span>
            </p>
            <p class="description"><span></span><span>Check to remove variations from this feed.</span></p>

            <!-- ----------------------------------------------------------------------------------------------------- -->

            <p>
                <label class="addfeed-top-label" for="feed_variation_show_main">Show main variable product item</label>
                <span class="addfeed-top-value">
                    <input type="hidden" value="0"  name="feed_variation_show_main">
                    <input type="checkbox" value="1" id="feed_variation_show_main" name="feed_variation_show_main"<?php
                    if( !isset($wpwoof_values['feed_variation_show_main']) || ! empty($wpwoof_values['feed_variation_show_main']) ) echo ' checked '; ?>>                    
                </span>
            </p>
            <p class="description"><span></span><span>Check to show main variable item.</span></p>

            <p>
                <label class="addfeed-top-label" for="feed_group_show_main">Show main grouped product item</label>
                <span class="addfeed-top-value">
                    <input type="hidden" value="0"  name="feed_group_show_main">
                    <input type="checkbox" value="1" id="feed_group_show_main" name="feed_group_show_main"<?php
                    if( !isset($wpwoof_values['feed_group_show_main']) || ! empty($wpwoof_values['feed_group_show_main']) ) echo ' checked '; ?>>                    
                </span>
            </p>
            <p class="description"><span></span><span>Check to show main grouped item.</span></p>

            <p>
                <label class="addfeed-top-label" for="feed_bundle_show_main">Show main bundle product item</label>
                <span class="addfeed-top-value">
                    <input type="hidden" value="0"  name="feed_bundle_show_main">
                    <input type="checkbox" value="1" id="feed_bundle_show_main" name="feed_bundle_show_main"<?php
                    if( !isset($wpwoof_values['feed_bundle_show_main']) || ! empty($wpwoof_values['feed_bundle_show_main']) ) echo ' checked '; ?>>
                </span>
            </p>
            <p class="description"><span></span><span>Check to show main bundle item.</span></p>

            <p>
                <label class="addfeed-top-label" for="feed_use_variation_data">Use variation data when it exists</label>
                <span class="addfeed-top-value">
                    <input type="hidden" value="0"  name="feed_use_variation_data">
                    <input type="checkbox" value="1" id="feed_bundle_show_main" name="feed_use_variation_data"<?php
                    if( !isset($wpwoof_values['feed_use_variation_data']) || ! empty($wpwoof_values['feed_use_variation_data']) ) echo ' checked '; ?>>
                </span>
            </p>
            <p class="description"><span></span><span>Check to include variation custom (pa_) and global data when it exists.</span></p>





        </div>
    </div>
    <?php
    $taxSrc = admin_url('admin-ajax.php');
    $taxSrc = add_query_arg( array( 'action'=>'wpwoofgtaxonmy'), $taxSrc);

    $google_cats = '';
    if(isset($wpwoof_values['feed_google_category_id']))
        $google_cats = $wpwoof_values['feed_google_category_id'];
    if( strpos($google_cats, ',') !== false ) {
        $google_cats = explode(',', $google_cats);
        $preselect = '';
        if(!empty($google_cats) && is_array($google_cats)){
            foreach ($google_cats as $google_cat) {
                $preselect .= "'".$google_cat."', ";
            }
            $preselect = rtrim($preselect, ', ');
        }
    } else {
        $preselect = "'$google_cats'";
    }
?>
<script type="text/javascript">    
    function showHideCountries(value){
        if(value=='false') jQuery('.CSS_tax_countries').hide();
        else  jQuery('.CSS_tax_countries').show();
    }

   <?php if (WoocommerceWpwoofCommon::isActivatedWMPL()) {?>

    function setLanguageToFeed(lang){
        if(!lang) lang='all';
        jQuery("#lang_wpwoof_categories li.language_all").each(function(){
              var elm = jQuery(this);
              if(elm.hasClass('language_'+lang)) {elm.show();} else {elm.hide();}
        });

    }
   <?php } ?>
    /*function showSKUorID(){
        var sText = (jQuery("#ID-feed_type").val()=='google') ? "SKU" : "ID";
        jQuery("#ID_mpn_field option").each(function() {
            if(jQuery(this).text() == sText) {
                jQuery(this).attr('selected', 'selected');
            }
        });

    }
    */
    function toggleFeedField(sClass){
        console.log('toggle:'+sClass);
        jQuery('[class*="stl-"]').hide();
        jQuery('[class*="stl-'+sClass+'"]').show();
        /* showSKUorID();*/
    }
    function toggleExtra(sClass){
        if(sClass){
            jQuery("."+sClass).toggle();
            if(jQuery("."+sClass).is(":visible") ){
                jQuery('#ID_ch_MoreImages').attr("checked",true);
            }else{
                jQuery('#ID_ch_MoreImages').attr("checked",false);
            }
        }else {
            jQuery("#IDextrafields").toggle();
        }
        return false;
    }
jQuery(document).ready(function($) {
    <?php if (WoocommerceWpwoofCommon::isActivatedWMPL()) {?>
        var feed_lg_elm = jQuery("select[name='feed_use_lang']");
        setLanguageToFeed(feed_lg_elm.val());
    <?php } ?>

    jQuery('#ID-feed_type').val();
    jQuery("[class*=' stl-']").hide();
    jQuery(".stl-"+jQuery('#ID-feed_type').val()).show();
    /* jQuery(".additional_image_link").hide(); */
    /*showSKUorID();*/
    var options = {
        empty_value: 'null',
        indexed: true,  // the data in tree is indexed by values (ids), not by labels
        on_each_change: '<?php echo $taxSrc; ?>', // this file will be called with 'id' parameter, JSON data must be returned
        choose: function(level) {
                    if( level < 1 )
                        return 'Select Main Category';
                    else 
                        return 'Select Sub Category';
                },
        loading_image: '<?php echo home_url( '/wp-includes/images/wpspin.gif');?>',
        get_parent_value_if_empty: true,
        set_value_on: 'each',
        preselect: {'wpwoof_google_category': [<?php echo $preselect; ?>]}
    };

    var displayParents = function() {
        var labels = []; // initialize array
        var IDs = []; // initialize array
        $(this).siblings('select') // find all select
        .find(':selected') // and their current options
        .each(function() { 

        if( $(this).text() != 'Select Main Category' &&  $(this).text() != 'Select Sub Category'){     
            if( $(this).val() != ''){
                labels.push($(this).text()); 
                IDs.push($(this).val()); 
            }
        }

        }); // and add option text to array
        $('#wpwoof_google_category_result').text(labels.join(' > ')); // and display the labels
        $('#feed_google_category').val( labels.join(' > ') );
        $('#feed_google_category_id').val( IDs.join(',') );
    }
    function checkBrandMissing(){
        if($('#ID_wpwoof_brand_select').val()!='wpwoofdefa_site_name' && $.trim($('#ID_wpwoof_brand_text').val())=='' ){
            $('#ID_wpwoof_brand_text').val("<?php echo htmlspecialchars(get_option( 'blogname'),ENT_QUOTES); ?>")
        }
    }
    $('#ID_wpwoof_brand_select').change(checkBrandMissing);
    checkBrandMissing();

    if($('#ID_tax_field').length){        
        showHideCountries($('#ID_tax_field').val());
    }

    $.getJSON('<?php echo $taxSrc; ?>', function(tree) { // initialize the tree by loading the file first
        $('#wpwoof_google_category').optionTree(tree, options).change(displayParents);
    });
});
</script>
    <hr class="wpwoof-break" />

    <div class="wpwoof-addfeed-fields">
        <h4 class="wpwoofeed-section-heading">Field Mapping</h4>
        <p>We do our best to do the mapping automatically, but if there something you want to change, use the dropdown to do the re-map.</p>
        <?php  
        $all_fields = wpwoof_get_all_fields();

        $required_fields = $all_fields['required'];
        $extra_fields = $all_fields['extra'];

        $condition_fields = array();
        $meta_keys = wpwoof_get_product_fields();
        $meta_keys_sort = wpwoof_get_product_fields_sort();
        $attributes = wpwoof_get_all_attributes();        
        foreach ($required_fields as $fieldkey => $field) {
            $sCssClass = "";
           /* if($fieldkey=='tax') trace($field);*/
            if( isset($field['dependet']) ) continue;
            if(isset($field['feed_type'])) $sCssClass = $oFeedFBGooglePro->addCssForFeed($field['feed_type']);
      
            if( !empty($field['delimiter']) ) {
                ?><hr class="wpwoof-break wpwoof-break-small<?php echo $sCssClass; ?>"/><?php
            }
            
            if(!empty($field['header']) ){ ?>
                <div class="wpwoof-definefield-top<?php echo $sCssClass; ?>">
                    <label class="wpwoofeed-section-heading"><?php echo $field['header']; ?></label>
                    <p><?php if(!empty($field['headerdesc'])) echo $field['headerdesc'];?></p>
                </div><?php
            }
            if(isset($field['type']) && $field['type']=='checkbox'){
                ?><div class="wpwoof-requiredfield-settings wpwoof-field-wrap stl-facebook stl-all stl-google" style="display: block;">
                <p>
                    <label class="wpwoof-required-label"></label>
                       <span class="wpwoof-required-value">
                           <label><input type="checkbox" class='wpwoof-mapping' value="1" name="field_mapping[<?php echo $fieldkey; ?>]"<?php
                               echo !empty($wpwoof_values['field_mapping'][$fieldkey]) ? " checked " : '';
                               ?> /> <?php echo $field['label']; ?></label>
                        </span>
                </p>
                </div><?php
            } else   if( !isset($field['define']) ) { ?>
                <div class="wpwoof-requiredfield-settings wpwoof-field-wrap<?php echo $sCssClass ?>">
                <p>
                <label class="wpwoof-required-label"><?php echo $fieldkey ?>:</label>
                <span class="wpwoof-required-value">
                <select <?php
                if (isset($field['attr'])) echo $oFeedFBGooglePro->showAttributes($field['attr']);
                ?> name="field_mapping[<?php echo $fieldkey; ?>][value]" class="wpwoof_mapping wpwoof_mapping_option">
                <?php
                $html = '';
                if (isset($field['custom'])) {
                    $html = $oFeedFBGooglePro->showCustomOprions($field['custom'], ( empty($wpwoof_values['field_mapping'][$fieldkey]['value']) ? "" : $wpwoof_values['field_mapping'][$fieldkey]['value'] ) );
                } else {
                    if (isset($field['woocommerce_default'])) {
                        if (empty($wpwoof_values['field_mapping'][$fieldkey]['value'])) {
                            if (empty($wpwoof_values['field_mapping']) || !is_array($wpwoof_values['field_mapping'])) {
                                $wpwoof_values['field_mapping'] = array();
                            }
                            if (empty($wpwoof_values['field_mapping'][$fieldkey]) || !is_array($wpwoof_values['field_mapping'][$fieldkey])) {
                                $wpwoof_values['field_mapping'][$fieldkey] = array();
                            }
                            $wpwoof_values['field_mapping'][$fieldkey]['value'] = 'wpwoofdefa_' . $field['woocommerce_default']['value'];
                        }
                    } else {
                        $html .= '<option value="">select</option>';
                    }
                    $meta_keys_remove = $meta_keys;
                    foreach ($meta_keys_sort['sort'] as $sort_id => $meta_fields) {
                        $html .= '<optgroup label="' . $meta_keys_sort['name'][$sort_id] . '">';
                        foreach ($meta_fields as $key) {
                            $value = $meta_keys[$key];
                            unset($meta_keys_remove[$key]);
                            $html .= '<option value="wpwoofdefa_' . $key . '" ' . ( isset($wpwoof_values['field_mapping'][$fieldkey]['value']) ? selected('wpwoofdefa_' . $key, $wpwoof_values['field_mapping'][$fieldkey]['value'], false) : '' ) . ' >' . $value['label'] . '</option>';
                        }
                        $html .= '</optgroup>';
                    }
                    $html .= '<optgroup label="Product Attributes">';
                    foreach ($attributes as $key => $value) {
                        $html .= '<option value="wpwoofattr_' . $key . '" ' . ( isset($wpwoof_values['field_mapping'][$fieldkey]['value']) ? selected('wpwoofattr_' . $key, $wpwoof_values['field_mapping'][$fieldkey]['value'], false) : '' ) . ' >' . $value . '</option>';
                    }
                    $html .= '</optgroup>';

                }
                echo $html;
                ?></select><?php
                    wpwoofeed_custom_attribute_input($fieldkey, $field, $wpwoof_values);
                ?></span></p>
                <p class="description"><span></span><span><?php echo $field['desc']; ?></span></p>
                <?php
                if(!empty($field['second_field']) 
                    && isset($required_fields[$field['second_field']] ) ) {
                    $depfield = $required_fields[$field['second_field']];
                    ?><p  <?php if( isset($depfield['attr']) )   echo $oFeedFBGooglePro->showAttributes($depfield['attr']); ?> >
                       <label class="wpwoof-required-label"><?php echo ( (!empty($depfield['label'])) ? $depfield['label'] : "") ?>:</label>
                       <span class="wpwoof-define-value">                          
                        <?php
                        if(!empty($depfield['rendervalues']) && method_exists($oFeedFBGooglePro,$depfield['rendervalues'])){
                            echo $oFeedFBGooglePro->{$depfield['rendervalues']}($depfield,$field['second_field']);
                        }?>
                       </span>
                    </p>
                    <p  class="description" >
                        <span></span>
                        <span <?php if( isset($depfield['attr']) )   echo $oFeedFBGooglePro->showAttributes($depfield['attr']); ?> ><?php echo $depfield['desc']; ?></span>
                    </p>
                    <?php
                }
                if( !empty($field['callback']) && function_exists($field['callback']) ) {
                        $field['callback']($fieldkey, $field, $wpwoof_values);
                 }
                ?>
                </div><?php


            } else {
                $condition_fields[$fieldkey] = $field;
            }
        }

        wpwoof_buildconditionfields($condition_fields,$attributes,$meta_keys_sort);
        $condition_fields = array();

        ?>

        <?php
        function wpwoof_buildconditionfields($condition_fields, $attributes, $meta_keys_sort) {
           global $wpwoof_values;
           if(!empty($condition_fields) && is_array($condition_fields) && count($condition_fields)>0)
            foreach ($condition_fields as $definekey => $definevalue) {

            $sCssClass = "";
            if(isset($definevalue['feed_type'])) foreach ($definevalue['feed_type'] as $ftp){
                $sCssClass.= " stl-".$ftp;
            }
            ?>
            <div class="wpwoof-definefield-top<?php echo $sCssClass; ?>">
                <label class="wpwoofeed-section-heading"><?php echo $definevalue['define']['label']; ?></label>
                <p><?php echo $definevalue['define']['desc']; ?></p>
            </div>
            <div class="wpwoof-definefield-settings wpwoof-field-wrap <?php echo $sCssClass; ?>">
                <p>
                <?php
                if( isset($definevalue['woocommerce_default']) &&   $definekey == "brand"  ) {
                    $html2 = '';     
                    $html = '';     
                    $post_type = "product";
                    $check = 0;
                    $default_value = '';
                    
                    
                    if( empty( $wpwoof_values['field_mapping'][$definekey]['value'] ) ) {
                        if( empty($wpwoof_values['field_mapping']) || !is_array($wpwoof_values['field_mapping']) ) {
                            $wpwoof_values['field_mapping'] = array();
                        }
                        if( empty($wpwoof_values['field_mapping'][$definekey]) || !is_array($wpwoof_values['field_mapping'][$definekey]) ) {
                            $wpwoof_values['field_mapping'][$definekey] = array();
                        }
                        $wpwoof_values['field_mapping'][$definekey]['value'] = $default_value;
                    }
                  foreach( $attributes as $taxonomy_name => $value ) {
                        $check = 1;
                        $val = '';
                        if( isset($wpwoof_values['field_mapping'][$definekey]['value']) )
                            $val = $wpwoof_values['field_mapping'][$definekey]['value'];
                        $html2 .= '<option value="wpwoofattr_'.$taxonomy_name.'" '.selected('wpwoofattr_'.$taxonomy_name, $val, false).'>'.$value.'</option>'; 
                    }
                    $html2 = '<optgroup label="Product Attributes">'.$html2.'</optgroup>';
                    $meta_keys = wpwoof_get_product_fields();
                    $meta_keys_remove = $meta_keys;
                    foreach($meta_keys_sort['sort'] as $sort_id => $meta_fields) {
                        $html .= '<optgroup label="'.$meta_keys_sort['name'][$sort_id].'">';
                        foreach($meta_fields as $key) {
                            $value = $meta_keys[$key];
                            unset($meta_keys_remove[$key]);
                            $html .= '<option value="wpwoofdefa_'.$key.'" '.selected('wpwoofdefa_'.$key, (empty($wpwoof_values['field_mapping'][$definekey]['value']) ? '' : $wpwoof_values['field_mapping'][$definekey]['value']), false).' >'.$value['label'].'</option>';
                        }
                        $html .= '</optgroup>';
                    } 
                    if($definekey == "brand" && empty($wpwoof_values['field_mapping'][$definekey]['value']) ) {
                        $html='<option value="" >select</option>'.$html;
                    }
                    ?>
                        <label class="wpwoof-define-label"><?php echo $definekey; ?>:</label>
                        <span class="wpwoof-define-value">                        
                            <select id="ID_wpwoof_brand_select" name="field_mapping[<?php echo $definekey ?>][value]" class="wpwoof_mapping_option">
                                <?php echo $html, $html2; ?>
                            </select>
                        </span>
                    <?php
                }
                if($definekey != "brand"  ) { ?>
                    <label  class="wpwoof-define-label"><?php echo $definekey; ?>:</label>
                    <span class="wpwoof-define-value">
                        <select name="field_mapping[<?php echo $definekey ?>][value]" class="wpwoof_mapping_option">
                            <?php 
                            $html = '';
                            if( isset($definevalue['woocommerce_default']) ) {
                                if( empty( $wpwoof_values['field_mapping'][$definekey]['value'] ) ) {
                                    if( empty($wpwoof_values['field_mapping']) || !is_array($wpwoof_values['field_mapping']) ) {
                                        $wpwoof_values['field_mapping'] = array();
                                    }
                                    if( empty($wpwoof_values['field_mapping'][$definekey]) || !is_array($wpwoof_values['field_mapping'][$definekey]) ) {
                                        $wpwoof_values['field_mapping'][$definekey] = array();
                                    }
                                    $wpwoof_values['field_mapping'][$definekey]['value'] = 'wpwoofdefa_'.$definevalue['woocommerce_default']['value'];
                                }
                            } else {
                                $html .= '<option value="">select</option>';
                            }

                            $meta_keys = wpwoof_get_product_fields();
                            $meta_keys_remove = $meta_keys;
                            foreach($meta_keys_sort['sort'] as $sort_id => $meta_fields) {
                                $html .= '<optgroup label="'.$meta_keys_sort['name'][$sort_id].'">';
                                foreach($meta_fields as $key) {
                                    $value = $meta_keys[$key];
                                    unset($meta_keys_remove[$key]);
                                    $html .= '<option value="wpwoofdefa_'.$key.'" '. ( isset($wpwoof_values['field_mapping'][$definekey]['value']) ? selected('wpwoofdefa_'.$key, $wpwoof_values['field_mapping'][$definekey]['value'], false) : '' ) . ' >'.$value['label'].'</option>';
                                }
                                $html .= '</optgroup>';
                            }
                            $attributes = wpwoof_get_all_attributes();
                            $html .= '<optgroup label="Product Attributes">';
                            foreach ($attributes as $key => $value) {
                                $html .= '<option value="wpwoofattr_'.$key.'" ' . ( isset($wpwoof_values['field_mapping'][$definekey]['value']) ? selected( 'wpwoofattr_'.$key, $wpwoof_values['field_mapping'][$definekey]['value'], false) : '' ).' >'.$value.'</option>';
                            }
                            $html .= '</optgroup>';
                            echo $html;
                            ?>
                        </select>
                    </span>
                <?php
                } ?>
                </p>
                <p>
                    <span class="wpwoof-define-label">Use this when <?php echo $definekey; ?> is missing:</span>
                    <?php if( isset($definevalue['define']['values']) ) { ?>    
                        <span class="wpwoof-define-value">
                            <select name="field_mapping[<?php echo $definekey ?>][define][missingvalue]">
                                <?php 
                                $pieces = explode(',', $definevalue['define']['values']);
                                foreach ($pieces as $piecekey => $piecevalue) {
                                    echo '<option 
                                    ' . ( isset($wpwoof_values['field_mapping'][$definekey]['value']) ?  selected( $piecevalue, $wpwoof_values['field_mapping'][$definekey]['define']['missingvalue'], false) : '' ) . ' value="'.$piecevalue.'">
                                    '.$piecevalue.'
                                    </option>';
                                }
                                ?>
                            </select>
                        </span>
                    <?php }  else { ?>
                        <span class="wpwoof-define-value">
                            <?php 
                            if( isset($wpwoof_values['field_mapping'][$definekey]['define']['missingvalue']) ) {
                                $brand_missingvalue = $wpwoof_values['field_mapping'][$definekey]['define']['missingvalue'];
                            } else {
                                $brand_missingvalue = '';
                            } 
                            if(!empty($definevalue['define']['type']) && $definevalue['define']['type']=='textarea'){ ?>
                                <textarea  name="field_mapping[<?php echo $definekey ?>][define][missingvalue]"><?php echo  $brand_missingvalue; ?></textarea><?php
                            } else {
                                ?>
                                <input  <?php echo ($definekey == "brand" ) ? "id=\"ID_wpwoof_brand_text\"" : "" ?> type="text" name="field_mapping[<?php echo $definekey ?>][define][missingvalue]" value="<?php echo  $brand_missingvalue; ?>" />
                            <?php } ?>
                        </span>
                    <?php  } ?>
                </p>
                <p style="text-align: center;"><b>OR define a global value</b></p>
                <p>
                    <span class="wpwoof-define-label wpwoof-defineg-label">
                        <input type="checkbox" value="1" name="field_mapping[<?php echo $definekey ?>][define][global]" 
                        <?php 
                        $checkvalue = isset($wpwoof_values['field_mapping'][$definekey]['define']['global']) ? $wpwoof_values['field_mapping'][$definekey]['define']['global'] : '';
                        checked(1, $checkvalue, true); 
                        ?>
                        />Global <?php echo $definekey; ?> is:
                    </span>
                    <?php  if( isset($definevalue['define']['values']) ) { ?>
                        <span class="wpwoof-define-value">
                        <select name="field_mapping[<?php echo $definekey ?>][define][globalvalue]">
                            <?php 
                            $pieces = explode(',', $definevalue['define']['values']);
                            foreach ( $pieces as $piecekey => $piecevalue ) {
                                echo '<option 
                                '.  ( isset($wpwoof_values['field_mapping'][$definekey]['value']) ? selected( $piecevalue, $wpwoof_values['field_mapping'][$definekey]['define']['globalvalue'], false) : '' ) . ' value="'.$piecevalue.'">
                                '.$piecevalue.'
                                </option>';
                            }
                            ?>
                        </select>
                        </span>
                    <?php } else { ?>
                        <?php
                        if( isset($wpwoof_values['field_mapping'][$definekey]['define']['globalvalue']) ) {
                            $brand_globalvalue = $wpwoof_values['field_mapping'][$definekey]['define']['globalvalue'];
                        } else {
                            $brand_globalvalue = '';
                        } ?>
                        <span class="wpwoof-define-value"><?php
                             if(!empty($definevalue['define']['type']) && $definevalue['define']['type']=='textarea'){ ?>
                                <textarea name="field_mapping[<?php echo $definekey ?>][define][globalvalue]"><?php echo stripslashes($brand_globalvalue); ?></textarea><?php
                            } else {
                                 ?>
                                 <input type="text" name="field_mapping[<?php echo $definekey ?>][define][globalvalue]"
                                        value="<?php echo stripslashes($brand_globalvalue); ?>" /><?php
                            }
                          ?>
                        </span>
                    <?php } ?>
                </p>
            </div>
            <hr class="wpwoof-break wpwoof-break-small <?php echo  $sCssClass; ?>"/>
        <?php }
        } ?>
        <?php /* Put Extra fields Block */ ?>
        <div class="addfeed-top-field stl-facebook stl-google">
            <strong class="wpwoofeed-section-heading" style="font-size:1.4em;display: block;text-align:center;">Google Product Taxonomy</strong>
            <p>
                <label class="addfeed-top-label" title='If you leave this blank you might see the following warning when creating the Product Catalog: "The following products were uploaded but have issues that might impact ads: Without google_product_category information, your products may not appear the way you want them to in ads.'>
                    Select Google Product Taxonomy <br>
                    <a href="https://support.google.com/merchants/answer/160081" target="_blank" title='If you leave this blank you might see the following warning when creating the Product Catalog: "The following products were uploaded but have issues that might impact ads: Without google_product_category information, your products may not appear the way you want them to in ads.'>Help</a>
                    <input type="hidden" name="feed_google_category" value="" id="feed_google_category" />
                    <input type="hidden" name="feed_google_category_id" value="" id="feed_google_category_id" />
                </label>

                <span class="addfeed-top-value">
                    <input type="text" name="wpwoof_google_category" id="wpwoof_google_category" style='display:none;' />
                </span>
            </p>
        </div>

        <div class="wpwoof-definefield-top stl-google stl-adsensecustom" style="margin-bottom: 40px;">
            <a href="javascript:void(0);" class="wpwoofeed-section-heading" onclick="toggleExtra();">Optional Fields</a>
        </div>
        <div class="wpwoof-definefield-top stl-facebook" style="margin-bottom: 40px;">
        <label class="wpwoofeed-section-heading">Optional Fields</label>
        </div>
        <div id="IDextrafields" class="stl-facebook" style="display:none;margin-bottom: 40px;">
        <?php
            $styleHide="";
          
            foreach ($extra_fields as $fieldkey => $field) {
                if($fieldkey=='product_image' || $fieldkey=='identifier_exists') continue;
                $sCssClass = "";
                if(isset($field['feed_type'])) foreach ($field['feed_type'] as $ftp){
                    $sCssClass.= " stl-".$ftp;
                }
                
                $sCssClass = (stripos($fieldkey,"additional_image_link")!==false) ? " additional_image_link" : $sCssClass;
             
                if (!empty($field['delimiter'])) {
                    echo '<hr class="wpwoof-break wpwoof-break-small' . $sCssClass . '"/>';
                }
                if (!isset($field['define'])) { ?>
                <div class="wpwoof-requiredfield-settings wpwoof-field-wrap<?php echo $sCssClass.$styleHide ?>">
                    <p>
                        <label class="wpwoof-required-label"><?php echo $fieldkey ?>:</label>
                    <span class="wpwoof-required-value">
                        <select name="field_mapping[<?php echo $fieldkey; ?>][value]"
                                class="wpwoof_mapping wpwoof_mapping_option">
                        <?php
                        $html = '';
                        if (isset($field['woocommerce_default'])) {
                            if (empty($wpwoof_values['field_mapping'][$fieldkey]['value'])) {
                                if (empty($wpwoof_values['field_mapping']) || !is_array($wpwoof_values['field_mapping'])) {
                                    $wpwoof_values['field_mapping'] = array();
                                }
                                if (empty($wpwoof_values['field_mapping'][$fieldkey]) || !is_array($wpwoof_values['field_mapping'][$fieldkey])) {
                                    $wpwoof_values['field_mapping'][$fieldkey] = array();
                                }
                                $wpwoof_values['field_mapping'][$fieldkey]['value'] = 'wpwoofdefa_' . $field['woocommerce_default']['value'];
                            }
                        } else {
                            $html .= '<option value="">select</option>';
                        }
                        $meta_keys_remove = $meta_keys;
                        foreach ($meta_keys_sort['sort'] as $sort_id => $meta_fields) {
                            $html .= '<optgroup label="' . $meta_keys_sort['name'][$sort_id] . '">';
                            foreach ($meta_fields as $key) {
                                $value = $meta_keys[$key];
                                unset($meta_keys_remove[$key]);
                                $html .= '<option value="wpwoofdefa_' . $key . '" ' .  ( isset($wpwoof_values['field_mapping'][$fieldkey]['value']) ? selected('wpwoofdefa_' . $key, $wpwoof_values['field_mapping'][$fieldkey]['value'], false) : '' ) . ' >' . $value['label'] . '</option>';
                            }
                            $html .= '</optgroup>';
                        }
                        $html .= '<optgroup label="Product Attributes">';
                        foreach ($attributes as $key => $value) {
                            $html .= '<option value="wpwoofattr_' . $key . '" ' . ( isset($wpwoof_values['field_mapping'][$fieldkey]['value']) ?  selected('wpwoofattr_' . $key, $wpwoof_values['field_mapping'][$fieldkey]['value'], false) : '' ) . ' >' . $value . '</option>';
                        }
                        $html .= '</optgroup>';
                        echo $html;
                        ?>
                        </select>
                        <?php wpwoofeed_custom_attribute_input($fieldkey, $field, $wpwoof_values); ?>
                    </span>
                    </p>
                    <p class="description"><span></span><span><?php echo $field['desc']; ?></span></p>
                    <?php
                    if (!empty($field['callback']) && function_exists($field['callback'])) {
                        $field['callback']($fieldkey, $field, $wpwoof_values);
                    }
                    ?>
                    </div><?php

                }
                else {
                    $condition_fields[$fieldkey] = $field;
                }

            }
            wpwoof_buildconditionfields($condition_fields,$attributes,$meta_keys_sort);

           ?>
    </div>
    <hr class="wpwoof-break" />

    <div class="wpwoof-addfeed-button">
        <div class="wpwoof-addfeed-button-inner">
            <!-- <p><b>Important:</b> The Free version is limited to 50 products</p> -->
            <!-- <p><b>Upgrade Now:</b> <a href="http://www.pixelyoursite.com/product-catalog-facebook" target="_blank"><b class="wpwoof-clr-orange wpwoof-15p">Click here for a big discount</b></a></p> -->
            <p class="wpwoof-action-buttons">
               <input id="IDwpwoofSubmit" <?php if( !isset($_REQUEST['edit']) || empty($_REQUEST['edit']) ) echo 'style="width:100%;" '; ?>type="submit"  name="wpwoof-addfeed-submit" class="wpwoof-button wpwoof-button-blue" value="<?php echo $wpwoof_add_button; ?>" />
                <?php  if( isset($_REQUEST['edit']) && !empty($_REQUEST['edit']) ) { ?>
                    <a href="<?php menu_page_url('wpwoof-settings'); ?>" class="wpwoof-button">Back</a>
                <?php } ?>
            </p>
        </div>
    </div>
        
    <?php if( isset($_REQUEST['edit']) && !empty($_REQUEST['edit']) ) { ?>
        <input type="hidden" name="edit_feed" value="<?php echo $_REQUEST['edit']; ?>">
    <?php } ?>
</div>