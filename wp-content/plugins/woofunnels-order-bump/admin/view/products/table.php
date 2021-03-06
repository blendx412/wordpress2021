<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wfob_product_list_wrap ">
    <div class="product_list">
        <table width="100%" class="product_section_table">
            <thead class="listing_table_head">
            <tr>
                <th class="product_th"><?php _e( 'Products', 'woofunnels-order-bump' ); ?></th>
                <th><?php _e( 'Type', 'woofunnels-order-bump' ); ?></th>
                <th><?php _e( 'Discount', 'woofunnels-order-bump' ); ?></th>
                <th><?php _e( 'Quantity', 'woofunnels-order-bump' ); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="tb_body">
            <tr v-for="(product, index) in products" v-bind:id="index" v-bind:data-proid="index" class="wfob-product-row">
                <td>
                    <input type="hidden" v-bind:name="'wfob_product['+index+'][id]'" v-bind:value="product.id" v-model='product.id' style="width: 51px;">
                    <div class="product_image">
                        <img v-bind:src="product.image" style="max-width: 100%">
                    </div>
                    <div class="product_details">
                        <div class="product_name">
                            <span class="wfob_product_title">{{product.title}}</span>
                        </div>
                        <div class="product_options">
                            <div v-if="product.type=='variable'">
								<?php _e( 'Price', 'woofunnels-order-bump' ); ?>:
                                <p v-html="product.price"></p>
                            </div>
                            <div v-if="product.type!=='variable'">
                                <div v-if="typeof product.regular_price!='undefined' && product.regular_price!=''">
									<?php _e( ' Regular Price', 'woofunnels-order-bump' ); ?>:
                                    <p v-html=" product.regular_price"></p>
                                </div>
                                <div v-if="typeof product.sale_price!='undefined' && product.sale_price!=''">
									<?php _e( 'Sale Price', 'woofunnels-order-bump' ); ?>:
                                    <p v-html=" product.sale_price"></p>
                                </div>
                            </div>
                            <div v-if="typeof product.stock!=='undefined' && product.stock==false" style="color:red">
								<?php _e( 'Status', 'woofunnels-order-bump' ); ?>:
                                <!--                                <p v-if="product.stock==true">--><?php //_e( 'In Stock', 'woofunnels-order-bump' ) ?><!--</p>-->
                                <p v-if="product.stock==false"><?php _e( 'Out of Stock', 'woofunnels-order-bump' ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </td>
                <td>
                    <div class="product_type">
                        <p>{{product.type}}</p>
                    </div>
                </td>
                <td>
                    <input type="number" step="0.01" min="0" v-bind:name="'wfob_product['+index+'][discount_amount]'" v-bind:value="!product.discount_amount?0:product.discount_amount" v-model='product.discount_amount' class="discount_number" @input="set_product_data(index,'discount_amount',$event)" oninput="this.value = Math.abs(this.value)" style="width: 60px;">
                    <select v-model="product.discount_type" v-bind:name="'wfob_product['+index+'][discount_type]'" v-on:change="set_product_data(index,'discount_type',$event)">
                        <option value="percent_discount_sale"><?php _e( 'Percentage % on Sale Price', 'woofunnels-aero-checkout' ); ?></option>
                        <option value="fixed_discount_sale"><?php _e( 'Fixed Amount $ on Sale Price', 'woofunnels-order-bump' ); ?></php></option>
                        <option value="percent_discount_reg"><?php _e( 'Percentage % on Regular Price', 'woofunnels-aero-checkout' ); ?></option>
                        <option value="fixed_discount_reg"><?php _e( 'Fixed Amount $ on Regular Price', 'woofunnels-order-bump' ); ?></option>
                    </select>
                </td>
                <td>
                    <input type="number" v-model="product.quantity" min="1" v-bind:value="!product.quantity?1:product.quantity" v-bind:name="'wfob_product['+index+'][discount_quantity]'" v-model='product.discount_quantity' @input="set_product_data(index,'discount_quantity',$event)" oninput="this.value = Math.abs(this.value)" style="width: 60px;">
                </td>
                <td>
                    <button type="button" class="wfob_form_remove_product" v-on:click="removeProduct(index)"><i class="dashicons dashicons-trash"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>
