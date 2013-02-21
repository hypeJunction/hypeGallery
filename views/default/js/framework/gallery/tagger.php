<?php
/*
 * Based on a Photo Tagging Library by Neill Horsman (http://www.neillh.com.au)
 */

if (FALSE) :
	?>
	<script type="text/javascript">
<?php endif; ?>
	
elgg.provide('framework.gallery.tagger');
	
framework.gallery.tagger.init = function() {

	$('.elgg-menu-item-starttagging')
	.live('click', framework.gallery.tagger.startTagging);

	$('.elgg-menu-item-stoptagging')
	.live('click', framework.gallery.tagger.stopTagging)

	$('.hj-gallery-tag-save')
	.unbind('submit')
	.bind('submit', framework.gallery.tagger.saveTag);

	$('ul.hj-gallery-tags-list > li')
	.hover(function() {
		var link = $('.hj-gallery-tags-map li').find('a.hj-gallery-tag-' + $(this).attr('id').replace('elgg-object-', ''));
		link.addClass('hj-gallery-tag-hover').find('span').show().addClass('hj-gallery-tag-selected');

	}, function() {
		var link = $('.hj-gallery-tags-map li').find('a.hj-gallery-tag-' + $(this).attr('id').replace('elgg-object-', ''));
		link.removeClass('hj-gallery-tag-hover').find('span').hide().removeClass('hj-gallery-tag-selected');
	});

	$('.hj-gallery-tags-map')
	.each(function() {
		$(this).css({
			'width' : $(this).parent().find('img').width(),
			'position' : 'absolute',
			'margin' : '0 auto',
			'left' : 0,
			'right' : 0
		});
		$(this)
		.closest('#hj-image-master')
		.css({ 'height' : $(this).parent().find('img').height() });

		var ratio = $(this).parent().find('img').width() / $(this).parent().find('img').attr('original-width');

		$(this)
		.children('li')
		.each(function() {
			var elem = $(this).find('a').first();
			if (!elem.data('dimensions')) {
				var origin_dim = {
					'top' : elem.css('top'),
					'left' : elem.css('left'),
					'width' : elem.css('width'),
					'height' : elem.css('height')
				};
				elem.data('dimensions', origin_dim);
			}
			elem.css({
				'top' : Math.round(parseInt(elem.data('dimensions').top, 10) * ratio),
				'left' : Math.round(parseInt(elem.data('dimensions').left, 10) * ratio),
				'width' : Math.round(parseInt(elem.data('dimensions').width, 10) * ratio),
				'height' : Math.round(parseInt(elem.data('dimensions').height, 10) * ratio)
			});
		});
		
		$('.hj-gallery-tags-map li a').hover(function() {
			$(this).find('span').show();
		}, function() {
			$(this).find('span').hide();
		});
	});
}

framework.gallery.tagger.startTagging = function(event) {

	event.preventDefault();
		
	$(this)
	.addClass('hidden');
		
	$('.elgg-menu-item-stoptagging')
	.removeClass('hidden');
		
	framework.gallery.tagger.uid = $('a', $(this)).eq(0).data();

	var $form = $('form.hj-gallery-tagger-form[data-uid="' + framework.gallery.tagger.uid + "']").eq(0);
	var $image = $('img.elgg-state-taggable[data-uid="' + framework.gallery.tagger.uid + "']").eq(0);

	console.log($form, $image);
	
	framework.gallery.tagger.options = $image.data();

	$image
	.hover(function(event) {
			
	}, function(event) {
		$form.addClass('hidden')
	});

	$image.imgAreaSelect({
		disable: false,
		handles: true,
		keys: { arrows: 15, shift: 5 },
		fadeSpeed: 200,
		imageWidth: framework.gallery.tagger.options.originalwidth,
		imageHeight: framework.gallery.tagger.options.originalheight,
		onSelectEnd: function(img, selection){
			$('input[name="x1"]', $form).val(selection.x1);
			$('input[name="y1"]', $form).val(selection.y1);
			$('input[name="x2"]', $form).val(selection.x2);
			$('input[name="y2"]', $form).val(selection.y2);
			$('input[name="w"]', $form).val(selection.width);
			$('input[name="h"]', $form).val(selection.height);
			$form.css('left', selection.x1);
			$form.css('top', selection.y2);
			$form.removeClass("hidden");
			if (selection.width == 0 && selection.height == 0) { 
				$form.addClass("hidden");
			}
		},
		onSelectChange: function(img, selection){
			$('input[type="text"]', $form).each(function() {
				$(this).val('');
			});
			$form.addClass('hidden');
		},
		onSelectStart: function(img, selection){
			$('input[type="text"]', $form).each(function() {
				$(this).val('');
			});
			$form.addClass('hidden');
		}
	});
}

framework.gallery.tagger.stopTagging = function(event) {
	event.preventDefault();
	event.stopPropagation();

	$(this)
	.addClass('hidden');

	$('.elgg-menu-item-starttagging')
	.removeClass('hidden');

	$('#hj-gallery-tagger-form')
	.addClass('hidden');

	framework.gallery.tagger.uid = $(this).find('a').data('uid');
	var $form = $('#hj-gallery-tagger-form');
	var $image = $('img.elgg-state-taggable[data-uid["' + framework.gallery.tagger.uid + "']").eq(0);

	$image.imgAreaSelect({
		disable: true,
		hide: true
	});
}


framework.gallery.tagger.saveTag = function(event) {
	event.preventDefault();
	event.stopPropagation();

	var form = $(this);

	var params = {
		dataType : 'json',
		data : $(this).serialize(),
		success : function(output) {
			$(target+':last')
			.append(output.output.data);
			$('input[type="text"]', form).each(function() {
				$(this).val('');
			});
			form.addClass('hidden');
			$('input[type="text"]', form).removeClass('hj-input-processing');

			var image = '#hj-entity-icon-' + window.tag_options.params.entity_guid + '.hj-gallery-taggable';
			$(image).imgAreaSelect({
				hide: true
			});

			elgg.trigger_hook('success', 'hj:framework:ajax');
		}
	};
	elgg.system_message(elgg.echo('hj:framework:processing'));
	$('input[type="text"]', $(this)).addClass('hj-input-processing');
		
	elgg.action($(this).attr('action'), params);
}

elgg.register_hook_handler('init', 'system', framework.gallery.tagger.init);

<?php if (FALSE) : ?>
	</script>
<?php endif; ?>