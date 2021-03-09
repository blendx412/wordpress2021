(function ($) {
    window = window || {};
    window.wfob_storage = {variation_data: {}};
    var cart_goes_zero = parseFloat(wfob_frontend.cart_total) === 0 ? true : false;
    var block_settings = {
        message: null,
        overlayCSS: {
            background: '#fff',
            opacity: 0.6
        }
    };
    var checkout_form = $('form.checkout');
    window.wfob_send_ajax = function (data, cb) {

        var url = wfob_frontend.admin_ajax;
        if (data.hasOwnProperty('url')) {
            url = data.url;
        }
        data.action = "wfob_" + data.action;
        if (wfob_frontend.hasOwnProperty('wc_endpoints') && wfob_frontend.wc_endpoints.hasOwnProperty(data.action)) {
            url = wfob_frontend.wc_endpoints[data.action];
        }
        data.wfob_nonce = wfob_frontend.wfob_nonce;

        $.ajax({
            'url': url,
            'type': data.type,
            'data': data,
            success: function success(rsp) {
                if (typeof cb === 'function') {
                    cb(rsp);
                }
                if (typeof rsp == "object" && rsp.hasOwnProperty('nonce')) {
                    wfob_frontend.wfob_nonce = rsp.nonce;
                }
            }
        });
    };

    $(document.body).on('update_checkout', function (e) {

        $(document).off('change', '.wfob_bump_product').on("change", '.wfob_bump_product', function () {
            var el = $(this);

            var parent = $(this).parents('.wfob_bump');

            var wfob_id = parent.data('wfob-id');
            wfob_id = parseInt(wfob_id);
            var product_key = parent.data('product-key');
            var cart_key = parent.attr('cart_key');

            var action = 'remove_order_bump';
            if ($(this).is(":checked")) {
                action = 'add_order_bump';
                cart_key = "";
            }
            var wfob_post_id = 0;
            var wfob_el = $('._wfob_post_id');
            if (wfob_el.length > 0) {
                wfob_post_id = wfob_el.val();
            }

            checkout_form.block(block_settings);
            wfob_send_ajax({
                'action': action,
                'type': 'post',
                'product_key': product_key,
                'wfob_id': wfob_id,
                'cart_key': cart_key,
                'wfob_post_id': wfob_post_id,
                'post_data': $('form.checkout').serialize()
            }, function (rsp) {
                checkout_form.unblock();
                if (rsp.cart_is_empty === true) {
                    window.location.reload(0);
                    return;
                }
                if (rsp.status === true) {
                    if (rsp.hasOwnProperty('new_item') && "" !== rsp.new_item) {
                        parent.attr('cart_key', rsp.new_item);
                    }
                } else {
                    if (action === 'add_order_bump') {
                        if (rsp.hasOwnProperty('error') && '' !== rsp.error) {
                            parent.find('.wfob_error_message').html(rsp.error);
                        }
                        el.prop('checked', false);
                    }
                }
                update_fragments(rsp);
                if (true == rsp.gateways_change) {
                    //update order review if gateways is change conditionally bug in 1.6.0
                    update_checkout();
                }
            });
        });
    });

    $(document).ready(function () {
        function shaking_variation_div() {
            $('#wfob_qr_model_wrap .wfob_option_btn').click(function () {

                let scrollElementVarOB = $(".wfob_qv-main table.variations");
                if (scrollElementVarOB.length > 0) {
                    $('.wfob_qv-main').animate({
                        scrollTop: ($("table.variations").offset().top - 100)
                    }, 2000);

                }

                $("form.variations_form.cart .variations").removeClass('wfob_animate_shake');
                setTimeout(function () {
                    $("form.variations_form.cart .variations").addClass('wfob_animate_shake');
                }, 500);
            });
        }

        function variation_form_init() {
            var variationForm = $(".variations_form");
            variationForm.on("hide_variation", function (event) {
                $('.wfob_single_add_to_cart_button').hide();
                $('.wfob_qv-summary .quantity').hide();
                $('.wfob_option_btn').show();
                shaking_variation_div();
            });
            variationForm.on("show_variation", function (event, variation) {
                var variation_id = variation.variation_id;
                wfob_storage.variation_data[variation_id] = variation;
                $('.wfob_single_add_to_cart_button').show();
                $('.wfob_qv-summary .quantity').show();
                $('.wfob_option_btn').hide();
            });
        }

        function validation_incomplete_variation() {

            var variation_error = $('.wfob_incomplete_variation');
            if (variation_error.length > 0) {
                var msg_container = $('.wfob_invalid_variarion');
                msg_container.show();
                $.scroll_to_notices(variation_error.eq(0));
                setTimeout(function () {
                    variation_error.addClass('wfob_attributes_error_shake');
                    setTimeout(function () {
                        variation_error.removeClass('wfob_attributes_error_shake');
                    }, 500);
                }, 1000);
                setTimeout(function () {
                    msg_container.hide();
                }, 4000);
                return false;
            }
            return true;
        }

        function prettyPhotoLoad() {
            wc_single_product_params.flexslider_enabled = true;
            wc_single_product_params.photoswipe_enabled = true;
            wc_single_product_params.zoom_enabled = true;
            $('.woocommerce-product-gallery').each(function () {
                $(this).wc_product_gallery();
            });
        }

        //Animate Type (anim_class = Fade-In)
        function wf_quick_view_animate_2(direction, anim_class) {
            $(".wfob_qv-inner-modal").css('opacity', '0').addClass(anim_class);
        }

        //Check User settings
        function wf_quick_view_animation_func(ajax_data, direction) {
            wf_quick_view_ajax(ajax_data, wf_quick_view_animate_2, null, 'wfob_qv-animation-fadein');
        }

        function wf_quick_view_close_popup(e) {
            e.preventDefault();
            $.each(e.target.classList, function (key, value) {
                if (value === 'wfob_qv-close' || value === 'wfob_qv-inner-modal') {
                    $('.wfob_qv-opac').hide();
                    $('.wfob_qv-panel').removeClass('wfob_qv-panel-active');
                    $('.wfob_qv-modal').html('');
                }
            });
        }

        function wf_quick_view_ajax(ajax_data, anim_type, direction, anim_class) {
            ajax_data.action = 'quick_view_ajax';
            ajax_data.type = 'post';


            wfob_send_ajax(ajax_data, function (rsp) {
                if (true == rsp.status) {
                    $('.wfob_qv-modal').html(rsp.html);
                    anim_type(direction, anim_class);
                    $('.wfob_qv-pl-active').removeClass('wfob_qv-pl-active');
                    prettyPhotoLoad();
                    $('.wfob_qv-panel').find('.variations_form').wc_variation_form();
                    $('.wfob_qv-panel .variations_form select').change();
                    if (ajax_data.hasOwnProperty('variation_id')) {
                        set_attributes_in_dropdown(ajax_data.variation_id, ajax_data.item_key);
                    }
                    variation_form_init();
                    var item_row = $('.wfob_bump[data-product-key="' + ajax_data.item_key + '"]');
                    $(document.body).trigger('wfob_quick_view_open', {'data': rsp});
                    if (item_row.length === 0) {
                        return;
                    }

                    var product_price = $('.wfob_qv-summary .price');
                    if (product_price.length > 0) {
                        var row_product_price = item_row.find('.wfob_price').html();
                        product_price.html(row_product_price);
                    }
                } else {
                    $('.wfob_qv-close').trigger('click');
                }
            });
        }

        function set_attributes_in_dropdown(variation_id, item_key) {
            if (!window.wfob_storage.variation_data.hasOwnProperty(variation_id)) {
                return false;
            }
            var var_data = window.wfob_storage.variation_data[variation_id];
            if (Object.keys(var_data).length === 0) {
                return false;
            }
            var attributes = var_data.attributes;
            if (Object.keys(attributes).length === 0) {
                return false;
            }
            var output = {};
            for (var i in attributes) {
                var attr = $('[data-attribute_name="' + i + '"]');
                if (attr.length > 0) {
                    var v = attributes[i];
                    attr.val(v);
                }
            }
            return output;
        }

        function get_variation_attributes(variation_id) {

            if (!window.wfob_storage.variation_data.hasOwnProperty(variation_id)) {
                return {};
            }
            var var_data = window.wfob_storage.variation_data[variation_id];
            if (Object.keys(var_data).length === 0) {
                return {};
            }
            var attributes = var_data.attributes;
            if (Object.keys(attributes).length === 0) {
                return {};
            }
            var output = {};
            for (var i in attributes) {
                var attr = $('[data-attribute_name="' + i + '"]');
                if (attr.length > 0) {
                    output[i] = attr.val();
                    window.wfob_storage.variation_data[variation_id].attributes[i] = attr.val();
                }
            }
            return output;
        }

        function update_variation_in_cart(selected_product_row) {
            var variation_id = $('.wfob_qv-summary .variation_id').val();
            if (variation_id > 0) {
                selected_product_row.attr('variation_id', variation_id);
            }
            var attributes = get_variation_attributes(variation_id);

            var cart_key = selected_product_row.attr('cart_key');

            var wfob_id = selected_product_row.data('wfob-id');
            var product_key = selected_product_row.data('product-key');

            checkout_form.block(block_settings);

            var wfob_post_id = 0;
            var wfob_el = $('._wfob_post_id');
            if (wfob_el.length > 0) {
                wfob_post_id = wfob_el.val();
            }
            wfob_send_ajax({
                'action': 'add_order_bump',
                'type': 'post',
                'remove_item_key': cart_key,
                'product_key': product_key,
                'variation_id': variation_id,
                'attributes': attributes,
                'wfob_id': wfob_id,
                'wfob_post_id': wfob_post_id,
                'post_data': $('form.checkout').serialize()
            }, function (rsp) {
                checkout_form.unblock();
                if (rsp.cart_is_empty === true) {
                    window.location.reload(0);
                    return;
                }
                if (rsp.hasOwnProperty('error') && '' !== rsp.error) {
                    selected_product_row.find('.wfob_error_message').html(rsp.error);
                    return;
                }

                if (rsp.hasOwnProperty('new_item') && "" !== rsp.new_item) {
                    selected_product_row.attr('cart_key', rsp.new_item);
                }
                let product_switcher = $('#product_switcher_need_refresh');
                if (product_switcher.length > 0) {
                    product_switcher.val(0);
                }
                update_fragments(rsp);
                if (true == rsp.gateways_change) {
                    //update order review if gateways is change conditionally bug in 1.6.0
                    update_checkout();
                }

            });
        }

        $('.wfob_qv-panel').on('click', '.wfob_qv-close', wf_quick_view_close_popup);
        $(document.body).on('click', '.wfob_qv-inner-modal', wf_quick_view_close_popup);

        $(document).keyup(function (e) {
            if (e.keyCode === 27) {
                $('.wfob_qv-close').trigger('click');
            }
        });

        // Main Quickview Button
        $(document.body).on('click', '.wfob_qv-button', function (e) {
            e.preventDefault();

            var parent = $(this).parents('.wfob_bump');
            $('.wfob_qv-opac').show();
            var wf_quick_view_panel = $('.wfob_qv-panel');
            wf_quick_view_panel.addClass('wfob_qv-panel-active');
            wf_quick_view_panel.find('.wfob_qv-opl').addClass('wfob_qv-pl-active');
            var p_id = $(this).attr('qv-id');
            var ajax_data = {};

            if (parent.length > 0) {
                ajax_data.item_key = parent.data('product-key');
                ajax_data.cart_key = parent.attr('cart_key');
            }
            ajax_data.wfob_id = parent.data('wfob-id');
            ajax_data.wfob_nonce = wfob_frontend.wfob_nonce;
            ajax_data.product_id = p_id;
            var v_id = $(this).attr('qv-var-id');
            if (v_id != undefined) {
                ajax_data.variation_id = v_id;
            }
            wf_quick_view_animation_func(ajax_data, 'top');
        });

        $(document).on('click', '.wfob_single_add_to_cart_button', function () {

            var modal = $(".wfob_qv-inner-modal");
            var item_key = modal.data('item-key');
            var cart_key = modal.data('cart-key');

            var is_variation = false;
            if ($('.wfob_qv-summary .variations_form').length > 0) {
                is_variation = true;
            }

            if (cart_key !== undefined && cart_key !== "") {
                //product already in cart
                var selected_product_row = $('.wfob_bump[cart_key="' + cart_key + '"]');
                if (selected_product_row.length > 0) {
                    if (true === is_variation) {
                        update_variation_in_cart(selected_product_row);
                    }
                    $('.wfob_qv-close').trigger('click');
                }
            } else if (item_key !== undefined && item_key !== '') {
                var _selected_product_row = $('.wfob_bump[data-product-key="' + item_key + '"]');
                if (_selected_product_row.length > 0) {
                    if (true === is_variation) {
                        update_variation_in_cart(_selected_product_row);
                    }
                    $('.wfob_qv-close').trigger('click');
                }
            }
        });

        $(document).on('change', '.wfob_choose_variation', function () {
            var parent = $(this).parents('.wfob_bump');
            var qv = parent.find('.wfob_qv-button');
            if (qv.length > 0) {
                qv.trigger('click');
            }
            $(this).prop('checked', false);
        });
    });

    function plugins_compatibility() {

        // WooCommerce Marcado Emi Gateway

        var MPv1_running = false;
        $(document.body).on('wfob_bump_trigger', function () {
            var paymentGateway = $('#payment_method_woo-mercado-pago-custom');
            if (paymentGateway.length > 0 && "woo-mercado-pago-custom" === paymentGateway.val()) {
                MPv1_running = true;
                $(document.body).trigger('update_checkout');
            }
        });
        checkout_form.on('click', 'input[name="payment_method"]', function () {
            if ("woo-mercado-pago-custom" === $(this).val() && typeof MPv1 == "object" && MPv1_running == true) {
                MPv1.guessingPaymentMethod({
                    'event': 'keyup'
                });
                MPv1_running = false;
            }
        });

        $(document.body).on('wfob_cart_goes_empty', function () {
            cart_goes_zero = true;
        });
    }


    function update_fragments(rsp) {

        if (rsp.hasOwnProperty('cart_total') && parseFloat(rsp.cart_total) == 0) {
            cart_goes_zero = true;
            update_checkout();
        } else {
            if (cart_goes_zero) {
                cart_goes_zero = false;
                update_checkout();
            } else {
                if (rsp.hasOwnProperty('fragments') && rsp.fragments.hasOwnProperty('.cart_total') && parseFloat(rsp.fragments['.cart_total']) === 0) {
                    $(document.body).trigger('wfob_cart_goes_empty', {'cart_total': parseFloat(rsp.cart_total)});
                    update_checkout();
                }
                if (rsp.hasOwnProperty('fragments')) {
                    $.each(rsp.fragments, function (key, value) {
                        $(key).replaceWith(value);
                    });
                }
            }
        }
        $(document.body).trigger('wfob_bump_trigger', rsp);


    }

    var update_checkout_running = null;

    function update_checkout() {
        clearTimeout(update_checkout_running);
        update_checkout_running = setTimeout(function () {
            $(document.body).trigger('update_checkout');
        }, 1000);
    }

    $(document.body).on('updated_checkout', function (e, d) {
        let product_switcher = $('#product_switcher_need_refresh');
        if (product_switcher.length > 0) {
            product_switcher.val(1);
        }
        if (typeof d == "object") {
            if (d.hasOwnProperty('fragments') && d.fragments.hasOwnProperty('cart_total') && parseFloat(d.fragments.cart_total) === 0) {
                cart_goes_zero = true;
                window.location.reload();
            }
        }
    });
    plugins_compatibility();
})(jQuery);