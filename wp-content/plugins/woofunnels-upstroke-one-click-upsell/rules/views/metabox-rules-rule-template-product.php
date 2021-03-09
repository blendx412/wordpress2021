<td class="rule-type">
	<?php
	$types = apply_filters( 'wfocu_wfocu_rule_get_rule_types_product', array() );
	// create field
	$args = array(
		'input'   => 'select',
		'name'    => 'wfocu_rule[product][<%= groupId %>][<%= ruleId %>][rule_type]',
		'class'   => 'rule_type',
		'choices' => $types,
	);

	wfocu_Input_Builder::create_input_field( $args, 'html_always' );
	?>
</td>


<?php
WFOCU_Common::render_rule_choice_template( array(
	'group_id'  => 0,
	'rule_id'   => 0,
	'rule_type' => 'general_always_2',
	'condition' => false,
	'operator'  => false,
	'category'  => 'product',
) );
?>
<td class="loading" colspan="2" style="display:none;"><?php _e( 'Loading...', 'woofunnels-upstroke-one-click-upsell' ); ?></td>
<td class="add"><a href="#" class="wfocu-add-rule button"><?php _e( "AND", 'woofunnels-upstroke-one-click-upsell' ); ?></a></td>
<td class="remove"><a href="#" class="wfocu-remove-rule wfocu-button-remove"></a></td>