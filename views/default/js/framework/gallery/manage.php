<?php if (FALSE) : ?>
	<script type="text/javascript">
	<?php endif; ?>

		elgg.provide('framework');
		elgg.provide('framework.gallery');

		framework.gallery.manage = function() {


			/**
			 * Order images in album
			 */
			$(".gallery-manage-album").sortable({
				items:                'li.elgg-item',
				connectWith:          '.gallery-manage-album',
				handle:               '.elgg-menu-item-drag',
				forcePlaceholderSize: true,
				placeholder:          'gallery-media-cover',
				opacity:              0.8,
				revert:               500,
				stop:                 framework.gallery.orderImages
			});


			/**
			 * AJAX photo approve
			 */
			$('.elgg-button-gallery-approve')
			.live('click', function(e) {
				e.preventDefault();

				$elem = $(this);

				elgg.action($elem.attr('href'), {
					beforeSend : function() {
						$elem.addClass('elgg-state-loading');
					},
					complete : function() {
						$elem.removeClass('elgg-state-loading');
					},
					success : function(response) {
						if (response.status >= 0) {
							$elem.parent('li').remove();
						}
					}
				})
			})


			/**
			 * AJAX photo delete
			 */
			$('.elgg-button-gallery-delete')
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

			/*
			 * AJAX change album cover
			 */
			$('.elgg-button-gallery-makecover')
			.live('click', function(e) {
				e.preventDefault();

				$elem = $(this);

				elgg.action($elem.attr('href'), {
					beforeSend : function() {
						$elem.addClass('elgg-state-loading');
					},
					complete : function() {
						$elem.removeClass('elgg-state-loading');
					},
					success : function(response) {
						if (response.status >= 0) {
							$('li.hidden:has(a.elgg-button-gallery-makecover)').removeClass('hidden');
							$elem.parent('li').addClass('hidden');
						}
					}
				})
			})

			/**
			 * AJAX edit image thumbs
			 */
			$('.elgg-button-gallery-cropper')
			.live('click', function(e) {
				e.preventDefault();
				
				var $thumbeditor = $('<div>').attr('id', 'thumbeditor');
				var guid = $(this).data('guid');
				
				elgg.ajax('ajax/view/framework/gallery/tools/cropper', {
					data : {
						guid : guid
					},
					beforeSend : function() {
						$('body').addClass('gallery-state-loading');
					},
					success : function(data) {
						$thumbeditor.html(data);
						$thumbeditor.dialog({
							title : elgg.echo('gallery:tools:crop:ready'),
							dialogClass : 'gallery-slideshow',
							width : $(window).width() - 150,
							height : $(window).height() - 150,
							modal : true,
							close : function() {
								$('#gallery-crop-master').imgAreaSelect({remove:true});
								$(this).dialog('destroy').remove();
							}
						});
						
						elgg.trigger_hook('cropper', 'framework:gallery');

						$('.elgg-form-gallery-thumb')
						.live('submit', function(e2) {

							e2.preventDefault();

							$form = $(this);

							var data = {};
							data['X-Requested-With'] = 'XMLHttpRequest';
							data['X-PlainText-Response'] = true;
							$form.ajaxSubmit({
								dataType : 'json',
								data : data,
								iframe : false,
								beforeSend : function() {
									$form.find('[type="submit"]').addClass('elgg-state-disabled').prop('disabled', true);
									$('#gallery-crop-master').imgAreaSelect({remove:true});
									$('body').addClass('gallery-state-loading');
								},
								success : function(response) {
									if (response.status >= 0) {
										$thumbeditor.dialog('close');
									}
									if (response.system_messages.success) {
										elgg.system_message(response.system_messages.success);
									}
									if (response.system_messages.error) {
										elgg.register_error(response.system_messages.error);
									}
								},
								error : function() {
									elgg.trigger_hook('cropper', 'framework:gallery');
								},
								complete : function() {
									$form.find('[type="submit"]').removeClass('elgg-state-disabled').prop('disabled', false);
									$('body').removeClass('gallery-state-loading');
								}
							})
							
							return false;
						})
					},
					error : function() {
						$thumbeditor.dialog('close');
					},
					complete : function() {
						$('body').removeClass('gallery-state-loading');
					}
				})
			})


		}

		framework.gallery.orderImages = function(event, ui) {

			var data = ui.item
			.closest('.gallery-manage-album')
			.sortable('serialize');

			elgg.action('action/gallery/order/images?' + data, {
				beforeSend : function() {
					$('.elgg-menu-item-drag').addClass('elgg-state-loading');
				},
				complete : function() {
					$('.elgg-menu-item-drag').removeClass('elgg-state-loading');
				},
				success : function(data) {
					var ord = ui.item
					.closest('.gallery-manage-album')
					.sortable('toArray');

					$.each(ord, function(key, id) {
						$('#' + id).find('.elgg-menu-item-position input').val(key);
					})
				}
			});

			// @hack fixes jquery-ui/opera bug where draggable elements jump
			ui.item.css('top', 0);
			ui.item.css('left', 0);
		};


		elgg.register_hook_handler('init', 'system', framework.gallery.manage);