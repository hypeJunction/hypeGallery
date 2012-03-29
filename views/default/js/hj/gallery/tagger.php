<?php
/*
 * Based on a Photo Tagging Library by Neill Horsman (http://www.neillh.com.au)
 */

if (FALSE) :
	?>
	<script type="text/javascript">
<?php endif; ?>
	
	elgg.provide('hj.gallery.tagger');
	
	hj.gallery.tagger.init = function() {
		$('.elgg-menu-item-starttagging')
		.live('click', hj.gallery.tagger.startTagging);

		$('.elgg-menu-item-stoptagging')
		.live('click', hj.gallery.tagger.stopTagging)

		$('.hj-gallery-tag-save')
		.unbind('submit')
		.bind('submit', hj.gallery.tagger.saveTag);

		$('ul.hj-gallery-tags-list > li')
		.hover(function() {
			var link = $('.hj-gallery-tags-map li').find('a.hj-gallery-tag-' + $(this).attr('id').replace('elgg-object-', ''));
			link.addClass('hj-gallery-tag-hover').find('span').show().addClass('hj-gallery-tag-selected');
		}, function() {
			var link = $('.hj-gallery-tags-map li').find('a.hj-gallery-tag-' + $(this).attr('id').replace('elgg-object-', ''));
			link.removeClass('hj-gallery-tag-hover').find('span').hide().removeClass('hj-gallery-tag-selected');
		});

		$('.hj-gallery-tags-map li a').hover(function() {
			$(this).find('span').show();
		}, function() {
			$(this).find('span').hide();
		});

	}
	
	hj.gallery.tagger.startTagging = function(event) {
		event.preventDefault();
		event.stopPropagation();
		
		$(this)
		.find('a')
		.addClass('hidden');
		
		$('.elgg-menu-item-stoptagging')
		.find('a')
		.removeClass('hidden');
		
		window.tag_options = $(this).find('a').data('options');
		var form = $('#hj-gallery-tagger-form');
		var image = '#hj-entity-icon-' + window.tag_options.params.entity_guid + '.hj-gallery-taggable';

		$(image)
		.hover(function(event) {
			
		}, function(event) {
			form.addClass('hidden')
		});
		
		$(image).imgAreaSelect({
			disable: false,
			handles: true,
			keys: { arrows: 15, shift: 5 },
			fadeSpeed: 200,
			parent: $(image).closest('div#hj-image-master') ,
			onSelectEnd: function(img, selection){
				$('input[name="x1"]', form).val(selection.x1);
				$('input[name="y1"]', form).val(selection.y1);
				$('input[name="x2"]', form).val(selection.x2);
				$('input[name="y2"]', form).val(selection.y2);
				$('input[name="w"]', form).val(selection.width);
				$('input[name="h"]', form).val(selection.height);
				form.css('left', selection.x1);
				form.css('top', selection.y2);
				form.removeClass("hidden");
				if (selection.width == 0 && selection.height == 0) { form.addClass("hidden"); }
			},
			onSelectChange: function(img, selection){
				$('input[type="text"]', form).each(function() {
					$(this).val('');
				});
				form.addClass('hidden');
			},
			onSelectStart: function(img, selection){
				$('input[type="text"]', form).each(function() {
					$(this).val('');
				});
				form.addClass('hidden');
			}
		});
	}

	hj.gallery.tagger.stopTagging = function(event) {
		event.preventDefault();
		event.stopPropagation();

		$(this)
		.find('a')
		.addClass('hidden');

		$('.elgg-menu-item-starttagging')
		.find('a')
		.removeClass('hidden');

		$('#hj-gallery-tagger-form')
		.addClass('hidden');

		var image = '#hj-entity-icon-' + window.tag_options.params.entity_guid + '.hj-gallery-taggable';

		$(image).imgAreaSelect({
			disable: true,
			hide: true
		});
	}


	hj.gallery.tagger.saveTag = function(event) {
		event.preventDefault();
		event.stopPropagation();

		var form = $(this);
		var values = new Object();
		values = $.parseJSON($(this).find('input[name="params"]').val());

		if (values.target) {
			var target = '#'+values.target;
		} else {
			var target = '#elgg-object-'+values.entity_guid;
		}

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

	elgg.register_hook_handler('init', 'system', hj.gallery.tagger.init);
	


<?php if (FALSE) : ?>
	</script>
<?php endif; ?>