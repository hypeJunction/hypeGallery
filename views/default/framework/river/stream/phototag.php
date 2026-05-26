<?php

namespace hypeJunction\Gallery;

$item = \elgg_extract('item', $vars);

$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

$subject_link = \elgg_view('output/url', [
	'text' => $subject->name,
	'href' => $subject->getURL()
]);

$image = $object->getContainerEntity();
$image_owner = $image->getOwnerEntity();
$tagged_user = $object->getOwnerEntity();

if ($tagged_user->guid != $image_owner->guid) {
	$object_link = \elgg_view('output/url', [
		'text' => $tagged_user->name,
		'href' => $tagged_user->guid
	]);
} else {
	$object_link = $object->title;
}

$image_link = \elgg_view('output/url', [
	'text' => $image->title,
	'href' => $image->getURL()
]);

$summary = \elgg_echo('gallery:phototag:river', [$subject_link, $object_link, $image_link]);

echo \elgg_view('river/elements/layout', [
	'item' => $item,
	'summary' => $summary,
]);
