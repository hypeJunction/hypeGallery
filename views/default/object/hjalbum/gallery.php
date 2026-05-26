<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars, false);
$full = \elgg_extract('full_view', $vars, false);
$size = \elgg_extract('size', $vars, '325x200');

if (!$entity instanceof hjAlbum) {
	return true;
}

$title = \elgg_view('output/url', [
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => \elgg_strip_tags($entity->description),
	'is_trusted' => true
]);

$count = $entity->getContainedFiles(['count' => true]);
if ($count) {
	$cover = \elgg_view_entity_icon($entity, $size);
	$cover .= \elgg_view('output/url', [
		'text' => '<i class="gallery-icon-slideshow gallery-icon-small"></i>',
		'title' => \elgg_echo('gallery:slideshow'),
		'class' => 'gallery-popup',
		'href' => '#',
		'data-guid' => $entity->guid
	]);
} else {
	$cover = '<div class="gallery-album-cover-placeholder" style="display:block;width:100%;height:200px;"></div>';
}

$owner = get_entity($entity->owner_guid);
if ($owner) {
	$author = \elgg_view('output/url', [
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

$info_link = \elgg_view('output/url', [
	'text' => '<i class="gallery-icon-info icon-small"></i>',
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
]);

$info .= \elgg_view('object/hjalbum/meta', $vars);
$info .= \elgg_view('output/longtext', [
	'value' => $entity->description,
	'class' => 'gallery-description'
]);

$subtitle = \elgg_echo('gallery:byline', [$owner_link, \elgg_view_friendly_time($entity->time_created)]);

$menu = \elgg_view_menu('entity', [
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'gallery-media-menu',
]);

if (!$full) {
	$summary = \elgg_view('object/elements/summary', [
		'entity' => $entity,
		'title' => false,
		'subtitle' => $subtitle,
		'tags' => $entity->tags,
		'content' => $info,
	]);

	$html = sprintf(
		'<div class="gallery-album-cover">%s<div class="gallery-album-meta"><div class="gallery-album-count">%s</div><div class="gallery-album-author">%s</div><div class="gallery-album-title">%s</div><div class="gallery-album-info-link">%s</div></div><div id="gallery-info-%d" class="gallery-media-extras hidden">%s</div>%s</div>',
		$cover,
		$count,
		$author,
		$title,
		$info_link,
		$entity->guid,
		$summary,
		$menu
	);
} else {
	$summary = \elgg_view('object/elements/summary', [
		'entity' => $entity,
		'title' => $title,
		'subtitle' => $subtitle,
		'content' => $info,
	]);

	$html = sprintf(
		'<div class="gallery-album-cover">%s<div class="gallery-album-meta"><div class="gallery-album-count">%s</div><div class="gallery-album-author">%s</div>%s%s</div></div>',
		$cover,
		$count,
		$author,
		$summary,
		$menu
	);
}

echo $html;
