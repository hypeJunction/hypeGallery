<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

elgg.provide('framework');
elgg.provide('framework.gallery');
elgg.provide('framework.gallery.filedrop');

framework.gallery.filedrop.init = function() {

	var $filedrop = $('#gallery-filedrop');

	var container_guid = $filedrop.data('containerGuid');

	framework.gallery.filedrop.template = $('.gallery-template', $filedrop).html();
		
	$('.gallery-filedrop-fallback-trigger', $filedrop)
	.live('click', function(e) {
		e.preventDefault();
		$('#gallery-filedrop-fallback').trigger('click');
	})
		
	$filedrop
	.filedrop({
		fallback_id : 'gallery-filedrop-fallback',
		url: elgg.security.addToken(elgg.normalize_url('action/gallery/upload/filedrop?container_guid='+ container_guid)),
		paramname: 'filedrop_files',
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		},
		//allowedfiletypes : $filedrop.data('allowedfiletypes'),
		queuefiles : 100,
		maxfiles : 100,
		maxfilesize : <?php echo (int)ini_get('upload_max_filesize') ?>,

		uploadFinished:function(i, file, response){

			if (response.status >= 0) {

				$('#gallery-filedrop-fallback').val(''); // in case upload was triggered by fallback

				$.data(file).find('.elgg-state-uploaded').show();
				$.data(file).find('.gallery-filedrop-progressholder').replaceWith($(response.output.form)).find('input').focus();
				
				$.data(file).closest('form').find('[type="submit"]').show();

				$.data(file).after($('<input>').attr({type:'hidden',name:'filedrop_guids[]'}).val(response.output.image_guid));

				elgg.trigger_hook('ajax:success', 'framework', { response : response, element : $filedrop });
				elgg.ui.initDatePicker();

			} else {

				$.data(file).find('.elgg-state-failed').show();
				$.data(file).find('.gallery-filedrop-progressholder').replaceWith($('<p>').addClass('gallery-item-in-bulk').text(response.system_messages.error.join('')));
			}
		},
		
		error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					$('#gallery-filedrop-fallback').show();
					elgg.register_error(elgg.echo('hj:gallery:filedrop:browsernotsupported'));
					break;

				case 'TooManyFiles':
					elgg.register_error(elgg.echo('hj:gallery:filedrop:toomanyfiles'));
					break;

				case 'FileTooLarge':
					elgg.register_error(elgg.echo('hj:gallery:filedrop:filetoolarge'));
					break;

				case 'FileTypeNotAllowed':
					elgg.register_error(elgg.echo('hj:gallery:filedrop:filetypenotallowed'));
					break;

				default:
					break;
			}
		},

		beforeEach: function(file){

		},

		uploadStarted:function(i, file, len){
			if(file.type.match(/^image\//)){
				framework.gallery.filedrop.createImage(file, $filedrop);
			} else if(file.type.match(/^video\//)){
				framework.gallery.filedrop.createVideo(file, $filedrop);
			} else if(file.type.match(/^audio\//)){
				framework.gallery.filedrop.createAudio(file, $filedrop);
			} else {
				framework.gallery.filedrop.createPlaceholder(file, $filedrop);
			}
		},

		progressUpdated: function(i, file, progress) {
			$.data(file).find('.gallery-filedrop-progress').width(progress);
		}

	});

}

framework.gallery.filedrop.createImage = function(file, $container){

	var $preview = $(framework.gallery.filedrop.template),
	$image = $('img', $preview);

	var reader = new FileReader();

	$image.width(300);

	reader.onload = function(e){

		// e.target.result holds the DataURL which
		// can be used as a source of the image:

		$image.attr('src',e.target.result);
	};

	// Reading the file as a DataURL. When finished,
	// this will trigger the onload function above:
	reader.readAsDataURL(file);

	$preview.appendTo($('.gallery-filedrop-queue', $container));

	// Associating a preview container
	// with the file, using jQuery's $.data():

	$.data(file, $preview);
}


framework.gallery.filedrop.createVideo = function(file, $container){

	var $preview = $(framework.gallery.filedrop.template),
	$image = $('img', $preview);

	var reader = new FileReader();

	$image.replaceWith($('<video>').attr({width:300, height:200, controls:true}).html($('<source>')));

	reader.onload = function(e){

		// e.target.result holds the DataURL which
		// can be used as a source of the image:

		$image.find('source').attr('src',e.target.result).attr('type', file.type);
	};

	// Reading the file as a DataURL. When finished,
	// this will trigger the onload function above:
	reader.readAsDataURL(file);

	$preview.appendTo($('.gallery-filedrop-queue', $container));

	// Associating a preview container
	// with the file, using jQuery's $.data():

	$.data(file, $preview);
}



framework.gallery.filedrop.createAudio = function(file, $container){

	var $preview = $(framework.gallery.filedrop.template),
	$image = $('img', $preview);

	var reader = new FileReader();

	$image.replaceWith($('<audio>').attr({controls:true}).html($('<source>')));

	reader.onload = function(e){

		// e.target.result holds the DataURL which
		// can be used as a source of the image:

		$image.find('source').attr('src',e.target.result).attr('type', file.type);
	};

	// Reading the file as a DataURL. When finished,
	// this will trigger the onload function above:
	reader.readAsDataURL(file);

	$preview.appendTo($('.gallery-filedrop-queue', $container));

	// Associating a preview container
	// with the file, using jQuery's $.data():

	$.data(file, $preview);
}


framework.gallery.filedrop.createPlaceholder = function(file, $filedrop){

	var $preview = $(framework.gallery.filedrop.template),
	$image = $('img', $preview);

	var reader = new FileReader();

	$image.width(300);
			
	$image.attr('src', elgg.get_site_url() + 'mod/hypeGallery/graphics/placeholder.png');

	reader.readAsDataURL(file);

	$preview.appendTo($('.gallery-filedrop-queue', $filedrop));

	$.data(file, $preview);
}

elgg.register_hook_handler('init', 'system', framework.gallery.filedrop.init);

<?php if (FALSE) : ?></script><?php
endif;
?>
