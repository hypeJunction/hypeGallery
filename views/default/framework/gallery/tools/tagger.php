<?php

namespace hypeJunction\Gallery;

if (!HYPEGALLERY_TAGGING) {
	return;
}

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'object', hjAlbumImage::SUBTYPE)) {
	return;
}

$mod .= '<div class="gallery-media-tags">';

$title = elgg_echo('gallery:inthisphoto');

if ($entity->canEdit()) {

	$mod .= '<div class="tagger-float-template hidden">';
	$mod .= '<div class="tagger-circle"></div>';
	$mod .= '<div class="tagger-close hidden"></div>';
	$mod .= '</div>';

	$form = elgg_view_form('gallery/phototag', array(
		'id' => 'gallery-tagger',
			), array(
		'entity' => $entity,
	));
	$form .= '<a class="elgg-button-gallery-tagger"></a>';
}

$mod .= "<div class=\"elgg-gallery gallery-tags-list\" data-guid=\"$entity->guid\">";
$tags = get_image_tags($entity);
if ($tags) {
	foreach ($tags as $tag) {
		$mod .= elgg_view_entity($tag);
	}
} else {
	$mod .= '<p class="placeholder">' . elgg_echo('gallery:inthisphoto:none') . '</p>';
	foreach ($tags as $tag) {
		$mod .= elgg_view_entity($tag);
	}
}
$mod .= '</div>';
$mod .= '</div>';

if ($form) {
	echo elgg_view_module('aside', elgg_echo('gallery:image:tag:create'), $form);
}

echo elgg_view_module('aside', $title, $mod);
