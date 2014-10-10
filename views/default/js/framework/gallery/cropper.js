define(['jquery', 'elgg', 'jquery.form', 'cropper'], function($, elgg) {

	var cropper = {
		bind: function() {
			$(document).on('initialize', '.gallery-croppable', cropper.build);
			$(document).on('destroy', '.gallery-croppable', cropper.destroy);
			$(document).on('click', '.gallery-croppable:not(.initialized)', cropper.build);
			$(document).on('submit', '.elgg-form-gallery-thumb', cropper.submit);
		},
		init: function() {
			if (!elgg.config.gallery_cropper) {
				cropper.bind();
				elgg.config.gallery_cropper = true;
			}
			setTimeout(function() {
				$('.gallery-croppable').trigger('initialize');
			}, 500);
		},
		build: function() {
			var $elem = $(this);
			$elem.uniqueId();
			if ($elem.is('.initialized') || !$elem.is(':visible')) {
				return;
			}

			$elem.addClass('initialized');

			var $form = $('.elgg-form-gallery-thumb');
			var params = {
				aspectRatio: $elem.data('cropperRatio'),
				data: {
					x: $elem.data('cropperX'),
					y: $elem.data('cropperY'),
					width: $elem.data('cropperWidth'),
					height: $elem.data('cropperHeight')
				},
				done: function(data) {
					var imgInfo = $elem.cropper('getImgInfo');
					$('input[name="x1"]', $form).val(data.x);
					$('input[name="x2"]', $form).val((data.x + data.width));
					$('input[name="y1"]', $form).val(data.y);
					$('input[name="y2"]', $form).val((data.y + data.height));
					var $preview = $('#gallery-crop-preview');
					var scaleX = $preview.width() / data.width;
					var scaleY = $preview.height() / data.height;
					$('img', $preview).eq(0).css({
						'margin-left': Math.round(-data.x * scaleX),
						'margin-top': Math.round(-data.y * scaleY),
						'width': Math.round(scaleX * imgInfo.naturalWidth),
						'height': Math.round(scaleY * imgInfo.naturalHeight)
					});
				}
			};
			$elem.cropper(params);
		},
		destroy: function(e) {
			if ($(this).is('.initialized')) {
				$(this).cropper('destroy');
			}
		},
		submit: function(e) {

			e.preventDefault();
			var $form = $(this);
			var data = $form.data();
			data['X-Requested-With'] = 'XMLHttpRequest';
			data['X-PlainText-Response'] = true;

			$form.ajaxSubmit({
				dataType: 'json',
				data: data,
				iframe: false,
				beforeSend: function() {
					$('.gallery-croppable').trigger('destroy');
					$form.find('[type="submit"]').addClass('elgg-state-disabled').prop('disabled', true);
					$('body').addClass('elgg-state-loading');
				},
				success: function(response) {
					if (response.status >= 0) {
						if ($form.closest('.ui-dialog-content').length) {
							$form.closest('.ui-dialog-content').dialog('close');
						} else {
							$('.gallery-croppable').trigger('initialize');
						}
					}
					if (response.system_messages.success) {
						elgg.system_message(response.system_messages.success);
					}
					if (response.system_messages.error) {
						elgg.register_error(response.system_messages.error);
					}
				},
				complete: function() {
					$form.find('[type="submit"]').removeClass('elgg-state-disabled').prop('disabled', false);
					$('body').removeClass('elgg-state-loading');
				}
			});
			return false;
		}
	};
	return cropper;
});


