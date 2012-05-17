<?php

$entity = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($entity)) {
	return true;
}

$icon_size = elgg_extract('thumb_size', $vars, 'small');

echo elgg_view_entity_icon($entity, $icon_size);