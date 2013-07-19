<?php

$entity = elgg_extract('entity', $vars);

$requested_size = $size = elgg_extract('size', $vars);

$config = elgg_get_config('icon_sizes');
$gallery_config = elgg_get_config('gallery_icon_sizes');
$gallery_config = array_merge($config, $gallery_config);

if (array_key_exists($requested_size, $gallery_config)) {
	$values = elgg_extract($requested_size, $gallery_config);
	$requested_w = $values['w'];
	$requested_h = $values['h'];
} else {
	list($requested_w, $requested_h) = explode('x', $requested_size);
}

$class = elgg_extract('img_class', $vars, 'hj-albumimage-icon centered');

$title = htmlspecialchars($entity->title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img = elgg_view('output/img', array(
	'src' => $entity->getIconURL($vars['size']),
	'class' => $class,
	'width' => $requested_w,
	'height' => $requested_h,
		));


if ($url) {

	$loader = elgg_normalize_url('mod/hypeGallery/graphics/loader.gif');
	$params = array(
		'href' => $url,
		'text' => $img,
		'title' => $title . ": " . elgg_strip_tags($entity->description),
		'is_trusted' => true,
		'data-guid' => $entity->guid,
		'style' => "display:block;width:{$requested_w}px;height:{$requested_h}px;background:transparent url($loader) 50% 50% no-repeat;"
	);
	$class = elgg_extract('link_class', $vars, '');

	if ($size != 'master') {
		if (!empty($class)) {
			$class = "$class hj-gallery-popup";
		} else {
			$class = "hj-gallery-popup";
		}
	}

	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
