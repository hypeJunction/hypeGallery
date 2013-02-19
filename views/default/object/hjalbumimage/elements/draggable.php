<?php

$entity = elgg_extract('entity', $vars);

if (!$entity) return;

if ($entity->canEdit()) {
	echo elgg_view_icon('cursor-drag-arrow', 'hj-draggable-element-handle');
}