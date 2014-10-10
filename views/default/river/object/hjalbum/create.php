<?php

namespace hypeJunction\Gallery;

elgg_push_context('activity');

$item = elgg_extract('item', $vars);

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

$key = "river:create:object:hjalbum";
$image_count = $object->countImages();

$summary = elgg_echo($key, array($subject_link, $object_link, $image_count));

$attachments = elgg_view_entity($object);

echo elgg_view('river/item', array(
	'item' => $item,
	'message' => elgg_get_excerpt(strip_tags($object->description)),
	'summary' => $summary,
	'attachments' => $attachments
));

elgg_pop_context();
