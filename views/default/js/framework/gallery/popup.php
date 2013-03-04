<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

elgg.provide('framework');
elgg.provide('framework.gallery');

framework.gallery.loader = $('<div>').addClass('hj-ajax-loader').addClass('hj-loader-circle').hide();
framework.gallery.dialog = $('<div id="gallery-dialog">');

framework.gallery.popup = function() {

	$('.hj-gallery-popup').live('click', framework.gallery.popupTrigger);

}

framework.gallery.popupTrigger = function(e) {

	$element = $(this);
	$dialog = framework.gallery.dialog;

	e.preventDefault;

	elgg.post($element.attr('href'), {
		data : {
			guid : $element.data('uid'),
			view : 'xhr',
			endpoint : 'layout'
		},

		beforeSend : function() {
			$dialog
			.html(framework.gallery.loader.show())
			.dialog({
				modal : true,
				dialogClass: 'hj-framework-dialog',
				title : elgg.echo('hj:framework:ajax:loading'),
				minWidth : 500,
				minHeight : 500,
				autoResize : true
			})
		},
		complete : function() {

		},
		success : function(response) {
			$dialog.dialog({ title : response.output.title });
			$img = $('img:first', $(response.output.body.content));

			$img.load(function() {
				$dialog
				.html($img)
				.dialog({
					height: $img.height() + 140,
					width : $img.width() + 50,
					position : {
						my : "center"
					}
				});
			})

			var buttons = new Array();
			var $set = $('.hj-gallery-popup');
			var index = $set.index($element);

			buttons.push({
				text: elgg.echo('hj:gallery:goto:full'),
				click : function() {
					window.location.replace($set.eq(index).attr('href'))
				}
			})
			if (index > 0) {
				buttons.push({
					text : elgg.echo('previous'),
					click : function() {
						$set.eq(index-1).trigger('click');
					}
				})
			}
			if (index < $set.length -1 ) {
				buttons.push({
					text : elgg.echo('next'),
					click : function() {
						$set.eq(index+1).trigger('click');
					}
				})
			}
			$dialog.dialog('option', 'buttons', buttons);
		}
	})

	return false;
}
		
elgg.register_hook_handler('init', 'system', framework.gallery.popup);
		
<?php if (FALSE) : ?>
	</script>
<?php endif; ?>