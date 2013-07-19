<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity))
	return;

if ($entity->tags) {
	$meta[] = elgg_view('output/tags', array('entity' => $entity));
}

if (HYPEGALLERY_CATEGORIES) {
	$meta[] = elgg_view('output/category', array('entity' => $entity));
}
if (HYPEGALLERY_COPYRIGHTS) {
	$meta[] = elgg_view('output/gallery/copyright', array('entity' => $entity));
}
if (HYPEGALLERY_INTERFACE_LOCATION) {
	$meta[] = elgg_view('output/location', array('entity' => $entity));
}
if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$meta[] = elgg_view('output/date', array('value' => $entity->date));
}

$meta[] = elgg_view('output/longtext', array(
	'value' => $entity->description
));

$meta[] = elgg_view_menu('entity', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'gallery-media-menu',
));

echo '<ul class="gallery-media-meta-details">';
foreach ($meta as $m) {
	if (!$m) {
		continue;
	}
	echo '<li>' . $m . '</li>';
}
echo '</ul>';