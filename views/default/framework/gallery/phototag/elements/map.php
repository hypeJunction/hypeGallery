<?php

$entity = elgg_extract('entity', $vars);

$tags = elgg_get_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => 'hjannotation',
	'container_guids' => $entity->guid,
	'metadata_name_value_pairs' => array(
		'name' => 'annotation_name',
		'value' => 'hjimagetag'
	),
	'limit' => 0,
	'order_by_metadata' => array(
		'name' => 'annotation_value',
		'direction' => 'ASC'
	)
));

echo '<ul class="hj-gallery-tags-list">';
foreach ($tags as $tag) {
	echo '<li>';
	echo elgg_view_entity($tag, array(
		'return_type' => 'list'
	));
	echo '</li>';
}
echo '</ul>';

echo '<ul class="hj-gallery-tags-map">';
foreach ($tags as $tag) {
	echo '<li>';
	echo elgg_view_entity($tag, array(
		'return_type' => 'map'
	));
	echo '</li>';
}
echo '</ul>';