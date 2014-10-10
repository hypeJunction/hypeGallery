define(function(require) {

	var $ = require('jquery');

	if ($('.elgg-form-gallery-manage').length) {
		require('framework/gallery/manager');
	}

	if ($('.taggable').length) {
		require(['framework/gallery/tagger'], function(tagger) {
			tagger.init();
		});
	}

	if ($('.gallery-croppable').length) {
		require(['framework/gallery/cropper'], function(cropper) {
			cropper.init();
		});
	}

	$(document).ajaxSuccess(function(data) {
		if ($(data).has('.gallery-croppable')) {
			require(['framework/gallery/cropper'], function(cropper) {
				cropper.init();
			});
		}
	});

	if ($('.gallery-popup,.gallery-slideshow').length) {
		require(['framework/gallery/popup'], function(popup) {
			popup.init();
		});
	}

	$(document).ajaxSuccess(function(data) {
		if ($(data).has('.gallery-popup,.gallery-slideshow')) {
			require(['framework/gallery/popup'], function(popup) {
				popup.init();
			});
		}
	});
});