import $ from 'jquery';

if ($('.elgg-form-gallery-manage').length) {
	import('framework/gallery/manager');
}

if ($('.taggable').length) {
	import('framework/gallery/tagger').then(({default: tagger}) => {
		tagger.init();
	});
}

if ($('.gallery-croppable').length) {
	import('framework/gallery/cropper').then(({default: cropper}) => {
		cropper.init();
	});
}

$(document).on('ajaxSuccess', function(data) {
	if ($(data).has('.gallery-croppable').length) {
		import('framework/gallery/cropper').then(({default: cropper}) => {
			cropper.init();
		});
	}
});

if ($('.gallery-popup,.gallery-slideshow').length) {
	import('framework/gallery/popup').then(({default: popup}) => {
		popup.init();
	});
}

$(document).on('ajaxSuccess', function(data) {
	if ($(data).has('.gallery-popup,.gallery-slideshow').length) {
		import('framework/gallery/popup').then(({default: popup}) => {
			popup.init();
		});
	}
});
