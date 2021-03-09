<?php
$funnel_id = WFOCU_Core()->funnels->get_funnel_id();

$groups = WFOCU_Common::get_funnel_rules( $funnel_id );

if ( empty( $groups ) ) {
	$default_rule_id = 'rule' . uniqid();
	$groups          = array(
		'group' . ( time() ) => array(
			$default_rule_id => array(
				'rule_type' => 'general_always',
				'operator'  => '==',
				'condition' => '',
			),
		),

	);
}
?>

<div class="wfocu-rules-builder woocommerce_options_panel" data-category="basic">
	<?php /*?>  <div class="wfocu_fsetting_table_head">
	    <div class="wfocu_fsetting_table_title "><?php _e( 'Conditions', 'woofunnels-upstroke-one-click-upsell' ); ?></div>
	      <p class="description"><?php _e( 'Create a set of rules to determine when this Thank You Page  will be displayed.', 'woofunnels-upstroke-one-click-upsell' ); ?></p>
    </div><?php */ ?>
    <div class="label">
        <h4><?php _e( 'Advanced Rules', 'woofunnels-upstroke-one-click-upsell' ); ?></h4>
    </div>
    <div id="wfocu-rules-groups" class="wfocu_rules_common">
        <div class="wfocu-rule-group-target">
			<?php if ( is_array( $groups ) ) : ?>
			<?php
			$group_counter = 0;
			foreach ( $groups as $group_id => $group ) :
				if ( empty( $group_id ) ) {
					$group_id = 'group' . $group_id;
				}
				?>

                <div class="wfocu-rule-group-container" data-groupid="<?php echo $group_id; ?>">
                    <div class="wfocu-rule-group-header">
						<?php if ( $group_counter == 0 ) : ?>
                            <h4><?php _e( 'Initiate this funnel when these advanced rules are matched', 'woofunnels-upstroke-one-click-upsell' ); ?></h4>
						<?php else : ?>
                            <h4 class="rules_or"><?php _e( 'or', 'woofunnels-upstroke-one-click-upsell' ); ?></h4>
						<?php endif; ?>
                        <a href="#" class="wfocu-remove-rule-group button"></a>
                    </div>
					<?php
					if ( is_array( $group ) ) :


						?>
                        <table class="wfocu-rules" data-groupid="<?php echo $group_id; ?>">
                            <tbody>
							<?php
							foreach ( $group as $rule_id => $rule ) :
								if ( empty( $rule_id ) ) {
									$rule_id = 'rule' . $rule_id;
								}
								?>
                            <tr data-ruleid="<?php echo $rule_id; ?>" class="wfocu-rule">
                                <td class="rule-type">
									<?php
									// allow custom location rules
									$types = apply_filters( 'wfocu_wfocu_rule_get_rule_types', array() );

									// create field
									$args = array(
										'input'   => 'select',
										'name'    => 'wfocu_rule[basic][' . $group_id . '][' . $rule_id . '][rule_type]',
										'class'   => 'rule_type',
										'choices' => $types,
									);
									wfocu_Input_Builder::create_input_field( $args, ( isset( $rule['rule_type'] ) ? $rule['rule_type'] : 'general_always' ) );
									?>
                                </td>

								<?php
								WFOCU_Common::ajax_render_rule_choice( array(
									'group_id'      => $group_id,
									'rule_id'       => $rule_id,
									'rule_type'     => ( isset( $rule['rule_type'] ) ? $rule['rule_type'] : 'general_always' ),
									'condition'     => isset( $rule['condition'] ) ? $rule['condition'] : false,
									'operator'      => ( isset( $rule['operator'] ) ? $rule['operator'] : '==' ),
									'rule_category' => 'basic',
								) );
								?>
                                <td class="loading" colspan="2"
                                    style="display:none;"><?php _e( 'Loading...', 'woofunnels-upstroke-one-click-upsell' ); ?></td>
                                <td class="add">
                                    <a href="#"
                                       class="wfocu-add-rule button"><?php _e( 'AND', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
                                </td>
                                <td class="remove">
                                    <a href="#" class="wfocu-remove-rule wfocu-button-remove"
                                       title="<?php _e( 'Remove condition', 'woofunnels-upstroke-one-click-upsell' ); ?>"></a>
                                </td>
                                </tr><?php endforeach; ?></tbody>
                        </table>
					<?php endif; ?>
                </div>
				<?php $group_counter ++; ?>
			<?php endforeach; ?>
        </div>

        <h4 class="rules_or"
            style="<?php echo( $group_counter > 1 ? 'display:block;' : 'display:none' ); ?>"><?php _e( 'or when these conditions are matched', 'woofunnels-upstroke-one-click-upsell' ); ?></h4>
        <button class="button button-primary wfocu-add-rule-group"
                title="<?php _e( 'Add a set of conditions', 'woofunnels-upstroke-one-click-upsell' ); ?>"><?php _e( 'OR', 'woofunnels-upstroke-one-click-upsell' ); ?></button>
		<?php endif; ?>
    </div>
</div>
