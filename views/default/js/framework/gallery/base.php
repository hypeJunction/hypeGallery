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

			$('.elgg-button-gallery-approve')
			.live('click', function(e) {
				e.preventDefault();

				$elem = $(this);

				elgg.action($elem.attr('href'), {
					success : function(response) {
						if (response.status >= 0) {
							$elem.parent('li').remove();
						}
					}
				})
			})
			
			$('.elgg-button-gallery-makecover')
			.live('click', function(e) {
				e.preventDefault();

				$elem = $(this);

				elgg.action($elem.attr('href'), {
					success : function(response) {
						if (response.status >= 0) {
							$('li.hidden:has(a.elgg-button-gallery-makecover)').removeClass('hidden');
							$elem.parent('li').addClass('hidden');
						}
					}
				})
			})

			$('.elgg-button-gallery-editthumb')
			.live('click', function(e) {
				e.preventDefault();

				$elem = $(this);
				$dialog = $('<div>');

				elgg.post($elem.attr('href'), {
					data : {
						view : 'xhr',
						endpoint : 'content'
					},
					beforeSend : function() {
						$dialog
						.html($('<div>').addClass('hj-ajax-loader').addClass('hj-loader-circle'))
						.dialog({
							dialogClass : 'hj-framework-dialog',
							title : elgg.echo('hj:framework:ajax:loading'),
							minWidth : 500
						});
					},
					error : function() {
						$dialog.dialog('close');
					},
					success : function(response) {
						if (response.status >= 0) {
							$dialog
							.html(response.output.body.content)
							.dialog({
								title : response.output.title
							})

							$img = $('#hj-image-cropper', $dialog);
							$img.load(function() {
								$dialog
								.dialog({
									minWidth : 800,
									height : $img.closest('form').height() + 140
								})
							})


							var buttons = new Array();
							$footer = $dialog.find('.elgg-foot');

							$dialog.find('input[type="submit"], input[type="button"], .elgg-button', '.elgg-foot').each(function() {
								var $button = $(this).hide();
								if ($button.hasClass('elgg-button-cancel-trigger')) {
									buttons.push({
										text : ($button.attr('value')) ? $button.attr('value') : elgg.echo('cancel'),
										click : function() {
											$dialog.dialog('close');
										}
									})
								} else {
									buttons.push({
										text : ($button.attr('value')) ? $button.attr('value') : $button.text(),
										click : function() {
											if ($button.attr('href')) {
												window.location.replace($button.attr('href'));
											} else {
												$button.trigger('click');
											}

										}
									})
								}
							})

							$dialog
							.dialog({
								buttons : buttons
							})

							$form = $dialog.find('form');
							
							var params = new Object();
							params.event = 'getTaggingForm';
							params.response = response;
							params.element = $elem;

							elgg.trigger_hook('ajax:success', 'framework', params, true);
							elgg.trigger_hook('cropper', 'framework:gallery');

							var params = ({
								dataType : 'json',
								beforeSend : function() {
									$form.hide();
									$dialog
									.append(framework.loaders.circle);
									$dialog.focus();
									$dialog.dialog({
										title : elgg.echo('hj:framework:ajax:saving'),
										buttons:null
									});
									return true;
								},
								complete : function() {
									$( "#dialog-ajax-progressbar" )
									.progressbar({
										value: 100
									});
									$dialog.dialog('close');
								},
								success : function(response, status, xhr) {

									var hookParams = new Object();
									hookParams.event = 'submitForm';
									hookParams.response = response;
									hookParams.element = $elem;
									hookParams.data = $form.serialize();

									elgg.trigger_hook('ajax:success', 'framework', hookParams, true);

									// an error occurred, reload the form
									if (response.status < 0) {
										$dialog.remove();
										$element.trigger('click');
										return false;
									}

									elgg.trigger_hook('refresh:lists', 'framework', hookParams);

									$form.resetForm();
								},
								iframe : false

							});

							$form.ajaxForm(params);
						}
					}
				})
			})


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