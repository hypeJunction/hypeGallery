<?php
/*
 * Based on a Photo Tagging Library by Neill Horsman (http://www.neillh.com.au)
 */

if (FALSE) :
	?>
	<script type="text/javascript">
<?php endif; ?>

elgg.provide('framework');
elgg.provide('framework.gallery');
	
framework.gallery.tagger = function() {

	var $template = $('.tagger-float-template').eq(0).clone(true);
	framework.gallery.taggerFloat = $('<div>').appendTo('body').addClass('tagger-float').html($template.html());

	$('.gallery-tag')
	.live('mouseenter', function(e) {
		var x = $(this).data('x');
		var y = $(this).data('y');

		var $img = $(this).closest('.gallery-media-full').find('img.taggable').eq(0);

		var position = $img.position();
		if ($(this).data('tagger-tag')) {
			var $circle = $(this).data('tagger-tag');
		} else {
			var $circle = $('<div>').addClass('tagger-tag').insertAfter($img);
			$circle.css({ position: 'absolute', left : position.left + x - 25 , top : position.top + y - 25});
		}
		$circle.show();
		$(this).data('tagger-tag', $circle);
	})
	.live('mouseleave', function() {
		$(this).data('tagger-tag').hide();
	})


	$('.elgg-button-gallery-tagger')
	.live('click', function(e) {
		e.preventDefault();
		if ($(this).is('.elgg-state-active')) {
			
			$(this)
			.removeClass('elgg-state-active')
			.attr('title', elgg.echo('gallery:tools:tagger:start'))
			.closest('.gallery-media-full')
			.unbind('mousemove', framework.gallery.mouseMove);
			
		} else {
			
			$(this)
			.addClass('elgg-state-active')
			.attr('title', elgg.echo('gallery:tools:tagger:stop'))
			.closest('.gallery-media-full')
			.bind('mousemove', framework.gallery.mouseMove);
			
		}
	})

	$('.elgg-button-gallery-tag-delete')
	.live('click', function(e) {

		var confirmText = elgg.echo('question:areyousure');
		if (!confirm(confirmText)) {
			return false;
		}

		e.preventDefault();

		var $elem = $(this);

		elgg.action($elem.attr('href'), {
			beforeSend : function() {
				$elem.addClass('elgg-state-loading');
			},
			complete : function() {
				$elem.removeClass('elgg-state-loading');
			},
			success : function(response) {
				if (response.status >= 0) {
					$('[id="elgg-object-' + response.output.guid + '"]').fadeOut();
				}
			}
		})
	})


	$('.gallery-friend-autocomplete')
	.each(function() {

		var $input = $(this);

		$(this).autocomplete({
			minLength: 0,
			source: elgg.get_site_url() + 'gallery/livesearch/friend',
			focus: function( event, ui ) {
				$input.val( ui.item.label );
				return false;
			},
			select: function( event, ui ) {
				$input.siblings('.tagged-user-preview').attr('src',  ui.item.icon );
				$input.val( ui.item.label );
				$input.next('[name="relationship_guid"]').val( ui.item.value );
				return false;
			}
		})

	})

	$('.gallery-tag-autocomplete').autocomplete({
		minLength: 3,
		source: elgg.get_site_url() + 'gallery/livesearch/tag'
	})

}

framework.gallery.mouseMove = function(e) {

	var $elem = $(this), $tagger = framework.gallery.taggerFloat, $src;

	if (e.target) $src = $(e.target);
    else if (e.srcElement) $src = e.srcElement;
    if ($src.nodeType == 3) // defeat Safari bug
        $src = $src.parentNode;

	if ($src.is('.taggable') || $src.is($tagger)) {
		
		var w = $src.width(), h = $src.height();
		var x1 = $src.offset().left, y1 = $src.offset().top;
		var x2 = x1 + w, y2 = y1 + h;
		if (x1 < e.pageX && x2 > e.pageX && y1 < e.pageY && y2 > e.pageY) {
			$tagger.show().offset({ top: e.pageY - 25, left: e.pageX - 25});
			$tagger.unbind('click').bind('click', function(e2) {

				$elem.unbind('mousemove');
					
				var $form = $tagger.find('form').show();
				$('[name="x1"]', $form).val(e.pageX - x1 - 25);
				$('[name="y1"]', $form).val(e.pageY - y1 - 25);
				$('[name="x2"]', $form).val(e.pageX + 50);
				$('[name="y2"]', $form).val(e.pageY + 50);
				$('[name="w"]', $form).val(50);
				$('[name="h"]', $form).val(50);

				$tagger
				.find('.tagger-close')
				.show()
				.bind('click', function(e3) {
					$(this).hide();
					$tagger.unbind('click').hide();
					$form.hide().resetForm();
					$('.tagged-user-preview').attr('src', '');
					$elem.bind('mousemove', framework.gallery.mouseMove)
				});

				$form
				.unbind('submit')
				.bind('submit', function(e) {
					e.preventDefault();

					var data = {};
					data['X-Requested-With'] = 'XMLHttpRequest';
					data['X-PlainText-Response'] = true;
					$form.ajaxSubmit({
						dataType : 'json',
						data : data,
						iframe : false,
						beforeSend : function() {
							$tagger.addClass('elgg-state-loading');
						},
						success : function(response) {
							$tagger.removeClass('elgg-state-loading');
							if (response.status >= 0) {
								$tagger
								.find('.tagger-close')
								.trigger('click');
							}
							$('.gallery-tags-list', $elem).append(response.output);
							
							if (response.system_messages.success) {
								elgg.system_message(response.system_messages.success);
							}
							if (response.system_messages.error) {
								elgg.register_error(response.system_messages.error);
							}
						},
						error : function() {
							$tagger.removeClass('elgg-state-loading')
						}
					})
					return false;
				})
			})
		} else {
			$tagger.hide();
		}

	} else {
		$tagger.hide();
	}
}
elgg.register_hook_handler('init', 'system', framework.gallery.tagger);

<?php if (FALSE) : ?>
	</script>
<?php endif; ?>