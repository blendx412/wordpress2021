/* http://keith-wood.name/countdown.html
 * Slovenian localisation for the jQuery countdown extension
 * Written by Borut Tomažin (debijan{at}gmail.com) (2011)
 * updated by Jan Zavrl (jan@iuvo.si) (2015) */
(function($) {
	'use strict';
	$.countdown.regionalOptions.sl = {
		labels: ['Let','Mesecev','Tednov','Dni','Ur','MINUTE ','SEKUNDE'], // Plurals
		labels1: ['Leto','Mesec','Teden','Dan','Ura','MINUTA','SEKUNDA'], // Singles
		labels2: ['Leti','Meseca','Tedna','Dneva','Uri','MINUTE','SEKUNDE'], // Doubles
		labels3: ['Leta','Meseci','Tedni','Dnevi','Ure','MINUTE','SEKUNDE'], // 3's
		labels4: ['Leta','Meseci','Tedni','Dnevi','Ure','MINUTE','SEKUNDE'], // 4's
		compactLabels: ['l','m','t','d'],
		whichLabels: function(amount) {
			return (amount > 4 ? 0 : amount);
		},
		digits: ['0','1','2','3','4','5','6','7','8','9'],
		timeSeparator: ':',
		isRTL: false
	};
	$.countdown.setDefaults($.countdown.regionalOptions.sl);
})(jQuery);
