<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars, false);
$full = \elgg_extract('full_view', $vars, false);
$size = \elgg_extract('size', $vars, '325x200');

if (!$entity instanceof hjAlbumImage) {
	return true;
}

$cover = \elgg_view_entity_icon($entity, $size);

if (!\elgg_in_context('gallery-manage')) {
	$cover .= \elgg_view('output/url', [
		'text' => '<i class="gallery-icon-slideshow gallery-icon-small"></i>',
		'title' => \elgg_echo('gallery:slideshow'),
		'class' => 'gallery-popup',
		'href' => '#',
		'data-guid' => $entity->guid
	]);
}

$title = \elgg_view('output/url', [
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => \elgg_strip_tags($entity->description),
	'is_trusted' => true
]);

$info_link = \elgg_view('output/url', [
	'text' => '<i class="gallery-icon-info icon-small"></i>',
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
]);

$menu = \elgg_view_menu('entity', [
	'entity' => $entity,
	'sort_by' => 'priority',
]);


$owner = get_entity($entity->owner_guid);
if ($owner) {
	$owner_icon = \elgg_view('output/url', [
		'text' => \elgg_view('output/img', [
			'src' => $owner->getIconURL('small'),
		]),
		'href' => $owner->getURL(),
		'title' => $owner->name
	]);
	$owner_link = \elgg_view('output/url', [
		'href' => $owner->getURL(),
		'text' => $owner->name,
		'is_trusted' => true,
	]);
}

if (\elgg_in_context('gallery-manage')) {
	$summary = \elgg_view('forms/edit/object/hjalbumimage', $vars);
	$alt_menu = \elgg_view_menu('manage_album_image', [
		'entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'gallery-media-menu',
	]);
} else {
	$info = \elgg_view('object/hjalbum/meta', $vars);
	$info .= \elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'gallery-description'
	]);

	$subtitle = \elgg_echo('gallery:byline', [$owner_link, \elgg_view_friendly_time($entity->time_created)]);

	$summary = \elgg_view('object/elements/summary', [
		'entity' => $entity,
		'title' => false,
		'content' => $info,
		'subtitle' => $subtitle,
	]);
}

$container = get_entity($entity->container_guid);
if ($container instanceof hjAlbum) {
	$album = \elgg_view_entity_icon($container, 'small');
}

if (!$full) {
	$html = sprintf(
		'<div class="gallery-media-cover">%s%s<div class="gallery-media-meta"><div class="gallery-media-album">%s</div><div class="gallery-media-author">%s</div><div class="gallery-media-title">%s%s</div></div><div id="gallery-info-%d" class="gallery-media-extras hidden">%s</div>%s</div>',
		$alt_menu,
		$cover,
		$album,
		$owner_icon,
		$title,
		$info_link,
		$entity->guid,
		$summary,
		$menu
	);
} else {
	$html = sprintf(
		'<div class="gallery-media-cover">%s%s<div class="gallery-media-meta"><div class="gallery-media-album">%s</div><div class="gallery-media-author">%s</div><div class="gallery-media-title">%s</div>%s%s</div></div>',
		$alt_menu,
		$cover,
		$album,
		$owner_icon,
		$title,
		$summary,
		$menu
	);
}

echo $html;
