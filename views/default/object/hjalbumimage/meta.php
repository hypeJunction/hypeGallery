<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof \ElggEntity) {
	return;
}

$owner = $entity->getOwnerEntity();
$owner_link = elgg_view('output/url', [
	'href' => $owner->getURL(),
	'text' => $owner->name,
	'is_trusted' => true,
]);

$meta = [];
$meta[] = elgg_echo('gallery:byline', [$owner_link, elgg_view_friendly_time($entity->time_created)]);
if ($entity->description) {
	$meta[] = elgg_view('output/longtext', ['value' => $entity->description]);
}

if (HYPEGALLERY_CATEGORIES) {
	$meta[] = elgg_view('output/gallery/categories', ['value' => $entity->categories]);
}

if (HYPEGALLERY_COPYRIGHTS) {
	$meta[] = elgg_view('output/text', ['value' => $entity->copyright]);
}

if (HYPEGALLERY_INTERFACE_LOCATION) {
	$meta[] = elgg_view('output/location', ['value' => $entity->location]);
}

if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$meta[] = elgg_view('output/date', ['value' => $entity->date]);
}

echo '<ul class="gallery-media-meta-details elgg-subtext">';
foreach ($meta as $m) {
	if (!$m) {
		continue;
	}

	echo '<li>' . $m . '</li>';
}

echo '</ul>';
