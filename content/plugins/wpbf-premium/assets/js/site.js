/**
 * Custom polyfill NodeList.prototype.forEach
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/API/NodeList/forEach
 */
if (window.NodeList && !NodeList.prototype.forEach) {
	NodeList.prototype.forEach = Array.prototype.forEach;
}

var WpbfPremium = {};

/**
 * This module is intended to handle the site wide JS functionality.
 * Except for the desktop menu and mobile menu.
 *
 * Along with the desktop-menu.js and mobile-menu.js, this file will be combined to site-min.js file.
 * 
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
WpbfPremium.site = (function ($) {

	/**
	 * Whether we're inside customizer or not.
	 *
	 * @var bool
	 */
	var isInsideCustomizer = window.wp && wp.customize ? true : false;

	/**
	 * Pre-defined breakpoints.
	 *
	 * @var Object
	 */
	var breakpoints = {
		desktop: 1024,
		tablet: 768,
		mobile: 480
	};

	// Run the module.
	init();

	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {

		setupBreakpoints();
		setupResponsiveVideoOptIn();
		setupPostGridMasonry();

	}

	/**
	 * Setup breakpoints for desktop, tablet, and mobile.
	 */
	function setupBreakpoints() {

		setupBreakpoint('desktop');
		setupBreakpoint('tablet');
		setupBreakpoint('mobile');

	}

	/**
	 * Setup breakpoint by device type.
	 *
	 * Retrieve breakpoint based on body class,
	 * then set it as the value of top level `breakpoints` variable.
	 *
	 * @param {string} device The device type. Accepts 'desktop', 'tablet', or 'mobile'.
	 */
	function setupBreakpoint(device) {

		var matchRule = "wpbf-" + device + "-breakpoint-[\\w-]*\\b";
		var breakpointClass = $('body').attr("class").match(matchRule);

		if (null != breakpointClass) {
			breakpoints[device] = breakpointClass.toString().match(/\d+/);
			breakpoints[device] = Array.isArray(breakpoints[device]) ? breakpoints[device][0] : breakpoints[device];
		}

	}

	/**
	 * Setup responsive video opt-in.
	 */
	function setupResponsiveVideoOptIn() {

		$(document).on('click', '.wpbf-video-opt-in-button, .wpbf-video-opt-in-image', function (e) {
			e.preventDefault();

			var $parentNext = $(this).parent().next();
			var url = $parentNext.attr("data-wpbf-video");

			$parentNext.attr('data-wpbf-video');
			$parentNext.children().attr("src", url);
			$parentNext.removeClass('opt-in');
			this.parentNode.remove(this);
		});

	}

	/**
	 * Setup post grid masonry.
	 */
	function setupPostGridMasonry() {

		window.addEventListener('load', function (e) {

			var $grid = $('.wpbf-post-grid-masonry');
			if (!$grid.length) return;

			$grid.isotope({
				itemSelector: '.wpbf-article-wrapper',
				transitionDuration: 0,
			});

		});

	}

	init();

	return {
		isInsideCustomizer: isInsideCustomizer,
		breakpoints: breakpoints
	};

})(jQuery);
