<?php

namespace hypeJunction\Gallery;

elgg_push_context('activity');

$item = elgg_extract('item', $vars);
$posted = $item->posted;
$river_time = "river_$posted";

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_link = elgg_view('output/url', array(
	'text' => $subject->name,
	'href' => $subject->getURL()
		));
$object_link = elgg_view('output/url', array(
	'text' => $object->title,
	'href' => $object->getURL()
		));

$key = "river:update:object:hjalbum";
$image_count = $object->countImages();
$raw = $object->$river_time;
$image_guids_new = json_decode((string) $raw, true);
if (!is_array($image_guids_new)) {
	// Backward compat with serialize()d metadata written by older versions
	$image_guids_new = @unserialize((string) $raw, ['allowed_classes' => false]);
}
if (!is_array($image_guids_new)) {
	$image_guids_new = [];
}

foreach ($image_guids_new as $guid) {
	if (get_entity($guid)) {
		$image_count_new++;
	}
}

if (!$image_count_new) {
	$image_count_new = elgg_echo('gallery:new');
}

$summary = elgg_echo($key, array($subject_link, $image_count_new, $object_link, $image_count));

$attachments = elgg_view_entity($object, array(
	'river_time' => $river_time
		));

echo elgg_view('river/item', array(
	'item' => $item,
	'message' => elgg_get_excerpt(strip_tags($object->description)),
	'summary' => $summary,
	'attachments' => $attachments
));

elgg_pop_context();
