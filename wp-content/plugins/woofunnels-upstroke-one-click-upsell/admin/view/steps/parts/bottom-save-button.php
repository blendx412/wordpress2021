<div id="offer_settings_btn_bottom" class="wfocu_form_submit wfocu_btm_grey_area wfocu_clearfix" v-if="current_offer_id>0 ">

    <a href="javascript:void(0)" v-on:click="delete_offer" class="wfocu_del_offer_style wfocu_left"><?php _e( 'Delete Offer', 'woofunnels-upstroke-one-click-upsell' ) ?></a>
    <div class=" wfocu_btm_save_wrap wfocu_clearfix">
	<span class="wfocu_save_funnel_offer_products_ajax_loader spinner"></span>
	<a href="javascript:void(0)" v-if="Object.keys(products).length>0" v-on:click="submit" class="wfocu_save_btn_style"><?php _e( 'Save Offer', 'woofunnels-upstroke-one-click-upsell' ) ?></a>
    </div>
 </div>