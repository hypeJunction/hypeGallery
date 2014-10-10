<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

$subtitle = elgg_view('object/hjalbum/meta', array(
	'entity' => $entity,
	'full_view' => true
		));

$metadata = elgg_view_menu('entity', array(
	'entity' => $entity,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority'
		));

if ($full) {

	$body .= '<div class="gallery-media-full-view">';
	$body .= elgg_view_entity_icon($entity, 'taggable', array(
		'href' => false,
		'img_class' => 'taggable'
	));
	$body .= '</div>';

	$summary = elgg_view('object/hjalbumimage/meta', $vars);

	$comments = elgg_view_comments($entity);

	echo '<div class="gallery-media-full">';
	echo "$body$summary$comments";
	echo '</div>';
} else {

	$icon = elgg_view_entity_icon($entity, 'medium');

	$params = array(
		'entity' => $entity,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => elgg_get_excerpt(strip_tags($entity->description))
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body);
}