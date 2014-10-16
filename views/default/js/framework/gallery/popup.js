define(['jquery', 'elgg'], function($, elgg) {

	var popup = {
		bind: function() {
			$(document).on('click', '.gallery-popup', popup.popupTrigger);
			$(document).on('click', '.gallery-slideshow .gallery-preview-item', popup.previewItem);
			$(document).on('click', '.gallery-slideshow .meta-title', popup.metaTitle);
			$(document).on('click', '.gallery-slideshow *', popup.attributes);
			$(document).on('click', '.gallery-slideshow .controls .prev, .gallery-slideshow .controls .next', popup.navigate);
		},
		init: function() {
			if (!elgg.config.gallery_popup) {
				popup.bind();
				elgg.config.gallery_popup = true;
			}
		},
		popupTrigger: function(e) {

			var guid = $(this).data('guid');
			var $slideshow = $('<div>').attr('id', 'slideshow' + guid);
			$(window).resize(function() {
				$slideshow.dialog({
					position: {my: "center", at: "center", of: window},
					width: $(window).width() - 150,
					height: $(window).height() - 150
				});
			});
			elgg.getJSON('ajax/view/object/hjalbum/slideshow', {
				beforeSend: function() {
					$('body').addClass('elgg-state-loading');
				},
				data: {
					guid: guid
				},
				success: function(data) {

					$slideshow.dialog({
						title: elgg.echo('gallery:slideshow:loading'),
						dialogClass: 'gallery-slideshow',
						width: $(window).width() - 150,
						height: $(window).height() - 150,
						modal: true,
						close: function() {
							$(this).dialog('destroy').remove();
						}
					});
					var $preview = $('<div>').addClass('preview-pane');
					var $master = $('<div>').addClass('master-view');
					var $controls = $('<div>').addClass('controls');
					$controls.append($('<span>').addClass('prev'));
					$controls.append($('<span>').addClass('pager'));
					$controls.append($('<span>').addClass('next'));
					var $masterpane = $('<div>').addClass('master-pane').append($master).append($controls);
					$masterpane.append($('<div>').addClass('meta-description'));
					var $view = $('<ul>');
					$.each(data.output.img, function(key, opts) {
						var $img = $('<img>')
								.addClass('gallery-preview-item')
								.attr({
									'data-guid': opts.guid,
									'src': elgg.get_site_url() + 'gallery/icon/' + opts.guid + '/medium',
									'alt': opts.title + ': ' + opts.description
								})
								.data({
									'title': opts.title,
									'description': opts.description,
									'master': elgg.get_site_url() + 'gallery/icon/' + opts.guid + '/master'
								});
						$view.append($img);
					});
					$('img', $view).wrap('<li>');
					$slideshow.html($masterpane);
					$view.appendTo($preview);
					$preview.appendTo($slideshow);
					$slideshow.dialog({
						title: data.output.album_title
					});
					if ($('.gallery-preview-item[data-guid="' + guid + '"]').length) {
						$('.gallery-preview-item[data-guid="' + guid + '"]').trigger('click');
					} else {
						$('.gallery-preview-item:first').trigger('click');
					}

				},
				error: function() {
					$slideshow.dialog('destroy');
				},
				complete: function() {
					$('body').removeClass('elgg-state-loading');
				}

			});
		},
		previewItem: function(e) {

			e.preventDefault();
			$('.gallery-slideshow .master-view').html($('<img>').addClass('elgg-state-loading taggable').attr('src', $(this).data('master')));
			$('.gallery-slideshow .master-view').append($('<div>')
					.addClass('meta-title')
					.attr({
						'data-guid': $(this).data('guid')
					}).text($(this).data('title')));
			var $parent = $(this).parent();
			$parent.addClass('elgg-state-selected').siblings().removeClass('elgg-state-selected');
			var pos = $parent.prevAll().andSelf().length;
			var total = $parent.siblings().andSelf().length;
			var elemWidth = $(this).parent().outerWidth(true);
			var prevWidth = pos * elemWidth;
			var paneWidth = $parent.closest('.preview-pane').innerWidth();
			var margin = paneWidth / 2 - prevWidth;
			$parent.parent().css('margin-left', paneWidth / 2 - prevWidth + elemWidth * 1.5);
			$('.gallery-slideshow .controls .pager').text(elgg.echo('gallery:slideshow:pager', [pos, total]));
			if ($parent.prev().length) {
				$('.gallery-slideshow .controls .prev').removeClass('hidden').data('elem', $parent.prev());
			} else {
				$('.gallery-slideshow .controls .prev').addClass('hidden').data('elem', null);
			}

			if ($parent.next().length) {
				$('.gallery-slideshow .controls .next').removeClass('hidden').data('elem', $parent.next());
			} else {
				$('.gallery-slideshow .controls .next').addClass('hidden').data('elem', null);
			}
		},
		metaTitle: function(e) {
			e.preventDefault();
			var $elem = $(this);
			$elem.toggleClass('elgg-state-active');
			var $desc = $elem.closest('.gallery-slideshow').find('.meta-description');
			if ($elem.hasClass('elgg-state-active')) {
				elgg.ajax("ajax/view/object/hjalbumimage/ajaxmeta?guid=" + $elem.data('guid'), {
					cache: true,
					beforeSend: function() {
						$('body').addClass('elgg-state-loading');
					},
					success: function(output) {
						$desc.html(output).show();
					},
					error: function() {
						$desc.hide();
					},
					complete: function() {
						$('body').removeClass('elgg-state-loading');
					}
				});
			} else {
				$desc.hide();
			}
		},
		attributes: function(e) {
			if ($('.gallery-slideshow .meta-title:first').hasClass('elgg-state-active')
					&& !$(e.target).closest('.meta-title,.meta-description').length) {
				$('.gallery-slideshow .meta-title').removeClass('elgg-state-active');
				$('.gallery-slideshow .meta-description').hide();
			}
		},
		navigate: function(e) {
			e.preventDefault();
			$(this).data('elem').find('img').trigger('click');
		}
	};

	return popup;
});


