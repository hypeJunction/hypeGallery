<?php

elgg_load_js('hj.framework.ajax');
elgg_load_js('hj.framework.tabs');

elgg_load_js('jquery.imgareaselect');
elgg_load_css('jquery.imgareaselect');

elgg_load_js('hj.gallery.cropper');
elgg_load_js('hj.gallery.tagger');

elgg_load_css('hj.gallery.base');

$limit = get_input('limit', 3);
$offset = get_input('offset', 0);

$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'limit' => $limit,
	'offset' => $offset
);

$albums = elgg_get_entities($data_options);

$count = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'count' => true
		));

$target = "hj-list-albums";

$view_params = array(
	'full_view' => true,
	'list_type' => 'list',
	'list_id' => $target,
	'list_class' => 'hj-album-list',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'pagination' => true,
	'offset' => $offset,
	'limit' => $limit,
	'count' => $count,
	'base_url' => 'hj/sync',
	'data-options' => $data_options
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
	'full_view' => true,
	'push_context' => 'gallery',
	'dom_order' => 'prepend'
);

$params = array_merge($view_params, $params);
$params = hj_framework_extract_params_from_params($params);
$title_menu_params = hj_framework_json_query($params);
$title = elgg_echo('hj:gallery:albums:all');

$album_max = elgg_get_plugin_setting('album_max', 'hypeGallery');
$album_count = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'owner_guid' => elgg_get_logged_in_user_guid(),
	'count' => true
		));

if (!$album_max || $album_count < $album_max || $album_max == '0') {

	$add = array(
		'name' => 'add',
		'title' => elgg_echo('hj:gallery:addnew'),
		'text' => elgg_view('input/button', array('value' => elgg_echo('hj:gallery:addnew'), 'class' => 'elgg-button-action')),
		'href' => "action/framework/entities/edit",
		'data-options' => htmlentities($title_menu_params, ENT_QUOTES, 'UTF-8'),
		'is_action' => true,
		'rel' => 'fancybox',
		'class' => "hj-ajaxed-add",
		'priority' => 200
	);

	elgg_register_menu_item('title', $add);
}

if (elgg_is_logged_in()) {
	$title_menu = elgg_view_menu('title');
}

$content = elgg_view_module('albums', null, $content);
$content = elgg_view_module('aside', $title . $title_menu, $content);

$content = elgg_view_layout('one_sidebar', array(
	'content' => $content,
	'sidebar' => elgg_view_menu('page', array('context' => 'gallery'))
		));

$page = elgg_view_layout('one_column', array(
	'content' => $content,
	'class' => 'hj-gallery-page'
		));

echo elgg_view_page($title, $page);
