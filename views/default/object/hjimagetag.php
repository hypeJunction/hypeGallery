<?php

namespace hypeJunction\Gallery;

$tag = \elgg_extract('entity', $vars);
if (!$tag instanceof \ElggEntity) {
	return;
}

$image = $tag->getContainerEntity();
$image_owner = $image->getOwnerEntity();
$tagged_user = $tag->getOwnerEntity();

if ($tagged_user->guid != $image_owner->guid || empty($tag->title)) {
	$text = \elgg_view('output/img', [
		'src' => $tagged_user->getIconURL('tiny')
	]) . $tagged_user->name;
	if ($tag->title) {
		$text .= ' ~ ' . $tag->title;
	}

	$href = $tagged_user->getURL();
} else {
	$text = $tag->title;
	$href = '#';
}

$content = \elgg_view('output/url', [
	'title' => $tag->title,
	'href' => $href,
	'text' => '<span>' . $text . '</span>',
]);

if ($tag->canEdit()) {
	$content .= \elgg_view('output/url', [
		'text' => '<i class="gallery-icon-delete icon-small"></i>',
		'class' => 'elgg-button-gallery-tag-delete',
		'data-guid' => $tag->guid,
		'href' => "action/gallery/delete/object?guid=$tag->guid",
		'is_action' => true
	]);
}

echo \elgg_format_element('div', [
	'id' => "elgg-object-$tag->guid",
	'class' => 'gallery-tag',
	'data-guid' => $tag->guid,
	'data-x' => $tag->x1 + $tag->width / 2,
	'data-y' => $tag->y1 + $tag->height / 2,
], $content);
