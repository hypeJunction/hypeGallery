<?php if (FALSE) : ?>
	<script type="text/javascript">
	<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.gallery');

	framework.gallery.init = function() {

		$(".gallery-manage-album tbody").sortable({
			items:                'tr.hj-draggable-element',
			connectWith:          '.gallery-manage-album tbody',
			handle:               '.hj-draggable-element-handle',
			forcePlaceholderSize: true,
			placeholder:          'tr.hj-draggable-element-placeholder',
			opacity:              0.8,
			revert:               500,
			stop:                 framework.gallery.orderImages
		});

	}

	framework.gallery.orderImages = function(event, ui) {

		var data = ui.item
		.closest('.gallery-manage-album tbody')
		.sortable('serialize');

		elgg.action('action/gallery/order/images?' + data);

		// @hack fixes jquery-ui/opera bug where draggable elements jump
		ui.item.css('top', 0);
		ui.item.css('left', 0);
	};

	framework.gallery.upload = function() {
		//alert('hello');
	}

	elgg.register_hook_handler('init', 'system', framework.gallery.init);
	elgg.register_hook_handler('image:upload', 'framework:gallery', framework.gallery.upload);