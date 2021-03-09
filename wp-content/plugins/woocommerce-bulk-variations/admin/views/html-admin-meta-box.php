<div id="wcbv_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
	<div class="options_group">

		<p class="form-field">
			<label for="wcbv-position"><?php _e( 'Position', 'radykal' ); ?></label>
			<select name="wcbv_position" id="wcbv-position" style="width: 400px;">
				<?php
					foreach($options['position'] as $key  => $value) {
						echo '<option value="'.$key.'" '.selected($stored_options['position'], $key, false).'>'.$value.'</option>';
					}
				?>
			</select>
		</p>

		<p class="form-field">
			<label for="wcbv-layout"><?php _e( 'Layout', 'radykal' ); ?></label>
			<select name="wcbv_layout" id="wcbv-layout" style="width: 400px;">
				<?php
					foreach($options['layout'] as $key  => $value) {
						echo '<option value="'.$key.'" '.selected($stored_options['layout'], $key, false).'>'.$value.'</option>';
					}
				?>
			</select>
		</p>

		<p class="form-field">
			<label for="wcbv-enable-select2"><?php _e( 'Enable Select2', 'radykal' ); ?></label>
			<input type="checkbox" class="checkbox" id="wcbv-enable-select2" name="wcbv_enable_select2" value="yes" <?php checked($stored_options['enable_select2'], 'yes'); ?> />
			<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Enable a searchable dropdown.', 'radykal' ); ?>"></span>
		</p>

		<p class="form-field">
			<label for="wcbv-replace-choose" style="line-height: 14px;"><?php _e( 'Select Placeholder = Attribute Name', 'radykal' ); ?></label>
			<input type="checkbox" class="checkbox" id="wcbv-replace-choose" name="wcbv_replace_choose_option" value="yes" <?php checked($stored_options['replace_choose_option'], 'yes'); ?> />
			<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Will replace the default placeholder with the attribute name.', 'radykal' ); ?>"></span>
		</p>

		<p class="form-field">
			<label for="wcbv-fixed-amount" style="line-height: 14px;"><?php _e( 'Fixed Amount Of Variations', 'radykal' ); ?></label>
			<input type="number" min="0" step="1" class="short" id="wcbv-fixed-amount" name="wcbv_fixed_amount" placeholder="0" value="<?php echo $stored_options['fixed_amount']; ?>" />
			<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Set a fixed amount of variations. Any value greater than 0 will disable the "Add Varations" button.', 'radykal' ); ?>"></span>
		</p>

	</div>
</div>