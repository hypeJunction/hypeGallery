<?php

$entity = elgg_extract('entity', $vars);

if (elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	echo elgg_view('object/hjalbumimage/elements/sidebar', $vars);
	return true;
}

$search_title = elgg_echo('hj:gallery:filter');
$search_box = elgg_view('framework/gallery/filters/gallery', $vars);

echo elgg_view_module('aside', $search_title, $search_box);