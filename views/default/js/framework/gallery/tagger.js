define(['jquery', 'elgg', 'jquery.form'], function($, elgg) {

	var tagger = {
		taggerFloat : {},
		init: function() {

			var $template = $('.tagger-float-template').eq(0).clone(true);
			tagger.taggerFloat = $('<div>').appendTo('body').addClass('tagger-float').html($template.html());

			$(document).on('mouseenter', '.gallery-tag', tagger.showTag);
			$(document).on('mouseleave', '.gallery-tag', tagger.hideTag);
			$(document).on('click', '.elgg-button-gallery-tagger', tagger.initTagging);
			$(document).on('click', '.elgg-button-gallery-tag-delete', tagger.deleteTag);

			$('.elgg-button-gallery-tagger').trigger('click');

		},
		initTagging: function(e) {
			e.preventDefault();
			if ($(this).is('.elgg-state-active')) {
				$(this).removeClass('elgg-state-active')
						.attr('title', elgg.echo('gallery:tools:tagger:start'))
						.closest('.elgg-layout')
						.find('.gallery-media-full')
						.unbind('mousemove', tagger.mouseMove);

			} else {
				$(this).addClass('elgg-state-active')
						.attr('title', elgg.echo('gallery:tools:tagger:stop'))
						.closest('.elgg-layout')
						.find('.gallery-media-full')
						.bind('mousemove', tagger.mouseMove);

			}
		},
		showTag: function(e) {
			var x = $(this).data('x');
			var y = $(this).data('y');

			var $img = $(this).closest('.elgg-layout').find('.gallery-media-full').find('img.taggable').eq(0);

			var position = $img.position();
			if ($(this).data('tagger-tag')) {
				var $circle = $(this).data('tagger-tag');
			} else {
				var $circle = $('<div>').addClass('tagger-tag').insertAfter($img);
				$circle.css({position: 'absolute', left: position.left + x - 40, top: position.top + y - 40});
			}
			$circle.show();
			$(this).data('tagger-tag', $circle);
		},
		hideTag: function() {
			$(this).data('tagger-tag').hide();
		},
		deleteTag: function(e) {
			var confirmText = elgg.echo('question:areyousure');
			if (!confirm(confirmText)) {
				return false;
			}

			e.preventDefault();

			var $elem = $(this);

			elgg.action($elem.attr('href'), {
				beforeSend: function() {
					$elem.addClass('elgg-state-loading');
				},
				complete: function() {
					$elem.removeClass('elgg-state-loading');
				},
				success: function(response) {
					if (response.status >= 0) {
						$('[id="elgg-object-' + response.output.guid + '"]').fadeOut();
					}
				}
			});
		},
		mouseMove: function(e) {

			var $elem = $(this), $tagger = tagger.taggerFloat, $src;

			if (e.target) {
				$src = $(e.target);
			} else if (e.srcElement) {
				$src = e.srcElement;
			}
			if ($src.nodeType === 3) {
				// defeat Safari bug
				$src = $src.parentNode;
			}

			if ($src.is('.taggable') || $src.is($tagger)) {

				var w = $src.width(), h = $src.height();
				var x1 = $src.offset().left, y1 = $src.offset().top;
				var x2 = x1 + w, y2 = y1 + h;
				if (x1 < e.pageX && x2 > e.pageX && y1 < e.pageY && y2 > e.pageY) {
					$tagger.show().offset({top: e.pageY - 40, left: e.pageX - 40});
					$tagger.unbind('click').bind('click', function(e2) {

						var tagger_offset = $tagger.offset();
						var src_offset = $src.offset();
						var tag_y1 = e.pageY - src_offset.top;
						var tag_x1 = e.pageX - src_offset.left;

						$elem.unbind('mousemove');

						var $form = $('.elgg-form-gallery-phototag');
						$('[name="x1"]', $form).val(tag_x1 - 40);
						$('[name="y1"]', $form).val(tag_y1 - 40);
						$('[name="x2"]', $form).val(tag_x1 + 40);
						$('[name="y2"]', $form).val(tag_y1 + 40);
						$('[name="w"]', $form).val(80);
						$('[name="h"]', $form).val(80);

						$('.gallery-tagger-area-preview > img').css({
							marginLeft: '-' + Math.round(tag_x1 - 40) + 'px',
							marginTop: '-' + Math.round(tag_y1 - 40) + 'px'
						});

						$tagger.find('.tagger-close')
								.show()
								.bind('click', function(e3) {
									$(this).hide();
									$tagger.unbind('click').hide();
									if ($('.elgg-input-tokeninput', $form).length) {
										$('.elgg-input-tokeninput', $form).bind('clear', function(e) {
											$(this).tokenInput("clear");
										}).trigger('clear');

									}
									$form.resetForm();
									$('.tagged-user-preview').attr('src', '');
									$elem.bind('mousemove', tagger.mouseMove);
								});


						$form.unbind('submit')
								.bind('submit', function(e) {
									e.preventDefault();

									var data = {};
									data['X-Requested-With'] = 'XMLHttpRequest';
									data['X-PlainText-Response'] = true;
									$form.ajaxSubmit({
										dataType: 'json',
										data: data,
										iframe: false,
										beforeSend: function() {
											$form.find('[type="submit"]').prop('disabled', true).addClass('elgg-state-disabled');
										},
										success: function(response) {
											if (response.status >= 0) {
												$tagger
														.find('.tagger-close')
														.trigger('click');
											}
											$('.gallery-tags-list').append(response.output);

											if (response.system_messages.success) {
												elgg.system_message(response.system_messages.success);
											}
											if (response.system_messages.error) {
												elgg.register_error(response.system_messages.error);
											}
										},
										complete: function() {
											$form.find('[type="submit"]').prop('disabled', false).removeClass('elgg-state-disabled');
										}
									});
									return false;
								});
					});
				} else {
					$tagger.hide();
				}

			} else {
				$tagger.hide();
			}
		}
	};

	return tagger;
});


