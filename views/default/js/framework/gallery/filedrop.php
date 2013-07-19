<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('framework');
	elgg.provide('framework.gallery');
	elgg.provide('framework.gallery.filedrop');

	framework.gallery.filedrop.init = function() {

		var $filedrop = $('#gallery-filedrop');
		var $filedropfallback = $('.filedrop-fallback', $filedrop);
		var $message = $('.filedrop-message', $filedrop);

		var container_guid = $filedrop.data('containerGuid');
		var batch_time = $filedrop.data('batchTime');

		$filedrop
		.live('click', function(e) {
			e.preventDefault();
			$('#gallery-filedrop-input').trigger('click');
		})
		
		$filedrop.filedrop({

				fallback_id : 'gallery-filedrop-input',
				url: elgg.security.addToken(elgg.normalize_url('action/gallery/upload?container_guid=' + container_guid + '&batch_upload_time=' + batch_time)),              // upload handler, handles each file separately, can also be a function returning a url
				paramname: 'gallery_files',          // POST parameter name used on serverside to reference file
				headers: {							// Send additional request headers
					'X-Requested-With': 'XMLHttpRequest'
				},

				//allowedfiletypes: ['image/jpg', 'image/jpeg','image/png','image/gif'],   // filetypes allowed by Content-Type.  Empty array means no restrictions
				allowedfiletypes : $filedrop.data('allowedfiletypes'),
				queuefiles: 10,

				uploadFinished:function(i, file, response){

					$.data(file).addClass('elgg-state-complete');
					
				},

				error: function(err, file) {
					switch(err) {
						case 'BrowserNotSupported':
							elgg.register_error(elgg.echo('hj:framework:filedrop:browsernotsupported'));
							$filedrop.hide();
							//$filedropfallback.show();
							break;

						case 'FileTypeNotAllowed':
							elgg.register_error(elgg.echo('hj:framework:filedrop:filetypenotallowed'));
							break;

						default:
							break;
					}
				},

				// Called before each upload is started
				beforeEach: function(file){
				},

				uploadStarted:function(i, file, len){
					if(file.type.match(/^image\//)){
						framework.gallery.filedrop.createImage(file, $filedrop);
					} else {
						framework.gallery.filedrop.createPlaceholder(file, $filedrop);
					}
				},

				progressUpdated: function(i, file, progress) {
					$.data(file).find('.filedrop-progress').width(progress);
				}

			});

	}

	framework.gallery.filedrop.template = '<div class="filedrop-preview">'+
		'<span class="filedrop-imageholder">'+
		'<img />'+
		'<span class="elgg-state-uploaded"></span>'+
		'</span>'+
		'<div class="filedrop-progressholder">'+
		'<div class="filedrop-progress"></div>'+
		'</div>'+
		'</div>';


	framework.gallery.filedrop.createImage = function(file, $container){

		var preview = $(framework.gallery.filedrop.template),
		image = $('img', preview);

		var reader = new FileReader();

		image.width = 100;
		image.height = 100;

		reader.onload = function(e){

			// e.target.result holds the DataURL which
			// can be used as a source of the image:

			image.attr('src',e.target.result);
		};

		// Reading the file as a DataURL. When finished,
		// this will trigger the onload function above:
		reader.readAsDataURL(file);

		$('.filedrop-message', $container).hide();
		preview.appendTo($('.filedrop', $container));

		// Associating a preview container
		// with the file, using jQuery's $.data():

		$.data(file,preview);
	}

	framework.gallery.filedrop.createPlaceholder = function(file, $filedrop){

		var preview = $(framework.gallery.filedrop.template),
		image = $('img', preview);

		var reader = new FileReader();

		image.width = 100;
		image.height = 100;
			
		image.attr('src', elgg.get_site_url() + 'mod/hypeFramework/graphics/filedrop/background_tile_1.jpg');

		reader.readAsDataURL(file);

		$('.filedrop-message', $filedrop).hide();
		preview.appendTo($('.filedrop', $filedrop));

		$.data(file, preview);
	}

	elgg.register_hook_handler('init', 'system', framework.gallery.filedrop.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
