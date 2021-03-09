<?php

$sidebar_menu        = WFOCU_Common::get_sidebar_menu();
$funnel_sticky_line  = __( 'Now Building', 'woofunnels-upstroke-one-click-upsell' );
$funnel_sticky_title = '';
$funnel_onboarding   = true;
if ( isset( $_GET['edit'] ) && ! empty( $_GET['edit'] ) ) {   // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
	$funnel_sticky_title = get_the_title( wc_clean( $_GET['edit'] ) );  // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

	$funnel_onboarding_status = get_post_meta( wc_clean( $_GET['edit'] ), '_wfocu_is_rules_saved', true );  // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

	if ( 'yes' === $funnel_onboarding_status ) {
		$funnel_onboarding  = false;
		$funnel_sticky_line = __( 'Now Editing', 'woofunnels-upstroke-one-click-upsell' );
	}
}

$funnel_status = get_post_status( wc_clean( $_GET['edit'] ) );  // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
$funnel_id     = wc_clean( $_GET['edit'] );  // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

?>
<div class="wfocu_body">
    <div class="wfocu_fixed_header">
        <div class="wfocu_p20_wrap wfocu_box_size wfocu_table">
            <div class="wfocu_head_m wfocu_tl wfocu_table_cell">
                <div class="wfocu_head_mr" data-status="<?php echo ( $funnel_status !== 'publish' ) ? 'sandbox' : 'live'; ?>">
                    <div class="funnel_state_toggle wfocu_toggle_btn">
                        <input name="offer_state" id="state<?php echo esc_attr( $funnel_id ); ?>" data-id="<?php echo esc_attr( $funnel_id ); ?>" type="checkbox" class="wfocu-tgl wfocu-tgl-ios" <?php echo ( $funnel_status === 'publish' ) ? 'checked="checked"' : ''; ?> />
                        <label for="state<?php echo esc_attr( $funnel_id ); ?>" class="wfocu-tgl-btn wfocu-tgl-btn-small"></label>
                    </div>
                    <span class="wfocu_head_funnel_state_on" <?php echo ( $funnel_status !== 'publish' ) ? ' style="display:none"' : ''; ?>><?php esc_attr_e( 'Live', 'woofunnels-upstroke-one-click-upsell' ); ?></span>
                    <span class="wfocu_head_funnel_state_off" <?php echo ( $funnel_status === 'publish' ) ? 'style="display:none"' : ''; ?>> <?php esc_attr_e( 'Sandbox', 'woofunnels-upstroke-one-click-upsell' ); ?></span>
                </div>
                <div class="wfocu_head_ml">
					<?php echo esc_attr( $funnel_sticky_line ); ?> <strong><span id="wfocu_funnel_name"><?php echo esc_attr( $funnel_sticky_title ); ?></span></strong>
                    <a href="javascript:void()" data-izimodal-open="#modal-update-funnel" data-izimodal-transitionin="fadeInDown"><i class="dashicons dashicons-edit"></i></a>
                </div>
            </div>
            <div style="display: none;" id="wfocu_funnel_description">

            </div>
            <div class="wfocu_head_r wfocu_tr wfocu_table_cell">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=upstroke' ) ); ?>" class="wfocu_head_close"><i class="dashicons dashicons-no-alt"></i></a></div>
        </div>
    </div>
    <div class="wfocu_fixed_sidebar">
		<?php
		if ( is_array( $sidebar_menu ) && count( $sidebar_menu ) > 0 ) {
			ksort( $sidebar_menu );
			$funnel_data = WFOCU_Core()->funnels->get_funnel_offers_admin();
			foreach ( $sidebar_menu as $menu ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				$menu_icon = ( isset( $menu['icon'] ) && ! empty( $menu['icon'] ) ) ? $menu['icon'] : 'dashicons dashicons-admin-generic';
				if ( isset( $menu['name'] ) && ! empty( $menu['name'] ) ) {

					$section_url = add_query_arg( array(
						'page'    => 'upstroke',
						'section' => $menu['key'],
						'edit'    => WFOCU_Core()->funnels->get_funnel_id(),
					), admin_url( 'admin.php' ) );

					$class = '';
					if ( isset( $_GET['section'] ) && $menu['key'] === wc_clean( $_GET['section'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
						$class = 'active';
					}

					global $wfocu_is_rules_saved;

					$main_url = $section_url;

					?>
                    <a class="wfocu_s_menu <?php echo esc_attr( $class ); ?> wfocu_s_menu_<?php echo esc_attr( $menu['key'] ); ?>" href="<?php echo esc_url( $main_url ); ?>" data-href="<?php echo esc_url( $section_url ); ?>">
					<span class="wfocu_s_menu_i">
						<i class="<?php echo esc_attr( $menu_icon ); ?>"></i>
					</span>
                        <span class="wfocu_s_menu_n">
						<?php echo esc_attr( $menu['name'] ); ?>
					</span>
                    </a>
					<?php
				}
			}
		}
		?>
    </div>
    <div class="wfocu_wrap wfocu_box_size">
        <div class="wfocu_p20 wfocu_box_size">
            <div class="wfocu_wrap_inner <?php echo ( isset( $_REQUEST['section'] ) ) ? 'wfocu_wrap_inner_' . esc_attr( wc_clean( $_REQUEST['section'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification ?>">

				<?php
				$get_keys = wp_list_pluck( $sidebar_menu, 'key' );


				/**
				 * Redirect if any unregistered action found
				 */
				if ( false === in_array( $this->section_page, $get_keys, true ) ) {
					wp_redirect( admin_url( 'admin.php?page=upstroke&section=offers&edit=' . wc_clean( $_GET['edit'] ) ) );   // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
					exit;
				} else {

					/**
					 * Any registered section should also apply an action in order to show the content inside the tab
					 * like if action is 'stats' then add_action('wfocu_dashboard_page_stats', __FUNCTION__);
					 */
					if ( false === has_action( 'wfocu_dashboard_page_' . $this->section_page ) ) {
						include_once( $this->admin_path . '/view/' . $this->section_page . '.php' );  // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

					} else {
						/**
						 * Allow other add-ons to show the content
						 */
						do_action( 'wfocu_dashboard_page_' . $this->section_page );
					}
				}


				do_action( 'wfocu_funnel_page', $this->section_page, WFOCU_Core()->funnels->get_funnel_id() );
				?>

                <div class="wfocu_clear"></div>
            </div>
        </div>
    </div>

    <div class="wfocu_izimodal_default" id="modal-update-funnel" style="display: none;">
        <div class="sections">
            <form class="wfocu_forms_update_funnel" data-wfoaction="update_funnel" novalidate>
                <div class="wfocu_vue_forms" id="part-update-funnel">
                    <div class="vue-form-generator">
                        <fieldset>
                            <div class="form-group featured required field-input"><label for="funnel-name">Name<!----></label>
                                <div class="field-wrap">
                                    <div class="wrapper"><input id="funnel-name" type="text" name="funnel_name" placeholder="Enter Name" required="required" class="form-control"><!----></div><!---->
                                </div><!----><!----></div>
                            <div class="form-group featured field-textArea"><label for="funnel-desc">Description<!----></label>
                                <div class="field-wrap"><textarea id="funnel-desc" placeholder="Enter Description (Optional)" rows="3" name="funnel_desc" class="form-control"></textarea><!----></div>
                                <!----><!----></div>
                        </fieldset>
                    </div>
                </div>
                <fieldset>
                    <div class="wfocu_form_submit">
                        <input type="hidden" name="_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wfocu_update_funnel' ) ); ?>"/>
                        <button type="submit" class="wfocu_submit_btn_style" value="add_new">Update</button>
                    </div>
                    <div class="wfocu_form_response">

                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
