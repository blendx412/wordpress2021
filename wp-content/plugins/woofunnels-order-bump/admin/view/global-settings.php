<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div id="poststuff">
    <div class="inside">
        <div class="wrap wfob_global wfob_global_settings">
            <div class="wfob_page_heading"><img class="wfob_logo" src="<?php echo WFOB_PLUGIN_URL; ?>/admin/assets/img/logo_autobot.png" alt="Order Bump"/></div>
            <div class="wfob_clear_10"></div>
            <div class="wfob_head_bar">
                <div class="wfob_bar_head"><?php _e( 'Global Settings', 'woofunnels-order-bump' ); ?></div>
                <a href="<?php echo admin_url( 'admin.php?page=wfob' ); ?>" class="button button-green button-large wfob_btn_setting">
					<?php echo __( '<< Back to OrderBumps', 'woofunnels-order-bump' ); ?>
                </a>
            </div>


            <div class=" wfob_global_settings_wrap wfob_page_col2_wrap">
                <div class="wfob_page_left_wrap" id="wfob_global_setting_vue">


                    <div class="wfob-product-tabs-view-vertical wfob-product-widget-tabs">
                        <div class="wfob-product-widget-container">
                            <div class="wfob-product-tabs wfob-tabs-style-line" role="tablist">
                                <div class="wfob-product-tabs-wrapper wfob-tab-center">
                                    <div class="wfob-tab-title wfob-tab-desktop-title global_custom_css wfob-active" id="tab-title-global_custom_css" data-tab="1" role="tab" aria-controls="wfob-tab-content-global_custom_css">
										<?php echo __( 'Global Custom CSS', 'woofunnels-order-bump' ); ?>
                                    </div>
                                    <div class="wfob-tab-title wfob-tab-desktop-title additional_information_tab wfob-active" id="tab-title-additional_information" data-tab="2" role="tab" aria-controls="wfob-tab-content-additional_information">
										<?php echo __( 'Miscellaneous', 'woofunnels-order-bump' ); ?>
                                    </div>
                                </div>
                                <div class="wfob-product-tabs-content-wrapper">
                                    <div class="wfob_global_setting_inner" id="wfob_global_setting">
                                        <form class="wfob_forms_wrap wfob_forms_global_settings " v-on:submit.prevent="save">
                                            <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                                            <fieldset>
                                                <div class="wfob_form_submit" style="display: inline-block">
                                                    <input type="submit" class="wfob_submit_btn_style" value="<?php _e( 'Save Settings', 'woofunnels-aero-checkout' ); ?>"/>
                                                    <span class="wfob_spinner spinner" style="float: right"></span>
                                                </div>
                                            </fieldset>
                                        </form>


                                        <div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">


                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="wfob_page_right_wrap">
					<?php do_action( 'wfob_page_right_content' ); ?>
                </div>
                <div class="wfob_clear"></div>
            </div>
        </div>
    </div>
</div>



