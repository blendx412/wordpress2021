<?php

wp_enqueue_script( 'wfocu-modal-script', WFOCU_PLUGIN_URL . '/admin/assets/js/wfocu-modal.js', array( 'jquery' ), WFOCU_VERSION );
wp_enqueue_style( 'wfocu-modal-style', WFOCU_PLUGIN_URL . '/admin/assets/css/wfocu-modal.css', null, WFOCU_VERSION );
wp_enqueue_style( 'wfocu-modal-common-style', WFOCU_PLUGIN_URL . '/admin/assets/css/wfocu-elementor-common.css', null, WFOCU_VERSION );

$maybe_offer_id = WFOCU_Core()->template_loader->get_offer_id();

$offer_data = WFOCU_Core()->offers->get_offer_meta( $maybe_offer_id ); ?>

    <div class='' id="wfocu_shortcode_help_box" style="display: none;">

        <h3><?php _e( 'Shortcodes', 'woofunnels-upstroke-one-click-upsell' ); ?></h3>
        <div style="font-size: 1.1em; margin: 5px;"><?php _e( 'Here are set of Shortcodes that can be used on this page.', 'woofunnels-upstroke-one-click-upsell' ); ?> </i> </div>
		<?php foreach ( ( ! empty( $offer_data ) && isset( $offer_data->products ) ) ? $offer_data->products : array() as $hash => $product_id ) { ?>
            <h4><?php _e( sprintf( 'Product: %1$s', wc_get_product( $product_id )->get_title() ), 'woofunnels-upstroke-one-click-upsell' ); ?></h4>

            <table class="table widefat">
                <thead>
                <tr>
                    <td><?php _e( 'Title', 'woofunnels-upstroke-one-click-upsell' ); ?></td>
                    <td style="width: 70%;"><?php _e( 'Shortcodes', 'woofunnels-upstroke-one-click-upsell' ); ?></td>

                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>
						<?php _e( 'Product Offer Price', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_offer_price key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>
                <tr>
                    <td>
						<?php _e( 'Product Regular Price', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_regular_price key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>
                <tr>
                    <td>

						<?php _e( ' Product Price HTML', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_price_full key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>

                <tr>
                    <td>
						<?php _e( 'Product Offer Save Value', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>
                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_save_value key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>
                <tr>
                    <td>
						<?php _e( ' Product Offer Save Percentage', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>

                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_save_percentage key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>

                <tr>
                    <td>
						<?php _e( ' Product Single Unit Price', 'woofunnels-upstroke-one-click-upsell' ); ?>
                    </td>

                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_single_unit_price key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>
                <tr>
                    <td>
						<?php _e( 'Product Offer Save Value & Percentage', 'woofunnels-upstroke-one-click-upsell' ); ?>

                    </td>
                    <td>
                        <input type="text" style="width: 75%;" readonly onClick="this.select()"
                               value='<?php printf( '[wfocu_product_savings key="%s"]', $hash ); ?>'/>
                    </td>

                </tr>

                </tbody>
            </table>
		<?php } ?>
        <br/>

        <h3><strong>Order Personalization Shortcodes</strong></h3>

        <table class="table widefat">
            <caption><p style="float: left;">To personalize upsell pages with different order attributes, use these merge tags-</p></caption>
            <thead>
            <tr>
                <td width="300">Name</td>
                <td>Shortcodes</td>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td>Customer First Name</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="customer_first_name"]'/>
                </td>
            </tr>

            <tr>
                <td>Customer Last Name</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="customer_last_name"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Number</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_no"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Date</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_date"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Total</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_total"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Item Count</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_itemscount"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Shipping Method</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_shipping_method"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Billing Country</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_billing_country"]'/>
                </td>
            </tr>

            <tr>
                <td>Order Shipping Country</td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfocu_order_data key="order_shipping_country"]'/>
                </td>
            </tr>

            </tbody>
        </table>

    </div>
<?php
