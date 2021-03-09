<?php global $wfob_is_rules_saved; ?>
<script type="text/template" id="wfob-rule-template-basic">
	<?php include 'metabox-rules-rule-template-basic.php'; ?>
</script>
<div class="wfob_btm_grey_area wfob_clearfix">
    <div class="wfob_form_submit wfob_btm_save_wrap wfob_clearfix">
        <button type="submit" class="wfob_save_btn_style wfob_save_bump_rules"><?php ( 'yes' === $wfob_is_rules_saved ) ? _e( 'Save Rules', 'woofunnels-order-bump' ) : _e( 'Save & Proceed', 'woofunnels-order-bump' ); ?></button>
    </div>
</div>


<div class="wfob_success_modal" style="display: none" id="modal-rules-settings_success" data-iziModal-icon="icon-home">


</div>
</form>
</div>
