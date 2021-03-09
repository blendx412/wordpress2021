jQuery(document).ready(function( $ ) {
	$('.landing-v4.straightener .product-summary .price-wrapper').insertAfter('.kolicina-buttons');
	$('.landing-v4.straightener .currently-viewing').insertBefore('.landing-v4.straightener .product-summary .price-wrapper');
	setTimeout(function() {
    	$('.page-template-template-landingpage.landing-v4.straightener .accordion .elementor-tab-title').click();
    }, 3000);
});
