<?php

elgg_push_context('activity');

$item = elgg_extract('item', $vars);
$posted = $item->posted;
$river_time = "river_$posted";

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_link = elgg_view('framework/bootstrap/user/elements/name', array('entity' => $subject));
$object_link = elgg_view('framework/bootstrap/object/elements/title', array('entity' => $object));

$key = "river:update:object:hjalbum";
$image_count = $object->countImages();
$image_guids_new = unserialize($object->$river_time);

foreach ($image_guids_new as $guid) {
	if (get_entity($guid)) {
		$image_count_new++;
	}
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