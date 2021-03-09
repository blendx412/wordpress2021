jQuery(document).ready(function($) {

	$('#_wcbv').change(function() {
		if($(this).is(':checked')) {
			$('.hide_if_wcbv').show();
		}
		else {
			$('.hide_if_wcbv').hide();
		}
	}).change();

});