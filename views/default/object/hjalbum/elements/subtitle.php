<?php

$entity = elgg_extract('entity', $vars);
$full_view = elgg_extract('full_view', $vars, false);

if (!$full_view) {
	return true;
}

$subtitle = array();

if ($entity->location && $entity->location !== '0,0') {
	$subtitle[] = elgg_view('output/location', array('entity' => $entity));
}

if ($entity->date) {
	$subtitle[] = elgg_view('output/date', array('value' => $entity->date));
}

if ($entity->copyright) {
	$subtitle[] = elgg_view('output/text', array('value' => $$entity->copyright));
}
if ($entity->friend_tags) {
	$subtitle[] = elgg_view('output/relationship_tags', array('value' => $entity->friend_tags, 'entity' => $entity));
}

echo implode('<br />', $subtitle);