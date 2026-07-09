<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

$size = elgg_extract('size', $vars);
$requested_size = $size;

$gallery_config = get_icon_sizes($entity);

if (array_key_exists($requested_size, $gallery_config)) {
	$values = elgg_extract($requested_size, $gallery_config);
	$requested_w = $values['w'];
	if ($values['square']) {
		$requested_h = $values['h'];
	}
} else {
	list($requested_w, $requested_h) = explode('x', $requested_size);
}

$class = elgg_extract('img_class', $vars, 'gallery-album-icon centered');

$title = htmlspecialchars($entity->title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img = elgg_view('output/img', [
	'src' => $entity->getIconURL($vars['size']),
	'class' => $class,
	'width' => $requested_w,
	'height' => $requested_h,
]);


if ($url) {
	$params = [
		'href' => $url,
		'text' => $img,
		'title' => $title . ': ' . elgg_strip_tags((string) $entity->description),
		'is_trusted' => true,
		'data-guid' => $entity->guid,
	];

	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
