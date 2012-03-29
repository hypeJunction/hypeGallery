<?php
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

$selected_album_guid = get_input('e');
$selected_album = get_entity($selected_album_guid);

if ($selected_album) {
	$images = $selected_album->getContainedFiles('hjalbumimage');
}

$selected_album_view = '<div id="hj-gallery-album-full">' . elgg_view_entity($selected_album, array('full_view' => false)) . '</div>';

$target = "hj-gallery-carousel-$selected_album->guid";

$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbumimage',
	'container_guid' => $selected_album->guid,
	'count' => true,
	'limit' => $limit
);

$count = elgg_get_entities($data_options);
unset($data_options['count']);

$view_params = array(
	'full_view' => true,
	'list_id' => $target,
	'list_type' => get_input('list_type', 'list'),
	'list_class' => 'hj-view-list',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'pagination' => true,
	'data-options' => $data_options,
	'limit' => $limit,
	'count' => $count,
	'base_url' => 'hj/sync/priority'
);

$images_view = elgg_view_entity_list($images, $view_params);

$col2 = elgg_view_module('album', null, $selected_album_view);
$col3 = elgg_view_module('image', null, $images_view);

$page = elgg_view_layout('one_column', array(
    'content' => $col2 . $col3,
	'class' => 'hj-gallery-page'
));

echo elgg_view_page($title, $page);
