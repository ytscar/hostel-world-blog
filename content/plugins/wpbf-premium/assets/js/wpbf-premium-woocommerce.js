(function($) {

	// We will have to move everything related to the menu item out of here
	// as - with Elementor and other page builders - the chance exists that people drop products on non WooCommerce archives.
	// That means, this needs to function everywhere.
	// We've already added the styles from the wpbf-premium-woocommerce.css file to wpbf-premium.css.
	// Ideally we might create another stylesheet & js file for those styles & scripts to only load those if WooCommerce is active
	// and still load them, if WooCommerce scripts & styles are deactivated.

	var duration = $(".wpbf-navigation").data('sub-menu-animation-duration');
	var closeCartTimeoutId = null;
	var $elms = {};
	var states = {};

	// Init.
	function init() {
		setupElements();
		setupEvents();
	}

	/**
	 * Check if current add to cart action is a single-product page add to cart (the ajax add to cart in single-product page).
	 * 
	 * @returns bool
	 */
	function isSinlgeProductPageAddToCart() {
		return document.querySelector('.single_add_to_cart_button') && !document.querySelector('#wpbf-woo-quick-view-content .single_add_to_cart_button');
	}

	// Setup elements.
	function setupElements() {
		$elms.cartPopupOverlay = $('.wpbf-woo-menu-item-popup-overlay');
	}

	// Setup events.
	function setupEvents() {

		$(document.body).on('added_to_cart', function () {
			if (isSinlgeProductPageAddToCart()) {
				waitForFragmentRefreshed();
			} else {
				addToCartPopup();
			}
		});

		$(document).on('mouseenter', '.wpbf-woo-menu-item-popup', function() {
			// only continue if the popup is opened via "added_to_cart" event
			if (!states.overlayOpened) return;
			clearTimeout(closeCartTimeoutId);
		});

		$(document).on('mouseleave', '.wpbf-woo-menu-item-popup', function() {
			closeCartOverlay();
		});

	}

	function waitForFragmentRefreshed() {
		$(document.body).on('wc_fragments_refreshed', addToCartPopup);
	}

	// Add to cart popup.
	function addToCartPopup() {

		$(document.body).off('wc_fragments_refreshed', addToCartPopup);

		setTimeout(function() {
			openCartPopup();
		}, 250);

		closeCartTimeoutId = setTimeout(function() {
			closeCartPopup();
		}, 4000);

	}

	// Open cart popup.
	function openCartPopup() {
		$('.wpbf-navigation').addClass('wpbf-cart-popup-opened');
		$('.wpbf-woo-menu-item.wpbf-woo-menu-item-popup .wpbf-woo-sub-menu').fadeIn(duration);

		$elms.cartPopupOverlay.fadeIn(duration, function() {
			states.overlayOpened = true;
		});
	}

	// Close cart popup.
	function closeCartPopup() {
		$('.wpbf-navigation').removeClass('wpbf-cart-popup-opened');
		$('.wpbf-woo-menu-item.wpbf-woo-menu-item-popup .wpbf-woo-sub-menu').fadeOut(duration);
		closeCartOverlay();
	}

	// Close cart overlay.
	function closeCartOverlay() {
		if (!states.overlayOpened) return;
		$elms.cartPopupOverlay.fadeOut(duration, function() {
			states.overlayOpened = false;
		});
	}

	// Open off canvas sidebar.
	function openOffCanvasSidebar() {
		$('.wpbf-woo-off-canvas-sidebar').addClass('active');
		$('.wpbf-woo-off-canvas-sidebar-overlay').fadeIn(300);
	}

	// Close off canvas sidebar.
	function closeOffCanvasSidebar() {
		$('.wpbf-woo-off-canvas-sidebar').removeClass('active');
		$('.wpbf-woo-off-canvas-sidebar-overlay').fadeOut(300);
	}

	// Trigger openOffCanvasSidebar().
	$(document).on('click', '.wpbf-woo-off-canvas-sidebar-button', function() {
		openOffCanvasSidebar();
	});

	// Trigger closeOffCanvasSidebar().
	$(document).on('click', '.wpbf-woo-off-canvas-sidebar-overlay, .wpbf-woo-off-canvas-sidebar .wpbf-close', function() {
		closeOffCanvasSidebar();
	});

	// Trigger closeOffCanvasSidebar() on escape key.
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			if ($('.wpbf-woo-off-canvas-sidebar-overlay').is(':visible')) {
				closeOffCanvasSidebar();
			}
		}
	});

	// Remove item from WooCommerce cart menu item.
	$(document).on('click', '.wpbf-woo-sub-menu-remove', function(e) {
		e.preventDefault();

		var link = $(this).attr('href');
		jQuery.post(link, function(results) {
			$('.wpbf-woo-sub-menu').animate({ opacity: 0 }, duration );
			$(document.body).trigger('wc_fragment_refresh');
		});
	})

	init();

})(jQuery);
