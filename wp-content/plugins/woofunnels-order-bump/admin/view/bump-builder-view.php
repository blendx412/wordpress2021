<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $this WFOB_Admin;
 */

$bump_id = $this->get_bump_id();


if ( false === $bump_id ) {
	wp_die( __( 'Something is wrong with the URL, Automation ID is required', 'woofunnels-order-bump' ) );
}


$sidebar_menu      = WFOB_Common::get_sidebar_menu();
$bump_sticky_line  = __( 'Now Building', 'woofunnels-order-bump' );
$bump_sticky_title = '';
$bump_onboarding   = true;
if ( isset( $bump_id ) && ! empty( $bump_id ) ) {
	$bump_sticky_title = get_the_title( $bump_id );

	$bump_onboarding_status = get_post_meta( $bump_id, '_wfob_is_rules_saved', true );

	if ( 'yes' == $bump_onboarding_status ) {
		$bump_onboarding  = false;
		$bump_sticky_line = __( 'Now Editing', 'woofunnels-order-bump' );
	}
}

$status  = get_post_status( $bump_id );
$bump_id = ( $bump_id );

?>
<style>
    .wfob_loader {
        position: absolute;
        width: calc(100% - 104px);
        height: 100%;
        margin-top: -8px;
        text-align: center;
        background: #fff;
        z-index: 100;
        min-height: 900px; /*opacity: 0.7;*/
    }

    .wfob_loader .spinner {
        visibility: visible;
        margin: auto;
        width: 50px;
        float: none;
        margin-top: 25%;
    }
</style>


<?php include_once __DIR__ . '/global/model.php'; ?>
<div class="wfob_body">
    <div class="wfob_fixed_header">
        <div class="wfob_p20_wrap wfob_box_size wfob_table">
            <div class="wfob_head_m wfob_tl wfob_table_cell">
                <div class="wfob_head_mr" data-status="<?php echo ( $status !== 'publish' ) ? 'sandbox' : 'live'; ?>">
                    <div class="bump_state_toggle wfob_toggle_btn">
                        <input name="offer_state" id="state<?php echo $bump_id; ?>" data-id="<?php echo $bump_id; ?>" type="checkbox" class="wfob-tgl wfob-tgl-ios wfob_checkout_page_status" <?php echo ( $status == 'publish' ) ? 'checked="checked"' : ''; ?> />
                        <label for="state<?php echo $bump_id; ?>" class="wfob-tgl-btn wfob-tgl-btn-small"></label>
                    </div>
                    <span class="wfob_head_bump_state_on" <?php echo ( $status !== 'publish' ) ? ' style="display:none"' : ''; ?>><?php _e( 'Live', 'woofunnels-order-bump' ); ?></span>
                    <span class="wfob_head_bump_state_off" <?php echo ( $status == 'publish' ) ? 'style="display:none"' : ''; ?>> <?php _e( 'Sandbox', 'woofunnels-order-bump' ); ?></span>
                </div>
                <div class="wfob_head_ml">
					<?php echo $bump_sticky_line; ?> <strong><span id="wfob_bump_name"><?php echo $bump_sticky_title; ?></span></strong>
                    <a href="javascript:void()" data-izimodal-open="#modal-add-bump" data-izimodal-transitionin="fadeInDown"><i class="dashicons dashicons-edit"></i></a>
                </div>
            </div>
            <div style="display: none;" id="wfob_bump_description">

            </div>
            <div class="wfob_head_r wfob_tr wfob_table_cell">
                <a href="<?php echo admin_url( 'admin.php?page=wfob' ); ?>" class="wfob_head_close"><i class="dashicons dashicons-no-alt"></i></a></div>
        </div>
    </div>
    <div class="wfob_fixed_sidebar">
		<?php
		if ( is_array( $sidebar_menu ) && count( $sidebar_menu ) > 0 ) {
			ksort( $sidebar_menu );


			foreach ( $sidebar_menu as $menu ) {
				$menu_icon = ( isset( $menu['icon'] ) && ! empty( $menu['icon'] ) ) ? $menu['icon'] : 'dashicons dashicons-admin-generic';
				if ( isset( $menu['name'] ) && ! empty( $menu['name'] ) ) {

					$section_url = add_query_arg( array(
						'page'    => 'wfob',
						'section' => $menu['key'],
						'wfob_id' => $bump_id,
					), admin_url( 'admin.php' ) );

					$class = '';
					if ( $menu['key'] === $this->get_bump_section() ) {
						$class = 'active';
					}

					global $wfob_is_rules_saved;

					$main_url = $section_url;

					?>
                    <a class="wfob_s_menu <?php echo $class; ?> wfob_s_menu_<?php echo $menu['key']; ?>" href="<?php echo $main_url; ?>" data-href="<?php echo $section_url; ?>">
                        <span class="wfob_s_menu_i">						<img src="<?php echo $menu_icon; ?>" alt="">					</span>
                        <span class="wfob_s_menu_n">						<?php echo $menu['name']; ?>					</span> </a>
					<?php
				}
			}
		}

		$wrap_class     = '';
		$wrapperSection = $this->get_bump_section();
		if ( ! empty( $wrapperSection ) ) {
			$wrap_class = ' wfob_wrap_inner_' . $this->get_bump_section();
		}
		?>
    </div>
    <div class="wfob_wrap wfob_box_size">
        <div class="wfob_loader"><span class="spinner"></span></div>
        <div class="wfob_p20 wfob_box_size">
            <div class="wfob_wrap_inner <?php echo $wrap_class; ?>">

				<?php
				$get_keys = wp_list_pluck( $sidebar_menu, 'key' );

				/**
				 * Redirect if any unregistered section found
				 */

				/**
				 * Any registered section should also apply an action in order to show the content inside the tab
				 * like if action is 'stats' then add_action('wfob_dashboard_page_stats', __FUNCTION__);
				 */
				if ( false === has_action( 'wfob_dashboard_page_' . $this->get_bump_section() ) ) {
					include_once( $this->admin_path . '/view/section-' . $this->get_bump_section() . '.php' );
				} else {
					/**
					 * Allow other add-ons to show the content
					 */
					do_action( 'wfob_dashboard_page_' . $this->get_bump_section() );
				}


				do_action( 'wfob_bump_page', $this->get_bump_section(), $bump_id );
				?>
                <div class="wfob_clear"></div>
            </div>
        </div>
    </div>
</div>
<style>
    <?php include_once __DIR__ . '/global/wfob-swal-model.css'; ?>
</style>
