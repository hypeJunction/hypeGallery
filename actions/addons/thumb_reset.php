<?php

namespace hypeJunction\Gallery;

use ElggFile;
use hypeJunction\Filestore\IconHandler;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof ElggFile || !$entity->canEdit()) {
	elgg_register_error_message(elgg_echo('gallery:tools:crop:error'));
	forward(REFERER);
}

$icon_sizes = get_icon_sizes($entity);
unset($icon_sizes['master']);

$result = IconHandler::makeIcons($entity, null, array(
			'icon_sizes' => $icon_sizes,
			'coords' => $coords,
		));

if (!$result) {
	elgg_register_error_message($exception);
} else {
	elgg_register_success_message(elgg_echo('gallery:tools:crop:success'));
}

if (elgg_is_xhr) {
	print(json_encode($coords));
}
forward(REFERER);
