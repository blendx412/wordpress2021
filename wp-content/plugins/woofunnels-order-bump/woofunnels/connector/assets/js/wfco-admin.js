(function ($) {
    'use strict';

    $(document).ready(function () {
        wfco_modal_add_integration();
        wfco_modal_edit_integration();
        wfco_update_integration();
        wfco_add_integration();
        wfco_sync_integration();

        $(window).bind('load', function () {
            if ($('.wfco-loader').length > 0) {
                $('.wfco-loader').each(function () {
                    let $this = $(this);
                    if ($this.is(":visible")) {
                        setTimeout(function () {
                            $this.remove();
                        }, 400);
                    }
                });
            }
        });

        /** Metabox panel close */
        $(".wfco_allow_panel_close .hndle").on("click", function () {
            var $this = $(this);
            var parentPanel = $(this).parents(".wfco_allow_panel_close");
            parentPanel.toggleClass("closed");
        });

        wfco_integration_settings_html();

    });

    function wfco_modal_add_integration() {
        if ($("#modal-add-integration").length > 0) {
            $("#modal-add-integration").iziModal({
                // title: 'Connect Integration',
                headerColor: '#6dbe45',
                background: '#efefef',
                borderBottom: false,
                history: false,
                width: 600,
                overlayColor: 'rgba(0, 0, 0, 0.6)',
                transitionIn: 'bounceInDown',
                transitionOut: 'bounceOutDown',
                navigateCaption: true,
                navigateArrows: "false",
                onOpening: function (modal) {
                    modal.startLoading();
                },
                onOpened: function (modal) {
                    modal.stopLoading();
                    // vue_add_coupon(modal);
                    $('form.wfco_update_coupon').show();
                    $('.wfco-coupon-create-success-wrap').hide();
                    $('.wfco_submit_btn_style').text(wfcoParams.texts.update_btn);
                },
                onClosed: function (modal) {
                    //console.log('onClosed');
                }
            });
        }
    }

    function wfco_modal_edit_integration() {
        if ($("#modal-edit-integration").length > 0) {
            $("#modal-edit-integration").iziModal({
                // title: 'Update Integration',
                headerColor: '#6dbe45',
                background: '#efefef',
                borderBottom: false,
                history: false,
                width: 600,
                overlayColor: 'rgba(0, 0, 0, 0.6)',
                transitionIn: 'bounceInDown',
                transitionOut: 'bounceOutDown',
                navigateCaption: true,
                navigateArrows: "false",
                onOpening: function (modal) {
                    modal.startLoading();
                },
                onOpened: function (modal) {
                    modal.stopLoading();
                    // vue_add_automation(modal);
                },
                onClosed: function (modal) {
                    //console.log('onClosed');
                }
            });
        }
    }

    function wfco_update_integration() {
        if ($('.wfco_update_integration').length > 0) {
            let wp_form_ajax = new wp_admin_ajax('.wfco_update_integration', true, function (ajax) {
                    ajax.before_send = function (element, action) {
                        if (ajax.action === 'wfco_update_integration') {
                            $('.wfco_update_btn_style').val(wfcoParams.texts.update_btn_process);
                        }
                    };
                    ajax.success = function (rsp) {
                        if (ajax.action === 'wfco_update_integration') {

                            if (rsp.status === true) {
                                if (rsp.data_changed == 1) {
                                    $('form.wfco_update_integration').hide();
                                    $('.wfco-automation-update-success-wrap').show();
                                    // $('.wfco_form_response').html(rsp.msg);
                                    setTimeout(function () {
                                        window.location.href = rsp.redirect_url;
                                    }, 3000);
                                }
                                else {
                                    $("#modal-edit-integration").iziModal('close');
                                    setTimeout(function () {
                                        swal({
                                            title: wfcoParams.texts.update_int_prompt_title,
                                            type: "success",
                                            showConfirmButton: false,
                                        });
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                    }, 1000);
                                }

                            }
                            else {
                                $('.wfco_form_response').html(rsp.msg);
                                $('.wfco_update_integration').find("input[type=submit]").prop('disabled', false);
                                $('.wfco_save_btn_style').val('update');
                            }
                        }
                    }
                    ;
                }
            );
        }
    }

    function wfco_add_integration() {
        if ($('.wfco_add_integration').length > 0) {
            let wp_form_ajax = new wp_admin_ajax('.wfco_add_integration', true, function (ajax) {
                ajax.before_send = function (element, action) {
                    if (ajax.action === 'wfco_save_integration') {
                        $('.wfco_save_btn_style').val(wfcoParams.texts.connect_btn_process);
                    }
                };
                ajax.success = function (rsp) {
                    if (ajax.action === 'wfco_save_integration') {

                        if (rsp.status === true) {
                            if(rsp.is_direct_integration === true){
                                swal({
                                    title: wfcoParams.texts.connect_success_title,
                                    type: "success",
                                    showConfirmButton: false,
                                });
                                jQuery('.wfco-integration-add').removeClass('wfco_btn_spin');
                            }
                            else{
                                $('form.wfco_add_integration').hide();
                                $('.wfco-integration-create-success-wrap').show();
                                $('.wfco_form_response').html(rsp.msg);
                            }

                            setTimeout(function () {
                                window.location.href = rsp.redirect_url;
                            }, 3000);

                        } else {
                            $('.wfco_form_response').html(rsp.msg);
                            $('.wfco_add_integration').find("input[type=submit]").prop('disabled', false);
                            $('.wfco_save_btn_style').val('save');
                        }
                    }
                };
            });
        }
    }

    function wfco_integration_settings_html() {
        jQuery(document).on(
            'click', '.wfco-integration-add', function () {
                var $this = jQuery(this);
                // var selected_value = $this.val();
                var selected_value = $this.data('slug');
                var type = $this.attr('data-type');
                var Title = $this.attr('data-iziModal-title');
                if (selected_value != '') {
                    var selected_integration = wp.template('int-' + selected_value);
                    jQuery('#wfco_integrations_fields').html('');
                    wfco_make_html(1, '#wfco_integrations_fields', selected_integration());
                    if(type == 'direct'){
                        console.log('direct');
                        jQuery('.wfco_add_integration').trigger('submit');
                        $this.addClass('wfco_btn_spin');
                    }
                } else {
                    jQuery('#wfco_integrations_fields').html('');
                }
                $("#modal-add-integration").iziModal('setTitle', Title);
            });
        jQuery(document).on(
            'click', '.wfco-integration-edit', function () {
                var $this = jQuery(this);
                var selected_value = $this.data('slug');
                var Title = $this.attr('data-iziModal-title');
                // console.log(selected_value);
                if (selected_value != '') {
                    var selected_integration = wp.template('int-' + selected_value);
                    jQuery('#wfco_integrations_edit_fields').html('');
                    wfco_make_html(1, '#wfco_integrations_edit_fields', selected_integration());
                } else {
                    jQuery('#wfco_integrations_edit_fields').html('');
                }
                $("#modal-edit-integration").iziModal('setTitle', Title);
            });
    }

    function wfco_sync_integration() {
        if ($('.wfco_sync_integration').length > 0) {
            let wp_form_ajax = new wp_admin_ajax('.wfco_sync_integration', true, function (ajax) {
                ajax.before_send = function (element, action) {
                    if (ajax.action === 'wfco_save_integration') {
                        $('.wfco_save_btn_style').text(wfcoParams.texts.connect_btn_process);
                    }
                };
                ajax.success = function (rsp) {
                    if (ajax.action === 'wfco_save_integration') {

                        if (rsp.status === true) {
                            // $('form.wfco_sync_integration').hide();
                            $('.wfco-autoresponder-sync-success-wrap').show();
                            // $('.wfco_form_response').html(rsp.msg);
                            setTimeout(function () {
                                window.location.href = rsp.redirect_url;
                            }, 3000);

                        } else {
                            $('.wfco_form_response').html(rsp.msg);
                        }
                    }
                };
            });
        }
    }

    function wfco_make_html(empty_old_html, container_element, new_html) {
        var output_container = jQuery(container_element);
        if (empty_old_html == 1) {
            jQuery(container_element).html('');
            var output_container_html = jQuery(container_element).html();
            output_container.html(output_container_html + new_html);
        } else if (empty_old_html == 2) {
            output_container.append(new_html);
        }
    }

})(jQuery);
