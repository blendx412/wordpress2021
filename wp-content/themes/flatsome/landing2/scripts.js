"use strict";
jQuery(document).ready(function () {
    if (jQuery("body").hasClass("page-template-page-spin-wheel")) {
        jQuery("#message-purchased").remove();
        jQuery(".wheelwrap").click(function () {
            jQuery(".spin-wheel .button-main").trigger("click");
        });
        jQuery(".spin-wheel .button-main").click(function () {
            setTimeout(function () {
                window.location.href = uu.site_url + "/?coupon=funviki20";
            }, 6700);
        });
    }
    var lastScrollTop = 0;
    jQuery(window).scroll(function (event) {
        var st = jQuery(this).scrollTop();
        if (st > lastScrollTop) {
            jQuery(".header-wrapper.stuck").css("display", "none");
        } else {
            jQuery(".header-wrapper.stuck").css("display", "block");
        }
        lastScrollTop = st;
    });
    if (jQuery(window).width() <= 549) {
        jQuery(".row-deal .product-deal .product-info").click(function () {
            setTimeout(function () {
                jQuery('.row-deal .product-deal:first-child button[name="add-to-cart"]').click();
            }, 800);
        });
    }
    if (jQuery("body").hasClass("product-template-default")) {
        jQuery("#qty1" + jQuery(".qty").val()).prop("checked", true);
        if (!jQuery("body").hasClass("uu-acf_product_index_checkout")) {
            jQuery(".section-product .qty").val("1");
        }
        jQuery(".section-product .uu-quantity input, .section-product .uu-quantity-simple input").on("change", function () {
            jQuery(".section-product .qty").val(jQuery(this).val()).trigger("change");
        });
        setTimeout(function () {
            jQuery(".uu-quantity.variable #qty1 + label").trigger("click");
        }, 900);
    }
    if (jQuery("body").hasClass("home")) {
        jQuery(".home .slider-count .col.product").slice(16).css("display", "none");
        jQuery(".show-more").click(function () {
            jQuery(".home .slider-count .col.product").slice(16).css("display", "block");
            jQuery(this).css("display", "none");
        });
    }
    jQuery("#masthead .header-cart-title").text(uu.my_cart);
    if (jQuery("body").hasClass("uu-acf_product_index_checkout")) {
        function dtindexPrice() {
            let cartTotal = jQuery(".uu-order-summary .uu-total .table-right").text();
            jQuery(".index-checkout #place_order").append("<span class='place-order-price mobile-yes'>- " + cartTotal + "</span>");
        }
        function dtindex() {
            jQuery(".uu-acf_product_index_checkout .section-product form.cart .qty").on("change", function () {
                jQuery(".index-checkout .qty").val(jQuery(this).val()).trigger("change");
            });
        }
        jQuery(document.body).on("updated_cart_totals", function () {
            dtindex();
            dtindexPrice();
        });
        jQuery(document).ajaxComplete(function () {
            dtindex();
            dtindexPrice();
        });
        jQuery(document).on("change", "form[name='checkout'] input[name='payment_method']", function () {
            dtindexPrice();
        });
    }
    if (jQuery(".uu-quantity").hasClass("variable")) {
        console.log("yes");
        jQuery(".uu-quantity .input-group:nth-child(3) .label").click(function () {
            jQuery("#formezaupsss").detach().appendTo(".uu-quantity .input-group:nth-child(3) .variations-order");
        });
        jQuery(".uu-quantity .input-group:nth-child(2) .label").click(function () {
            jQuery("#formezaupsss").detach().appendTo(".uu-quantity .input-group:nth-child(2) .variations-order");
        });
        jQuery(".uu-quantity .input-group:nth-child(1) .label").click(function () {
            jQuery("#formezaupsss").detach().appendTo(".uu-quantity .input-group:nth-child(1) .variations-order");
        });
        setTimeout(function () {
            let currentimage = jQuery(".product-images a img").attr("src");
            jQuery(".input-group .package-image img").attr("srcset", currentimage);
        }, 800);
        jQuery(".variations ul, .variations select").click(function () {
            let currentimage = jQuery(".product-images a img").attr("src");
            jQuery(this).closest(".input-group").find(".package-image img").attr("srcset", currentimage);
        });
    }
    if (jQuery(".uu-quantity-simple").hasClass("variable")) {
        setTimeout(function () {
            let currentimage = jQuery(".product-images a img").attr("src");
            jQuery(".uu-variation-img img").attr("src", currentimage).attr("srcset", currentimage);
        }, 800);
        jQuery("#formezaupsss").on("change", ".variations ul, .variations select", function (e) {
            let variationElement = jQuery(this);
            setTimeout(function () {
                let currentimage = jQuery(".product-images a img").attr("src");
                variationElement.closest("form").find(".uu-variation-img img").attr("src", currentimage).attr("srcset", currentimage);
            }, 1);
        });
    }
    setTimeout(function () {
        jQuery(".flickity-viewport").each(function () {
            let countslidersNumber = jQuery(this).find(".flickity-slider").children().length;
            let countsliders = countslidersNumber;
            jQuery(this)
                .closest(".slider")
                .find(".flickity-page-dots")
                .append("<div class='countslider'>/ " + countsliders + "</div>");
        });
    }, 500);
    let slider1 = jQuery(".banner-d1 .text-inner").html();
    jQuery(".col-m1 .col-inner").html(slider1);
    let slider2 = jQuery(".banner-d2 .text-inner").html();
    jQuery(".col-m2 .col-inner").html(slider2);
    let slider3 = jQuery(".banner-d3 .text-inner").html();
    jQuery(".col-m3 .col-inner").html(slider3);
    jQuery(".section-mob-content").each(function () {
        let content1 = jQuery(this).find(".content-d1 .col-inner").html();
        jQuery(this).find(".content-m1 .col-inner").html(content1);
        let content2 = jQuery(this).find(".content-d2 .col-inner").html();
        jQuery(this).find(".content-m2 .col-inner").html(content2);
        let content3 = jQuery(this).find(".content-d3 .col-inner").html();
        jQuery(this).find(".content-m3 .col-inner").html(content3);
        let content4 = jQuery(this).find(".content-d4 .col-inner").html();
        jQuery(this).find(".content-m4 .col-inner").html(content4);
        let content5 = jQuery(this).find(".content-d5 .col-inner").html();
        jQuery(this).find(".content-m5 .col-inner").html(content5);
        let content6 = jQuery(this).find(".content-d6 .col-inner").html();
        jQuery(this).find(".content-m6 .col-inner").html(content6);
    });
    if (jQuery("body").hasClass("home") || jQuery("body").hasClass("product-template-default")) {
        setTimeout(function () {
            jQuery(".row-products .slider-count .product-small").each(function () {
                jQuery(this).find(".review-line .review-number").text(createRandomBigger());
                let NumberDecimal = jQuery(this).find(".random-decimal").text();
                let NumberDecimalParsed = parseInt(NumberDecimal);
                let RandomRatingWord = [" " + uu.review_word_one + " ", " " + uu.review_word_two + " ", " " + uu.review_word_three + " ", " " + uu.review_word_four + " "];
                if (NumberDecimalParsed >= 9.3) {
                    
					//jQuery(this).find(".review-title").text(RandomRatingWord[0]);
                }
                if (NumberDecimalParsed >= 9.5) {
                    //jQuery(this).find(".review-title").text(RandomRatingWord[1]);
                }
                if (NumberDecimalParsed >= 9.7) {
                    //jQuery(this).find(".review-title").text(RandomRatingWord[2]);
                }
                if (NumberDecimalParsed >= 9.8) {
                   // jQuery(this).find(".review-title").text(RandomRatingWord[3]);
                }
            });
            jQuery(".section-product1, .section-product6").each(function () {
                let NumberProduct = parseFloat(jQuery(this).find(".rating-main span").html());
                let NumberProductParsed = parseFloat(NumberProduct.toFixed(1));
                var decimal = (NumberProductParsed + "").split(".")[1];
                var decimalParsed = parseInt(decimal);
                jQuery(this)
                    .find(".random-decimal-one")
                    .text((NumberProductParsed - 0.1).toFixed(1));
                jQuery(this).find(".random-decimal-two").text(NumberProductParsed.toFixed(1));
                jQuery(this).find(".random-decimal-three").text(NumberProductParsed.toFixed(1));
                let RandomRatingWord2 = [" " + uu.review_word_one + " ", " " + uu.review_word_two + " ", " " + uu.review_word_three + " ", " " + uu.review_word_four + " "];
                if (decimalParsed >= 3) {
                   // jQuery(this).find(".review-title").text(RandomRatingWord2[0]);
                }
                if (decimalParsed >= 5) {
                  //  jQuery(this).find(".review-title").text(RandomRatingWord2[1]);
                }
                if (decimalParsed >= 7) {
                 //   jQuery(this).find(".review-title").text(RandomRatingWord2[2]);
                }
                if (decimalParsed >= 8) {
                 //   jQuery(this).find(".review-title").text(RandomRatingWord2[3]);
                }
            });
            jQuery(".section-product1 .item-review").clone().appendTo(".item-review-clone");
            jQuery(".row-deal .col-products .reviews-random").each(function () {
                jQuery(this).text(createRandomBigger());
            });
            function createRandom() {
                var num = Math.floor(Math.random() * 8) + 2;
                return num;
            }
            function createRandomBigger() {
                var num = Math.floor(Math.random() * 1000) + 100;
                return num;
            }
        }, 600);
    }

    if (jQuery("body").hasClass("product-template-default")) {
        jQuery(".product-info, .single-product .uu-quantity").before("<span class='scroll-to' data-label='Scroll to: #buy' data-bullet='false' data-link='#buy' data-title='Change this'></span>");

        //jQuery('.product-template-default').append('<a href="#buy"><div class="buy-fixed"><span class="buy-fixed-cta ga-floater">'+uu.order_now+'<img src="'+uu.site_url+'/wp-content/uploads/2020/05/left-and-right-arrows.svg" /> </span> <span class="discount-wrap">'+uu.up_to+' -<span class="price-fixed"></span></span></div></a>');let priceFloat=jQuery(".uu-quantity .input-group:last-of-type .salepercentage .inner").text();jQuery(".single-product .buy-fixed .price-fixed").text(priceFloat);

        //if(jQuery('.uu-quantity-simple .salepercentage').length){priceFloat=jQuery(".uu-quantity-simple .salepercentage .inner").text();

        //jQuery(".single-product .buy-fixed .price-fixed").text(priceFloat);}
    }

    setTimeout(function () {
        jQuery(".mobile-nav .cart-img-icon").attr("src", uu.site_url + "/wp-content/themes/flatsome/landing2/images/shopping-cart-3.svg");
    }, 500);
    if (!jQuery("body").hasClass("woocommerce-checkout")) {
        let countDownDate = new Date();
        countDownDate = countDownDate.setMinutes(countDownDate.getMinutes() + 10);
        let x = setInterval(function () {
            let now = new Date().getTime();
            let distance = countDownDate - now;
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            if (hours < 10) hours = "0" + hours;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            jQuery(".timer").each(function () {
                jQuery(this).html(minutes + ":" + seconds);
            });
            if (distance < 0) {
                clearInterval(x);
                jQuery(".timer").each(function () {
                    jQuery(this).html("00:00");
                });
            }
        }, 1000);
    }
    jQuery(".tooltip").click(function () {
        jQuery(this).toggleClass("active");
    });
    jQuery(".uu-quantity-simple").on("click", "label", function () {
        jQuery(".price-after-quantity .uu-shipping").html(jQuery(this).find(".product-data-hidden .product-shipping").html());
        jQuery(".price-after-quantity .uu-price ins").html(jQuery(this).find(".product-data-hidden .product-price").html());
        jQuery(".price-after-quantity .uu-price del").html(jQuery(this).find(".product-data-hidden .product-price-org").html());
    });
});
