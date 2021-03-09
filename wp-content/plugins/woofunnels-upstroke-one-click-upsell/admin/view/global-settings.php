<div class="wrap wfocu_global wfocu_global_settings">
    <div class="wfocu_page_heading"><img class="upstroke_logo" src="<?php echo WFOCU_PLUGIN_URL; ?>/admin/assets/img/logo_upstroke.png" alt="Up Stroke"/></div>
    <div class="wfocu_clear_10"></div>
    <div class="wfocu_head_bar">
        <div class="wfocu_bar_head"><?php _e( 'Global Settings', 'woofunnels-upstroke-one-click-upsell' ); ?></div>
        <a href="<?php echo admin_url( 'admin.php?page=upstroke' ); ?>" class="button button-green button-large wfocu_btn_setting">
			<?php echo __( '<< Back to Listing', 'woofunnels-upstroke-one-click-upsell' ); ?> </a>
    </div>

    <div id="poststuff" class=" wfocu_global_settings_wrap wfocu_page_col2_wrap">
        <div class="wfocu_page_left_wrap" id="wfocu_global_setting_vue">
            <div class="wfocu-product-tabs-view-vertical wfocu-product-widget-tabs">
                <div class="wfocu-product-widget-container">
                    <div class="wfocu-product-tabs wfocu-tabs-style-line" role="tablist">
                        <div class="wfocu-product-tabs-wrapper wfocu-tab-center">

                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab wfocu-active" id="tab-title-additional_information" data-tab="1" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'Gateways', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title description_tab " id="tab-title-description" data-tab="2" role="tab" aria-controls="wfocu-tab-content-description">
								<?php _e( 'Order Statuses', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="3" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'Confirmation Email', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="4" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'Tracking & Analytics', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="5" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'External Scripts', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="6" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'Offer Confirmation', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                            <div class="wfocu-tab-title wfocu-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="7" role="tab" aria-controls="wfocu-tab-content-additional_information">
								<?php _e( 'Miscellaneous', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>

                        </div>
                        <div class="wfocu-product-tabs-content-wrapper">

                            <div class="wfocu_global_setting_inner" id="wfocu_global_setting">


                                <form class="wfocu_forms_wrap wfocu_forms_global_settings ">
                                    <fieldset>
                                        <vue-form-generator :schema="schema" :model="model" :options="formOptions">

                                        </vue-form-generator>
                                    </fieldset>

                                </form>


                                <div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="wfocu_form_button">
                <button v-on:click.self="onSubmit" style="float: left;" class="wfocu_save_btn_style"><?php _e( 'Save Settings', 'woofunnels-upstroke-one-click-upsell' ); ?></button>
                <span class="wfocu_loader_global_save spinner" style="float: left;"></span>
            </div>
        </div>
        <div class="wfocu_page_right_wrap">
			<?php do_action( 'wfocu_page_right_content' ); ?>
        </div>
        <div class="wfocu_clear"></div>
    </div>
    <div style="display: none" class="wfocu-global-settings-help-ofc" data-iziModal-title="<?php echo __( 'Offer Confirmation Help', 'woofunnels-upstroke-one-click-upsell' ) ?>" data-iziModal-icon="icon-home">
        <div class="sections wfocu_img_preview" style="height: 254px;">
            <img src="<?php echo WFOCU_PLUGIN_URL . '/assets/img/global-settings-help-guide-offer.jpg' ?>"/>
        </div>
    </div>
</div>




