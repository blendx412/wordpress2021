<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style [data-type='wfob' ]></style>
<?php
$product = WFOB_Common::get_bump_products( WFOB_Common::get_id() );
?>
<div class="wfob_design_setting" id="wfob_design_setting">

    <div class="wfob_design_setting_inner">
        <div class="wfob_fsetting_table_head">
            <div class="wfob_fsetting_table_head_in wfob_clearfix">
                <div class="wfob_fsetting_table_title "><?php echo __( '<strong>Design</strong>', 'woofunnels-order-bump' ); ?>
                </div>
				<?php if ( count( $product ) > 0 ) { ?>
                    <div class="setting_save_buttons wfob_form_submit">
                        <span class="wfob_spinner spinner"></span>
                        <button class="wfob_save_btn_style" v-on:click="save()"><?php _e( 'Save Design', 'woofunnels-order-bump' ); ?></button>
                    </div>
				<?php } ?>
            </div>
        </div>


        <div class="wfob_content_wrap wfob_sec_wrap_h">


			<?php if ( count( $product ) > 0 ) { ?>
                <div class="wfob_wrap_l">
                    <div class="wfob-product-tabs-view-horizontal wfob-product-widget-tabs">
                        <div class="wfob-product-widget-container">


                            <div class="wfob-product-tabs wfob-tabs-style-line" role="tablist">
                                <div class="wfob-product-tabs-wrapper wfob-tab-center wfob_tab_wrap">
                                    <div class="wfob-tab-title wfob-tab-desktop-title wfob_error_msgs wfob-active" id="wfob-error-msgs" data-tab="1" role="tab" aria-controls="wfob-error-msgs">
										<?php _e( 'Title', 'woofunnels-order-bump' ); ?>
                                    </div>
                                    <div class="wfob-tab-title wfob-tab-desktop-title wfob_tracking_analytics " id="wfob-tracking-analytics" data-tab="2" role="tab" aria-controls="wfob-tracking-analytics">
										<?php _e( 'Skin', 'woofunnels-order-bump' ); ?>
                                    </div>

                                    <div class="wfob-tab-title wfob-tab-desktop-title wfob_miscellaneous " id="wfob-miscellaneous" data-tab="3" role="tab" aria-controls="wfob-miscellaneous">
										<?php _e( 'Style', 'woofunnels-order-bump' ); ?>
                                    </div>


                                </div>
                                <div class="wfob-product-tabs-content-wrapper">
                                    <div class="wfob_global_setting_inner">
                                        <div class="wfob_global_container" id="wfob_global_settings">
                                            <form id="modal-global-settings-form" class="wfob_forms_global_settings" data-wfoaction="global_settings" v-on:submit.prevent="save" @change="onchange()">
                                                <div class="wfob_vue_forms">
                                                    <div class="wfob_design_generator">
                                                        <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                                                    </div>
                                                    <div class="wfob_skin_selection" v-if="2==current_tab">
                                                        <div class="wfob_skin_selection_single layout_1" v-on:click="setSkin('layout_1')" v-bind:data-selected="model.layout=='layout_1'?'yes':''">
                                                            <img src="<?php echo WFOB_PLUGIN_URL; ?>/assets/img/skin_1.jpg">
                                                        </div>
                                                        <div class="wfob_skin_selection_single layout_2" v-on:click="setSkin('layout_2')" v-bind:data-selected="model.layout=='layout_2'?'yes':''">
                                                            <img src="<?php echo WFOB_PLUGIN_URL; ?>/assets/img/skin_2.jpg">
                                                        </div>
                                                        <br>
                                                    </div>
                                                    <div class="wfob_skin_selection" v-if="4==current_tab">
                                                        <div class="custom_style_wrap">
                                                            <fieldset>
                                                                <div class="form-group wfob_design_box_heading field-label">
                                                                    <label for="title">Custom CSS Tweaks </label>
                                                                </div>
                                                            </fieldset>

                                                            <div class="form-group">
                                                                <textarea rows="15" cols="50" v-model="model.custom_css" v-on:keyup="custom_css()"></textarea>
                                                                <div class="hint"><?php _e( 'Add your custom CSS to apply on all AeroCheckout pages', 'woofunnels-order-bump' ); ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--                                                {{schema.groups[1]}}-->
                                                    <br>
                                                    <br>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wfob_wrap_r">
                    <h2><?php _e( 'Preview', 'woofunnels-order-bump' ); ?></h2>
					<?php
					WFOB_Core()->admin->get_bump_layout();
					?>
                </div>
				<?php
			} else {


				$current_bump_id = $this->get_bump_id();
				$section_url     = add_query_arg( array(
					'page'    => 'wfob',
					'section' => 'products',
					'wfob_id' => $current_bump_id,
				), admin_url( 'admin.php' ) );


				?>
                <div class="wfob_wrap_l wfob_no_product_added">
                    <div class="wfob_welcome_wrap">
                        <div class="wfob_welcome_wrap_in">
                            <div class="wfob_first_product">
                                <div class="wfob_welc_head">
                                    <div class="wfob_welc_icon"><img src="<?php echo WFOB_PLUGIN_URL; ?>/admin/assets/img/clap.png" alt="" title=""/></div>
                                    <div class="wfob_welc_title"> <?php _e( 'Add Product', 'woofunnels-order-bump' ); ?>
                                    </div>
                                </div>


                                <div class="wfob_welc_text">
                                    <p><?php _e( 'You need to add atleast one product to this order bump to select design.', 'woofunnels-order-bump' ); ?></p>
                                </div>
                                <a href="<?php echo $section_url; ?>" class="wfob_step wfob_button_add wfob_button_inline wfob_modal_open wfob_welc_btn">
									<?php _e( 'Go To Products', 'woofunnels-order-bump' ); ?>
                                </a>


                            </div>
                        </div>
                    </div>


                </div>
				<?php
			}
			?>
        </div>
        <div class="wfob_fsetting_table_foot_wrap">
            <div class="wfob_fsetting_table_head">
				<?php if ( count( $product ) > 0 ) { ?>
                    <div class="wfob_fsetting_table_head_in wfob_clearfix">
                        <div class="setting_save_buttons wfob_form_submit">
                            <span class="wfob_spinner spinner"></span>
                            <button class="wfob_save_btn_style" v-on:click="save()"><?php _e( 'Save Design', 'woofunnels-order-bump' ); ?></button>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>
