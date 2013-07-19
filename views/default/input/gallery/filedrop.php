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

echo '<div class="filedrop-wrap">';
echo "<div id=\"gallery-filedrop\" data-allowedfiletypes=\"$types\" data-container-guid=\"$entity->guid\" data-batch-time=\"$time\">";
echo '<div class="filedrop">';
echo '<span class="filedrop-message">' . elgg_echo('hj:gallery:filedrop:instructions') . '</span>';
echo elgg_view('input/file', array(
	'id' => 'gallery-filedrop-input',
	'name' => 'gallery_files',
	'multiple' => true,
	'class' => 'hidden',
));
echo elgg_view('input/file', array(
	'id' => 'gallery-filedrop-fallback',
	'name' => 'gallery_files[]',
	'multiple' => true,
	'class' => 'hidden',
));
echo '</div>';
echo '</div>';
echo '</div>';