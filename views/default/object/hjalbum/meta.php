<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return;
}

$meta = array();
if (HYPEGALLERY_CATEGORIES) {
	$meta[] = elgg_view('output/gallery/categories', array('value' => $entity->categories));
}
if (HYPEGALLERY_COPYRIGHTS) {
	$meta[] = elgg_view('output/text', array('value' => $entity->copyright));
}
if (HYPEGALLERY_INTERFACE_LOCATION) {
	$meta[] = elgg_view('output/location', array('value' => $entity->location));
}
if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$meta[] = elgg_view('output/date', array('value' => $entity->date));
}

echo '<ul class="gallery-media-meta-details elgg-subtext">';
foreach ($meta as $m) {
	if (!$m) {
		continue;
	}
	echo '<li>' . $m . '</li>';
}
echo '</ul>';
