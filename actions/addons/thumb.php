<?php

namespace hypeJunction\Gallery;

use ElggFile;
use hypeJunction\Filestore\IconHandler;

$guid = get_input('guid');
$entity = $guid ? get_entity((int) $guid) : null;

if (!$entity instanceof ElggFile || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('gallery:tools:crop:error'));
}

$coords = [
	'x1' => (int) get_input('x1', 0),
	'y1' => (int) get_input('y1', 0),
	'x2' => (int) get_input('x2', 0),
	'y2' => (int) get_input('y2', 0),
];

$icon_sizes = get_icon_sizes($entity);
unset($icon_sizes['master']);

$result = IconHandler::makeIcons($entity, null, [
	'icon_sizes' => $icon_sizes,
	'coords' => $coords,
]);

if (!$result) {
	return elgg_error_response(elgg_echo('gallery:tools:crop:error'));
}

return elgg_ok_response($coords, elgg_echo('gallery:tools:crop:success'));
