( function( $ ) {

	var customizeBreakpoints = {
		desktop: 1024,
		tablet: 768,
		mobile: 480
	};

	var mediaQueries = {
		tablet: 'max-width: ' + (customizeBreakpoints.desktop - 1).toString() + 'px',
		mobile: 'max-width: ' + (customizeBreakpoints.tablet - 1).toString() + 'px'
	};

	/**
	 * Setup style tag.
	 *
	 * @param {string} id The style data id.
	 * @return {HTMLElement} The style tag.
	 */
	function setupStyleTag(id) {
		var tag = document.createElement('style');
		tag.dataset.id = id;
		tag.className = 'wpbf-customize-live-style';

		document.head.append(tag);
		return tag;
	}

	/* Theme colors */

	// Base color alt.
	wp.customize( 'base_color_global', function( value ) {
		var styleTag = setupStyleTag('base_color_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--base-color-alt: ' + newval + ';}';
		} );
	} );

	// Base color.
	wp.customize( 'base_color_alt_global', function( value ) {
		var styleTag = setupStyleTag('base_color_alt_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--base-color: ' + newval + ';}';
		} );
	} );

	// Brand color.
	wp.customize( 'brand_color_global', function( value ) {
		var styleTag = setupStyleTag('brand_color_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--brand-color: ' + newval + ';}';
		} );
	} );

	// Brand color alt.
	wp.customize( 'brand_color_alt_global', function( value ) {
		var styleTag = setupStyleTag('brand_color_alt_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--brand-color-alt: ' + newval + ';}';
		} );
	} );

	// Accent color.
	wp.customize( 'accent_color_global', function( value ) {
		var styleTag = setupStyleTag('accent_color_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--accent-color: ' + newval + ';}';
		} );
	} );

	// Accent color alt.
	wp.customize( 'accent_color_alt_global', function( value ) {
		var styleTag = setupStyleTag('accent_color_alt_global');

		value.bind( function( newval ) {
			styleTag.innerHTML = ':root {--accent-color-alt: ' + newval + ';}';
		} );
	} );

	/* Social */

	// Background color.
	wp.customize( 'social_background_color', function( value ) {
		var styleTag = setupStyleTag('social_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-social-icons a {background-color: ' + newval + ';}';
		} );
	} );

	// Icon color.
	wp.customize( 'social_color', function( value ) {
		var styleTag = setupStyleTag('social_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-social-icons a {color: ' + newval + ';}';
		} );
	} );

	// Font size.
	wp.customize( 'social_font_size', function( value ) {
		var styleTag = setupStyleTag('social_font_size');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-social-icon {font-size: ' + newval + 'px;}';
		} );
	} );

	/* Text */

	// Font size.
	wp.customize( 'page_font_size', function( value ) {

		var styleTag = setupStyleTag('page_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				body {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					body {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					body {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Line height.
	wp.customize( 'page_line_height', function( value ) {
		var styleTag = setupStyleTag('page_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = '#content {line-height: ' + newval + ';}';
		} );
	} );
	
	// Bold color.
	wp.customize( 'page_bold_color', function( value ) {
		var styleTag = setupStyleTag('page_bold_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'b, strong {color: ' + newval + ';}';
		} );
	} );

	/* Menu */

	// Letter spacing.
	wp.customize( 'menu_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('menu_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu, .wpbf-mobile-menu {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* Sub Menu */

	// Letter spacing.
	wp.customize( 'sub_menu_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('sub_menu_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu .sub-menu, .wpbf-mobile-menu .sub-menu {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H1 */

	// Font size.
	wp.customize( 'page_h1_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h1_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h1 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h1 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h1 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Line height.
	wp.customize( 'page_h1_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h1_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h1, h2, h3, h4, h5, h6 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h1_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h1_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h1, h2, h3, h4, h5, h6 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H2 */

	// Font size.
	wp.customize( 'page_h2_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h2_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h2 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h2 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h2 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Color.
	wp.customize( 'page_h2_font_color', function( value ) {
		var styleTag = setupStyleTag('page_h2_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h2 {color: ' + newval + ';}';
		} );
	} );

	// Line height.
	wp.customize( 'page_h2_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h2_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h2 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h2_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h2_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h2 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H3 */

	// Font size.
	wp.customize( 'page_h3_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h3_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h3 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h3 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h3 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Color.
	wp.customize( 'page_h3_font_color', function( value ) {
		var styleTag = setupStyleTag('page_h3_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h3 {color: ' + newval + ';}';
		} );
	} );

	// Line height.
	wp.customize( 'page_h3_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h3_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h3 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h3_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h3_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h3 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H4 */

	// Font size.
	wp.customize( 'page_h4_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h4_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h4 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h4 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h4 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Color.
	wp.customize( 'page_h4_font_color', function( value ) {
		var styleTag = setupStyleTag('page_h4_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h4 {color: ' + newval + ';}';
		} );
	} );

	// Line height.
	wp.customize( 'page_h4_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h4_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h4 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h4_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h4_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h4 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H5 */

	// Font size.
	wp.customize( 'page_h5_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h5_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h5 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h5 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h5 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Color.
	wp.customize( 'page_h5_font_color', function( value ) {
		var styleTag = setupStyleTag('page_h5_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h5 {color: ' + newval + ';}';
		} );
	} );

	// Line height.
	wp.customize( 'page_h5_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h5_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h5 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h5_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h5_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h5 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* H6 */

	// Font size.
	wp.customize( 'page_h6_font_size', function( value ) {

		var styleTag = setupStyleTag('page_h6_font_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				h6 {font-size: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					h6 {font-size: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					h6 {font-size: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Color.
	wp.customize( 'page_h6_font_color', function( value ) {
		var styleTag = setupStyleTag('page_h6_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h6 {color: ' + newval + ';}';
		} );
	} );

	// Line height.
	wp.customize( 'page_h6_line_height', function( value ) {
		var styleTag = setupStyleTag('page_h6_line_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h6 {line-height: ' + newval + '!important;}';
		} );
	} );

	// Letter spacing.
	wp.customize( 'page_h6_letter_spacing', function( value ) {
		var styleTag = setupStyleTag('page_h6_letter_spacing');

		value.bind( function( newval ) {
			styleTag.innerHTML = 'h6 {letter-spacing: ' + newval + 'px;}';
		} );
	} );

	/* Navigation */

	// Stacked advanced background color.
	wp.customize( 'menu_stacked_bg_color', function( value ) {
		var styleTag = setupStyleTag('menu_stacked_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-stacked-advanced-wrapper {background-color: ' + newval + ';}';
		} );
	} );

	// Stacked advanced content.
	wp.customize( 'menu_stacked_wysiwyg', function( value ) {
		value.bind( function( newval ) {
			$('.wpbf-menu-stacked-advanced-wrapper .wpbf-3-4').html( newval );
		} );
	} );

	// Stacked advanced logo height.
	wp.customize( 'menu_stacked_logo_height', function( value ) {
		var styleTag = setupStyleTag('menu_stacked_logo_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-stacked-advanced-wrapper {padding-top: ' + newval + 'px; padding-bottom: ' + newval + 'px;}';
		} );
	} );

	// Off canvas toggle size.
	wp.customize( 'menu_off_canvas_hamburger_size', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_hamburger_size');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-toggle {font-size: ' + newval + ';}';
		} );
	} );

	// Off canvas push menu body classes.
	wp.customize( 'menu_position', function( value ) {
		value.bind( function( newval ) {

			var isPushMenu = wp.customize('menu_off_canvas_push').get();

			if ( ! isPushMenu ) return;

			if ( 'menu-off-canvas' === newval ) {
				$('body').addClass('wpbf-push-menu-right').removeClass('wpbf-push-menu-left');
			} else if ( 'menu-off-canvas-left' === newval ) {
				$('body').addClass('wpbf-push-menu-left').removeClass('wpbf-push-menu-right');
			} else {
				$('body').removeClass('wpbf-push-menu-right wpbf-push-menu-left');
			}

		} );
	} );

	// Off canvas push menu body classes.
	wp.customize( 'menu_off_canvas_push', function( value ) {
		value.bind( function( newval ) {

			var isOffCanvasMenu = wp.customize('menu_position').get();

			if ( 'menu-off-canvas' !== isOffCanvasMenu && 'menu-off-canvas-left' !== isOffCanvasMenu ) return;

			if ( newval && 'menu-off-canvas' === isOffCanvasMenu ) {
				$('body').addClass('wpbf-push-menu-right').removeClass('wpbf-push-menu-left');
			} else if ( newval && 'menu-off-canvas-left' === isOffCanvasMenu ) {
				$('body').addClass('wpbf-push-menu-left').removeClass('wpbf-push-menu-right');
			} else {
				$('body').removeClass('wpbf-push-menu-right wpbf-push-menu-left');
			}

		} );
	} );

	// Off canvas width.
	wp.customize( 'menu_position', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_width');

		value.bind( function( newval ) {

			if ( 'menu-off-canvas' !== newval && 'menu-off-canvas-left' !== newval ) return;

			var menuWidth = wp.customize('menu_off_canvas_width').get();

			if ( 'menu-off-canvas' === newval ) {

				styleTag.innerHTML = '\
					.wpbf-menu-off-canvas-right {width: ' + menuWidth + 'px; right: -' + menuWidth + 'px;}\
					.wpbf-push-menu-right.active {left: -' + menuWidth + 'px;}\
					.wpbf-push-menu-right.active .wpbf-navigation-active {left: -' + menuWidth + 'px !important;}\
				';

			} else if ( 'menu-off-canvas-left' === newval ) {

				styleTag.innerHTML = '\
					.wpbf-menu-off-canvas-left {width: ' + menuWidth + 'px; left: -' + menuWidth + 'px;}\
					.wpbf-push-menu-left.active {left: ' + menuWidth + 'px;}\
					.wpbf-push-menu-left.active .wpbf-navigation-active {left: ' + menuWidth + 'px !important;}\
				';

			}

		} );
	} );

	// Off canvas width.
	wp.customize( 'menu_off_canvas_width', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_width');

		value.bind( function( newval ) {

			var offCanvasMenu = wp.customize('menu_position').get();

			if ( 'menu-off-canvas' === offCanvasMenu ) {

				styleTag.innerHTML = '\
					.wpbf-menu-off-canvas-right {width: ' + newval + 'px; right: -' + newval + 'px;}\
					.wpbf-push-menu-right.active {left: -' + newval + 'px;}\
					.wpbf-push-menu-right.active .wpbf-navigation-active {left: -' + newval + 'px !important;}\
				';

			} else if ( 'menu-off-canvas-left' === offCanvasMenu ) {

				styleTag.innerHTML = '\
					.wpbf-menu-off-canvas-left {width: ' + newval + 'px; left: -' + newval + 'px;}\
					.wpbf-push-menu-left.active {left: ' + newval + 'px;}\
					.wpbf-push-menu-left.active .wpbf-navigation-active {left: ' + newval + 'px !important;}\
				';

			}

		} );
	} );

	// Off canvas hamburger icon color.
	wp.customize( 'menu_off_canvas_hamburger_color', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_hamburger_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-nav-item, .wpbf-nav-item a {color: ' + newval + ';}';
		} );
	} );

	// Off canvas background color.
	wp.customize( 'menu_off_canvas_bg_color', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-off-canvas, .wpbf-menu-full-screen {background-color: ' + newval + ';}';
		} );
	} );

	// Off canvas sub menu arrow color.
	wp.customize( 'menu_off_canvas_submenu_arrow_color', function( value ) {
		var styleTag = setupStyleTag('menu_off_canvas_submenu_arrow_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-off-canvas .wpbf-submenu-toggle {color: ' + newval + ';}';
		} );
	} );

	// Off canvas overlay color.
	wp.customize( 'menu_overlay_color', function( value ) {
		var styleTag = setupStyleTag('menu_overlay_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-overlay {background-color: ' + newval + ';}';
		} );
	} );

	/* Transparent Header */

	// Width.
	wp.customize('menu_transparent_width', function (value) {
		var styleTag = setupStyleTag('menu_transparent_width');

		value.bind(function (newval) {
			newval = !newval ? '1200px' : newval;
			styleTag.innerHTML = '.wpbf-navigation-transparent .wpbf-nav-wrapper {max-width: ' + newval + ';}';
		});
	});

	// Background color.
	wp.customize( 'menu_transparent_background_color', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active), .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-nav-wrapper {background-color: ' + newval + ';}';
		} );
	} );

	// Font color.
	wp.customize( 'menu_transparent_font_color', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-menu > .menu-item > a {color: ' + newval + ';}';
		} );
	} );

	// Font color alt.
	wp.customize( 'menu_transparent_font_color_alt', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_font_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-menu > .menu-item > a:hover, .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-menu > .current-menu-item > a {color: ' + newval + '!important;}';
		} );
	} );

	// Logo color.
	wp.customize( 'menu_transparent_logo_color', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_logo_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-logo a, .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-logo a {color: ' + newval + ';}';
		} );
	} );

	// Logo color alt.
	wp.customize( 'menu_transparent_logo_color_alt', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_logo_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-logo a:hover, .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-logo a:hover {color: ' + newval + ';}';
		} );
	} );

	// Tagline color.
	wp.customize( 'menu_transparent_tagline_color', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_tagline_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-tagline {color: ' + newval + ';}';
		} );
	} );

	// Off canvas hamburger icon color.
	wp.customize( 'menu_transparent_hamburger_color', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_hamburger_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-nav-item, .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-nav-item a {color: ' + newval + ';}';
		} );
	} );

	// Mobile menu icon color.
	wp.customize( 'menu_transparent_hamburger_color_mobile', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_hamburger_color_mobile');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-nav-item, .wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-nav-item a {color: ' + newval + ';}';
		} );
	} );

	// Mobile menu hamburger background color.
	wp.customize( 'menu_transparent_hamburger_bg_color_mobile', function( value ) {
		var styleTag = setupStyleTag('menu_transparent_hamburger_bg_color_mobile');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent:not(.wpbf-navigation-active) .wpbf-mobile-menu-toggle {background-color: ' + newval + ';}';
		} );
	} );

	/* Sticky Navigation */

	// Width.
	wp.customize('menu_active_width', function (value) {
		var styleTag = setupStyleTag('menu_active_width');

		value.bind(function (newval) {
			newval = !newval ? '1200px' : newval;
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-nav-wrapper {max-width: ' + newval + ';}';
		});
	});

	// Height.
	wp.customize( 'menu_active_height', function( value ) {
		var styleTag = setupStyleTag('menu_active_height');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-nav-wrapper {padding-top: ' + newval + 'px; padding-bottom: ' + newval + 'px;}';
		} );
	} );

	// Stacked background color.
	wp.customize( 'menu_active_stacked_bg_color', function( value ) {
		var styleTag = setupStyleTag('menu_active_stacked_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu-stacked-advanced-wrapper {background-color: ' + newval + ';}';
		} );
	} );

	// Background color.
	wp.customize( 'menu_active_bg_color', function( value ) {
		var styleTag = setupStyleTag('menu_active_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active, .wpbf-navigation-active .wpbf-mobile-nav-wrapper {background-color: ' + newval + ';}';
		} );
	} );

	// Font color.
	wp.customize( 'menu_active_font_color', function( value ) {
		var styleTag = setupStyleTag('menu_active_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu > .menu-item > a {color: ' + newval + ';}';
		} );
	} );

	// Font color alt.
	wp.customize( 'menu_active_font_color_alt', function( value ) {
		var styleTag = setupStyleTag('menu_active_font_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '\
				.wpbf-navigation-active .wpbf-menu > .menu-item > a:hover {color: ' + newval + ';}\
				.wpbf-navigation-active .wpbf-menu > .current-menu-item > a {color: ' + newval + '!important;}\
			';
		} );
	} );

	// Logo color.
	wp.customize( 'menu_active_logo_color', function( value ) {
		var styleTag = setupStyleTag('menu_active_logo_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-logo a, .wpbf-navigation-active .wpbf-mobile-logo a {color: ' + newval + ';}';
		} );
	} );

	// Logo color alt.
	wp.customize( 'menu_active_logo_color_alt', function( value ) {
		var styleTag = setupStyleTag('menu_active_logo_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-logo a:hover, .wpbf-navigation-active .wpbf-mobile-logo a:hover {color: ' + newval + ';}';
		} );
	} );

	// Logo width.
	wp.customize( 'menu_active_logo_size', function( value ) {

		var styleTag = setupStyleTag('menu_active_logo_size');

		value.bind( function( newval ) {

			var obj = JSON.parse(newval),
			desktop = obj.desktop,
			tablet = obj.tablet,
			mobile = obj.mobile,
			desktopsuffix = $.isNumeric(desktop) ? 'px' : '',
			tabletsuffix = $.isNumeric(tablet) ? 'px' : '',
			mobilesuffix = $.isNumeric(mobile) ? 'px' : '';

			styleTag.innerHTML = '\
				.wpbf-navigation-active .wpbf-logo img {width: ' + desktop + desktopsuffix + ';}\
				@media (' + mediaQueries.tablet + ') {\
					.wpbf-navigation-active .wpbf-mobile-logo img {width: ' + tablet + tabletsuffix + ';}\
				}\
				@media (' + mediaQueries.mobile + ') {\
					.wpbf-navigation-active .wpbf-mobile-logo img {width: ' + mobile + mobilesuffix + ';}\
				}\
			';

		} );

	} );

	// Off canvas hamburger icon color.
	wp.customize( 'menu_active_off_canvas_hamburger_color', function( value ) {
		var styleTag = setupStyleTag('menu_active_off_canvas_hamburger_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-nav-item, .wpbf-navigation-active .wpbf-nav-item a {color: ' + newval + ';}';
		} );
	} );

	// Full screen menu item spacing.
	wp.customize( 'menu_padding', function( value ) {
		var styleTag = setupStyleTag('full_screen_menu_padding');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-menu-full-screen .wpbf-menu > .menu-item > a {padding-top: ' + newval + 'px; padding-bottom: ' + newval + 'px;}';
		} );
	} );

	// Mobile menu icon color.
	wp.customize( 'mobile_menu_active_hamburger_color', function( value ) {
		var styleTag = setupStyleTag('mobile_menu_active_hamburger_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-mobile-nav-item, .wpbf-navigation-active .wpbf-mobile-nav-item a {color: ' + newval + ';}';
		} );
	} );

	// Mobile menu hamburger color.
	wp.customize( 'mobile_menu_active_hamburger_bg_color', function( value ) {
		var styleTag = setupStyleTag('mobile_menu_active_hamburger_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-mobile-menu-toggle {background-color: ' + newval + ';}';
		} );
	} );

	/* Mobile Navigation */

	// Mobile overlay color.
	wp.customize( 'mobile_menu_overlay_color', function( value ) {
		var styleTag = setupStyleTag('mobile_menu_overlay_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-mobile-menu-overlay {background-color: ' + newval + ';}';
		} );
	} );

	/* Call to Action */

	// Text.
	wp.customize( 'cta_button_text', function( value ) {
		value.bind( function( newval ) {
			$('.wpbf-cta-menu-item a').text( newval );
		} );
	} );

	// Border radius.
	wp.customize( 'cta_button_border_radius', function( value ) {
		var styleTag = setupStyleTag('cta_button_border_radius');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a {border-radius: ' + newval + 'px;}';
		} );
	} );

	// Background color.
	wp.customize( 'cta_button_background_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a, .wpbf-mobile-menu .wpbf-cta-menu-item a {background-color: ' + newval + ';}';
		} );
	} );

	// Background color alt.
	wp.customize( 'cta_button_background_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_background_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a:hover, .wpbf-mobile-menu .wpbf-cta-menu-item a:hover {background-color: ' + newval + ';}';
		} );
	} );

	// Font color.
	wp.customize( 'cta_button_font_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a, .wpbf-mobile-menu .wpbf-cta-menu-item a {color: ' + newval + ';}';
		} );
	} );

	// Font color alt.
	wp.customize( 'cta_button_font_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_font_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation .wpbf-menu .wpbf-cta-menu-item a:hover, .wpbf-mobile-menu .wpbf-cta-menu-item a:hover {color: ' + newval + ';}';
		} );
	} );

	// Transparent background color.
	wp.customize( 'cta_button_transparent_background_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_transparent_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a {background-color: ' + newval + ';}';
		} );
	} );

	// Transparent background color alt.
	wp.customize( 'cta_button_transparent_background_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_transparent_background_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a:hover {background-color: ' + newval + ';}';
		} );
	} );

	// Transparent font color.
	wp.customize( 'cta_button_transparent_font_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_transparent_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a {color: ' + newval + ';}';
		} );
	} );

	// Transparent font color alt.
	wp.customize( 'cta_button_transparent_font_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_transparent_font_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-transparent .wpbf-menu .wpbf-cta-menu-item a:hover {color: ' + newval + ';}';
		} );
	} );

	// Sticky background color.
	wp.customize( 'cta_button_sticky_background_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_sticky_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a {background-color: ' + newval + ';}';
		} );
	} );

	// Sticky background color alt.
	wp.customize( 'cta_button_sticky_background_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_sticky_background_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a:hover {background-color: ' + newval + ';}';
		} );
	} );

	// Sticky font color.
	wp.customize( 'cta_button_sticky_font_color', function( value ) {
		var styleTag = setupStyleTag('cta_button_sticky_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a {color: ' + newval + ';}';
		} );
	} );

	// Sticky font color alt.
	wp.customize( 'cta_button_sticky_font_color_alt', function( value ) {
		var styleTag = setupStyleTag('cta_button_sticky_font_color_alt');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-navigation-active .wpbf-menu .wpbf-cta-menu-item a:hover {color: ' + newval + ';}';
		} );
	} );

	/* Footer */

	// Width.
	wp.customize('footer_widgets_width', function (value) {
		var styleTag = setupStyleTag('footer_widgets_width');

		value.bind(function (newval) {
			newval = !newval ? '1200px' : newval;
			styleTag.innerHTML = '.wpbf-inner-widget-footer {max-width: ' + newval + ';}';
		});
	});

	// Background color.
	wp.customize( 'footer_widgets_bg_color', function( value ) {
		var styleTag = setupStyleTag('footer_widgets_bg_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-widget-footer {background-color: ' + newval + ';}';
		} );
	} );

	// Headline color.
	wp.customize( 'footer_widgets_headline_color', function( value ) {
		var styleTag = setupStyleTag('footer_widgets_headline_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-widget-footer .wpbf-widgettitle {color: ' + newval + ';}';
		} );
	} );

	// Font color.
	wp.customize( 'footer_widgets_font_color', function( value ) {
		var styleTag = setupStyleTag('footer_widgets_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-widget-footer {color: ' + newval + ';}';
		} );
	} );

	// Accent color.
	wp.customize( 'footer_widgets_accent_color', function( value ) {
		var styleTag = setupStyleTag('footer_widgets_accent_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-widget-footer a {color: ' + newval + ';}';
		} );
	} );

	// Font size.
	wp.customize( 'footer_widgets_font_size', function( value ) {
		var styleTag = setupStyleTag('footer_widgets_font_size');

		value.bind( function( newval ) {
			var suffix = $.isNumeric(newval) ? 'px' : '';
			styleTag.innerHTML = '.wpbf-widget-footer {font-size: ' + newval + suffix + ';}';
		} );
	} );

	/* WooCommerce Cart */

	// Menu item custom cart text.
	wp.customize("woocommerce_menu_item_custom_label", function (value) {
    var textEl = document.querySelector(".wpbf-woo-menu-item-label");
		if (!textEl) return;
    textEl.dataset.defaultValue = textEl.innerHTML;

    value.bind(function (newval) {
      textEl.innerHTML = newval ? newval : textEl.dataset.defaultValue;
    });
  });

	/* WooCommerce Quick View */

	// Quick view font size.
	wp.customize( 'woocommerce_loop_quick_view_font_size', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_quick_view_font_size');

		value.bind( function( newval ) {
			var suffix = $.isNumeric(newval) ? 'px' : '';
			styleTag.innerHTML = '.wpbf-woo-quick-view {font-size: ' + newval + suffix + ';}';
		} );
	} );

	// Quick view font color.
	wp.customize( 'woocommerce_loop_quick_view_font_color', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_quick_view_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-woo-quick-view {color: ' + newval + ';}';
		} );
	} );

	// Quick view background color.
	wp.customize( 'woocommerce_loop_quick_view_background_color', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_quick_view_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-woo-quick-view {background-color: ' + newval + ';}';
		} );
	} );

	// Off canvas sidebar font color.
	wp.customize( 'woocommerce_loop_off_canvas_sidebar_font_color', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_off_canvas_sidebar_font_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-woo-off-canvas-sidebar-button {color: ' + newval + ';}';
		} );
	} );

	// Off canvas sidebar background color.
	wp.customize( 'woocommerce_loop_off_canvas_sidebar_background_color', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_off_canvas_sidebar_background_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-woo-off-canvas-sidebar-button {background-color: ' + newval + ';}';
		} );
	} );

	// Off canvas sidebar overlay color.
	wp.customize( 'woocommerce_loop_off_canvas_sidebar_overlay_color', function( value ) {
		var styleTag = setupStyleTag('woocommerce_loop_off_canvas_sidebar_overlay_color');

		value.bind( function( newval ) {
			styleTag.innerHTML = '.wpbf-woo-off-canvas-sidebar-overlay {background-color: ' + newval + ';}';
		} );
	} );

} )( jQuery );