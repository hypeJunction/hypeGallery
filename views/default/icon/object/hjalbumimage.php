<?php


$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar', 'full', 'cover', 'preview');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$class = elgg_extract('img_class', $vars, 'hj-albumimage-icon centered');

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$icon_sizes = elgg_get_config('icon_sizes');
$size = $vars['size'];

$img = elgg_view('output/img', array(
	'src' => $entity->getIconURL($vars['size']),
	'alt' => $title,
	'class' => $class,
	'width' => $size != 'master' ? $icon_sizes[$size]['w'] : NULL,
	'height' => $size != 'master' ? $icon_sizes[$size]['h'] : NULL,
		));

if ($url) {

	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
		'data-uid' => $entity->guid
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
