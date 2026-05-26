<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars, false);

if (!$entity instanceof \ElggEntity) {
	return;
}

$cover = \elgg_view_entity_icon($entity, $size);

$guid = $entity->guid;

$title = \elgg_view('input/text', [
	'name' => "files[$guid][title]",
	'placeholder' => \elgg_echo('label:hjalbumimage:title'),
	'value' => $entity->title
]);

$info_link = \elgg_view('output/url', [
	'text' => \elgg_echo('gallery:edit:more'),
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
]);

$info .= '<label>' . \elgg_echo('label:hjalbumimage:description') . '</label>';
$info .= \elgg_view('input/plaintext', [
	'name' => "files[$guid][description]",
	'value' => $entity->description
]);

$info .= '<label>' . \elgg_echo('label:hjalbumimage:tags') . '</label>';
$info .= \elgg_view('input/tags', [
	'name' => "files[$guid][tags]",
	'value' => $entity->tags
]);

if (HYPEGALLERY_CATEGORIES) {
	$info .= '<label>' . \elgg_echo('label:hjalbumimage:category') . '</label>';
	$info .= \elgg_view('input/gallery/categories', [
		'name' => "files[$guid][categories]",
		'value' => $entity->categories
	]);
}

if (HYPEGALLERY_COPYRIGHTS) {
	$info .= '<label>' . \elgg_echo('label:hjalbumimage:copyright') . '</label>';
	$info .= \elgg_view('input/text', [
		'name' => "files[$guid][copyright]",
		'value' => $entity->copyright
	]);
}

if (HYPEGALLERY_INTERFACE_LOCATION) {
	$info .= '<label>' . \elgg_echo('label:hjalbumimage:location') . '</label>';
	$info .= \elgg_view('input/location', [
		'name' => "files[$guid][location]",
		'value' => $entity->location
	]);
}

if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$info .= '<label>' . \elgg_echo('label:hjalbumimage:date') . '</label>';
	$info .= \elgg_view('input/date', [
		'name' => "files[$guid][date]",
		'value' => $entity->date
	]);
}

//$info .= '<label>' . elgg_echo('label:hjalbumimage:access_id') . '</label>';
//	$info .= elgg_view('input/access', array(
//		'name' => "files[$guid][access_id]",
//		'value' => $entity->access_id
//			));

echo '<div class="clearfix"></div>';
echo '<div class="gallery-item-in-bulk">';
echo '<div class="gallery-media-form-title">' . $title . '</div>';
echo '<div class="gallery-media-form-info-link">' . $info_link . '</div>';
echo '<div id="gallery-info-' . $entity->guid . '" class="gallery-media-extras hidden">';
echo $info;
echo '</div>';
echo '</div>';
echo \elgg_view('input/hidden', [
	'name' => 'filedrop_guids[]',
	'value' => $guid,
]);
