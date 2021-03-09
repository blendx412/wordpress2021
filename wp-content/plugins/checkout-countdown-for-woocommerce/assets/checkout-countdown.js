// Checkout Countdown for WooCommerce.

/*
 * Global Checkout Countdown Status via the window.ccfwooController object.
 *
 * STOP the countdown window.ccfwooController.stopInterval();
 * RESTART the countdown by setting window.ccfwooController.startInterval();
 */
var ccfwooController = {
	counting: false,
	cartItems: ccfwooLocal.cart_count,
	cart: 0,
	interval: false,
	htmlElements: false,
	config: ccfwooLocal, // localized data.
	setElements: function (classNames) {
		const classes = classNames ? classNames : 'checkout-countdown-wrapper';
		const elements = document.getElementsByClassName(classes);
		this.htmlElements = elements;

		return elements;
	},
	getElements: function () {
		return this.htmlElements;
	},
	setHtml: function (textType, elements, duration) {
		// DOM elements.
		elements = elements ? elements : this.htmlElements;
		// Duration for counting down.
		duration = duration ? duration : false;

		if (!elements || !textType) {
			return false;
		}

		var i;

		for (i = 0; i < elements.length; i++) {
			// Get the content which is inside the wrapper.
			const contentElement = elements[i].firstElementChild;

			if (textType === 'loading') {
				ccfwooLoadingHTML(contentElement);
			}
			if (textType === 'counting') {
				ccfwooUpdateCountingHTML(contentElement, duration);
			}
			if (textType === 'expired') {
				ccfwooFinishedCountingHTML(contentElement);
			}
			if (textType === 'banner') {
				ccfwooBannerHTML(contentElement);
			}
		}

		return true;
	},
	isCounting: function () {
		return this.counting;
	},
	hasCart: function () {
		return this.cartItems >= 1 ? true : false;
	},
	getCartItems: function () {
		return this.cartItems;
	},
	setCartItems: function (value) {
		this.cartItems = value;
		return;
	},
	setIsCounting: function (value) {
		if (value === false) {
			ccfwooController.classes('remove', 'checkout-countdown-is-counting');
		} else {
			ccfwooController.classes('add', 'checkout-countdown-is-counting');
			ccfwooController.classes('remove', 'ccfwoo-is-hidden');
		}

		this.counting = value;
	},
	stopInterval: function (clearDate) {
		if (clearDate === true) {
			localStorage.removeItem('ccfwoo_end_date');
		}

		this.setIsCounting(false);
		clearInterval(this.interval);
	},
	startInterval: function () {
		// we are only counting if there's a cart.
		if (this.hasCart()) {
			this.setIsCounting(true);
		} else {
			this.setIsCounting(false);
		}

		this.interval = setInterval(ccfwooCounter, 1000);
	},
	restartInterval: function () {
		// Restart the countdown.
		this.stopInterval(true);
		this.setHtml('loading');
		this.startInterval();

	},
	setNewDate: function (seconds) {
		// If manual seconds, otherwise settings page minutes to seconds.
		const addOnSeconds = seconds ? seconds : 60 * ccfwooLocal.ccfwoo_minutes;

		date = new Date();
		date.setSeconds(date.getSeconds() + addOnSeconds);

		localStorage.setItem('ccfwoo_end_date', date);

		return date;
	},
	triggerEvent: function (target, eventName) {
		// Create the event.
		eventName = new Event(eventName, { bubbles: true });

		if (target === 'document') {
			document.dispatchEvent(eventName);
		}
		if (target === 'window') {
			window.dispatchEvent(eventName);
		}
		if (target === 'body') {
			var getBody = document.getElementsByTagName('BODY')[0];
			getBody.dispatchEvent(eventName);
		}
	},
	classes: function (type, classNames, newClassNames) {
		elements = this.htmlElements;

		if (!elements) {
			return false;
		}

		var i;
		// foreach HTML element.
		for (i = 0; i < elements.length; i++) {
			if (type === 'add') {
				elements[i].classList.add(classNames);
			}
			if (type === 'remove') {
				elements[i].classList.remove(classNames);
			}
			if (type === 'replace' && newClassNames) {
				elements[i].classList.remove(classNames);
				elements[i].classList.add(newClassNames);
			}
		}
	},
};

/*
 * Init Checkout Countdown - Pure JS version of Document Ready.
 */
document.addEventListener('DOMContentLoaded', function (event) {
	ccfwooController.setElements(); // Set the HTML Elements.

	// Combat Caching plugins by setting the cart items from cookies.
	const cookie = ccfwooGetCookie('woocommerce_items_in_cart');

	cookieCart = cookie && cookie >= 1 ? 1 : 0;
	ccfwooController.setCartItems(cookieCart);

	ccfwooCounter(); // Run without delay once.

	ccfwooController.startInterval(); // Start the interval.

	// Add CSS Class when counting.
	if (ccfwooController.isCounting()) {
		ccfwooController.classes('add', 'checkout-countdown-is-counting');
	}
});

/*
 * Event when the countdown has finsihed counting.
 */
document.addEventListener('ccfwooFinishedCounting', function (event) {
	ccfwooController.classes('remove', 'checkout-countdown-is-counting');
});

/*
 * Handles the countdown as an interval.
 */
