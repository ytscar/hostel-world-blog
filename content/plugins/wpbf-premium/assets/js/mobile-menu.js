/**
 * This module is intended to handle the mobile menu JS functionality.
 *
 * Along with the site.js and desktop-menu.js, this file will be combined to site-min.js file.
 * 
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
WpbfPremium.mobileMenu = (function ($) {

	/**
	 * Defined breakpoints.
	 *
	 * @var Object
	 */
	var breakpoints = WpbfPremium.site.breakpoints;

	// Run the module.
	init();

	/**
	 * Call main functions.
	 */
	function init() {

		setupHashLinkBehavior();
		setupMobileMenuToggle();
		setupMobileMenuClose();
		setupMobileSubmenuToggle();
		stopPropagation();

	}

	/**
	 * Check if a mobile menu type is enabled.
	 * 
	 * @param {string} menuType The menu type. Accepts 'mobile-off-canvas'.
	 */
	function isMobileMenuEnabled(menuType) {

		switch (menuType) {
			case 'mobile-off-canvas':
				return (document.querySelector('.wpbf-mobile-menu-off-canvas') ? true : false);

			default:
				return false;
		}

	}

	/**
	 * Setup behavior when clicking links that contain a hash.
	 */
	function setupHashLinkBehavior() {
		
		/**
		 * On mobile menu item hash link click,
		 * it will either close the mobile menu, or open the submenu if exists.
		 * 
		 * This is for 'mobile-off-canvas' menu.
		 */
		$(document).on('click', '.wpbf-mobile-menu a', function () {
			// Stop if this is not a 'mobile-off-canvas' menu.
			if (!isMobileMenuEnabled('mobile-off-canvas')) return;

			// Stop if href doesn't contain hash.
			if (!this.href.match("#") && !this.href.match("/#")) return;

			var hasSubMenu = this.parentNode.classList.contains('menu-item-has-children');

			// If the link doesn't have sub-menu, then simply close the mobile menu.
			if (!hasSubMenu) {
				closeMobileMenu('mobile-off-canvas');
			} else {
				if ($(this).closest('.wpbf-mobile-mega-menu').length) {
					// But the link has sub-menu, and its top level parent menu item is a mega menu, then close the mobile menu.
					closeMobileMenu('mobile-off-canvas');
				} else {
					// And if its top level parent menu item is not a mega menu, then toggle it's sub-menu.
					toggleMobileSubmenuOnHashLinkClick(this);
				}
			}
		});

	}

	/**
	 * Setup mobile menu toggle for 'mobile-off-canvas' menu.
	 */
	function setupMobileMenuToggle() {

		$(document).on('click', '.wpbf-mobile-menu-toggle', function () {
			if (isMobileMenuEnabled('mobile-off-canvas')) {
				toggleMobileMenu('mobile-off-canvas');
			}
		});

	}

	/**
	 * Setup mobile menu closing.
	 */
	function setupMobileMenuClose() {

		$(document).on('click', '.wpbf-mobile-menu-off-canvas .wpbf-close', function () {
			toggleMobileMenu('mobile-off-canvas');
		});

		window.addEventListener('click', function () {
			if (isMobileMenuEnabled('mobile-off-canvas')) closeMobileMenu('mobile-off-canvas');
		});

		/**
		 * Close the mobile menu by pressing the Esc key.
		 * This is applied to 'mobile-off-canvas' menu.
		 */
		$(document).on('keyup', function (e) {
			if (e.key !== 'Escape' && e.key !== 'Esc' && e.keyCode !== 27) return;

			if (isMobileMenuEnabled('mobile-off-canvas')) {
				closeMobileMenu('mobile-off-canvas');
			}
		});

		// On window resize, close the mobile menu if window width is wider than desktop breakpoint.
		$(window).on('resize', function () {
			var windowWidth = $(window).width();

			if (isMobileMenuEnabled('mobile-off-canvas')) {
				if (windowWidth > breakpoints.desktop) {
					closeMobileMenu('mobile-off-canvas');
				}
			}
		});

	}

	/**
	 * Toggle the mobile menu to open or close.
	 * This will only run for 'mobile-off-canvas' menu.
	 *
	 * @param {string} menuType The menu type. Accepts 'mobile-off-canvas'.
	 */
	function toggleMobileMenu(menuType) {

		// Toggle here is the mobile menu toggle button.
		var toggle = document.querySelector('.wpbf-mobile-menu-toggle');
		if (!toggle) return;

		if (toggle.classList.contains('active')) {
			closeMobileMenu(menuType);
		} else {
			openMobileMenu(menuType);
		}

	}

	/**
	 * Open the mobile menu.
	 *
	 * Used in 'mobile-off-canvas' menu.
	 * This function will only be called in `toggleMobileMenu` function.
	 *
	 * @param {string} menuType The menu type. Accepts 'mobile-off-canvas'.
	 */
	function openMobileMenu(menuType) {

		// Toggle here is the mobile menu toggle button.
		var toggle = document.querySelector('.wpbf-mobile-menu-toggle');
		if (!toggle) return;

		toggle.classList.add("active");
		toggle.setAttribute('aria-expanded', 'true');

		if ('mobile-off-canvas' === menuType) {
			document.body.classList.add('active-mobile');
			$('.wpbf-mobile-menu-container').addClass('active');
			$('.wpbf-mobile-menu-overlay').css({ display: 'block' }).stop().animate({ opacity: '1' }, 300);
		}

	}

	/**
	 * Close the mobile menu.
	 *
	 * Used in 'mobile-off-canvas' menu.
	 * This function will only be called in `toggleMobileMenu` function and in several direct calls.
	 *
	 * @param {string} type The menu type. Accepts 'mobile-off-canvas'.
	 */
	function closeMobileMenu(type) {

		// Toggle here is the mobile menu toggle button.
		var toggle = document.querySelector('.wpbf-mobile-menu-toggle');
		if (!toggle) return;

		if ('mobile-off-canvas' === type) {
			if (!toggle.classList.contains('active')) return;

			document.body.classList.remove('active-mobile');
			toggle.classList.remove("active");
			toggle.setAttribute('aria-expanded', 'false');
			$('.wpbf-mobile-menu-container').removeClass('active');

			$('.wpbf-mobile-menu-overlay').stop().animate({ opacity: '0' }, 300, function () {
				this.style.display = 'none';
			});
		}

	}

	/**
	 * Setup mobile submenu toggle for 'mobile-off-canvas' menu.
	 */
	function setupMobileSubmenuToggle() {

		$(document).on('click', '.wpbf-mobile-menu-off-canvas .wpbf-submenu-toggle', function (e) {
			e.preventDefault();
			toggleMobileSubmenu(this);
		});

	}

	/**
	 * Toggle mobile submenu to expand or collapse.
	 * This will only run for 'mobile-off-canvas' menu.
	 * 
	 * @param {HTMLElement} toggle The submenu's toggle button (the expand/collapse arrow).
	 */
	function toggleMobileSubmenu(toggle) {

		if (toggle.classList.contains("active")) {
			closeMobileSubmenu(toggle);
		} else {
			openMobileSubmenu(toggle);
		}

	}

	/**
	 * Open submenu.
	 * Used in mobile-off-canvas.
	 *
	 * @param {HTMLElement} toggle The mobile submenu's toggle button.
	 */
	function openMobileSubmenu(toggle) {

		$('i', toggle).removeClass('wpbff-arrow-down').addClass('wpbff-arrow-up');
		toggle.classList.add('active')
		toggle.setAttribute('aria-expanded', 'true');
		$(toggle).siblings('.sub-menu').stop().slideDown();

		if (!$(toggle).closest('.wpbf-navigation').hasClass('wpbf-mobile-sub-menu-auto-collapse')) return;

		var $sameLevelItems = $(toggle).closest('.menu-item-has-children').siblings('.menu-item-has-children');

		$sameLevelItems.each(function (i, menuItem) {
			closeMobileSubmenu(menuItem.querySelector('.wpbf-submenu-toggle'));
		});

	}

	/**
	 * Close submenu.
	 * Used in mobile-off-canvas.
	 *
	 * @param {HTMLElement} toggle The mobile submenu's toggle button.
	 */
	function closeMobileSubmenu(toggle) {

		$('i', toggle).removeClass('wpbff-arrow-up').addClass('wpbff-arrow-down');
		toggle.classList.remove('active')
		toggle.setAttribute('aria-expanded', 'false');
		$(toggle).siblings('.sub-menu').stop().slideUp();

	}

	/**
	 * Toggle submenu on hash link click.
	 * Used in 'mobile-off-canvas' menu.
	 * 
	 * @param {HTMLElement} link The anchor element of a menu item.
	 */
	function toggleMobileSubmenuOnHashLinkClick(link) {

		var toggle = $(link).siblings('.wpbf-submenu-toggle');
		if (!toggle.length) return;
		toggle = toggle[0];

		if (toggle.classList.contains("active")) {
			closeMobileSubmenu(toggle);
		} else {
			openMobileSubmenu(toggle);
		}

	}

	/**
	 * Stop event propagation.
	 */
	function stopPropagation() {

		$(document).on('click', '.wpbf-mobile-menu-container, .wpbf-mobile-menu-toggle', function (e) {
			e.stopPropagation();
		});

	}

})(jQuery);
