define(['jquery', 'elgg'], function($, elgg) {

	/**
	 * Order images in album
	 */
	$(".gallery-manage-album").sortable({
		items: 'li.elgg-item',
		connectWith: '.gallery-manage-album',
		handle: '.elgg-menu-item-drag',
		forcePlaceholderSize: true,
		placeholder: 'gallery-media-cover',
		opacity: 0.8,
		revert: 500,
		stop: function(event, ui) {

			var data = ui.item.closest('.gallery-manage-album').sortable('serialize');
			elgg.action('action/gallery/order/images?' + data, {
				beforeSend: function() {
					$('.elgg-menu-item-drag').addClass('elgg-state-loading');
				},
				complete: function() {
					$('.elgg-menu-item-drag').removeClass('elgg-state-loading');
				},
				success: function(data) {
					var ord = ui.item
							.closest('.gallery-manage-album').sortable('toArray');
					$.each(ord, function(key, id) {
						$('#' + id).find('.elgg-menu-item-position input').val(key);
					});
				}
			});
			// @hack fixes jquery-ui/opera bug where draggable elements jump
			ui.item.css('top', 0);
			ui.item.css('left', 0);
		}
	});
	/**
	 * AJAX photo approve
	 */
	$(document).on('click', '.elgg-button-gallery-approve', function(e) {
		e.preventDefault();
		var $elem = $(this);
		elgg.action($elem.attr('href'), {beforeSend: function() {
				$elem.addClass('elgg-state-loading');
			},
			complete: function() {
				$elem.removeClass('elgg-state-loading');
			},
			success: function(response) {
				if (response.status >= 0) {
					$elem.parent('li').remove();
				}
			}
		});
	});
	/**
	 * AJAX photo delete
	 */
	$(document).on('click', '.elgg-button-gallery-delete', function(e) {

		var confirmText = elgg.echo('question:areyousure');
		if (!confirm(confirmText)) {
			return false;
		}

		e.preventDefault();
		var $elem = $(this);
		elgg.action($elem.attr('href'), {beforeSend: function() {
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
	});
	/*
	 * AJAX change album cover
	 */
	$(document).on('click', '.elgg-button-gallery-makecover', function(e) {
		e.preventDefault();
		var $elem = $(this);
		elgg.action($elem.attr('href'), {beforeSend: function() {
				$elem.addClass('elgg-state-loading');
			},
			complete: function() {
				$elem.removeClass('elgg-state-loading');
			},
			success: function(response) {
				if (response.status >= 0) {
					$('li.hidden:has(a.elgg-button-gallery-makecover)').removeClass('hidden');
					$elem.parent('li').addClass('hidden');
				}
			}
		});
	});
	/**
	 * AJAX edit image thumbs
	 */
	$(document).on('click', '.elgg-button-gallery-cropper', function(e) {

		e.preventDefault();
		var $thumbeditor = $('<div>').attr('id', 'thumbeditor');
		var guid = $(this).data('guid');
		elgg.ajax('ajax/view/framework/gallery/tools/cropper', {
			data: {
				guid: guid
			},
			beforeSend: function() {
				$('body').addClass('elgg-state-loading');
			},
			error: function() {
				$thumbeditor.dialog('close');
			}, complete: function() {
				$('body').removeClass('elgg-state-loading');
			},
			success: function(data) {
				$thumbeditor.html(data);
				$thumbeditor.dialog({
					title: elgg.echo('gallery:tools:crop:ready'),
					dialogClass: 'gallery-slideshow',
					width: $(window).width() - $(window).width() * 0.1,
					height: $(window).height() - $(window).height() * 0.1,
					modal: true,
					close: function() {
						$(this).dialog('destroy').remove();
					}
				});
			}
		});
	});
});

