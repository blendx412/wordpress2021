<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wrap wfco_connectors_listing wfco_global">
    <div class="wfco_page_heading"><img class="connector_logo" src="<?php echo WFCO_PLUGIN_URL; ?>/assets/img/logo_autobot.png" alt="<?php echo __( 'Autobot', 'woofunnels' ); ?>"/></div>
    <div class="wfco_clear_10"></div>
    <div class="wfco_head_bar">
        <div class="wfco_bar_head"><?php _e( 'Connectors', 'woofunnels' ); ?></div>
        <a href="javascript:void(0)" class="button button-green button-large" data-izimodal-open="#modal-add-coupon" data-iziModal-title="Create New Deadline Coupon" data-izimodal-transitionin="fadeInDown"><?php echo __( 'Add New', 'woofunnels' ); ?></a>
        <a href="<?php echo admin_url( 'admin.php?page=connector&tab=settings' ); ?>" class="button button-green button-large wfco_btn_setting"><?php echo __( 'Global Settings', 'woofunnels' ); ?></a>
    </div>
    <div id="poststuff">
        <div class="inside">
            <div class=" wfco_global_settings_wrap wfco_page_col2_wrap">
                <div class="wfco_page_left_wrap" id="wfco_global_setting_vue">
                    <div class="wfco-setting-tabs-view-vertical wfco-setting-widget-tabs">
                        <div class="wfco-setting-widget-container">
                            <div class="wfco-setting-tabs wfco-tabs-style-line" role="tablist">
                                <div class="wfco-setting-tabs-wrapper wfco-tab-center">
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab wfco-active" id="tab-title-additional_information" data-tab="1" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Settings', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title description_tab " id="tab-title-description" data-tab="2" role="tab" aria-controls="wfco-tab-content-description">
										<?php _e( 'Map Fields', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="3" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Sync Data', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="4" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Import Users', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="5" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Export Users', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="6" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Advanced', 'woofunnels' ); ?>
                                    </div>
                                    <div class="wfco-tab-title wfco-tab-desktop-title additional_information_tab" id="tab-title-additional_information" data-tab="7" role="tab" aria-controls="wfco-tab-content-additional_information">
										<?php _e( 'Miscellaneous', 'woofunnels' ); ?>
                                    </div>
                                </div>
                                <div class="wfco-setting-tabs-content-wrapper">
                                    <div class="wfco-integration-setting">

                                    </div>
                                    <div class="wfco_global_setting_inner" id="wfco_global_setting">
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Settings
											<?php
											$show_integrations = false;
											//		$all_automations   = WFCO_Load_Integrations::get_integrations();
											$all_automations = array();
											foreach ( $all_automations as $source_slug => $source_object ) {
												if ( isset( $source_object->is_setting ) && $source_object->is_setting ) {
													$show_integrations = true;
												}
											}
											?>
                                            <form class="wfco_update_integration" id="wfco-autoresponder" method="post" data-wfoaction="update_integration">
                                                <div class="wfco_vue_forms" id="part-add-funnel">
                                                    <div id="wfco_integrations_edit_fields"></div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Map Fields</div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Sync Data</div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Import Users</div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Export Users</div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Advanced</div>
                                        <div class="wfco_forms_wrap wfco_forms_global_settings ">Miscellaneous</div>

                                        <div style="display: none" id="modal-global-settings_success" data-iziModal-icon="icon-home">


                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wfco_form_button">
                        <button style="float: left;" class="wfco_save_btn_style">
							<?php _e( 'Save Settings', 'woofunnels-order-coupon' ); ?></button>
                        <span class="wfco_loader_global_save spinner" style="float: left;"></span>
                    </div>
                </div>
                <div class="wfco_page_right_wrap">
					<?php do_action( 'wfco_page_right_content' ); ?>
                </div>
                <div class="wfco_clear"></div>
            </div>
        </div>
    </div>
</div>

<div class="wfco_izimodal_default" style="display: none" id="modal-add-integration">
    <div class="sections">
        <form class="wfco_add_integration" id="wfco-autoresponder" method="post" data-wfoaction="save_integration">
            <div class="wfco_vue_forms" id="part-add-funnel">
                <div id="wfco_integrations_fields"></div>
            </div>
        </form>
        <div class="wfco-integration-create-success-wrap wfco-display-none">
            <div class="wfco-integration-connect-success-logo">
                <!--                <i class="dashicons dashicons-yes"></i>-->
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                </div>
            </div>
            <div class="wfco-integration-connect-message"><?php _e( 'Integration Connected Successfully. Redirecting the page...', 'woofunnels' ); ?></div>
        </div>
    </div>
