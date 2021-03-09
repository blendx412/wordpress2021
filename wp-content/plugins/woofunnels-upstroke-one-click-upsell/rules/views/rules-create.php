<?php
global $wfocu_is_rules_saved;
?>
<div id="wfocu_funnel_rule_add_settings" data-is_rules_saved="<?php echo ( "yes" == $wfocu_is_rules_saved ) ? "yes" : "no"; ?>">
    <div class="wfocu_welcome_wrap">
	<div class="wfocu_welcome_wrap_in">
	    <div class="wfocu_welc_head">
		<div class="wfocu_welc_icon">  <img src="<?php echo WFOCU_PLUGIN_URL ?>/admin/assets/img/clap.png" alt="" title=""/></div>
		<div class="wfocu_welc_title"> <?php _e( 'You’re Ready To Go', 'woofunnels-upstroke-one-click-upsell' ); ?>
		</div>
	    </div>
	    <div class="wfocu_welc_text"><p><?php _e( 'As a first step you need to set up Rules (set of conditions) to trigger this funnel.', 'woofunnels-upstroke-one-click-upsell' ); ?></p></div>

	    <button class="button-primary wfocu_funnel_rule_add_settings wfocu_welc_btn"><?php echo __( "+ Add Rules", 'woofunnels-upstroke-one-click-upsell' ); ?></button>
	</div>
    </div>
</div>