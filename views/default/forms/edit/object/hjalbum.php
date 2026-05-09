<?php

namespace hypeJunction\Gallery;

$container = elgg_extract('container', $vars, false);
$entity = elgg_extract('entity', $vars, false);
$container = ($entity instanceof \ElggEntity) ? $entity->getContainerEntity() : $container;

$sticky_values = elgg_get_sticky_values('edit:object:hjalbum');
$time = time();

$main .= '<label>' . elgg_echo('label:hjalbum:title') . '</label>';
$main .= elgg_view('input/text', [
	'name' => 'title',
	'placeholder' => elgg_echo('label:hjalbum:title'),
	'required' => true,
	'value' => $sticky_values['title'] ?? $entity->title
]);


$main .= '<label>' . elgg_echo('label:hjalbum:upload') . '</label>';
$main .= elgg_view('input/gallery/filedrop', [
	'entity' => $entity,
	'batch_upload_time' => $sticky_values['batch_upload_time'] ?? $time
]);

$main .= '<div class="clearfix">';
if (HYPEGALLERY_COLLABORATIVE_ALBUMS) {
	$main .= '<div class="gallery-col-half">';
	$main .= '<label>' . elgg_echo('label:hjalbum:permission') . '</label>';
	$main .= elgg_view('input/dropdown', [
		'name' => 'permission',
		'required' => true,
		'options_values' => get_permissions_options($container),
		'value' => $sticky_values['permission'] ?? $entity->permission
	]);
	$main .= '</div>';
}

$main .= '<div class="gallery-col-half">';
$main .= '<label>' . elgg_echo('label:hjalbum:access_id') . '</label>';
$main .= elgg_view('input/access', [
	'entity' => $entity,
	'value' => $sticky_values['access_id'] ?? $entity->access_id,
	'name' => 'access_id',
]);
$main .= '</div>';
$main .= '</div>';

$details .= '<label>' . elgg_echo('label:hjalbum:description') . '</label>';
$details .= elgg_view('input/plaintext', [
	'name' => 'description',
	'value' => $sticky_values['description'] ?? $entity->description
]);

$details .= '<label>' . elgg_echo('label:hjalbum:tags') . '</label>';
$details .= elgg_view('input/tags', [
	'name' => 'tags',
	'value' => $sticky_values['tags'] ?? $entity->tags
]);

if (HYPEGALLERY_CATEGORIES) {
	$details .= '<label>' . elgg_echo('label:hjalbum:category') . '</label>';
	$details .= elgg_view('input/gallery/categories', [
		'name' => 'categories',
		'value' => $sticky_values['categories'] ?? $entity->categories
	]);
}

if (HYPEGALLERY_COPYRIGHTS) {
	$details .= '<label>' . elgg_echo('label:hjalbum:copyright') . '</label>';
	$details .= elgg_view('input/text', [
		'name' => 'copyright',
		'value' => $sticky_values['copyright'] ?? $entity->copyright
	]);
}

if (HYPEGALLERY_INTERFACE_LOCATION) {
	$details .= '<label>' . elgg_echo('label:hjalbum:location') . '</label>';
	$details .= elgg_view('input/location', [
		'name' => 'location',
		'value' => $sticky_values['location'] ?? $entity->location
	]);
}

if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$details .= '<label>' . elgg_echo('label:hjalbum:date') . '</label>';
	$details .= elgg_view('input/date', [
		'name' => 'date',
		'value' => $sticky_values['date'] ?? $entity->date
	]);
}

echo '<div class="gallery-edit-form clearfix">';
echo $main;

echo '<div class="elgg-foot">';

echo '<div id="gallery-hidden-details" class="hidden">';
echo $details;
echo '</div>';

echo elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $entity->guid
]);

echo elgg_view('input/hidden', [
	'name' => 'container_guid',
	'value' => $container->guid
]);

echo elgg_view('input/hidden', [
	'name' => 'batch_upload_time',
	'value' => $sticky_values['batch_upload_time'] ?? $time
]);

echo elgg_view('output/url', [
	'text' => '<i class="gallery-icon-info"></i><span>' . elgg_echo('gallery:edit:details') . '</span>',
	'href' => '#gallery-hidden-details',
	'rel' => 'toggle',
	'class' => 'gallery-hidden-details-toggle'
]);

echo elgg_view('input/submit', [
	'value' => elgg_echo('save'),
	'class' => 'elgg-button elgg-button-submit float-alt'
]);
echo '</div>';

echo '</div>';
