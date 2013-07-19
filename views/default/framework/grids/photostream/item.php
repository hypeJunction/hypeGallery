<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return;
}

if (isset($vars['class'])) {
	$class = elgg_extract('class', $vars, '');
	unset($vars['class']);
}
$attr = array(
	'class' => trim("elgg-item $class"),
	'data-guid' => $entity->guid,
	'data-ts' => max(array($entity->time_created, $entity->time_updated, $entity->last_action))
);

$attributes = elgg_format_attributes($attr);

$item_view = elgg_view_entity($entity, $vars);

echo "<li $attributes>$item_view</li>";
