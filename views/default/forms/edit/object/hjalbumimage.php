<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($entity)) {
	return;
}

$cover = elgg_view_entity_icon($entity, $size);

$guid = $entity->guid;

$title = elgg_view('input/text', array(
	'name' => "files[$guid][title]",
	'placeholder' => elgg_echo('label:hjalbumimage:title'),
	'value' => $entity->title
		));

$info_link = elgg_view('output/url', array(
	'text' => elgg_echo('gallery:edit:more'),
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
		));

$info .= '<label>' . elgg_echo('label:hjalbumimage:description') . '</label>';
$info .= elgg_view('input/plaintext', array(
	'name' => "files[$guid][description]",
	'value' => $entity->description
		));

$info .= '<label>' . elgg_echo('label:hjalbumimage:tags') . '</label>';
$info .= elgg_view('input/tags', array(
	'name' => "files[$guid][tags]",
	'value' => $entity->tags
		));

if (HYPEGALLERY_CATEGORIES) {
	$info .= '<label>' . elgg_echo('label:hjalbumimage:category') . '</label>';
	$info .= elgg_view('input/gallery/categories', array(
		'name' => "files[$guid][categories]",
		'value' => $entity->categories
	));
}

if (HYPEGALLERY_COPYRIGHTS) {
	$info .= '<label>' . elgg_echo('label:hjalbumimage:copyright') . '</label>';
	$info .= elgg_view('input/text', array(
		'name' => "files[$guid][copyright]",
		'value' => $entity->copyright
	));
}

if (HYPEGALLERY_INTERFACE_LOCATION) {
	$info .= '<label>' . elgg_echo('label:hjalbumimage:location') . '</label>';
	$info .= elgg_view('input/location', array(
		'name' => "files[$guid][location]",
		'value' => $entity->location
	));
}

if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$info .= '<label>' . elgg_echo('label:hjalbumimage:date') . '</label>';
	$info .= elgg_view('input/date', array(
		'name' => "files[$guid][date]",
		'value' => $entity->date
	));
}

//$info .= '<label>' . elgg_echo('label:hjalbumimage:access_id') . '</label>';
//	$info .= elgg_view('input/access', array(
//		'name' => "files[$guid][access_id]",
//		'value' => $entity->access_id
//			));

$html = <<<__HTML
	<div class="clearfix"></div>
	<div class="gallery-item-in-bulk">
		<div class="gallery-media-form-title">$title</div>
		<div class="gallery-media-form-info-link">$info_link</div>
		<div id="gallery-info-$entity->guid" class="gallery-media-extras hidden">
			$info
		</div>
	</div>
__HTML;

echo $html;
echo elgg_view('input/hidden', array(
	'name' => 'filedrop_guids[]',
	'value' => $guid,
));
