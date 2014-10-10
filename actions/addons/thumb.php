<?php

namespace hypeJunction\Gallery;

use ElggFile;
use hypeJunction\Filestore\IconHandler;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof ElggFile || !$entity->canEdit()) {
	register_error(elgg_echo('gallery:tools:crop:error'));
	forward(REFERER);
}

$coords = array(
	'x1' => (int) get_input('x1', 0),
	'y1' => (int) get_input('y1', 0),
	'x2' => (int) get_input('x2', 0),
	'y2' => (int) get_input('y2', 0),
);

$icon_sizes = get_icon_sizes($entity);
unset($icon_sizes['master']);

$result = IconHandler::makeIcons($entity, null, array(
			'icon_sizes' => $icon_sizes,
			'coords' => $coords,
		));

if (!$result) {
	register_error($exception);
} else {
	system_message(elgg_echo('gallery:tools:crop:success'));
}

if (elgg_is_xhr) {
	print(json_encode($coords));
}
forward(REFERER);
