import $ from 'jquery';

if ($('.elgg-form-gallery-manage').length) {
	import('js/framework/gallery/manager');
}

if ($('.taggable').length) {
	import('js/framework/gallery/tagger').then(({default: tagger}) => {
		tagger.init();
	});
}

if ($('.gallery-croppable').length) {
	import('js/framework/gallery/cropper').then(({default: cropper}) => {
		cropper.init();
	});
}

$(document).on('ajaxSuccess', function(data) {
	if ($(data).has('.gallery-croppable').length) {
		import('js/framework/gallery/cropper').then(({default: cropper}) => {
			cropper.init();
		});
	}
});

if ($('.gallery-popup,.gallery-slideshow').length) {
	import('js/framework/gallery/popup').then(({default: popup}) => {
		popup.init();
	});
}

$(document).on('ajaxSuccess', function(data) {
	if ($(data).has('.gallery-popup,.gallery-slideshow').length) {
		import('js/framework/gallery/popup').then(({default: popup}) => {
			popup.init();
		});
	}
});
