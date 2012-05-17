<?php

elgg_load_js('hj.gallery.base');

elgg_load_js('hj.comments.base');
elgg_load_css('hj.comments.base');
elgg_load_js('hj.likes.base');

elgg_load_js('hj.framework.carousel');
elgg_load_css('hj.framework.carousel');

elgg_load_js('jquery.imgareaselect');
elgg_load_css('jquery.imgareaselect');

elgg_load_js('hj.gallery.cropper');
elgg_load_js('hj.gallery.tagger');

elgg_load_css('hj.gallery.base');

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	$entity = get_entity(get_input('guid'));
}

if (!$entity) {
	return true;
}

$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');

if (elgg_in_context('album_full_view')) {
	$vars['images_list_type'] = get_input('list_type', 'gallery');
	$vars['thumb_size'] = 'full';
}
$view = "object/hjalbum/$list_type";
if (elgg_view_exists($view)) {
	echo elgg_view($view, $vars);
	return true;
}

echo elgg_view('object/hjalbum/elements/cover', $vars);