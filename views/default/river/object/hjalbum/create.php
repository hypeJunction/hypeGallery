<?php

$item = elgg_extract('item', $vars);

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_link = elgg_view('framework/bootstrap/user/elements/name', array('entity' => $subject));
$object_link = elgg_view('framework/bootstrap/object/elements/title', array('entity' => $object));

$key = "river:create:object:hjalbum";
$image_count = $object->countImages();
$summary = elgg_echo($key, array($subject_link, $object_link, $image_count));

$attachments = elgg_view_entity($object, array(
	'river_time' => $item->posted
));

echo elgg_view('river/item', array(
	'item' => $item,
	'message' => elgg_get_excerpt(strip_tags($object->description)),
	'summary' => $summary,
	'attachments' => $attachments
));
