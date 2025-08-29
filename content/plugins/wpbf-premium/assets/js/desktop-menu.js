/**
 * This module is intended to handle the desktop menu JS functionality.
 *
 * Along with the site.js and mobile-menu.js, this file will be combined to site-min.js file.
 * 
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
WpbfPremium.desktopMenu = (function ($) {

	/**
	 * Whether we're inside customizer or not.
	 * 
	 * @var bool
	 */
	var isInsideCustomizer = WpbfPremium.isInsideCustomizer;

	/**
	 * Defined breakpoints.
	 *
	 * @var Object
	 */
	var breakpoints = WpbfPremium.site.breakpoints;

	/**
	 * The sub-menu animation duration.
	 *
	 * @var int
	 */
	var duration = parseInt($(".wpbf-navigation").data("sub-menu-animation-duration"), 10);

	// Run the module.
	init();

	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {

		setupHashLinkBehavior();
		setupMenuToggle();
		setupMenuClose();
		setupMegaMenu();
		setupSubmenuToggle();
		setupSubmenuAnimations();
		setupWooMenu();
		setupTransparentHeader();
		stopPropagation();

		// If we're inside customizer, then listen to the customizer's partial refresh.
		if (isInsideCustomizer) {
			wp.customize.bind("preview-ready", function () {
				listenPartialRefresh();
			});
		}

	}

	/**
	 * Listen to WordPress selective refresh (partial refresh) inside customizer.
	 */
	function listenPartialRefresh() {

		wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
			/**
			 * A lot of partial refresh registered to work on header area.
			 * Better to not checking the "placement.partial.id".
			 */
			duration = parseInt($(".wpbf-navigation").data("sub-menu-animation-duration"), 10);
		});

	}

	/**
	 * Setup behavior when clicking links that contain a hash.
	 */
	function setupHashLinkBehavior() {

		/**
		 * On 'full-screen' menu item hash link click, close the menu.
		 * The 'full-screen' menu doesn't have submenu - it's only 1 level deep.
		 */
		$(document).on('click', '.wpbf-menu-full-screen a', function () {
			// Stop if this is not a 'full-screen' menu.
			if (!isMenuEnabled('full-screen')) return;

			// Stop if href doesn't contain hash.
			if (!this.href.match("#") && !this.href.match("/#")) return;

			closeMenu('full-screen');
		});

		/**
		 * On 'off-canvas' menu item hash link click,
		 * it will either close the menu, or open the submenu if exists.
		 */
		$(document).on('click', '.wpbf-menu-off-canvas a', function () {
			// Stop if this is not a 'off-canvas' menu.
			if (!isMenuEnabled('off-canvas')) return;

			// Stop if href doesn't contain hash.
			if (!this.href.match("#") && !this.href.match("/#")) return;

			var hasSubMenu = this.parentNode.classList.contains('menu-item-has-children');

			// If the link doesn't have sub-menu, then simply close the menu.
			if (!hasSubMenu) {
				closeMenu('off-canvas');
			} else {
				if ($(this).closest('.wpbf-mega-menu').length) {
					// But the link has sub-menu, and its top level parent menu item is a mega menu, then close the menu.
					closeMenu('off-canvas');
				} else {
					// And if its top level parent menu item is not a mega menu, then toggle it's sub-menu.
					toggleSubmenuOnHashLinkClick(this);
				}
			}

		});

	}

	/**
	 * Toggle submenu on hash link click.
	 * Used in 'off-canvas' menu.
	 * 
	 * @param {HTMLElement} link The anchor element of a menu item.
	 */
	function toggleSubmenuOnHashLinkClick(link) {

		var toggle = $(link).siblings('.wpbf-submenu-toggle');
		if (!toggle.length) return;
		toggle = toggle[0];

		if (toggle.classList.contains("active")) {
			closeSubmenu(toggle);
		} else {
			openSubmenu(toggle);
		}

	}

	/**
	 * Setup menu toggle for 'full-screen' and 'off-canvas' menu.
	 */
	function setupMenuToggle() {

		$(document).on('click', '.wpbf-menu-toggle', function () {
			if (isMenuEnabled('full-screen')) {
				toggleMenu('full-screen');
			} else if (isMenuEnabled('off-canvas')) {
				toggleMenu('off-canvas');
			}
		});

	}

	/**
	 * Check if a menu type is enabled.
	 * 
	 * @param {string} menuType The menu type. Accepts 'full-screen' or 'off-canvas'.
	 */
	function isMenuEnabled(menuType) {

		switch (menuType) {
			case 'full-screen':
				return (document.querySelector('.wpbf-menu-full-screen') ? true : false);

			case 'off-canvas':
				return (document.querySelector('.wpbf-menu-off-canvas') ? true : false);

			default:
				return false;
		}

	}

	/**
	 * Toggle the menu to open or close.
	 * This will only run for 'full-screen' or 'off-canvas' menu.
	 *
	 * @param {string} menuType The menu type. Accepts 'full-screen' or 'off-canvas'.
	 */
	function toggleMenu(menuType) {

		var menu;

		if ('full-screen' === menuType) {
			menu = document.querySelector('.wpbf-menu-full-screen');

			if (menu.classList.contains('active')) {
				closeMenu(menuType, menu);
			} else {
				openMenu(menuType, menu);
			}
		} else if ('off-canvas' === menuType) {
			menu = document.querySelector('.wpbf-menu-off-canvas');

			if (menu.classList.contains("active")) {
				closeMenu(menuType, menu);
			} else {
				openMenu(menuType, menu);
			}
		}

	}

	/**
	 * Open the menu.
	 *
	 * Used in 'full-screen' and 'off-canvas' menu.
	 * This function will only be called in `toggleMenu` function.
	 *
	 * @param {string} menuType The menu type. Accepts 'full-screen' or 'off-canvas'.
	 * @param {HTMLElement} menu The menu which is being opened.
	 */
	function openMenu(menuType, menu) {

		var toggle = document.querySelector('.wpbf-menu-toggle');

		toggle.classList.add("active");
		toggle.setAttribute('aria-expanded', 'true');
		menu.classList.add('active');

		if ('full-screen' === menuType) {
			$(menu).stop().fadeIn(150);
		} else if ('off-canvas' === menuType) {
			menu.setAttribute('tabindex', '-1');
			menu.focus();
			document.body.classList.add('active');
			$('.wpbf-menu-overlay').stop().css({ display: 'block' }).animate({ opacity: '1' }, 300);
		}

	}

	/**
	 * Close the menu.
	 *
	 * Used in 'full-screen' and 'off-canvas' menu.
	 * This function will be called in `toggleMenu` function and in `setupMenuToggle` function.
	 *
	 * @param {string} menuType The menu type. Accepts 'full-screen' or 'off-canvas'.
	 * @param {HTMLElement} menu The menu which is being closed.
	 */
	function closeMenu(menuType, menu) {

		if (!menu) {
			if ('full-screen' === menuType) {
				menu = document.querySelector('.wpbf-menu-full-screen');
			} else if ('off-canvas' === menuType) {
				menu = document.querySelector('.wpbf-menu-off-canvas');
			}
		}

		var toggle = document.querySelector('.wpbf-menu-toggle');

		toggle.classList.remove("active");
		toggle.setAttribute('aria-expanded', 'false');
		menu.classList.remove('active');

		if ('full-screen' === menuType) {
			$(menu).stop().fadeOut(150);
		} else if ('off-canvas' === menuType) {
			document.body.classList.remove('active');
			$('.wpbf-menu-overlay').stop().animate({ opacity: '0' }, 300, function () {
				this.style.display = 'none';
			});
		}

	}

	/**
	 * Setup menu closing.
	 */
	function setupMenuClose() {

		// Close 'full-screen' menu by clicking the close button.
		$(document).on('click', '.wpbf-menu-full-screen .wpbf-close', function () {
			closeMenu('full-screen');
		});

		// Close 'full-screen' menu on overlay click.
		$(document).on('click', '.wpbf-menu-full-screen', function () {
			closeMenu('full-screen');
		});
		// Prevent 'full-screen' menu from closing if we're clicking on child-elements.
		$(document).on('click', '.wpbf-menu-full-screen.active nav', function (e) {
			e.stopPropagation();
		});

		// Close 'off-canvas' menu by clicking the close button.
		$(document).on('click', '.wpbf-menu-off-canvas .wpbf-close', function () {
			toggleMenu('off-canvas');
		});

		// Close 'off-canvas' menu by clicking the window.
		window.addEventListener('click', function () {
			if (isMenuEnabled('off-canvas')) closeMenu('off-canvas');
		});

		/**
		 * Close the menu by pressing the Esc key.
		 * This is applied to 'full-screen' and 'off-canvas' menu.
		 */
		$(document).on('keyup', function (e) {
			if (e.key !== 'Escape' && e.key !== 'Esc' && e.keyCode !== 27) return;

			if (isMenuEnabled('full-screen')) {
				closeMenu('full-screen');
			} else if (isMenuEnabled('off-canvas')) {
				closeMenu('off-canvas');
			}
		});

		// On window resize, close the off-canvas menu if window width is narrower than desktop breakpoint.
		$(window).on('resize', function () {
			var windowWidth = $(window).width();

			if (isMenuEnabled('off-canvas')) {
				if (windowWidth < breakpoints.desktop) {
					closeMenu('off-canvas');
				}
			}
		});

	}

	/**
	 * Setup mega menu.
	 */
	function setupMegaMenu() {

		// Prevent click on headlines.
		$(document).on('click', '.wpbf-mega-menu > .sub-menu > .menu-item a[href="#"]', function (e) {
			e.preventDefault();
		});

		// On window resize, manage mega menu classes based on window width.
		$(window).on('resize', function () {
			var windowWidth = $(window).width();
			var $megaMenu;

			if (windowWidth > breakpoints.desktop) {
				$megaMenu = $('.wpbf-mobile-mega-menu');

				if ($megaMenu.length) {
					$megaMenu.removeClass('wpbf-mobile-mega-menu').addClass('wpbf-mega-menu');

					if ($megaMenu.hasClass('wpbf-mobile-mega-menu-container-width')) {
						$megaMenu.removeClass('wpbf-mobile-mega-menu-container-width').addClass('wpbf-mega-menu-container-width');
					}

					if ($megaMenu.hasClass('wpbf-mobile-mega-menu-full-width')) {
						$megaMenu.removeClass('wpbf-mobile-mega-menu-full-width').addClass('wpbf-mega-menu-full-width');
					}

					if ($megaMenu.hasClass('wpbf-mobile-mega-menu-custom-width')) {
						$megaMenu.removeClass('wpbf-mobile-mega-menu-custom-width').addClass('wpbf-mega-menu-custom-width');
					}
				}
			} else {
				$megaMenu = $('.wpbf-mega-menu');

				if ($megaMenu.length) {
					$megaMenu.removeClass('wpbf-mega-menu').addClass('wpbf-mobile-mega-menu');

					if ($megaMenu.hasClass('wpbf-mega-menu-container-width')) {
						$megaMenu.removeClass('wpbf-mega-menu-container-width').addClass('wpbf-mobile-mega-menu-container-width');
					}

					if ($megaMenu.hasClass('wpbf-mega-menu-full-width')) {
						$megaMenu.removeClass('wpbf-mega-menu-full-width').addClass('wpbf-mobile-mega-menu-full-width');
					}

					if ($megaMenu.hasClass('wpbf-mega-menu-custom-width')) {
						$megaMenu.removeClass('wpbf-mega-menu-custom-width').addClass('wpbf-mobile-mega-menu-custom-width');
					}
				}
			}
		});

	}

	/**
	 * Setup submenu toggle for 'off-canvas' menu.
	 */
	function setupSubmenuToggle() {

		$(document).on('click', '.wpbf-menu-off-canvas .wpbf-submenu-toggle', function (e) {
			e.preventDefault();

			if (this.classList.contains("active")) {
				closeSubmenu(this);
			} else {
				openSubmenu(this);
			}
		});

	}

	/**
	 * Open the submenu.
	 * Applied to 'off-canvas' menu.
	 *
	 * @param {HTMLElement} toggle The submenu's toggle button (the expand/collapse arrow).
	 */
	function openSubmenu(toggle) {

		$('i', toggle).removeClass('wpbff-arrow-down').addClass('wpbff-arrow-up');
		$(toggle).addClass('active').attr('aria-expanded', 'true').siblings('.sub-menu').stop().slideDown();

	}

	/**
	 * Close the submenu.
	 * Applied to 'off-canvas' menu.
	 *
	 * @param {HTMLElement} toggle The submenu's toggle button (the expand/collapse arrow).
	 */
	function closeSubmenu(toggle) {

		$('i', toggle).removeClass('wpbff-arrow-up').addClass('wpbff-arrow-down');
		$(toggle).removeClass('active').attr('aria-expanded', 'false').siblings('.sub-menu').stop().slideUp();

	}

	/**
	 * Setup submenu animations.
	 */
	function setupSubmenuAnimations() {

		setupDownAnim();
		setupUpAnim();
		setupZoomInAnim();
		setupZoomOutAnim();

	}

	/**
	 * Setup WooCommerce menu.
	 */
	function setupWooMenu() {

		$(document).on({
			mouseenter: function () {
				$(this).find('.wpbf-woo-sub-menu').stop().fadeIn(duration);
			},
			mouseleave: function () {
				$(this).find('.wpbf-woo-sub-menu').stop().fadeOut(duration);
			}
		}, '.wpbf-woo-menu-item.menu-item-has-children');

	}

	/**
	 * Setup submenu's down animation.
	 */
	function setupDownAnim() {
		var selector = '.wpbf-sub-menu-animation-down > .menu-item-has-children';

		$(document)
			.on('mouseenter', selector, function () {
				$('.sub-menu', this).first().css({ display: 'block' }).stop().animate({ marginTop: '0', opacity: '1' }, duration);
			})
			.on('mouseleave', selector, function () {
				$('.sub-menu', this).first().stop().animate({ opacity: '0', marginTop: '-10px' }, duration, function () {
					this.style.display = 'none';
				});
			});
	}

	/**
	 * Setup submenu's up animation.
	 */
	function setupUpAnim() {
		var selector = '.wpbf-sub-menu-animation-up > .menu-item-has-children';

		$(document)
			.on('mouseenter', selector, function () {
				$('.sub-menu', this).first().css({ display: 'block' }).stop().animate({ marginTop: '0', opacity: '1' }, duration);
			})
			.on('mouseleave', selector, function () {
				$('.sub-menu', this).first().stop().animate({ opacity: '0', marginTop: '10px' }, duration, function () {
					this.style.display = 'none';
				});
			});
	}

	/**
	 * Setup submenu's zoom in animation.
	 */
	function setupZoomInAnim() {
		var selector = '.wpbf-sub-menu-animation-zoom-in > .menu-item-has-children';

		$(document)
			.on('mouseenter', selector, function () {
				$('.sub-menu', this).first().stop(true).css({ display: 'block' }).transition({ scale: '1', opacity: '1' }, duration);
			})
			.on('mouseleave', selector, function () {
				$('.sub-menu', this).first().stop(true).transition({ scale: '.95', opacity: '0' }, duration).fadeOut(5);
			});
	}

	/**
	 * Setup submenu's zoom out animation.
	 */
	function setupZoomOutAnim() {
		var selector = '.wpbf-sub-menu-animation-zoom-out > .menu-item-has-children';

		$(document)
			.on('mouseenter', selector, function () {
				$('.sub-menu', this).first().stop(true).css({ display: 'block' }).transition({ scale: '1', opacity: '1' }, duration);
			})
			.on('mouseleave', selector, function () {
				$('.sub-menu', this).first().stop(true).transition({ scale: '1.05', opacity: '0' }, duration).fadeOut(5);
			});
	}

	/**
	 * Setup transparent header.
	 */
	function setupTransparentHeader() {

		$(window).on('resize', function () {
			var windowWidth = $(window).width();

			if (isTransparentHeaderDisabled('mobile')) {
				// var mobileLogo = $('.wpbf-logo').data("menu-mobile-logo");
				// var transparentLogo = $('.wpbf-logo').data("menu-transparent-logo");

				// Remove transparent header related classes on mobile and tablet.
				if (windowWidth <= breakpoints.desktop) {
					$('body').removeClass('wpbf-transparent-header');
					$('.wpbf-navigation').removeClass('wpbf-navigation-transparent');
					// $('.wpbf-mobile-logo img').attr('src', mobileLogo);
				} else {
					$('body').addClass('wpbf-transparent-header');
					$('.wpbf-navigation').addClass('wpbf-navigation-transparent');
					// $('.wpbf-mobile-logo img').attr('src', transparentLogo);
				}
			}
		});

	}

	/**
	 * Check if a transparent header is disabled on specific device.
	 * 
	 * @param {string} device The device. Accepts 'desktop', 'tablet', or 'mobile'.
	 */
	function isTransparentHeaderDisabled(device) {

		return (document.querySelector('[data-transparent-header-disabled="' + device + '"]') ? true : false);

	}

	/**
	 * Stop event propagation.
	 */
	function stopPropagation() {

		$(document).on('click', '.wpbf-menu-off-canvas, .wpbf-menu-toggle', function (e) {
			e.stopPropagation();
		});

	}

})(jQuery);
