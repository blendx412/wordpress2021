var WCStickyProductBar = /** @class */ (function () {
    function WCStickyProductBar(options) {
		this.options = options;
	}
	
	WCStickyProductBar.prototype.isEnabled = function()
	{
		var isStickyProductBarEnabled = false;

		// check bar is enabled for the given platform
		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
		  if (this.options.enableMobile == 'yes') {
			isStickyProductBarEnabled = true;
		  }
		} else if (this.options.enableDesktop == 'yes') {
		  isStickyProductBarEnabled = true;
		}
		
		return isStickyProductBarEnabled;
	};

	WCStickyProductBar.prototype.activateRating = function()
	{
		// activate rating plugin
		jQuery("." + this.options.id + " .rateyo").rateYo();
	};

	WCStickyProductBar.prototype.show = function()
	{
		jQuery("body").addClass("wc-sticky-product-bar-displayed");
	};
	
	WCStickyProductBar.prototype.hide = function()
	{
		jQuery("body").removeClass("wc-sticky-product-bar-displayed");
	};

	WCStickyProductBar.prototype.onUpdateCheckout = function(e, data)
	{
		jQuery("." + this.options.id + " .action-button").html(jQuery("#place_order").html());
	};

	WCStickyProductBar.prototype.onCheckoutClick = function(event)
	{
		// make sure the customer will accept terms and conditions
		if (jQuery("form.checkout [name=terms]").length > 0 && !jQuery("form.checkout [name=terms]").prop("checked")) {
			if (confirm(this.options.termsQuestions)) {
				jQuery("." + this.options.id + " [name=terms]").prop("checked", true);
				jQuery("form.checkout [name=terms]").prop("checked", true);
			} else {
				return;
			}
		}
		
		jQuery("#place_order").click();  
	};

	WCStickyProductBar.prototype.onTermsChange = function(event)
	{
		jQuery("form.checkout [name=terms]").prop("checked", jQuery(event.target).prop("checked"));
	};

	WCStickyProductBar.prototype.onUpdateCartTotal = function()
	{
		var TotalSource = jQuery(".cart_totals [data-title=Total] .woocommerce-Price-amount");
	
		if (TotalSource.length > 0) {
		  jQuery("." + this.options.id + " .total .woocommerce-Price-amount").html(TotalSource.html());
		} else if (jQuery(".cart-empty").length > 0) {
		  jQuery("." + this.options.id).hide();
		}
	};

	WCStickyProductBar.prototype.onProductOptionChange = function(event)
	{
		var Product = jQuery(".product form.cart").parents(".product");
		var PriceSource = Product.find(".woocommerce-variation-price .price");
		if (PriceSource.length == 0) {
			PriceSource = Product.find(".price:first");
		}
		
		if (PriceSource.length > 0) {
			jQuery("." + this.options.id + " .price").html(PriceSource.html());
		}

		if (jQuery(event.target).attr("name") == "quantity") {
			jQuery("." + this.options.id + ".product-page input[name=quantity]").val(jQuery(event.target).val());
		}
	};

	WCStickyProductBar.prototype.onQuantityChange = function()
	{
		var stickyBarQuantity = jQuery("." + this.options.id + ".product-page input[name=quantity]");
		var productQuantity = jQuery(".cart input[name=quantity]").not(stickyBarQuantity);

		productQuantity.val(stickyBarQuantity.val());
	};

	WCStickyProductBar.prototype.onAddToCartClick = function(event)
	{
		var addToCartButton = jQuery(".cart button[type=submit]").not(".wc-sticky-product-bar button[type=submit]");

		if (jQuery(event.target).hasClass("variable")) {
		  var top = addToCartButton.offset().top;
		  top -= jQuery(window).height() / 2;
	  
		  window.scrollTo(addToCartButton.offset().left, top);  
		} else {
		  jQuery(".single_add_to_cart_button").click();
		}
	
		return false;
	};

	WCStickyProductBar.prototype.onComponentTotalChanged = function()
	{
		// seems like composite uses timeout so we won't be able to get final totals without timeout
		setTimeout(function() {
		  var TotalSource = jQuery(".composite_price ins .woocommerce-Price-amount");
	
		  if (TotalSource.length > 0) {
			jQuery("." + this.options.id + ".product-page .woocommerce-Price-amount").html(TotalSource.html());
		  }  
		}, 100);
	};

	WCStickyProductBar.prototype.tryToShow = function()
	{
		// conditional show bar when add to cart button is not visible
		if (this.options.alwaysVisible == "yes") {
			this.show();

			return;
		};

		this.activateRating();

		if (jQuery(".cart button[type=submit]").visible()) {
			this.hide();
		}
		
		var showStickyProductBarTimeoutId = null;
		
		jQuery(window).on("scroll", function() {
			clearTimeout(showStickyProductBarTimeoutId);
			
			showStickyProductBarTimeoutId = setTimeout(function() {
				if (jQuery(".cart button[type=submit]").visible()) {
					this.hide();
				} else {
					this.show();
				} 
			}, 20);
		});
	}

	WCStickyProductBar.prototype.getClickEventName = function()
	{
  		// we need to choose correct event to avoid double execution
		var clickEventName = "click";
		if ("ontouchend" in document.documentElement) {
			clickEventName = "touchend";
		}

		return clickEventName;
	};

	WCStickyProductBar.prototype.register = function ()
	{
		if (!this.isEnabled()) {
			return;
		}

		this.tryToShow();

		var _this = this;
		var clickEventName = this.getClickEventName();

		// passthrough click on place an order button
  		// checkout button is clicked on sticky bar
		jQuery("." + this.options.id + ".checkout-page .action-button").on(clickEventName, function(event) { return _this.onCheckoutClick(event); });
		// add to cart button is clicked
		jQuery("." + this.options.id + ".product-page .action-button").on(clickEventName, function(event) { return _this.onAddToCartClick(event); });
		// passthrough click on terms checkbox
		jQuery("." + this.options.id + ".checkout-page [name=terms]").on("change", function(event) { return _this.onTermsChange(event); });
		// product quantity is updated on the sticky bar
		jQuery("." + this.options.id + ".product-page input[name=quantity]").on("change", function() { return _this.onQuantityChange(); });
		// update price on any product option change  
		jQuery(document).on("change", ".product form.cart :input", function(event) { return _this.onProductOptionChange(event); });
		// handle when shipping method is changed on the cart page
		jQuery(document).on("wc_fragments_refreshed", function() { return _this.onUpdateCartTotal(); });
		jQuery(document).on("updated_shipping_method", function() { return _this.onUpdateCartTotal(); });
		// update place order text on event from checkout page
		jQuery(document).on("updated_checkout", function(e, data) { return _this.onUpdateCheckout(e, data); });

		// handle composite products
		jQuery(".composite_data").on("wc-composite-initializing", function(event, composite) {
			composite.actions.add_action("composite_totals_changed", function() { return _this.onComponentTotalChanged(); }, 1000);
		});
	};
	
    return WCStickyProductBar;
}());

(new WCStickyProductBar(options)).register();