function ccfwooCounter() {
	// Stop early if no cart available and display the banner.
	if (!ccfwooController.hasCart()) {
		ccfwooController.setHtml('banner');
		ccfwooController.classes('remove', 'checkout-countdown-is-counting');
		ccfwooController.stopInterval(true);
		return;
	}

	// Workout the start and end dates.
	const range = ccfwooGetDurationRange();
	// Get duration in a nice formats.
	const duration = ccfwooFormatDuration(range.start, range.end);

	// Stop if zero or below.
	if (duration.isPast === true) {
		// Set loading dots.
		ccfwooController.setHtml('loading');
		// Stop the interval and clear date.
		ccfwooController.stopInterval(true);
		// Dispatch our reached zero event.
		ccfwooController.triggerEvent('document', 'ccfwooReachedZero', true);
		// Wait a second.
		setTimeout(function () {
			// stop here if we are still counting.
			if (ccfwooController.isCounting()) {
				return;
			}
			ccfwooController.triggerEvent('document', 'ccfwooFinishedCounting', true);
			// Expired text.
			ccfwooController.setHtml('expired');
		}, 1000);
		// Wait 5 seconds and display default text.
		setTimeout(function () {
			// stop here if we are still counting.
			if (ccfwooController.isCounting()) {
				return;
			}
			ccfwooController.setHtml('banner');
		}, 6000);
	} else {
		// Update the counter in the DOM.
		ccfwooController.setHtml('counting', false, duration);
	}

	return;
}

/*
 * Set the counting html in the DOM.
 */
function ccfwooUpdateCountingHTML(element, duration) {
	// Exit if no element.
	if (!element) {
		return;
	}

	var string_minutes = ccfwooLocal.countdown_text.replace('{minutes}', duration.minutes);
	var counter_string = string_minutes.replace('{seconds}', duration.seconds);

	element.innerHTML = counter_string;
}

/*
 * Set the loading dots in the DOM.
 */
function ccfwooLoadingHTML(element) {
	// Exit if no element.
	if (!element) {
		return;
	}

	element.innerHTML = ccfwooLocal.loading_html;
}
/*
 * Set the banner html in the DOM.
 */
function ccfwooBannerHTML(element) {
	// Exit if no element.
	if (!element) {
		return;
	}

	// Banner message if selected.
	if (ccfwooLocal.enable_banner_message === 'on') {
		element.innerHTML = ccfwooLocal.banner_message_text;
	}

	// Banner message is off.
	if (ccfwooLocal.enable_banner_message !== 'on') {
		element.innerHTML = ccfwooLocal.loading_html;
		ccfwooController.classes('add', 'ccfwoo-is-hidden');
	}
}

function ccfwooFinishedCountingHTML(element) {
	// Exit if no element.
	if (!element) {
		return;
	}

	element.innerHTML = ccfwooLocal.expired_text;
}

/*
 * Get the duration range (start and end) dates of our countdown.
 */
function ccfwooGetDurationRange() {
	const rightNow = new Date();

	var endDate = localStorage.getItem('ccfwoo_end_date') ? localStorage.getItem('ccfwoo_end_date') : false;

	if (endDate) {
		endDate = new Date(endDate);
	} else {
		endDate = ccfwooController.setNewDate();
	}

	const range = {
		start: rightNow,
		end: endDate,
	};

	return range;
}

/*
 * Work out the duration and format it into nice object with additional details.
 */
function ccfwooFormatDuration(startDate, endDate) {
	let diff = new Date(endDate) - new Date(startDate);

	let ValidDates = Number.isInteger(diff);

	let weekdays = Math.floor(diff / 1000 / 60 / 60 / 24 / 7);
	let days = Math.floor(diff / 1000 / 60 / 60 / 24 - weekdays * 7);
	let hours = Math.floor(diff / 1000 / 60 / 60 - weekdays * 7 * 24 - days * 24);
	let minutes = Math.floor(diff / 1000 / 60 - weekdays * 7 * 24 * 60 - days * 24 * 60 - hours * 60);
	let seconds = Math.floor(diff / 1000 - weekdays * 7 * 24 * 60 * 60 - days * 24 * 60 * 60 - hours * 60 * 60 - minutes * 60);
	let milliseconds = Math.floor(diff - weekdays * 7 * 24 * 60 * 60 * 1000 - days * 24 * 60 * 60 * 1000 - hours * 60 * 60 * 1000 - minutes * 60 * 1000 - seconds * 1000);

	// Check if the start date is past the end date.
	let isPast = diff / 1000 <= 0 ? true : false;

	let formattedDifference = {
		milliseconds: milliseconds,
		seconds: ccfwooLeadingZero(seconds),
		minutes: ccfwooLeadingZero(minutes),
		hours: hours,
		days: days,
		weekdays: weekdays,
		totalSeconds: diff / 1000,
		isPast: isPast,
		ValidDates: ValidDates,
	};

	return formattedDifference;
}
/*
 * Get any cookie from the browers. e.g woocommerce_items_in_cart.
 */
function ccfwooGetCookie(name) {
	var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
	if (match) return match[2];
}

/*
 * Add leading zeros to any number.
 */
function ccfwooLeadingZero(number) {
	if (ccfwooLocal.leading_zero === 'on') {
		// Set numbers to be 2 sizes, e.g 05.
		const size = 2;

		number = number.toString();

		while (number.length < size) number = '0' + number;

		return number;
	}

	return number;
}
