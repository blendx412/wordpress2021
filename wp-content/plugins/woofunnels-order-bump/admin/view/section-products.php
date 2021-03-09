<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>
    .wfob_wrap_r {
        float: right;
        width: 100%;

    }
</style>

<div class="wfob_funnel_setting" id="wfob_product_container">
    <div class="wfob_funnel_setting_inner">
        <div class="wfob_fsetting_table_head">
            <div class="wfob_fsetting_table_head_in wfob_clearfix">
                <div class="wfob_fsetting_table_title "><?php echo __( '<strong>Products</strong>', 'woofunnels-order-bump' ); ?>
                </div>
                <div class="setting_save_buttons wfob_form_submit" v-if="!isEmpty()">
                    <span class="wfob_save_funnel_setting_ajax_loader wfob_spinner spinner spinner"></span>
                    <button class="wfob_save_btn_style" v-on:click="save_products()"><?php _e( 'Save Products', 'woofunnels-order-bump' ); ?></button>
                </div>
            </div>
        </div>

        <div class="products_container" v-if="!isEmpty()">
			<?php include_once __DIR__ . '/products/table.php'; ?>
        </div>
		<?php include_once __DIR__ . '/products/add-new.php'; ?>
        <div class="product_add_new" v-if="!isEmpty()">
            <div class="wfob_steps">
                <div class="wfob_step wfob_button_add wfob_modal_open" data-izimodal-open="#modal-add-product">
                    + <?php _e( 'Add Product', 'woofunnels-order-bump' ); ?>
                </div>
            </div>
        </div>
        <div id="offer_settings_btn_bottom" class="wfob_form_submit wfob_btm_grey_area wfob_clearfix" v-if="!isEmpty()">
            <div class=" wfob_btm_save_wrap wfob_clearfix">
                <span class="wfob_spinner spinner"></span>
                <a href="javascript:void(0)" class="wfob_save_btn_style" v-on:click="save_products()"><?php _e( 'Save Products', 'woofunnels-order-bump' ); ?></a>
            </div>
        </div>
        <div class="wfob_clear"></div>
    </div>

</div>
<?php include __DIR__ . '/products/models.php'; ?>
