<?php
$custom_page       = array(
	'name'      => __( 'Custom Page', 'woofunnels-upstroke-one-click-upsell' ),
	'thumbnail' => WFOCU_PLUGIN_URL . '/admin/assets/img/thumbnail-link-to-custom.jpg',
	'type'      => 'link',
);
$custom_active_img = WFOCU_PLUGIN_URL . '/admin/assets/img/thumbnail-custom-page.jpg';
?>
<div class="wfocu_template_box" v-if="template_group==`custom_page`" v-bind:class="current_template==`custom-page`?` wfocu_temp_box_custom wfocu_template_box_single  wfocu_selected_template`:`wfocu_template_box wfocu_temp_box_custom wfocu_template_box_single`  " data-slug="custom-page">
    <div class="wfocu_template_box_inner">
        <a href="javascript:void(0)" class="wfocu_template_img_cover" data-izimodal-open="#modal-prev-template_custom-page" data-izimodal-transitionin="fadeInDown">
            <div class="wfocu_overlay">
                <div class="wfocu_overlay_icon"><i class="dashicons dashicons-admin-links"></i></div>
            </div>
            <div class="wfocu_template_thumbnail">
                <div class="wfocu_img_thumbnail ">

                    <div class="wfocu_thumb_def">
                        <img src="<?php echo $custom_page['thumbnail']; ?>" alt="<?php echo __( 'Custom Page', 'woofunnels-upstroke-one-click-upsell' ); ?>"/></div>
                    <div class="wfocu_thumb_active">
                        <img src="<?php echo $custom_active_img; ?>" alt="<?php echo __( 'Custom Page', 'woofunnels-upstroke-one-click-upsell' ); ?>"/></div>

                </div>
            </div>
        </a>
        <div class="wfocu_template_btm_strip wfocu_clearfix">
            <div class="wfocu_template_name" id="custom_template_name">
            {{currentTemplateName}}
            </div>
            <div class="wfocu_template_button">
                <a v-if="currentTemplatePath ==``" href="javascript:void(0)" style="display: none;" ></a>
                <a v-else-if="currentTemplatePath !==`` && current_template==`custom-page`" href="javascript:void(0)" disabled class="button" v-on:click="customize_template(`custom-page`)"><?php echo __( 'Applied', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
                <a v-else href="javascript:void(0)" class="button" v-on:click="set_template(`custom-page`)"><?php echo __( 'Apply', 'woofunnels-upstroke-one-click-upsell' ); ?></a>
            </div>
        </div>
    </div>
</div>

<div style="display: none" id="modal-prev-template_custom-page" class="modal-prev-temp wfocu_izimodal_default" data-iziModal-title="<?php echo $custom_page['name']; ?>" data-iziModal-icon="icon-home">

    <div class="sections wfocu_custom_preview">
        <form class="wfocu_add_funnel" id="modal-page-search-form" data-wfoaction="get_custom_page" v-on:submit.prevent="onSubmit">
            <div class="wfocu_vue_forms">
                <fieldset>
                    <div class="form-group ">
                        <div id="part-custom-template">
                            <label><?php _e( 'Select a Page', 'woofunnels-upstroke-one-click-upsell' ); ?></label>
                            <multiselect v-model="selectedProducts" id="ajax" label="page_name" track-by="page_name" placeholder="Type to search" open-direction="bottom" :options="products" :multiple="false" :searchable="true" :loading="isLoading" :internal-search="true" :clear-on-select="true" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="true" :hide-selected="true" @search-change="asyncFind">
                                <template slot="clear" slot-scope="props">
                                    <div class="multiselect__clear" v-if="selectedProducts.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                                </template>
                                <span slot="noResult"><?php echo __( 'Oops! No elements found. Consider changing the search query.', 'woofunnels-upstroke-one-click-upsell' ); ?></span>
                            </multiselect>
                            <input type="hidden" name="funnel_id" v-bind:value="funnel_id">
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="wfocu_form_submit">
                        <button type="submit" class="wfocu_submit_btn_style" value="save_page"><?php echo __( 'Save Template', 'woofunnels-upstroke-one-click-upsell' ); ?></button>
                    </div>
                    <div class="wfocu_form_response">
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>