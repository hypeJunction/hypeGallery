<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars);
$size = \elgg_extract('size', $vars);

echo \elgg_view_entity_icon($entity, $size, [
	'link_class' => 'gallery-popup'
]);
