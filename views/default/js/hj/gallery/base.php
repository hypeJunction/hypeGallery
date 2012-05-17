<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

elgg.provide('hj.gallery.base');

hj.gallery.base.init = function() {
	$('.hj-gallery-grouped-images')
	.fancybox({
		'autoDimensions' : true
	});

	$('#hj-image-master')
	.each(function() {
		$(this).height($(this).find('img').height())
	})
}

elgg.register_hook_handler('init', 'system', hj.gallery.base.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', hj.gallery.base.init);



<?php if (FALSE) : ?>
	</script>
<?php endif; ?>