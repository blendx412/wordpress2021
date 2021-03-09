<?php
$allTemplates   = WFOCU_Core()->template_loader->get_templates();
$get_all_groups = WFOCU_Core()->template_loader->get_all_groups();
$offers         = WFOCU_Core()->funnels->get_funnel_offers_admin();

?>
<div class="wfocu_wrap_l">
    <div class="wfocu_p15">
        <div class="wfocu_heading_l"><?php echo __( 'Offers', 'woofunnels-upstroke-one-click-upsell' ); ?></div>
        <div class="wfocu_steps">
            <div class="wfocu_step">
				<?php echo __( 'Checkout', 'woofunnels-upstroke-one-click-upsell' ); ?>
                <span class="wfocu_down_arrow"></span>
            </div>
            <div class="wfocu_steps_sortable">
				<?php include __DIR__ . '/steps/offer-ladder.php'; ?>
            </div>
            <div class="wfocu_step">
				<?php echo __( 'Thank You Page', 'woofunnels-upstroke-one-click-upsell' ); ?>
                <span class="wfocu_up_arrow"></span>
            </div>
        </div>
    </div>
</div>

<?php

if ( false === is_array( $offers['steps'] ) || ( is_array( $offers['steps'] ) && count( $offers['steps'] ) == 0 ) ) {
	$funnel_id      = $offers['id'];
	$section_url    = add_query_arg( array(
		'page'    => 'upstroke',
		'section' => 'offers',
		'edit'    => $funnel_id,
	), admin_url( 'admin.php' ) );
	$offer_page_url = $section_url;
	?>
    <div class="wfocu_wrap_r wfocu_no_offer_wrap_r">
        <div class="wfocu_no_offers_wrapper wfocu_p20">
            <div class="wfocu_welcome_wrap">
                <div class="wfocu_welcome_wrap_in">
                    <div class="wfocu_no_offers_notice">
                        <div class="wfocu_welc_head">
                            <div class="wfocu_welc_icon"><img src="<?php echo WFOCU_PLUGIN_URL ?>/admin/assets/img/clap.png" alt="" title=""/></div>
                            <div class="wfocu_welc_title"> <?php _e( 'No offers in Current Funnel', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                        </div>
                        <div class="wfocu_welc_text">
                            <p><?php _e( ' You have to create some offers and add products in there.', 'woofunnels-upstroke-one-click-upsell' ); ?></p>

                        </div>
                    </div>
                    <a href="<?php echo $offer_page_url ?>" class="wfocu_step wfocu_button_add wfocu_button_inline  wfocu_welc_btn">
						<?php _e( '+ Create Your First Offer', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
	<?php
} else {
	?>
    <div class="wfocu_wrap_r" id="wfocu_step_design">
        <div class="wfocu-loader"><img src="<?php echo admin_url( 'images/spinner-2x.gif' ); ?>"/></div>
        <div class="wfocu_template">
            <div class="wfocu_fsetting_table_head">
                <div class="wfocu_fsetting_table_head_in wfocu_clearfix">
                    <div class="wfocu_fsetting_table_title"><?php echo  sprintf(__( 'Customizing Design for Offer <strong>%s</strong>','woofunnels-upstroke-one-click-upsell'), '{{getOfferNameByID()}}' ); ?>
                        <span class="wfocu_template_group" v-if="mode==`single`"><?php _e('using ','woofunnels-upstroke-one-click-upsell') ?> <strong>{{getTemplateGroupNiceName()}}</strong></span>
                    </div>
                </div>
            </div>

            <div v-if="!isEmpty(products)" class="wfocu_template_box_holder">

                <div class="wfocu_template_preview" v-if="mode==`single`">
                    <div class="wfocu_tp_wrap">
                        <div class="wfocu_wrap_i">
                            <img v-bind:src="getTemplateImage()">
                        </div>
                        <div class="wfocu_wrap_g"></div>
                        <div class="wfocu_wrap_c">
                            <div>
                                <div><strong><?php _e('Current Template','woofunnels-upstroke-one-click-upsell') ?></strong>: {{getTemplateNiceName()}}</div>
                                <br/>
                                <div class="wfocu_btns">
                                    <a href="javascript:void(0)" class="button" v-on:click="preview_template(current_template)"><?php esc_attr_e('Preview','')?></a>
                                    <a href="javascript:void(0)" class="button button-primary" v-on:click="customize_template(current_template)"><?php esc_attr_e('Customize','')?></a>
                                </div>
                                <div class="ftr">
                                    <a href="javascript:void(0)" class="wfocu_rm_template" v-on:click="remove_template()">Remove Template</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wfocu_btns"><a target="_blank" href="<?php echo esc_url('https://templates.buildwoofunnels.com/upstroke/'); ?>" class="wfocu-all-temps"><?php esc_attr_e('View all templates','');?></a> </div>

                </div>
                <div class="wfocu_template_holder_head2" v-if="mode==`choice`">
                    <div class="wfocu_offer_design_mode">
						<?php
						foreach ( $get_all_groups as $key => $template_group ) {
							?>
                            <a data-template="<?php echo $key; ?>" class="wfocu_design_btn" v-bind:class="template_group == `<?php echo $key; ?>`?` wfocu_btn_selected`:``" v-on:click="template_group = `<?php echo $key; ?>`"><?php echo $template_group->get_nice_name(); ?></a>
							<?php
						}
						?>
                        <a class="wfocu_design_btn" v-bind:class="template_group == `custom_page`?` wfocu_btn_selected`:``" v-on:click="template_group = `custom_page`"><?php echo __( 'Custom Page', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
                    </div>
                </div>
                <div class="wfocu_template_type_holder_in" v-if="mode==`choice`">
                    <div class="wfocu_single_template_list wfocu_template_list wfocu_clearfix" v-if="have_multiple_product==1">
						<?php
						foreach ( $get_all_groups as $key => $template_group ) {
							$get_templates = $template_group->get_templates();
							foreach ( $get_templates as $temp ) {
								$template = WFOCU_Core()->template_loader->get_template( $temp );

								if ( 'customizer' === $key && ! empty( $template['is_multiple'] ) ) {
									continue;
								}

								$temp_name = isset( $template['name'] ) ? $template['name'] : '';

								$prev_thumbnail = (isset( $template['thumbnail'] ) ) ? $template['thumbnail'] : '';
								$prev_full      = isset( $template['large_img'] ) ? $template['large_img'] : '';

								$import_status = null;
								if ( isset( $template['group'] ) && $template['group'] !== 'customizer' ) {
									$import_status = 'no';
									if ( true === $template['import_allowed'] ) {
										$import_status = 'yes';
									}
								}
								$temp_slug      = $temp;
								$temp_group     = $key;
								$overlay_icon   = '<i class="dashicons dashicons-visibility"></i>';
								$template_class = 'wfocu_temp_box_normal';
								$has_preview    = ( isset( $template['large_img'] ) || isset( $template['preview_url'] ) ) ? true : false;
								$preview_url    = ( isset( $template['preview_url'] ) && ! empty( $template['preview_url'] ) ) ? $template['preview_url'] : false;
								include plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'admin/view/templates/grid-template.php';

							}
						}
						include plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'admin/view/templates/grid-template-custom-page.php';

						?></div>

                </div>
                <div class="wfocu_template_type_holder_in" v-if="mode==`choice`">
                    <div class="wfocu_multiple_template_list wfocu_template_list wfocu_clearfix" v-if="have_multiple_product==2">
						<?php
						foreach ( $get_all_groups as $key => $template_group ) {

							$get_templates = $template_group->get_templates();

							foreach ( $get_templates as $temp ) {

								$template = WFOCU_Core()->template_loader->get_template( $temp );

								if ( 'customizer' === $key && empty( $template['is_multiple'] ) ) {
									continue;
								}
								$import_status = null;

								if ( isset( $template['group'] ) && $template['group'] !== 'customizer' ) {
									$import_status = 'no';
									if ( true === $template['import_allowed'] ) {
										$import_status = 'yes';
									}
								}

								$temp_name      = isset( $template['name'] ) ? $template['name'] : '';
								$prev_thumbnail = isset( $template['thumbnail'] ) ? $template['thumbnail'] : '';
								$prev_full      = isset( $template['large_img'] ) ? $template['large_img'] : '';
								$temp_slug      = $temp;
								$temp_group     = $key;
								$overlay_icon   = '<i class="dashicons dashicons-visibility"></i>';
								$template_class = 'wfocu_temp_box_normal';
								$has_preview    = ( isset( $template['large_img'] ) || isset( $template['preview_url'] ) ) ? true : false;

								$preview_url = ( isset( $template['preview_url'] ) && ! empty( $template['preview_url'] ) ) ? $template['preview_url'] : false;

								include plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'admin/view/templates/grid-template-multi.php';
							}
						}
						include plugin_dir_path( WFOCU_PLUGIN_FILE ) . 'admin/view/templates/grid-template-custom-page.php';

						?>

                        <div class="wfocu_success_modal" style="display: none" id="modal-template_success" data-iziModal-icon="icon-home">
                        <div class="wfocu_success_modal" style="display: none" id="modal-template_clear" data-iziModal-icon="icon-home">

                        </div>


                    </div>
                </div>
                <div class="ftr" v-if="mode==`choice` && current_template !== ``">
                    <a href="javascript:void(0)" v-on:click="mode = `single`">< Go back to selection</a>
                </div>
            </div>
        </div>


        <!-- Fallback when we do not have any products to show -->
        <div v-if="isEmpty(products)" class="wfocu-scodes-wrap">
<!--            <div class="wfocu-scodes-head">--><?php //_e( 'This offer does not have any products.', 'woofunnels-upstroke-one-click-upsell' ); ?><!--</div>-->

            <div class="wfocu_welcome_wrap" v-if="isEmpty(products)">
                <div class="wfocu_welcome_wrap_in">

                    <div class="wfocu_first_product" v-if="isEmpty(products)">
                        <div class="wfocu_welc_head">
                            <div class="wfocu_welc_icon"><img src="<?php echo WFOCU_PLUGIN_URL ?>/admin/assets/img/clap.png" alt="" title=""/></div>
                            <div class="wfocu_welc_title"> <?php _e( 'Add Product To This Offer', 'woofunnels-upstroke-one-click-upsell' ); ?>
                            </div>
                        </div>
                        <div class="wfocu_welc_text">
                            <p><?php _e( ' Add a product which is perfectly aligned with customer\'s main order. Greater the relevancy of offer, greater the chances of acceptance.', 'woofunnels-upstroke-one-click-upsell' ); ?></p>

                        </div>
                    </div>
                    <button type="button" style="cursor: pointer;" class="wfocu_step wfocu_button_inline wfocu_welc_btn" v-on:click="window.location = '<?php echo esc_url(admin_url('admin.php?page=upstroke&section=offer&edit='.$offers['id'].' ')); ?>'">
				        <?php _e( '+ Add New Product', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </button>
                </div>
            </div>

        </div>


        <!-- Show shortcodes in case of custom pages.  -->
        <div v-else-if="current_template == 'custom-page'" class="wfocu-scodes-wrap">
            <div class="wfocu-scodes-head">
				<?php _e( 'Shortcodes For Custom Page', 'woofunnels-upstroke-one-click-upsell' ); ?>

            </div>
            <div class="wfocu-scodes-desc"> Using page builders to build custom upsell pages?
                <a href="https://buildwoofunnels.com/docs/upstroke/design/custom-designed-one-click-upsell-pages/" target="_blank">Read this guide to learn more</a> about using Button widgets of your
                page builder.
            </div>


            <div v-for="shortcode in shortcodes" class="wfocu-scodes-inner-wrap">
                <div class="wfocu-scodes-list-wrap">
                    <div class="wfocu-scode-product-head"><?php _e( 'Product - ', 'woofunnels-upstroke-one-click-upsell' ); ?> {{shortcode.name}}</div>
                    <div class="wfocu-scodes-products">
                        <div v-for="key in shortcode.shortcodes" class="wfocu-scodes-row">

                            <div class="wfocu-scodes-label">{{key.label}}</div>
                            <div class="wfocu-scodes-value">
                                <div class="wfocu-scodes-value-in">
                                    <span class="wfocu-scode-text"><input readonly type="text" v-bind:value="key.value"></span>
                                    <a href="javascript:void(0)" v-on:click="copy" class="wfocu_copy_text"><?php _e( 'Copy', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show saved template to chose from here. -->
		<?php
		$template_names = get_option( 'wfocu_template_names', [] );
		if ( count( $template_names ) > 0 ) { ?>

            <div  v-if="!isEmpty(products) && template_group == 'customizer'" class="wfocu-scodes-wrap preset-holder">
                <div class="wfocu-scodes-head">
					<?php _e( 'Your Saved Presets', 'woofunnels-upstroke-one-click-upsell' ); ?>
                </div>
                <div class="wfocu-scodes-desc"> <?php _e( 'Click on the button to apply preset to the selected template. This will modify the default settings of the template and load it with settings of preset.', 'woofunnels-upstroke-one-click-upsell' ) ?>

                </div>
                <div class="wfocu-scodes-inner-wrap">
                    <div class="wfocu-scodes-list-wrap">
                        <div class="wfocu-scode-product-head"><?php _e( 'Apply and Customize Saved Presets', 'woofunnels-upstroke-one-click-upsell' ); ?> </div>
                        <div class="wfocu-scodes-products">

							<?php
							foreach ( $template_names as $template_slug => $template ) { ?>
                                <div class="customize-inside-control-row wfocu_template_holder wfocu-scodes-row">
                                    <div class="wfocu-scodes-label"><?php echo $template['name']; ?></div>
                                    <div class="wfocu-scodes-value-in wfocu-preset-right">
                                        <span class="wfocu-ajax-apply-preset-loader wfocu_hide"><img src="<?php echo admin_url( 'images/spinner.gif' ); ?>"></span>
                                        <a href="javascript:void(0);" class="wfocu_apply_template button-primary" data-slug="<?php echo $template_slug ?>"><?php _e( 'Apply', 'woofunnels-upstroke-one-click-upsell' ) ?></a>
                                        <a href="javascript:void(0)" class="wfocu_customize_template button-primary" style="display: none;"><?php echo __( 'Applied', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
                                    </div>
                                </div>
								<?php
							} ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>
<?php } ?>
