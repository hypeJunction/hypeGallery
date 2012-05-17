<?php

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$group_guid = get_input('group_guid', null);
$group = get_entity($group_guid);

elgg_push_breadcrumb(elgg_echo('groups'));
elgg_push_breadcrumb($group->name);

$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'container_guid' => $group->guid,
	'limit' => $limit,
	'offset' => $offset,
	'count' => true
);

$count = elgg_get_entities($data_options);
$data_options['count'] = false;

$albums = elgg_get_entities($data_options);

$target = "hj-list-albums";

$view_params = array(
	'full_view' => true,
	'list_type' => get_input('list_type', 'gallery'),
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

$title = elgg_echo('hj:gallery:albums:group', array($group->name));

$content = elgg_view_layout('one_sidebar', array(
	'content' => $content,
	'title' => $title,
		));

echo elgg_view_page($title, $content);
