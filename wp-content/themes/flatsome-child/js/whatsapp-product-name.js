jQuery(document).ready(function( $ ) {

	var productName = $("h1").text();
	/*console.log(productName);
	console.log(encodeURIComponent(productName));*/

	var whatsappHeaderUrlOriginalText = $("a.whatsapp-header-url").attr("href");
	$("a.whatsapp-header-url").attr("href", whatsappHeaderUrlOriginalText + encodeURIComponent(productName));

	$('#read-more-action').click(function() {
        var dots = document.getElementById("dots");
    	var moreText = document.getElementById("read-more");
    	var btnText = document.getElementById("read-more-action");

    	if (dots.style.display === "none") {
    		dots.style.display = "inline";
    		btnText.innerHTML = "Read more";
    		moreText.style.display = "none";
    	} else {
    		dots.style.display = "none";
    		btnText.innerHTML = "Read less";
    		moreText.style.display = "inline";
    	}
    });

    // Order bump in slider
    /*
    if ($('body').hasClass('woocommerce-checkout')) {
    	setTimeout(function() {
    		var orderBumpContainer = $('.wfob_bump_wrapper.woocommerce_checkout_order_review_below_payment_gateway');
    		var cssStyle = $('.wfob_bump_wrapper.woocommerce_checkout_order_review_below_payment_gateway style');
    		cssStyle.clone().appendTo('body');
    		cssStyle.remove();
    		orderBumpContainer.addClass('owl-carousel order-bump-carousel');
    		$('.order-bump-carousel').owlCarousel({
				loop:false,
				margin:10,
				nav:true,
				items: 1,
				autoHeight:true
			});
			$('.wfob_bump_checkbox').change(function(){
				setTimeout(function() {
					location.reload();
				}, 1000);
			});
    	}, 1000);
    }
    */

});


jQuery(document).ready(function( $ ) {

	
		clicked = true;
	var itemsQuantity = 1;
	var variationBoxTitle = 'Proizvod ';
	$('.variations_form').append('<input type="hidden" class="item-quantity" name="item-quantity">');
	$('.item-quantity').val(1);
	$('#izbira3').addClass('izbira-active');

	$('#izbira1, #izbira2, #izbira3').click(function(e) {
		e.preventDefault();
	});

	$( "#izbira1" ).click(function() {
		$(this).siblings().removeClass("izbira-active");
		$(this).addClass("izbira-active");

		if (itemsQuantity == 1) {
			$('#wcbv-add-row').click();
			$('.wcbv-row:nth-child(2) select').select2Buttons();
			$('.wcbv-row:nth-child(2) .select-buttons a').click(function() {
				resetButtons('.wcbv-row:nth-child(2)');
			});
			$('.wcbv-row:nth-child(2) .variation-title').html(variationBoxTitle + 2);
		}
		if (itemsQuantity == 3) {
			$('.wcbv-row:nth-child(3)').remove();
		}

		itemsQuantity = 2;
		$('.product-type-simple .qty').val(2);

	});


	$( "#izbira2" ).click(function() {

		$(this).siblings().removeClass("izbira-active");
		$(this).addClass("izbira-active");

		if (itemsQuantity == 1) {
			$('#wcbv-add-row').click();
			$('#wcbv-add-row').click();
			$('.wcbv-row:nth-child(2) select').select2Buttons();
			$('.wcbv-row:nth-child(3) select').select2Buttons();
			$('.wcbv-row:nth-child(2) .select-buttons a').click(function() {
				resetButtons('.wcbv-row:nth-child(2)');
			});
			$('.wcbv-row:nth-child(3) .select-buttons a').click(function() {
				resetButtons('.wcbv-row:nth-child(3)');
			});
			$('.wcbv-row:nth-child(2) .variation-title').html(variationBoxTitle + 2);
		}
		if (itemsQuantity == 2) {
			$('#wcbv-add-row').click();
			$('.wcbv-row:nth-child(3) select').select2Buttons();
			$('.wcbv-row:nth-child(3) .select-buttons a').click(function() {
				resetButtons('.wcbv-row:nth-child(3)');
			});
		}
		$('.wcbv-row:nth-child(3) .variation-title').html(variationBoxTitle + 3);
		itemsQuantity = 3;
		$('.product-type-simple .qty').val(3);
	});


	$( "#izbira3" ).click(function() {

		$(this).siblings().removeClass("izbira-active");
		$(this).addClass("izbira-active");

		if (itemsQuantity == 2) {
			$('.wcbv-row:nth-child(2)').remove();
		}
		if (itemsQuantity == 3) {
			$('.wcbv-row:nth-child(3)').remove();
			$('.wcbv-row:nth-child(2)').remove();
		}
		itemsQuantity = 1;
		$('.product-type-simple .qty').val(1);

	});

	var currency_symbol = $('.single-product .new-product-price').data('currency');
	var regular_price = $('.single-product .new-product-price').data('regular-price');
	$('.kolicina-buttons button').click(function(){
		var price = $(this).data('price')*1;
		if ($(this).attr('id') == 'izbira1') {
			price = price*2;
			new_regular_price = regular_price*2;
		}
		if ($(this).attr('id') == 'izbira2') {
			price = price*3;
			new_regular_price = regular_price*3;
		}
		if ($(this).attr('id') == 'izbira3') {
			new_regular_price = regular_price;
		}
		price = price.toFixed(0) + currency_symbol;
		
		
		// Change price on different sale amount
		$('.single-product .sale-price .woocommerce-Price-amount').html(price);
		$('.single-product .regular-price .woocommerce-Price-amount').html(new_regular_price + currency_symbol);
	});

	setTimeout(function(){
		$('select').select2Buttons();
		$('.select-buttons a').click(function() {
			resetButtons('.wcbv-row:nth-child(1)');
		});
	}, 100);

	function resetButtons(element) {
		$(element + ' .select2Buttons').remove();
		$(element + ' select').select2Buttons();
		$(element + ' .select-buttons a').click(function() {
			resetButtons(element);
		});
	}
});

