jQuery(document).ready(function( $ ) {
    
    $(".variations select").change(function(){
        $([document.documentElement, document.body]).animate({
        scrollTop: $(".woocommerce-product-gallery").offset().top
    }, 300);   
    });
});
