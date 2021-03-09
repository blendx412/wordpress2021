<?php $funnel_id = WFOCU_Core()->funnels->get_funnel_id();
global $wfocu_is_rules_saved;
?>
<div class="wfocu_wrap_r wfocu_table wfocu_rules_container <?php echo ( "yes" == $wfocu_is_rules_saved ) ? '': 'wfocu-tgl'; ?>" id="wfocu_funnel_rule_settings" data-is_rules_saved="<?php echo ( "yes" == $wfocu_is_rules_saved ) ? "yes" : "no"; ?>">
    <div class="wfocu_fsetting_table_head">
        <div class="wfocu_fsetting_table_head_in wfocu_clearfix">
            <div class="wfocu_fsetting_table_title "><?php echo __( 'Rules to Trigger the Funnel', 'woofunnels-upstroke-one-click-upsell' ); ?></div>
            <div class="wfocu_form_submit ">
		 <span class="wfocu_save_funnel_rules_ajax_loader spinner"></span>
                <button v-on:click.self="onSubmit" class="wfocu_save_btn_style wfocu_save_funnel_rules">
                    <?php if('yes' === $wfocu_is_rules_saved)  {
	                    _e( 'Save Rules', 'woofunnels-upstroke-one-click-upsell' );
                    }else{
	                    _e( 'Save & Proceed', 'woofunnels-upstroke-one-click-upsell' );
                    }?>


                </button>
            </div>
        </div>
    </div>
    <form class="wfocu_rules_form" data-wfoaction="update_rules" method="POST">


        <input type="hidden" name="funnel_id" value="<?php echo $funnel_id; ?>">

