<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

echo elgg_view('output/longtext', array(
	'value' => $entity->description,
	'class' => 'mbm'
));
echo elgg_view('object/hjalbumimage/meta', $vars);

echo elgg_view_comments($entity);