</div>
<div class="wfco_izimodal_default" style="display: none" id="modal-edit-integration">
    <div class="sections">
		<?php
		$show_integrations = false;
		//		$all_automations   = WFCO_Load_Integrations::get_integrations();
		$all_automations = array();
		foreach ( $all_automations as $source_slug => $source_object ) {
			if ( isset( $source_object->is_setting ) && $source_object->is_setting ) {
				$show_integrations = true;
			}
		}
		?>
        <form class="wfco_update_integration" id="wfco-autoresponder" method="post" data-wfoaction="update_integration">
            <div class="wfco_vue_forms" id="part-add-funnel">
                <div id="wfco_integrations_edit_fields"></div>
            </div>
        </form>
        <div class="wfco-automation-update-success-wrap wfco-display-none">
            <div class="wfco-automation-update-success-logo">
                <!--                <i class="dashicons dashicons-yes"></i>-->
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                </div>
            </div>
            <div class="wfco-automation-update-message"><?php _e( 'We have detected change in the integration during updation.', 'woofunnels' ); ?></div>
        </div>
    </div>
</div>
<?php
$all_automations = WFCO_Load_Integrations::get_integrations();
if ( is_array( $all_automations ) && count( $all_automations ) > 0 ) {
	foreach ( $all_automations as $source_slug => $source_object ) {
		if ( isset( $source_object->is_setting ) && $source_object->is_setting ) {
			?>
            <script type="text/html" id="tmpl-int-<?php echo $source_slug; ?>">
				<?php echo $source_object->get_settings_view(); ?>
            </script>
			<?php
		}
	}
}

?>
<div class="wfco_izimodal_default" style="display: none" id="modal-add-connector">
    <div class="sections">
		<?php
		//		$all_automations   = WFCO_Load_Integrations::get_integrations();
		$all_automations    = array();
		$autoresponder_arr  = array();
		$integrations_saved = array();
		//		$integrations_saved = array_keys( WFCO_Common::$integrations_saved_data );
		foreach ( $integrations_saved as $autoresponder ) {
			if ( isset( $all_automations[ $autoresponder ]->is_setting ) && $all_automations[ $autoresponder ]->is_setting ) {
				$autoresponder_arr[ $autoresponder ] = $all_automations[ $autoresponder ]->nice_name;
			}
		}

		if ( is_array( $autoresponder_arr ) && count( $autoresponder_arr ) > 0 ) {
			?>
            <form class="wfco_add_new_connector" id="wfco-add-new-connector" method="post" data-wfoaction="save_add_new_connector_settings">
                <div class="wfco-form-group">
                    <label><?php echo __( 'Title', 'woofunnels' ); ?></label>
                    <input required type="text" name="wfco_dc_title" class="form-control"/>
                </div>
                <div class="wfco-form-group">
                    <label><?php echo __( 'Select Autoresponder', 'woofunnels' ); ?></label>
                    <select required class="wfco-dc-autoresponder form-control" name="wfco_dc_autoresponder">
                        <option value="">Select Autoresponder</option>
						<?php
						foreach ( $autoresponder_arr as $key => $value ) {
							echo '<option value="' . $key . '">' . $value . '</option>';
						}
						?>
                    </select>
                </div>
                <div class="wfco-form-groups wfco_form_submit">
                    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'wfco-new-connector' ); ?>">
                    <input type="submit" class="wfco_save_btn_style" name="connector_Submit" value="Create">
                </div>
                <div class="wfco_form_response" style="text-align: center;font-size: 15px;margin-top: 10px;"></div>
            </form>
			<?php
		} else {
			/*
		    _e( 'Please add Integration', 'woofunnels' );
			$url = add_query_arg( array(
				'page' => 'connector',
				'tab'  => 'integrations',
			), admin_url( 'admin.php' ) );
			wp_redirect( $url );
			exit;
			*/
		}
		?>
        <div class="wfco-connector-create-success-wrap">
            <div class="wfco-connector-create-success-logo">
                <!--                <i class="dashicons dashicons-yes"></i>-->
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                    <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                </div>
            </div>
            <div class="wfco-connector-create-message"><?php _e( 'Deadline Coupon Created Successfully. Launching Deadline Coupon Editor...', 'woofunnels' ); ?></div>
        </div>
    </div>
</div>
