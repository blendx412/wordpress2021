<?php global $wfocu_is_rules_saved; ?>
        <script type="text/template" id="wfocu-rule-template-basic">
			<?php include 'metabox-rules-rule-template-basic.php'; ?>

        </script>
        <script type="text/template" id="wfocu-rule-template-product">

			<?php include 'metabox-rules-rule-template-product.php'; ?>
        </script>
      
        <fieldset>
          
        </fieldset>
    </form>
      <div class="wfocu_form_submit wfocu_btm_grey_area wfocu_clearfix">
	<div class="wfocu_btm_save_wrap wfocu_clearfix">
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
        <div class="wfocu_success_modal" style="display: none" id="modal-rules-settings_success" data-iziModal-icon="icon-home">


        </div>
</div>