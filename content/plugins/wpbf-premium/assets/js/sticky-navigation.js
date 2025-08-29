WpbfPremium.stickyNavigation = (function ($) {
	var isInsideCustomizer = WpbfPremium.site.isInsideCustomizer;
	var opts = {};
	var state = {};
	var data = {}

	/**
	 * Call main functions.
	 */
	function init() {
		listenPartialRefresh();
		setupData();
		setupStickyNavScroll();
	}

	/**
	 * Setup general options.
	 */
	function setupData() {
		opts.nav = document.querySelector('.wpbf-navigation');
		if (!opts.nav) return;

		// Options.
		opts.$nav = $(opts.nav);
		opts.sticky = opts.nav.dataset.sticky;
		opts.delay = opts.nav.dataset.stickyDelay;
		opts.animation = opts.nav.dataset.stickyAnimation;
		opts.duration = opts.nav.dataset.stickyAnimationDuration;
		opts.duration = parseInt(opts.duration, 10);
		opts.distance = parseInt(opts.$nav.offset().top) + parseInt(opts.delay, 10);

		// Scrolling states.
		state.prevScrollTop = window.pageYOffset;
		state.isFired = 0;

		// Logo urls.
		data.menuActiveLogoUrl = $('.wpbf-logo').data("menu-active-logo");
		data.menuLogoUrl = $('.wpbf-logo img').attr('src');
		data.mobileMenuLogoUrl = $('.wpbf-mobile-logo img').attr('src');
	}

	/**
	 * Listen to WordPress selective refresh inside customizer.
	 */
	function listenPartialRefresh() {
		if (!isInsideCustomizer) return;

		wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
			/**
			 * We won't be filtering the "placement.partial.id" here since it will be too much.
			 * Imagine how many customizer controls have partialRefresh on page header.
			 * Because the page header is changed not only on "placement.partial.id === 'sticky_navigation'".
			 */
			setupData();
			setupStickyNavScroll();
		});
	}

	/**
	 * Setup sticky navigation scroll.
	 */
	function setupStickyNavScroll() {
		if (opts.sticky) {
			$(window).on('scroll', navOnScroll);
		} else {
			$(window).off('scroll', navOnScroll);
		}
	}

	/**
	 * Function to run on nav on scroll.
	 */
	function navOnScroll() {
		if (isInsideCustomizer && (!opts.nav || !opts.sticky)) return;
		checkStickyNav();
		if (opts.animation === 'scroll') hideOnScroll();
	}

	/**
	 * Check sticky navigation.
	 */
	function checkStickyNav() {
		// How far we are away from the top.
		var scrollTop = $(window).scrollTop();

		// Height of the navigation bar.
		var navHeight = opts.$nav.outerHeight();

		// Animations.
		if (scrollTop > opts.distance && !state.isFired) {
			opts.nav.classList.add('wpbf-navigation-active');

			// Slide animation.
			if (opts.animation === 'slide') {
				opts.$nav.css({ 'position': 'fixed', 'left': '0', 'zIndex': '666', 'top': -navHeight }).stop().animate({ 'top': 0 }, opts.duration);
			} else if (opts.animation === 'fade') {
				// Fade animation.
				opts.$nav.css({ 'display': 'none', 'position': 'fixed', 'top': '0', 'left': '0', 'zIndex': '666' }).stop().fadeIn(opts.duration);
			} else if (opts.animation === 'shrink') {
				// Shrink animation.
				opts.$nav.css({ 'position': 'fixed', 'top': '0', 'left': '0', 'zIndex': '666' });
				if (opts.animation === 'shrink') opts.nav.classList.add('wpbf-navigation-shrink');
			} else {
				// No animation.
				opts.$nav.css({ 'position': 'fixed', 'top': '0', 'left': '0', 'zIndex': '666' });
				if (opts.animation === 'scroll') opts.nav.classList.add('wpbf-navigation-animate');
			}

			// Apply top margin to page header if transparent header is not enabled, prevents jumpy behaviour.
			if (!document.body.classList.contains('wpbf-transparent-header')) {
				$('.wpbf-page-header').css('marginTop', navHeight);
			}

			// Apply sticky navigation logos for desktop and mobiles if set.
			if (data.menuActiveLogoUrl) {
				$('.wpbf-logo img').attr('src', data.menuActiveLogoUrl);
				$('.wpbf-mobile-logo img').attr('src', data.menuActiveLogoUrl);
			}

			state.isFired = 1;
		} else if (scrollTop < opts.distance && state.isFired) {

			opts.nav.classList.remove('wpbf-navigation-active');
			opts.nav.classList.remove('wpbf-navigation-animate');
			opts.nav.classList.remove('wpbf-navigation-shrink');

			// Reset.
			if (!$('body').hasClass('wpbf-transparent-header')) {

				opts.$nav.css({ 'position': '', 'top': '', 'left': '', 'zIndex': '' });
				$('.wpbf-page-header').css('marginTop', '');

			} else {

				opts.$nav.css({ 'position': 'absolute', 'top': '', 'left': '', 'zIndex': '' });

			}

			if (data.menuActiveLogoUrl) {
				$(".wpbf-logo img").attr("src", data.menuLogoUrl);
				$(".wpbf-mobile-logo img").attr("src", data.mobileMenuLogoUrl);
			}

			state.isFired = 0;

		}

	};

	/**
	 * Hide on scroll.
	 */
	function hideOnScroll() {
		var scrollTop = $(window).scrollTop();
		var navHeight = opts.$nav.outerHeight();

		if (scrollTop > state.prevScrollTop && scrollTop > navHeight) {

			// Scroll down.
			$('.wpbf-navigation').css({ 'top': -navHeight });
			$('.wpbf-navigation').removeClass('wpbf-navigation-scroll-up').addClass('wpbf-navigation-scroll-down');

		} else {

			// Scroll up.
			if (scrollTop + $(window).height() < $(document).height()) {
				$('.wpbf-navigation').css({ 'top': '0px' });
				$('.wpbf-navigation').removeClass('wpbf-navigation-scroll-down').addClass('wpbf-navigation-scroll-up');
			}

		}

		state.prevScrollTop = scrollTop;
	}

	init();

	return {
		opts: opts,
		state: state,
		data: data
	};
})(jQuery);