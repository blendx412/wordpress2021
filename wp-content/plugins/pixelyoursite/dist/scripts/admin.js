
jQuery(document).ready(function(c){function n(e){var t=c("#"+e.data("target"));e.val()===e.data("value")?t.removeClass("form-control-hidden"):t.addClass("form-control-hidden")}function e(){"price"===c('input[name="pys[core][woo_event_value]"]:checked').val()?c(".woo-event-value-option").hide():c(".woo-event-value-option").show()}function t(){"price"===c('input[name="pys[core][edd_event_value]"]:checked').val()?c(".edd-event-value-option").hide():c(".edd-event-value-option").show()}function a(){var e=c("#pys_event_trigger_type").val(),t="#"+e+"_panel";c(".event_triggers_panel").hide(),c(t).show(),"page_visit"===e?c("#url_filter_panel").hide():c("#url_filter_panel").show();var n=c(t),a=n.data("trigger_type");0===c(".event_trigger",n).length-1&&s(n,a)}function s(e,t){var n=c(".event_trigger",e),a=c(n[0]).clone(!0),s=c(n[n.length-1]).data("trigger_id")+1,i="pys[event]["+t+"_triggers]["+s+"]";a.data("trigger_id",s),c("select",a).attr("name",i+"[rule]"),c("input",a).attr("name",i+"[value]"),a.css("display","block"),a.insertBefore(c(".insert-marker",e))}function i(){"page_visit"===c("#pys_event_trigger_type").val()?c(".event-delay").css("visibility","visible"):c(".event-delay").css("visibility","hidden")}function o(){c("#pys_event_facebook_enabled").is(":checked")?c("#facebook_panel").show():c("#facebook_panel").hide()}function r(){"CustomEvent"===c("#pys_event_facebook_event_type").val()?c(".facebook-custom-event-type").css("visibility","visible"):c(".facebook-custom-event-type").css("visibility","hidden")}function p(){c("#pys_event_facebook_params_enabled").is(":checked")?c("#facebook_params_panel").show():c("#facebook_params_panel").hide()}function _(){var e=c("#pys_event_facebook_event_type").val();c("#facebook_params_panel").removeClass().addClass(e)}function l(){"custom"===c("#pys_event_facebook_params_currency").val()?c(".facebook-custom-currency").css("visibility","visible"):c(".facebook-custom-currency").css("visibility","hidden")}function v(){c("#pys_event_pinterest_enabled").is(":checked")?c("#pinterest_panel").show():c("#pinterest_panel").hide()}function u(){"CustomEvent"===c("#pys_event_pinterest_event_type").val()?c(".pinterest-custom-event-type").css("visibility","visible"):c(".pinterest-custom-event-type").css("visibility","hidden")}function d(){c("#pys_event_pinterest_params_enabled").is(":checked")?c("#pinterest_params_panel").show():c("#pinterest_params_panel").hide()}function y(){var e=c("#pys_event_pinterest_event_type").val();c("#pinterest_params_panel").removeClass().addClass(e)}function m(){"custom"===c("#pys_event_pinterest_params_currency").val()?c(".pinterest-custom-currency").css("visibility","visible"):c(".pinterest-custom-currency").css("visibility","hidden")}function f(){c("#pys_event_ga_enabled").is(":checked")?c("#analytics_panel").show():c("#analytics_panel").hide()}function h(){"_custom"===c("#pys_event_ga_event_action").val()?c("#ga-custom-action").css("visibility","visible"):c("#ga-custom-action").css("visibility","hidden")}function g(){c("#pys_event_bing_enabled").is(":checked")?c("#bing_panel").show():c("#bing_panel").hide()}c(function(){c('[data-toggle="pys-popover"]').popover({container:"#pys",html:!0,content:function(){return c("#pys-"+c(this).data("popover_id")).html()}})}),c(".pys-select2").select2(),c(".pys-tags-select2").select2({tags:!0,tokenSeparators:[","," "]}),c("select.controls-visibility").on("change",function(e){n(c(this))}).each(function(e,t){n(c(t))}),c(".card-collapse").click(function(){var e=c(this).closest(".card").find(".card-body");e.hasClass("show")?e.hide().removeClass("show"):e.show().addClass("show")}),c(".collapse-control .custom-switch-input").change(function(){var e=c(this),t=c("."+e.data("target"));0<t.length&&(e.prop("checked")?t.show():t.hide())}).trigger("change"),e(),c('input[name="pys[core][woo_event_value]"]').change(function(){e()}),t(),c('input[name="pys[core][edd_event_value]"]').change(function(){t()}),c("#pys_select_all_events").change(function(){c(this).prop("checked")?c(".pys-select-event").prop("checked","checked"):c(".pys-select-event").prop("checked",!1)}),i(),a(),c("#pys_event_trigger_type").change(function(){i(),a()}),c(".add-event-trigger").click(function(){var e=c(this).closest(".event_triggers_panel");s(e,e.data("trigger_type"))}),c(".remove-row").click(function(e){c(this).closest(".row.event_trigger, .row.facebook-custom-param, .row.pinterest-custom-param").remove()}),o(),r(),p(),_(),l(),c("#pys_event_facebook_enabled").click(function(){o()}),c("#pys_event_facebook_event_type").change(function(){r(),_()}),c("#pys_event_facebook_params_enabled").click(function(){p()}),c("#pys_event_facebook_params_currency").change(function(){l()}),c(".add-facebook-parameter").click(function(){var e=c("#facebook_params_panel"),t=c(".facebook-custom-param",e),n=c(t[0]).clone(!0),a=c(t[t.length-1]).data("param_id")+1,s="pys[event][facebook_custom_params]["+a+"]";n.data("param_id",a),c("input.custom-param-name",n).attr("name",s+"[name]"),c("input.custom-param-value",n).attr("name",s+"[value]"),n.css("display","flex"),n.insertBefore(c(".insert-marker",e))}),v(),u(),d(),y(),m(),c("#pys_event_pinterest_enabled").click(function(){v()}),c("#pys_event_pinterest_event_type").change(function(){u(),y()}),c("#pys_event_pinterest_params_enabled").click(function(){d()}),c("#pys_event_pinterest_params_currency").change(function(){m()}),c(".add-pinterest-parameter").click(function(){var e=c("#pinterest_params_panel"),t=c(".pinterest-custom-param",e),n=c(t[0]).clone(!0),a=c(t[t.length-1]).data("param_id")+1,s="pys[event][pinterest_custom_params]["+a+"]";n.data("param_id",a),c("input.custom-param-name",n).attr("name",s+"[name]"),c("input.custom-param-value",n).attr("name",s+"[value]"),n.css("display","flex"),n.insertBefore(c(".insert-marker",e))}),f(),h(),c("#pys_event_ga_enabled").click(function(){f()}),c("#pys_event_ga_event_action").change(function(){h()}),g(),c("#pys_event_bing_enabled").click(function(){g()})});

jQuery( document ).ready(function($) {
    updateFDPValue($("#pys_facebook_fdp_purchase_event_fire"));
    $("#pys_facebook_fdp_purchase_event_fire").change(function () {

        updateFDPValue(this);
    });
    updatePostEventFields();
    $("#pys_event_trigger_type").change(function(){
        updatePostEventFields();
    });



    function updateFDPValue(input) {
        if($(input).val() == "scroll_pos") {
            $("#fdp_purchase_event_fire_scroll_block").show();
            $("#pys_facebook_fdp_purchase_event_fire_css").hide()
        } else  if($(input).val() == "css_click") {
            $("#fdp_purchase_event_fire_scroll_block").hide();
            $("#pys_facebook_fdp_purchase_event_fire_css").show()
        } else {
            $("#fdp_purchase_event_fire_scroll_block").hide();
            $("#pys_facebook_fdp_purchase_event_fire_css").hide()
        }
    }

    function updatePostEventFields() {
        if($("#pys_event_trigger_type").val() == "post_type") {
            $(".event-delay").css("visibility","visible");
            $(".triger_post_type").show();
            $("#url_filter_panel").hide();
        } else {
            $(".triger_post_type").hide();
        }
    }
});

