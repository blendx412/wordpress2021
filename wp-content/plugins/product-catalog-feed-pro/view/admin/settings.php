<?php

$license 	= get_option( 'pcbpys_license_key' );
$status 	= get_option( 'pcbpys_license_status' );

if( $status !== false && $status == 'valid' && !isset($_GET['pcbpys_license_deactivate']) ) { 
    include('admin-settings.php');
} else { ?>
    <div class="wrap">
        <h2><?php _e('Activate Plugin License'); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields('pcbpys_license'); ?>
            <div class="wpwoof-wrap">
                <div class="wpwoof-content wpwoof-box">
                    <div class="wpwoof-container">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row" valign="top">
                                        <?php _e('License Key'); ?>
                                    </th>
                                    <td>
                                        <input id="pcbpys_license_key" name="pcbpys_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                                        <label class="description" for="pcbpys_license_key"><?php _e('Enter your license key'); ?></label>
                                    </td>
                                </tr>
                                <?php if( false !== $license ) { ?>
                                    <?php if( $status !== false && $status == 'valid' ) { ?>
                                        <tr valign="top">
                                            <th scope="row" valign="top"><?php _e('License Status'); ?></th>
                                            <td>
                                                <span style="color:green;"><?php _e('active'); ?></span>
                                                <?php wp_nonce_field( 'pcbpys_nonce', 'pcbpys_nonce' ); ?>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><?php _e('Deactivate License'); ?></th>
                                            <td>
                                                <p>
                                                    <input type="submit" class="wpwoof-button wpwoof-button-red" name="pcbpys_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a class="wpwoof-button" href="<?php echo admin_url() ?>?page=wpwoof-settings"><?php _e('Back to Settings'); ?></a>
                                                </p>
                                            </td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <th valign="top"><?php _e('Activate License'); ?></th>
                                            <td>
                                                <?php wp_nonce_field( 'pcbpys_nonce', 'pcbpys_nonce' ); ?>
                                                <input type="submit" class="wpwoof-button wpwoof-button-blue" name="pcbpys_license_activate" value="<?php _e('Activate License'); ?>"/>
                                            </td>
                                        </tr>		
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php submit_button('Save Changes', 'wpwoof-button wpwoof-button-blue'); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php }