<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.gallery');

	framework.gallery.popup = function() {

		$('.gallery-popup').live('click', framework.gallery.popupTrigger);

		$('.gallery-slideshow .gallery-preview-item')
				.live('click', function(e) {
					e.preventDefault();
					$('.gallery-slideshow .master-view')
							.html($('<img>').addClass('taggable').attr('src', $(this).data('master')));
					$('.gallery-slideshow .master-view')
							.append($('<div>')
									.addClass('meta-title')
									.attr({
										'data-guid': $(this).data('guid')
									}).text($(this).data('title')))

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
				})

		$('.gallery-slideshow .meta-title')
				.live('click', function(e) {
					var $elem = $(this);
					$elem.toggleClass('elgg-state-active');
					var $desc = $elem.closest('.gallery-slideshow').find('.meta-description');

					if ($elem.hasClass('elgg-state-active')) {
						elgg.ajax("ajax/view/object/hjalbumimage/ajaxmeta?guid=" + $elem.data('guid'), {
							cache: true,
							beforeSend: function() {
								$desc.html($('<div>').addClass('elgg-ajax-loader')).show();
							},
							success: function(output) {
								$desc.html(output)
							},
							error: function() {
								$desc.hide();
							}
						})
					} else {
						$desc.hide();
					}
				})

		$('*').live('click', function(e) {
			if ($('.gallery-slideshow .meta-title:first').hasClass('elgg-state-active')
					&& !$(e.target).closest('.meta-title,.meta-description').length) {
				$('.gallery-slideshow .meta-title').removeClass('elgg-state-active');
				$('.gallery-slideshow .meta-description').hide();
			}
		})

		$('.gallery-slideshow .controls .prev, .gallery-slideshow .controls .next').live('click', function(e) {
			e.preventDefault();
			$(this).data('elem').find('img').trigger('click');
		})

	}

	framework.gallery.popupTrigger = function(e) {

		var guid = $(this).data('guid');
		var $slideshow = $('<div>').attr('id', 'slideshow' + guid);
		$(window).resize(function() {
			$slideshow
					.dialog({
						position: {my: "center", at: "center", of: window},
						width: $(window).width() - 25,
						height: $(window).height() - 25
					});
		});

		elgg.getJSON('ajax/view/object/hjalbum/slideshow', {
			beforeSend: function() {
				$slideshow.html($('<div>').addClass('elgg-ajax-loader'));
				$slideshow.dialog({
					title: elgg.echo('gallery:slideshow:loading'),
					dialogClass: 'gallery-slideshow',
					width: $(window).width() - 25,
					height: $(window).height() - 25,
					modal: true,
					close: function() {
						$(this).dialog('destroy').remove();
					}
				});
			},
			data: {
				guid: guid
			},
			success: function(data) {

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
							})

					$view.append($img);

				});

				$('img', $view).wrap('<li>');

				$slideshow.html($masterpane);
				$view.appendTo($preview);
				$preview.appendTo($slideshow);
				$slideshow.dialog({
					title: data.output.album_title
				})

				if ($('.gallery-preview-item[data-guid="' + guid + '"]').length) {
					$('.gallery-preview-item[data-guid="' + guid + '"]').trigger('click')
				} else {
					$('.gallery-preview-item:first').trigger('click');
				}

			},
			error: function() {
				$slideshow.dialog('destroy');
			}
		})

		return false;
	}

	elgg.register_hook_handler('init', 'system', framework.gallery.popup);

<?php if (FALSE) : ?>
	</script>
<?php endif; ?>