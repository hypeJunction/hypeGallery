<?php

$entity = elgg_extract('entity', $vars);
$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');

if (elgg_in_context('activity') || elgg_in_context('widgets') || elgg_in_context('main')) {
	return true;
} else if ($full) {
	$thumb_size = elgg_extract('thumb_size', $vars, 'large');
} else {
	$thumb_size = elgg_extract('thumb_size', $vars, 'medium');
}

$limit = get_input('limit', 0);

$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbumimage',
	'container_guid' => $entity->guid,
	'limit' => $limit,
	'offset' => get_input('offset', 0),
	'count' => true
);

$count = elgg_get_entities($data_options);
$data_options['count'] = false;

$albums = elgg_get_entities($data_options);

$target = "hj-gallery-album-images-$entity->guid";

$view_params = array(
	'full_view' => false,
	'list_type' => elgg_extract('images_list_type', $vars, 'gallery'),
	'list_id' => $target,
	'list_class' => 'hj-album-images-list',
	'gallery_class' => 'elgg-gallery hj-images-gallery',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'thumb_size' => $thumb_size,
	'pagination' => true,
	'offset' => 0,
	'limit' => $limit,
	'count' => $count,
	'base_url' => 'hj/sync',
	'data-options' => $data_options
);

$content = elgg_view_entity_list($albums, $view_params);

echo $content;
