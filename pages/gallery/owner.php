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
$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'owner_guid' => $owner->guid
);
$count = elgg_get_entities(array(
    'type' => 'object',
    'subtype' => 'hjalbum',
    'owner_guid' => $owner->guid,
    'count' => true
));

$selected_album_guid = get_input('e');
$selected_album = get_entity($selected_album_guid);

$selected_image_guid = get_input('im');
$selected_image = get_entity($selected_image_guid);

if (!$selected_album && $albums) {
    $selected_album = $albums[0];
}

if (!$selected_image && $selected_album) {
	$images = $selected_album->getContainedFiles('hjalbumimage');
	$selected_image = $images[0];
}

$target = "hj-list-albums";
$view_params = array(
    'full_view' => false,
    'list_type' => 'gallery',
    'list_id' => $target,
    'gallery_class' => 'hj-album-list',
    'item_class' => 'hj-view-entity elgg-state-draggable',
    'pagination' => true,
    'offset' => $offset,
    'limit' => $limit,
    'count' => $count,
	'base_url' => 'hj/sync/priority',
	'data-options' => htmlentities(json_encode($data_options), ENT_QUOTES, 'UTF-8')
);

$content = elgg_view_entity_list($albums, $view_params);
unset($view_params['data-options']);

$form = hj_framework_get_data_pattern('object', 'hjalbum');

$params = array(
    'subtype' => 'hjalbum',
    'form_guid' => $form->guid,
    'owner_guid' => $owner->guid,
    'context' => 'gallery',
    'handler' => 'hjalbum',
    'target' => $target,
    'full_view' => false,
    'push_context' => 'gallery'
);

$params = array_merge($view_params, $params);
$params = hj_framework_extract_params_from_params($params);

$footer_menu = elgg_view_menu('hjsectionfoot', array(
    'sort_by' => 'priority',
    'class' => 'elgg-menu-hz hj-menu-hz hj-right',
    'params' => $params
        ));

$title = elgg_echo('hj:gallery:albums:owner', array($owner->name));

$col1 = elgg_view_module('albums', null, $content);
$col1 = elgg_view_module('aside', $title . $footer_menu, $col1);

$selected_album = '<div id="hj-gallery-album-full">' . elgg_view_entity($selected_album, array('full_view' => true)) . '</div>';
$selected_image = '<div id="hj-gallery-image-full">' . elgg_view_entity($selected_image, array('full_view' => true)) . '</div>';

$col2 = elgg_view_module('album', null, $selected_album);
$col3 = elgg_view_module('image', null, $selected_image);

$page = elgg_view_layout('one_column', array(
    'content' => $col1 . $col2 . $col3,
	'class' => 'hj-gallery-page'
));

echo elgg_view_page($title, $page);
