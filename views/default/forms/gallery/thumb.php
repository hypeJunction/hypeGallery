<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

$x = $y = 0;
$width = $height = 200;

if ($entity->x2 > $entity->x1) {
	$x = $entity->x1;
	$y = $entity->y1;
	$width = $entity->x2 - $entity->x1;
	$height = $entity->y2 - $entity->y1;
}

echo '<div class="gallery-crop-master-wrapper">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('taggable'),
	'id' => "gallery-crop-master",
	'data-cropper-x' => $x,
	'data-cropper-y' => $y,
	'data-cropper-width' => $width,
	'data-cropper-height' => $height,
	'data-cropper-ratio' => 1,
	'class' => 'gallery-croppable',
));
echo '</div>';

echo '<div class="gallery-crop-preview-wrapper clearfix">';
echo '<span class="elgg-text-help">' . elgg_echo('gallery:tools:crop:instructions') . '</span>';

echo '<div class="elgg-col elgg-col-1of2">';
echo '<label>' . elgg_echo('gallery:tools:crop:preview') . '</label>';
echo '<div id="gallery-crop-preview">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('taggable'),
));
echo '</div>';
echo '</div>';

echo '<div class="elgg-col elgg-col-1of2">';
echo '<label>' . elgg_echo('gallery:tools:crop:current') . '</label>';
echo '<div id="gallery-crop-current">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('medium')
));
echo '</div>';
echo '</div>';

echo '</div>';

echo '<div class="elgg-foot">';

$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
	echo elgg_view('input/hidden', array(
		'name' => $coord,
		'value' => $vars['entity']->$coord
	));
}

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $entity->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('gallery:tools:crop')));

echo elgg_view('output/url', array(
	'text' => elgg_echo('gallery:image:thumb:reset'),
	'href' => "action/gallery/thumb_reset?guid=$entity->guid",
	'is_action' => true,
	'is_trusted' => true,
	'class' => 'elgg-button elgg-button-action elgg-button-gallery-reset-thumb',
));

echo '</div>';
