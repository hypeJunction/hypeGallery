<?php

namespace hypeJunction\Gallery;

if (!HYPEGALLERY_EXIF) {
	return;
}

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return;
}

$exif = $entity->getExif();
if (!$exif) {
	return;
}

$exif_title = elgg_view('output/url', array(
    'text' => elgg_echo('gallery:exif') . '&nbsp;<i class="gallery-icon-info icon-small"></i>',
    'href' => "#viewexif",
    'rel' => 'toggle'
        ));

$exif_body .= '<ul class="gallery-media-exif-details" id="viewexif" style="display:none">';
foreach ($exif as $key => $values) {
	$exif_body .= '<li>';
	$exif_body .= '<label>' . $values['label'] . '</label>';
	$exif_body .= '<span>' . $values['clean'] . '</span>';
	$exif_body .= '</li>';
}
$exif_body .= '</ul>';

echo elgg_view_module('aside', $exif_title, $exif_body, array(
	'class' => 'hj-gallery-exif-module'
));
