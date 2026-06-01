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
