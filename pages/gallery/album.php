<?php

elgg_load_js('hj.framework.fieldcheck');

elgg_load_js('hj.framework.ajax');
elgg_load_js('hj.framework.tabs');

elgg_load_js('jquery.imgareaselect');
elgg_load_css('jquery.imgareaselect');

elgg_load_js('hj.gallery.cropper');
elgg_load_js('hj.gallery.tagger');

elgg_load_css('hj.gallery.base');

if (elgg_is_admin_logged_in()) {
    //elgg_load_js('hj.framework.tabs.sortable');
}

$username = get_input('username');
$owner = get_user_by_username($username);
$limit = get_input('limit', 6);
$offset = get_input('offset', 0);

$albums = hj_framework_get_entities_by_priority('object', 'hjalbum', $owner->guid, null, $limit, $offset);

$selected_album_guid = get_input('e');
$selected_album = get_entity($selected_album_guid);

$selected_image_guid = get_input('im');
$selected_image = get_entity($selected_image_guid);

if (!$selected_image && $selected_album) {
	$images = $selected_album->getContainedFiles('hjalbumimage');
	$selected_image = $images[0];
}


$selected_album = '<div id="hj-gallery-album-full">' . elgg_view_entity($selected_album, array('full_view' => true)) . '</div>';
$selected_image = '<div id="hj-gallery-image-full">' . elgg_view_entity($selected_image, array('full_view' => true)) . '</div>';

$col2 = elgg_view_module('album', null, $selected_album);
$col3 = elgg_view_module('image', null, $selected_image);

$page = elgg_view_layout('one_column', array(
    'content' => $col2 . $col3,
	'class' => 'hj-gallery-page'
));

echo elgg_view_page($title, $page);
