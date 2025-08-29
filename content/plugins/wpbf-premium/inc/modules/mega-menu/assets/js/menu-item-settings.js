(function ($) {
	var $popup = $(".wpbf-menu-item-settings-popup-overlay");
	var popup = $popup[0];
	var animSpeed = 300;

	var currentMenuItem;

	var fields = {};

	// Collect necessary fields here.
	fields.enableMegaMenu = document.querySelector('#wpbf_enable_mega_menu');
	fields.megaMenuDropdownWidthType = document.querySelector('#wpbf_mega_menu_dropdown_width_type');
	fields.megaMenuDropdownCustomWidth = document.querySelector('#wpbf_mega_menu_dropdown_custom_width');
	fields.megaMenuDropdownColumn = document.querySelector('#wpbf_mega_menu_dropdown_column');

	var fieldRows = {};

	// Collect necessary field rows here.
	fieldRows.enableMegaMenu = fields.enableMegaMenu.parentNode.parentNode.parentNode.parentNode;
	fieldRows.megaMenuDropdownWidthType = fields.megaMenuDropdownWidthType.parentNode.parentNode;
	fieldRows.megaMenuDropdownCustomWidth = fields.megaMenuDropdownCustomWidth.parentNode.parentNode.parentNode.parentNode;
	fieldRows.megaMenuDropdownColumn = fields.megaMenuDropdownColumn.parentNode.parentNode;

	function init() {
		setupRangeSlider();
		setupMenuItemDataset();

		// Open popup by clicking "Menu Item Settings" button.
		$(document).on('click', '.wpbf-menu-item-settings-button', openSettingsPopup);

		// Change dropdown value by clicking the control.
		$(document).on('click', '.wpbf-menu-item-settings-number-selector li', changeDropdownColumn);

		// Switch mega menu settings.
		fields.enableMegaMenu.addEventListener('change', switchMegaMenu);

		// Close popup by clicking on close button.
		$(document).on('click', '.wpbf-menu-item-settings-close-button', closePopup);

		// Close popup by clicking on the overlay.
		document.addEventListener('click', function (e) {
			if (e.target.classList.contains('wpbf-menu-item-settings-popup-overlay') && $popup.is(":visible")) {
				closePopup();
			}
		});

		// Close popup on by tapping the escape key.
		document.addEventListener('keyup', function (e) {
			if (e.key !== 'Escape' && e.key !== 'Esc') return;
			if ($popup.is(':visible')) closePopup();
		});

		// Close popup by clicking on done button.
		$(document).on('click', '.wpbf-done-settings-button', closePopup);

		// Change the value of mega menu dropdown's width type.
		fields.megaMenuDropdownWidthType.addEventListener('change', switchCustomWidth);
		fields.megaMenuDropdownWidthType.addEventListener('change', populateMegaMenuClassNames);

		// Change the value of mega menu dropdown's column.
		fields.megaMenuDropdownColumn.addEventListener('change', populateMegaMenuClassNames);
	}

	/**
	 * Setup range slider field.
	 */
	function setupRangeSlider() {
		controls = document.querySelectorAll('.wpbf-menu-item-settings-range-slider');
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var sliderField = control.querySelector('.wpbf-menu-item-settings-slider-field');
			var valueField = control.querySelector('.wpbf-menu-item-settings-slider-value');
			var value = {};

			value.raw = valueField.value;
			value.unit = value.raw.replace(/\d+/g, '');
			value.unit = value.unit ? value.unit : '%';
			value.number = value.raw.replace(value.unit, '');
			value.number = parseInt(value.number.trim(), 10);

			valueField.addEventListener('change', function () {
				value.raw = this.value + '';
				value.unit = value.raw.replace(/\d+/g, '');
				value.unit = value.unit ? value.unit : '%';
				value.number = value.raw.replace(value.unit, '');
				value.number = parseInt(value.number.trim(), 10);

				sliderField.value = value.number;

				updateCustomWidthDbField();
				populateMegaMenuClassNames();
			});
			
			sliderField.addEventListener('input', function (e) {
				value.number = this.value;
				valueField.value = value.number + value.unit;
				
				updateCustomWidthDbField();
				populateMegaMenuClassNames();
			});
		});
	}

	/**
	 * Setup data-wpbf-x in each menu item.
	 * This function should only run once (inside init).
	 */
	function setupMenuItemDataset() {
		var menuItems = document.querySelectorAll('#menu-to-edit li.menu-item');

		menuItems.forEach(function (menuItem) {
			var classNameField = menuItem.querySelector('.edit-menu-item-classes');
			var customWidthField = menuItem.querySelector('.wpbf-mega-menu-custom-width-db');

			var className = classNameField.value.trim();
			var classNames = className.split(' ');

			// Setting up the enable mega menu dataset.
			if (className.includes('wpbf-mega-menu')) {
				menuItem.dataset.wpbfMegaMenuEnabled = '1';
			} else {
				menuItem.removeAttribute('data-wpbf-mega-menu-enabled');
			}

			// Setting up mega menu dropdown width type dataset.
			if (className.includes('wpbf-mega-menu-container-width')) {
				menuItem.dataset.wpbfWidthType = 'container-width';
			} else if (className.includes('wpbf-mega-menu-full-width')) {
				menuItem.dataset.wpbfWidthType = 'full-width';
			} else if (className.includes('wpbf-mega-menu-custom-width')) {
				menuItem.dataset.wpbfWidthType = 'custom-width';
			}

			var customWidth = customWidthField.value;

			// Setting up mega menu dropdown custom width dataset.
			if (customWidth) {
				menuItem.dataset.wpbfCustomWidth = customWidth;
			} else {
				menuItem.dataset.wpbfCustomWidth = '400px';
			}

			// Setting up mega menu dropdown column dataset.
			classNames.some(function (className) {
				if (!className.includes('wpbf-mega-menu-')) return false;
				var splits = className.split('wpbf-mega-menu-');
				var suffix = splits[1];
				if (!isNumeric(suffix)) return false;

				menuItem.dataset.wpbfColumn = suffix;
				return true;
			});
		});
	}

	/**
	 * Function to run when "Menu Item Settings" is clicked.
	 */
	function openSettingsPopup() {
		var menuItem = $(this).closest('li.menu-item')[0];
		var classNames = menuItem.className;
		classNames = classNames.split(' ');

		currentMenuItem = menuItem;

		var depth = 0;

		classNames.some(checkDepth);

		function checkDepth(className) {
			if (!className.includes('menu-item-depth-')) return false;

			var splits = className.split('menu-item-depth-');
			depth = parseInt(splits[1], 10);

			return true;
		}

		openPopup({
			menuItemId: this.dataset.menuItemId,
			menuItemDepth: depth,
			navMenuId: this.dataset.navMenuId
		});
	}

	/**
	 * Open the menu item settings popup.
	 */
	function openPopup(data) {
		popup.dataset.menuItemId = data.menuItemId;
		popup.dataset.menuItemDepth = data.menuItemDepth;
		popup.dataset.navMenuId = data.navMenuId;

		var titleField = currentMenuItem.querySelector('.edit-menu-item-title');
		document.querySelector('.wpbf-menu-item-settings-replace-title').innerHTML = titleField.value;

		if (currentMenuItem.dataset.wpbfMegaMenuEnabled) {
			fields.enableMegaMenu.checked = true;
			showMegaMenuFields();
		} else {
			fields.enableMegaMenu.checked = false;
			hideMegaMenuFields();
		}

		var widthType = currentMenuItem.dataset.wpbfWidthType;

		if (widthType) {
			fields.megaMenuDropdownWidthType.value = widthType;
		}

		switchCustomWidth();

		var customWidth = currentMenuItem.dataset.wpbfCustomWidth;

		if (customWidth) {
			fields.megaMenuDropdownCustomWidth.value = customWidth;
		}

		var columnNumber = currentMenuItem.dataset.wpbfColumn;

		if (columnNumber) {
			fields.megaMenuDropdownColumn.value = columnNumber;

			document.querySelectorAll('.wpbf-menu-item-settings-number-selector li').forEach(function (item) {
				if (columnNumber == item.dataset.wpbfValue) {
					item.classList.add('is-active');
				} else {
					item.classList.remove('is-active');
				}
			});

			document.querySelector('.wpbf-menu-item-settings-number-selector [data-wpbf-value="' + columnNumber + '"]').classList.add('is-active');
		}

		// Mega menu setting fields are only available to top level menu.
		if (0 === data.menuItemDepth) {
			showMegaMenuFields(true);
		} else {
			hideMegaMenuFields(true);
		}

		$popup.css({ display: 'flex' }).stop().animate({
			opacity: 1
		}, animSpeed);
	}

	/**
	 * Close the menu item settings popup.
	 */
	function closePopup() {
		$popup.stop().animate({
			opacity: 0
		}, animSpeed, function () {
			popup.style.display = 'none';
		});
	}

	/**
	 * Function to run when mega menu enable field is switched.
	 */
	function switchMegaMenu() {
		if (fields.enableMegaMenu.checked) {
			currentMenuItem.dataset.wpbfMegaMenuEnabled = '1';
			showMegaMenuFields();
			populateMegaMenuClassNames();
		} else {
			currentMenuItem.removeAttribute('data-wpbf-mega-menu-enabled');
			hideMegaMenuFields();
			removeMegaMenuClassNames();
		}
	}

	/**
	 * Function to run when mega menu dropdown width type field is switched.
	 */
	function switchCustomWidth() {
		var customWidthField = currentMenuItem.querySelector('.wpbf-mega-menu-custom-width-db');

		if ('custom-width' === fields.megaMenuDropdownWidthType.value) {
			customWidthField.value = fields.megaMenuDropdownCustomWidth.value;
			fieldRows.megaMenuDropdownCustomWidth.classList.remove('wpbf-is-hidden');
		} else {
			customWidthField.value = '';
			fieldRows.megaMenuDropdownCustomWidth.classList.add('wpbf-is-hidden');
		}
	}

	/**
	 * Update custom width db field (the one which value will be saved to db).
	 */
	function updateCustomWidthDbField() {
		var customWidthField = currentMenuItem.querySelector('.wpbf-mega-menu-custom-width-db');
		customWidthField.value = fields.megaMenuDropdownCustomWidth.value;
	}

	/**
	 * Populate mega menu class name.
	 * What would be modified is the value of current menu item's class name field.
	 */
	function populateMegaMenuClassNames() {
		var classNameField = currentMenuItem.querySelector('.edit-menu-item-classes');
		var classNames = classNameField.value.trim().split(' ');
		var newClassNames = [];

		classNames.forEach(function (className, index) {
			// If className is not our mega menu class names, then keep it.
			if ('' !== className && !className.includes('wpbf-mega-menu')) {
				newClassNames.push(className);
			}
		});

		var columnNumber = fields.megaMenuDropdownColumn.value;
		var widthType = fields.megaMenuDropdownWidthType.value;
		var customWidth = fields.megaMenuDropdownCustomWidth.value;

		newClassNames.push('wpbf-mega-menu');
		newClassNames.push('wpbf-mega-menu-' + widthType);
		newClassNames.push('wpbf-mega-menu-' + columnNumber);

		currentMenuItem.dataset.wpbfWidthType = widthType;
		currentMenuItem.dataset.wpbfCustomWidth = customWidth;
		currentMenuItem.dataset.wpbfColumn = columnNumber;

		classNameField.value = newClassNames.join(' ');
	}

	/**
	 * Show mega menu fields.
	 *
	 * @param {boolean} includeEnableField Whether to also show the enable checkbox or not.
	 */
	function showMegaMenuFields(includeEnableField) {
		if (includeEnableField) fieldRows.enableMegaMenu.classList.remove('wpbf-is-hidden');
		if (!fields.enableMegaMenu.checked) return;

		fieldRows.megaMenuDropdownWidthType.classList.remove('wpbf-is-hidden');

		if ('custom-width' === fields.megaMenuDropdownWidthType.value) {
			fieldRows.megaMenuDropdownCustomWidth.classList.remove('wpbf-is-hidden');
		} else {
			fieldRows.megaMenuDropdownCustomWidth.classList.add('wpbf-is-hidden');
		}

		fieldRows.megaMenuDropdownColumn.classList.remove('wpbf-is-hidden');
	}

	/**
	 * Hide mega menu fields.
	 *
	 * @param {boolean} includeEnableField Whether to also hide the enable checkbox or not.
	 */
	function hideMegaMenuFields(includeEnableField) {
		if (includeEnableField) fieldRows.enableMegaMenu.classList.add('wpbf-is-hidden');

		fieldRows.megaMenuDropdownWidthType.classList.add('wpbf-is-hidden');
		fieldRows.megaMenuDropdownCustomWidth.classList.add('wpbf-is-hidden');
		fieldRows.megaMenuDropdownColumn.classList.add('wpbf-is-hidden');
	}

	/**
	 * Function to run when mega menu's dropdown column is changed.
	 * For example, when user change the dropdown column from 2 columns to 3 columns.
	 */
	function changeDropdownColumn() {
		var el = this;

		document.querySelectorAll('.wpbf-menu-item-settings-number-selector li').forEach(function (item) {
			if (item == el) {
				item.classList.add('is-active');
			} else {
				item.classList.remove('is-active');
			}
		});

		fields.megaMenuDropdownColumn.value = this.dataset.wpbfValue;
		fields.megaMenuDropdownColumn.dispatchEvent(new Event('change'));
	}

	/**
	 * Remove all mega menu class names from current menu item's class name field.
	 */
	function removeMegaMenuClassNames() {
		var classNameField = currentMenuItem.querySelector('.edit-menu-item-classes');
		var classNames = classNameField.value.split(' ');

		var newClassNames = [];

		classNames.forEach(function (className, index) {
			// If className is not our mega menu class names, then keep it.
			if ('' !== className && !className.includes('wpbf-mega-menu')) {
				newClassNames.push(className);
			}
		});

		classNameField.value = newClassNames.join(' ');
	}

	/**
	 * Check if given value is a numeric value.
	 * 
	 * @see https://stackoverflow.com/questions/175739/built-in-way-in-javascript-to-check-if-a-string-is-a-valid-number#answer-24457420
	 *
	 * @param {mixed} value The value to check.
	 * @returns boolean
	 */
	function isNumeric(value) {
		return /^-?\d+$/.test(value);
	}

	init();
})(jQuery);