<?php

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'limit' => $limit,
	'offset' => $offset,
	'count' => true
);

$count = elgg_get_entities($data_options);
$data_options['count'] = false;

$albums = elgg_get_entities($data_options);

$target = "hj-list-albums";

$view_params = array(
	'full_view' => false,
	'list_type' => get_input('list_type', 'gallery'),
	'list_type_toggle' => true,
	'list_id' => $target,
	'list_class' => 'hj-album-list',
	'gallery_class' => 'elgg-gallery hj-album-gallery',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'pagination' => true,
	'offset' => $offset,
	'limit' => $limit,
	'count' => $count,
	'base_url' => 'hj/sync',
	'data-options' => $data_options
);

$content = elgg_view_entity_list($albums, $view_params);

$title = elgg_echo('hj:gallery:albums:all');

$content = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter_context' => 'all',
		));

echo elgg_view_page($title, $content);
