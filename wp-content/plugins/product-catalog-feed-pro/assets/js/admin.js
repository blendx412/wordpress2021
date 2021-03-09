jQuery(function($){
    var elmNeedClick = [];

    function Wpwoof_getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return null;
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    var menutab = Wpwoof_getParameterByName('tab'); 
    var edittab = Wpwoof_getParameterByName('edit');

    if( menutab == null || menutab < 0 )
        menutab = 0;
    if( edittab == null ) {
        //toggle tab content
        $('.wpwoof-menu li').each(function(tabIndex, tabEl) {
            var $tab = $(this);
            $tab.on('click', function() {
                $('.wpwoof-settings-panel').hide();
                $('.wpwoof-menu li').removeClass('wpwoof-menu-selected');
                $tab.addClass('wpwoof-menu-selected');
                $('.wpwoof-settings-panel').eq(tabIndex).show();
            });
        });
    }

    $(document).on('click', '.wpwoof-open-popup', function(event) {
        event.preventDefault();
        $(this).parents('.wpwoof-open-popup-wrap').find('.wpwoof-popup-wrap').show();
    });

    $(document).on('click', '.wpwoof-popup-close, .wpwoof-popup-done', function(){
        $(this).parents('.wpwoof-popup-wrap').hide();	
    });
    function sendWPWOOForm(){
        wpwoofShowModal();
        $('#IDwpwoofSubmit').toggleClass('wpwoof-loader');       
        $('#IDwpwoofSubmit').attr('type', 'button');
        var formhref = $('#wpwoof-addfeed').attr('action');
        console.log("SEND FORM");
        $.post(formhref,$('#wpwoof-addfeed').serialize()+"&wpwoof-addfeed-submit=ajax",function(answ){
             window.location.href=formhref;
        });
    }
    function wpFindInArray(arr,val){
        if(typeof arr === 'object'){
            for(var i in arr){
                    if(arr[i]==val)return i; 
            }
        }
        return -1;
    }

    $(document).on('submit', '#wpwoof-addfeed', function(e){     
        var feed_name = ($('#idFeedName').val()).trim();
        var regexEmpty = /^\s+$/;
        var regexTitle = /[!\!@#$%^&*()+\=\[\]{};':"\\|,<>\/?]/;
        if( feed_name == '') {
            e.preventDefault();
            alert('The feed name can not be empty.');
            $('html, body').animate({
                scrollTop: ($('#idFeedName').offset().top -150)
            }, 200);
            $('#idFeedName').focus();
            return false;
        } else if( regexTitle.test(feed_name) ) {
            e.preventDefault();	
            alert('A Feed Name should not contain special characters. The following special characters are not allowed: " ! @ # $ % ^ & * ( ) + \\ / = [ ] { } ; \' : " , < > ? ".');
            $('html, body').animate({
                scrollTop: ($('#idFeedName').offset().top -150)
            }, 200);
            $('#idFeedName').focus();
            return false;
        } else if(feed_name.length<4 || feed_name.length>100 ) {
            e.preventDefault();	
            alert('The feed name should contain at least 3 characters but less than 100 characters.');
            $('html, body').animate({
                scrollTop: ($('#idFeedName').offset().top -150)
            }, 200);
            $('#idFeedName').focus();
            return false;
        }else if( $('#IDtax_countries').length>0 && $('#IDtax_countries').is(":visible") && $('#IDtax_countries').val()=="" ){
            e.preventDefault();
            alert('Please define “apply tax for” under Price and Tax settings.');
            $('html, body').animate({
                scrollTop: ($('#IDtax_countries').offset().top -150)
            }, 200);
            $('#IDtax_countries').focus();
            return false;
        } if($('#feed_category_all').attr("checked")){
            $('#wpwoof-popup-categories input').each(function(){
                if($(this).attr('id')!='feed_category_all'){                   
                    $(this).remove();
                }
            });
        }
        
        if( $('input[name=edit_feed]').length  && $('input[name=edit_feed]')!="" && feed_name==$('input[name=old_feed_name]').val() ){
            sendWPWOOForm();
        }else{
            $.post(window.location.href,{'check_feed_name':feed_name},function(answ){ 
                if(answ.status && answ.status == "OK"){
                        sendWPWOOForm();
                       
                } else {
                    var key=1;                    
                    while( wpFindInArray(answ,feed_name + "-" + key)!=-1 ) key=key*1+1;
                    feed_name = feed_name + "-" + key;                   
                    $('#idFeedName').val(feed_name);
                    sendWPWOOForm();                    
                    }
            },"JSON");
        }
        return false;
        
    });

    $(document).on('click', '#wpwoof-hide-additional', function(){
        $('#wpwoof-additionalfield-wrap').toggleClass('wpwoof-additional-hide');
        if( $('#wpwoof-additionalfield-wrap').hasClass('wpwoof-additional-hide') ) {
            $(this).text('Show Additional Attributes');
        } else {
            $(this).text('Hide Additional Attributes');
        }
    });

    $(document).on('click', '#wpwoof-popup-categories li input.feed_category', function(e) {
        var cat_id = $(this).attr('id') || '';
        if( cat_id != 'feed_category_all' ) {
            var allchecked = true;
            $('#wpwoof-popup-categories li input.feed_category').each(function(index, el) {
                var cat_id = $(this).attr('id') || '';
                if( cat_id != 'feed_category_all' && $(this).prop('checked') == false )
                    allchecked = false;	
            });

            if( !allchecked ) {
                $('#feed_category_all').prop('checked', false);
            } else {
                $('#feed_category_all').prop('checked', true);
            }
        }
    });

    $(document).on('click', '#feed_category_all', function(e) {
        var tick = $(this).prop('checked');
        $('#wpwoof-popup-categories li input.feed_category').prop('checked', tick);
    });

    $(document).on('click', '#feed_check_all_additional', function(e) {
        var tick = $(this).prop('checked');
        $('input.wpwoof-field-additional').prop('checked', tick);
    });

    $(document).on('change', 'select.wpwoof_mapping_option', function(){
        if( $(this).val() == 'wpwoofdefa_use_custom_attribute' ) {
            if( !$(this).next('input' ).hasClass('wpwoof_mapping_attribute') ) {
                var name = $(this).attr('name');
                name = name.toString();
                name = name.replace('[value]', '[custom_attribute]');
                var html = '<input type="text" name="'+name+'" value="" class="wpwoof_mapping_attribute" />';
                $(this).after(html);
            }
        } else {
            if( $(this).next('input' ).hasClass('wpwoof_mapping_attribute') ) {
                $(this).next('input' ).remove();
            }
        }
    });
    $('body').on('click',function( event ) {
        if( $('#IDwpwoof-myModal').is( ":visible" ) ){   
            $('#IDwpwoof-myModal').hide();
        }
    });    
    
    $('span.wpwoof-close').on('click',function(){
        $('#IDwpwoof-myModal').hide(); 
    });
    $('a.regenerate').on('click',function(){ 
        
        if(  $(this).attr('href').indexOf('wpwoofeedcsvdownload')!=-1 && elmNeedClick.length>0  ){
            alert("Another feed is in progress now. Please try again later.");
            return false;
        }           
        if( $(this).is(':disabled') ) return false;       
        sendRegeneration( $(this).attr('href') ); 
        var elmID = $(this).attr('id');
        if(elmID){
             elmID=elmID.substring(0, elmID.length-1);      
             wpwoofHideButtons($('#'+elmID).data('feedid'),-29);
             wpwoofShowModal();
             if(  $(this).attr('href').indexOf('wpwoofeedcsvdownload')!=-1 ){
                elmNeedClick[$('#'+elmID).data('feedid')]=$(this).attr('href');
             }
        }        
        //$(this).find('span').html( ' in progress ');
        return false;
    });
    function sendRegeneration(url){        
        if(url){
            $.post(url); 
        }       
    } 

    function wpwoofShowButtons(feedID) {
         
         $('#idTr'+feedID+' a').removeAttr('disabled');
         $('#idTr'+feedID+' a.wpfooalarm').remove();
         $('#wpwoof_status_'+feedID).hide();
         if( $('#spinner'+feedID).length ) $('#spinner'+feedID).hide();                
    }
    function wpwoofHideButtons(feedID, marginleft,total) {       
        $('#idTr'+feedID+' a').attr('disabled',true);
        $("#wpwoof_img_"+feedID).css('margin-left',marginleft);
        if(total) $("#wpwoof_img_"+feedID).attr('title','generated - ' + Math.round( total ) + '%');
        $('#wpwoof_status_'+feedID).show(); 
   }
    function checkFeedsStatus(){
        $.post(window.location.href,{'wpwoof-status':'get'},function(data){
            var starded = new Array();
            /** forced download generated feeds */
            for( var l in elmNeedClick) {                
                var present = false;
                for( var k in data) {                     
                    if(l == data[k]['feed_id']){
                        present=true;
                        break;
                    }
                }
               if(!present) {                  
                   document.location.href=elmNeedClick[l]+"&dwnl=true";                 
                   //$('#id-wpwoof-iframe').attr("src",elmNeedClick[l]+"&dwnl=true");
                   elmNeedClick = [];
                   break;
                }
            }
            
            $.each($('div.wpwoof_statusbar'), function( index, elm ) {                
                var marginleft=-2; 
                var total = 100;               
                $.each(data, function(i, item) {    
                    if(starded.indexOf(data[i]['feed_id'])==-1){
                        starded.push(data[i]['feed_id']);
                        if($('#'+ data[i]['option_name']+'a').length) sendRegeneration( $('#'+ data[i]['option_name']+'a').attr('href') );
                    }                                  
                    if( data[i]['feed_id'] == $(elm).data('feedid') ){                        
                        var prods = ( data[i]['option_value']['parsed_product_ids'].length + Object.keys( data[i]['option_value']['products_left'] ).length); 
                        total = prods ? 100.0/prods * data[i]['option_value']['parsed_products']*1.0 : 0;
                        marginleft+=-29 + Math.round( 3 *  total/10 );
                       
                    }
                });
                if(marginleft<-2){
                    wpwoofHideButtons($(elm).data('feedid'),marginleft,total);                   
                }else{
                    //тут включаем все , фид готов
                    wpwoofShowButtons($(elm).data('feedid'));
                }                
            });

        },'JSON'); 
       setTimeout(checkFeedsStatus,47000);
    }


    $.each($('div.wpwoof_statusbar'), function( index, elm ) {
        wpwoofHideButtons($(elm).data('feedid'),-30);
    });
    checkFeedsStatus();    
    
    function  wpwoofShowModal(){        
        $('#IDwpwoof-myModal').css({'left' : 'calc(5% + ' + $('#adminmenuwrap').outerWidth() + 'px)'});       
        $('#IDwpwoof-myModal').show();    
        setTimeout(function(){            
            $('#IDwpwoof-myModal').hide();
        },30000);
    }
    
});