jQuery.fn.select2Buttons = function(options) {
  return this.each(function(){
    var $ = jQuery;
    var select = $(this);
    var multiselect = select.attr('multiple');
    select.hide();

    var buttonsHtml = $('<div class="select2Buttons"></div>');
    var selectIndex = 0;
    var addOptGroup = function(optGroup){
      if (optGroup.attr('label')){
        buttonsHtml.append('<strong>' + optGroup.attr('label') + '</strong>');
      }
      var ulHtml =  $('<ul class="select-buttons">');
      optGroup.children('option').each(function(index){

        var liHtml = $('<li></li>');
        if ($(this).attr('disabled') || select.attr('disabled')){
          liHtml.addClass('disabled');
          liHtml.append('<span>' + $(this).html() + '</span>');
        }else{
        	var attribute = $(this).parent().data('attribute');
        	if (index == 0) {
        		var icon = '<span class="remove-filter"><i class="fa fa-remove"></i> Filter</span>';
        	} else {
        		var icon = '';
        	}

        	if (attribute == 'attribute_pa_color' && index!=0) {
        		var title = '<span class="color-text">'+$(this).html()+'</span>';
        		var attrClass = 'color';
        		var dataColor = 'data-color="'+$(this).html()+'"';
        	} else if (attribute == 'attribute_pa_color' && index==0) {
        		var title = $(this).html();
        		var attrClass = 'color';
        		var dataColor = '';
        	} else {
        		var title = $(this).html();
        		var attrClass = 'default';
        		var dataColor = '';
        	}
          liHtml.append('<a href="#" ' + dataColor + ' class="'+attrClass+' '+$(this).val()+'" data-select-index="' + selectIndex + '">' + title + icon + '</a>');
        }

        // Mark current selection as "picked"
        if((!options || !options.noDefault) && $(this).attr('selected')){
          liHtml.children('a, span').addClass('picked');
          //if (liHtml.children('a').hasClass('color'))
          	//ulHtml.first().prepend(liHtml.children('a').data('color'));
        }
        ulHtml.append(liHtml);
        selectIndex++;
      });
      buttonsHtml.append(ulHtml);
    }

    var optGroups = select.children('optgroup');
    if (optGroups.length == 0) {
      addOptGroup(select);
    }else{
      optGroups.each(function(){
        addOptGroup($(this));
      });
    }

    select.after(buttonsHtml);

    buttonsHtml.find('a').click(function(e){
      e.preventDefault();
      var clickedOption = $(select.find('option')[$(this).attr('data-select-index')]);
      if(multiselect){
        if(clickedOption.attr('selected')){
          $(this).removeClass('picked');
          clickedOption.removeAttr('selected');
        }else{
          $(this).addClass('picked');
          clickedOption.attr('selected', 'selected');
        }
      }else{
        buttonsHtml.find('a, span').removeClass('picked');
        $(this).addClass('picked');
        clickedOption.attr('selected', 'selected');
      }
      select.trigger('change');
    });

    $('.color').click(function() {
	  	$(this).parents('select-buttons').append('test');
	  });

  });

};
