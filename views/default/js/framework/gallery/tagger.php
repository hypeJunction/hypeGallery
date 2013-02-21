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
	.live('submit', framework.gallery.tagger.saveTag);

	$('ul.hj-gallery-tags-list > li')
	.live('mouseover mouseout', function(event) {
		if (event.type == 'mouseover') {
			var link = $('.hj-gallery-tags-map li[data-uid="' + $(this).eq(0).data('uid') + '"]');
			link.find('a').show().addClass('hj-gallery-tag-hover').find('span').show().addClass('hj-gallery-tag-selected');
		} else {
			var link = $('.hj-gallery-tags-map li[data-uid="' + $(this).eq(0).data('uid') + '"]');
			link.find('a').hide().removeClass('hj-gallery-tag-hover').find('span').hide().removeClass('hj-gallery-tag-selected');
		}
	});

//	$('.hj-gallery-tagger-wrapper')
//	.live('mouseover mouseout', function(event) {
//		if (event.type == 'mouseover') {
//			$('.hj-gallery-tags-map > li a').show();
//		} else {
//			$('.hj-gallery-tags-map > li a').hide();
//		}
//	});
	
	$('.hj-gallery-tags-map > li')
	.live('mouseover mouseout', function(event) {
		if (event.type == 'mouseover') {
			$(this).find('span').show();
		} else {
			$(this).find('span').hide();
		}
	});

	framework.gallery.tagger.mapTags();
}

framework.gallery.tagger.mapTags = function() {

	$('.hj-gallery-tags-map')
	.each(function() {

		$map = $(this);
		$img = $(this).closest('.hj-gallery-tagger-wrapper').find('img.elgg-state-taggable').eq(0);

		$img.load(function() {
		
			$map.css({
				'width' : $img.width(),
				'height' : $img.height(),
				'position' : 'absolute',
				'margin' : '0 auto',
				'left' : 0,
				'right' : 0,
				'top' : 0
			});

			var ratio = $img.width() / $img.data('originalwidth');

			$(this)
			.children('li')
			.each(function() {
				var elem = $(this).find('a').eq(0);
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
		});
	})
}

framework.gallery.tagger.startTagging = function(event) {

	event.preventDefault();

	elgg.system_message(elgg.echo('hj:gallery:tagger:instructions'));

	$(this)
	.addClass('hidden');
		
	$('.elgg-menu-item-stoptagging')
	.removeClass('hidden');
		
	framework.gallery.tagger.uid = $('a', $(this)).eq(0).data('uid');

	var $form = $('form.hj-gallery-tag-save[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);
	var $image = $('img.elgg-state-taggable[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);

	framework.gallery.tagger.options = $image.data();

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
			$('.hj-gallery-tags-map').show();
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
		},
		onInit : function () {
			$('.hj-gallery-tagger-wrapper')
			.live('mouseover mouseout', function(event) {
				if (event.type == 'mouseover') {
					$('.hj-gallery-tags-map', $(this)).hide();
				} else {
					$('.hj-gallery-tags-map', $(this)).show();
				}
			})
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

	$('#hj-gallery-tag-save')
	.addClass('hidden');

	framework.gallery.tagger.uid = $(this).find('a').data('uid');
	
	var $form = $('form.hj-gallery-tag-save[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);
	var $image = $('img.elgg-state-taggable[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);

	$image.imgAreaSelect({
		disable: true,
		hide: true
	});
}


framework.gallery.tagger.saveTag = function(event) {
	event.preventDefault();

	var $form = $(this);
	var uid = $form.data('uid');

	var params = {
		dataType : 'json',
		data : $form.serialize(),
		beforeSend : function() {
			elgg.system_message(elgg.echo('hj:framework:ajax:saving'));
			$('input[type="text"]', $form).addClass('hj-input-processing');
		},
		complete : function() {
			$('input[type="text"]', $form).removeClass('hj-input-processing');
			$('input[type="text"]', $form).each(function() {
				$(this).val('');
			});

			$form.addClass('hidden');

			$('input[type="text"]', $form).removeClass('hj-input-processing');

			var $image = $('img.elgg-state-taggable[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);
			$image.imgAreaSelect({
				hide: true
			});
		},
		success : function(response) {
			var $list = $('.hj-gallery-tags-list[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);
			var $map = $('.hj-gallery-tags-map[data-uid="' + framework.gallery.tagger.uid + '"]').eq(0);

			$list.append($(response.output.list));
			$map.append($(response.output.map));

			framework.gallery.tagger.mapTags();
		}
	};
		
	elgg.action($(this).attr('action'), params);
}

elgg.register_hook_handler('init', 'system', framework.gallery.tagger.init);

<?php if (FALSE) : ?>
	</script>
<?php endif; ?>