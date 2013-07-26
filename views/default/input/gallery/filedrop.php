<?php
/**
 * Creates drag&drop file uploader
 * In non-html5 browsers falls back to regular file input
 *
 * On upload success, new file entity guid is stored in hidden inputs that submitted with the form in uploads[] array
 * @uses $vars['allowedfiletypes'] Options. Can be used to define allowed mimetypes
 *
 */
elgg_load_js('jquery.filedrop.js');
elgg_load_js('gallery.filedrop.js');
elgg_load_css('gallery.filedrop.css');

$entity = elgg_extract('entity', $vars);
$time = elgg_extract('batch_upload_time', $vars);
$types = htmlentities(json_encode(array('image/jpeg', 'image/jpg', 'image/png', 'image/gif')));

$attr = elgg_format_attributes(array(
	'id' => 'gallery-filedrop',
	'class' => 'gallery-filedrop-container',
	'data-filetypes' => $types,
	'data-container-guid' => $entity->guid,
	'data-batch-time' => $time
		));

$fallback_link = elgg_view('output/url', array(
	'text' => elgg_echo('hj:gallery:filedrop:fallback'),
	'href' => '#gallery-filedrop',
	'class' => 'gallery-filedrop-fallback-trigger'
));

$instructions = elgg_echo('hj:gallery:filedrop:instructions', array($fallback_link));

$fallback = elgg_view('input/file', array(
	'id' => 'gallery-filedrop-fallback',
	'name' => 'gallery_files[]',
	'multiple' => true,
	'class' => 'hidden',
));

echo <<<__HTML
<div class="gallery-filedrop-wrap">
	<div $attr>
		<div class="gallery-filedrop">
			<span class="gallery-filedrop-message">$instructions</span>
			$fallback
		</div>
		<div class="gallery-filedrop-queue">
		</div>
		<div class="gallery-template hidden">
			<div class="gallery-media-summary">
				<div class="gallery-album-image-placeholder">
					<img />
					<span class="elgg-state-uploaded"></span>
					<span class="elgg-state-failed"></span>
				</div>
				<div class="gallery-filedrop-progressholder">
					<div class="gallery-filedrop-progress"></div>
				</div>
			</div>
		</div>
	</div>
</div>
__HTML;
