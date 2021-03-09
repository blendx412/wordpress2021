<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="wrap wfco_connectors_listing wfco_global">
    <!--    <div class="wfco_page_heading"><img class="connector_logo" src="--><?php //echo WFCO_PLUGIN_URL; ?><!--/assets/img/logo_autobot.png" alt="-->
	<?php //echo __( 'Autobot', 'woofunnels' ); ?><!--"/></div>-->
    <div class="wfco_clear_10"></div>
    <div class="wfco_head_bar">
        <div class="wfco_bar_head"><?php _e( 'Connectors', 'woofunnels' ); ?></div>
        <!--        <a href="javascript:void(0)" class="button button-green button-large" data-izimodal-open="#modal-add-coupon" data-iziModal-title="Create New Deadline Coupon" data-izimodal-transitionin="fadeInDown">-->
		<?php //echo __( 'Add New', 'woofunnels' ); ?><!--</a>-->
        <!--        <a href="--><?php //echo admin_url( 'admin.php?page=connector&tab=settings' ); ?><!--" class="button button-green button-large wfco_btn_setting">-->
		<?php //echo __( 'Global Settings', 'woofunnels' ); ?><!--</a>-->
    </div>
    <div id="poststuff">
        <div class="inside">
            <div class="wrap wfco_global wfco_connector_listing">
                <div class="wfco_connector_listing_wrap wfco_clearfix">
                    <div class="wfco-row">
                        <form method="GET">
                            <input type="hidden" name="page" value="connector"/>
                            <input type="hidden" name="status" value="<?php echo( isset( $_GET['status'] ) ? $_GET['status'] : '' ); ?>"/>
							<?php
							$show_integrations = false;
							$all_connectors    = WFCO_Load_Integrations::get_integrations();
							$all_integration   = WFCO_Admin::get_available_connectors();
							//                          pre($all_connectors);
							//                          pre($all_integration);

							if ( is_array( $all_integration ) && count( $all_integration ) > 0 ) {
								foreach ( $all_integration as $source_slug => $integration ) {
									$edit_nonce      = wp_create_nonce( 'wfco-integration-edit' );
									$install_nonce   = wp_create_nonce( 'wfco-integration-install' );
									$delete_nonce    = wp_create_nonce( 'wfco-integration-delete' );
									$sync_nonce      = wp_create_nonce( 'wfco-integration-sync' );
									$connector_image = $integration['image'];
									if ( isset( $all_connectors[ $source_slug ] ) ) {
										$connector_object = $all_connectors[ $source_slug ];
										$connector_image  = $connector_object->get_image();
										//                                      pre($connector_object);continue;
									}

									?>
                                    <div class="wfco-col-md-4">
                                        <div class="wfco-integration-block_wrapper">
                                            <div class="wfco-integration-image">
                                                <img src="<?php echo $connector_image; ?>"/>
                                            </div>
                                            <div class="clear"></div>
                                            <div class="wfco-integration-action">
                                                <div class="wfco-integration-title"><?php echo $integration['name']; ?></div>
                                                <div class="wfco-integration-buttons wfco_body">
													<?php
													if ( class_exists( $integration['connector_class'] ) ) {
														if ( isset( WFCO_Common::$integrations_saved_data[ $source_slug ] ) ) {
															$connector        = $integration['connector_class']::get_instance();
															$id               = WFCO_Common::$integrations_saved_data[ $source_slug ]['id'];
															$modal_title      = __( 'Connect with ', 'woofunnels' ) . $integration['name'];
															$connector_single = add_query_arg( array(
																'page'    => 'connector',
																'section' => 'settings',
																'int'     => $source_slug,
																'id'      => $id,
															), admin_url( 'admin.php' ) );

															if ( true == $connector_object->is_setting_required ) {
																?>
                                                                <a href="javascript:void(0)" data-nonce="<?php echo $edit_nonce; ?>" data-id="<?php echo $id; ?>" data-slug="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco-integration-edit" data-izimodal-open="#modal-edit-integration" data-iziModal-title="<?php echo $modal_title; ?>" data-izimodal-transitionin="fadeInDown"><?php echo __( 'Settings', 'woofunnels' ); ?> </a>
                                                                <!--                                                            <a href="--><?php //echo $connector_single; ?><!--" data-nonce="--><?php //echo $edit_nonce; ?><!--" data-id="--><?php //echo $id; ?><!--" data-slug="--><?php //echo $source_slug; ?><!--" class="wfco_save_btn_style wfco-integration-edit">--><?php //echo __( 'Settings', 'woofunnels' ); ?><!-- </a>-->
																<?php
															}
															?>

															<?php if ( isset( $connector->sync ) && true == $connector->sync ) { ?>
                                                                <a href="javascript:void(0)" data-nonce="<?php echo $sync_nonce; ?>" data-id="<?php echo $id; ?>" data-slug="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco-integration-sync"><?php echo __( 'Sync', 'woofunnels' ); ?> </a>
															<?php } ?>
                                                            <a href="javascript:void(0)" data-nonce="<?php echo $delete_nonce; ?>" data-id="<?php echo $id; ?>" data-slug="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco-integration-delete"><?php echo __( 'Disconnect', 'woofunnels' ); ?> </a>
															<?php
														} else {
															$modal_title = __( 'Connect with ', 'woofunnels' ) . $integration['name'];
															$integration_type = 'indirect';
															if ( true == $connector_object->is_direct_integration ) {
																$integration_type = 'direct';
																?>
                                                                <a href="javascript:void(0)" data-type="<?php echo $integration_type;?>" data-slug="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco-integration-add wfco_save_btn_style"><?php echo __( 'Connect', 'woofunnels' ); ?> </a>
																<?php
															} else {
																?>
                                                                <a href="javascript:void(0)" data-type="<?php echo $integration_type;?>" data-slug="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco-integration-add" data-izimodal-open="#modal-add-integration" data-iziModal-title="<?php echo $modal_title; ?>" data-izimodal-transitionin="fadeInDown"><?php echo __( 'Connect', 'woofunnels' ); ?> </a>
																<?php
															}
															?>
															<?php
														}
													} else {
														$modal_title = '';
														?>
                                                        <a href="javascript:void(0)" data-nonce="<?php echo $install_nonce; ?>" data-connector="<?php echo $source_slug; ?>" class="wfco_save_btn_style wfco_connector_install" data-load-text="<?php echo __( 'Installing..', 'woofunnels' ); ?>" data-text="<?php echo __( 'Install', 'woofunnels' ); ?>"><?php echo __( 'Install', 'woofunnels' ); ?> </a>
														<?php


													}
													?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<?php
								}
							} else {
								?>
                                <label style="text-align: center;padding-top: 10px;"><?php echo __( 'No Integration to add', 'woofunnels' ); ?></label>
								<?php
							}

							?>
                        </form>
                    </div>
                </div>
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
