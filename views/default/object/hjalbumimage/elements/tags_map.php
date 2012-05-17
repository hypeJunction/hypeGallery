<?php

$entity = elgg_extract('entity', $vars);

$tags = hj_gallery_get_image_tags($entity);

if (!$tags) {
	return true;
}

$tags_map = '<style type="text/css">';
foreach ($tags as $tag) {
	$tags_map .= elgg_view_entity($tag, array('return_type' => 'styles'));
}
$tags_map .= '</style>';

$tags_map .= elgg_view_entity_list($tags, array(
	'return_type' => 'map',
	'list_id' => "hj-gallery-tags-map-$entity->guid",
	'list_class' => 'hj-gallery-tags-map',
		));

echo $tags_map;