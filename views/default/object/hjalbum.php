<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$list_type = elgg_extract('list_type', $vars, 'photostream');

if (elgg_in_context('activity') || elgg_in_context('main')) {
	echo elgg_view('object/hjalbum/river', $vars);
	return true;
}

$view = "object/hjalbum/$list_type";
if (elgg_view_exists($view)) {
	echo elgg_view($view, $vars);
} else {
	echo elgg_view('object/hjalbum/list', $vars);
}