(function($) {

	function init() {
		setupEddMenu();
	}

	/**
	 * Setup EDD menu.
	 */
	function setupEddMenu() {

		$(document).on({
			mouseenter: function () {
				$(this).find('.wpbf-edd-sub-menu').stop().fadeIn(duration);
			},
			mouseleave: function () {
				$(this).find('.wpbf-edd-sub-menu').stop().fadeOut(duration);
			}
		}, '.wpbf-edd-menu-item.menu-item-has-children');

	}

	var duration = $(".wpbf-navigation").data('sub-menu-animation-duration');

	init();

})(jQuery);
