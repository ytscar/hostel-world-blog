(function($) {

	var refererField = document.querySelector('[name="_wp_http_referer"]');

	// Transparent Header Advanced
	$('.wpbf-transparent-header-advanced').on( 'click', function () {
		if (this.dataset.expanded == 0 || !this.dataset.expanded ) {
			this.dataset.expanded = "1";
			$(this).html('- Advanced');
			$('.wpbf-transparent-header-advanced-wrapper').slideDown();
		} else {
			this.dataset.expanded = "0";
			$(this).html('+ Advanced');
			$('.wpbf-transparent-header-advanced-wrapper').slideUp();
		}
	} );

	// Blog Layouts Advanced
	$('.wpbf-blog-layouts-advanced').on( 'click', function () {
		if (this.dataset.expanded == 0 || !this.dataset.expanded ) {
			this.dataset.expanded = "1";
			$(this).html('- Advanced');
			$('.wpbf-blog-layouts-advanced-wrapper').slideDown();
		} else {
			this.dataset.expanded = "0";
			$(this).html('+ Advanced');
			$('.wpbf-blog-layouts-advanced-wrapper').slideUp();
		}
	} );

	// Select All
	$('.wpbf-performance-select-all').on( 'click', function () {
		if (this.dataset.selected == 0 || !this.dataset.selected ) {
			this.dataset.selected = "1";
			$('.wpbf-performance-setting').prop( "checked", true );
		} else {
			this.dataset.selected = "0";
			$('.wpbf-performance-setting').prop( "checked", false );
		}
	} );

	// Logo Image Upload
	$('.wpbf-company-logo-upload').click(function(e) {
		e.preventDefault();

		var custom_uploader = wp.media({
			title: 'Company Logo',
			button: {
				text: 'Add Logo'
			},
			multiple: false
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.wpbf-company-logo-url').val(attachment.url);

		})
		.open();
	});

	$('.wpbf-company-logo-remove').click(function(e) {
		e.preventDefault();
		$('.wpbf-company-logo-url').val('');
	});

	// Theme Image Upload
	$('.wpbf-screenshot-upload').click(function(e) {
		e.preventDefault();

		var custom_uploader = wp.media({
			title: 'Theme Screenshot',
			button: {
				text: 'Add Screenshot'
			},
			multiple: false
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.wpbf-screenshot-url').val(attachment.url);

		})
		.open();
	});

	$('.wpbf-screenshot-remove').click(function(e) {
		e.preventDefault();
		$('.wpbf-screenshot-url').val('');
	});

	jQuery(document).ready(function($) {
		$( '.color-picker' ).wpColorPicker();
	});


	$('.heatbox-tab-nav-item a').on('click', function(event) {
		var hash = this.href.substring(this.href.indexOf('#') + 1);
		if (!hash) return;

		setRefererValue(hash);

		// Manage tab menu items.
		$('.heatbox-tab-nav-item').removeClass('active');
		this.parentNode.classList.add('active');

		// Manage tab content items.
		$('.heatbox-admin-panel').css('display', 'none');
		$('.heatbox-admin-panel[data-tab-id="' + hash + '"]').css('display', 'block');
	});

	$(window).on('load', function() {

		var hash = window.location.hash.substr(1);
		if (!hash) return;

		setRefererValue(hash);

		$('.heatbox-tab-nav-item').removeClass('active');
		$('.heatbox-admin-panel').css('display', 'none');

		$('.heatbox-tab-nav-item a').each(function (i, el) {
			var linkHash = el.href.substring(el.href.indexOf('#') + 1);
			if (!linkHash) return;

			if (linkHash === hash) el.parentNode.classList.add('active');
		});
		$('.heatbox-admin-panel[data-tab-id="' + hash + '"]').css('display', 'block');

	});

	function setRefererValue(hash) {
		if (!refererField) return;
		var url;

		if (refererField.value.includes('#')) {
			url = refererField.value.split('#');
			url = url[0];

			refererField.value = url + '#' + hash;
		} else {
			refererField.value = refererField.value + '#' + hash;
		}
	}

	setupClearFontCache();

  function setupClearFontCache() {
    var notice = document.querySelector(
      ".wpbf-remove-downloaded-fonts-metabox .submission-status"
    );
    if (!notice) return;
    var doingAjax = false;
    $(".wpbf-remove-downloaded-fonts").on("click", clearFontCache);

    function clearFontCache(e) {
      if (doingAjax) return;
      doingAjax = true;
      var button = this;
      button.classList.add("is-loading");

      var data = {
        action: "wpbf_clear_font_cache",
        nonce: button.dataset.nonce,
      };

      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: data,
      })
        .done(function (r) {
          showNotice(r.success ? "success" : "error", r.data);
        })
        .fail(function (r) {
          showNotice("error", "Something went wrong.");
        })
        .always(function (r) {
          doingAjax = false;
          button.classList.remove("is-loading");
          setTimeout(function () {
            hideNotice();
          }, 4000);
        });
    }

    function showNotice(status, textContent) {
      notice.textContent = textContent;
      notice.classList.add(status === "success" ? "is-success" : "is-error");
      notice.classList.remove("is-hidden");
    }

    function hideNotice() {
      notice.textContent = "";
      notice.classList.remove("is-success");
      notice.classList.remove("is-error");
      notice.classList.add("is-hidden");
    }
  }

})( jQuery );
