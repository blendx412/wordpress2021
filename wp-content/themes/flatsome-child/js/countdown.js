jQuery(document).ready(function($) {

	// Set the date we're counting down to
    var end = new Date();
    end.setHours(23,59,59,999);
	var countDownDate = end.getTime();

	// Update the count down every 1 second
	var x = setInterval(function() {
		// Get todays date and time
		var now = new Date().getTime();

		// Find the distance between now and the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		var days_box = document.getElementById("days");
		var hours_box = document.getElementById("hours");
		var minutes_box = document.getElementById("minutes");
		var seconds_box = document.getElementById("seconds");

		// Display the result
		if (days_box)
		    days_box.innerHTML = addZeroToCounter(days);
		if (hours_box)
		    hours_box.innerHTML = addZeroToCounter(hours);
		if (minutes_box)
		    minutes_box.innerHTML = addZeroToCounter(minutes);
		if (seconds_box)
		    seconds_box.innerHTML = addZeroToCounter(seconds);

		// If the count down is finished, write some text
		if (distance < 0 && document.getElementById("counter-box")) {
			clearInterval(x);
			document.getElementById("counter-box").innerHTML = "EXPIRED";
		}
	}, 100);

	function addZeroToCounter(num) {
		if (parseInt(num) < 10) {
			num = '0' + num;
		}

		var character = String(num).split("");
		var combined = "<span class='num'>" + character[0] + "</span><span class='num'>" + character [1] + "</span>";

		return combined;
	}

});
