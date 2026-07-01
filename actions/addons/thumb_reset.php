<?php

namespace hypeJunction\Gallery;

use ElggFile;
use hypeJunction\Filestore\IconHandler;

$guid = get_input('guid');
$entity = $guid ? get_entity((int) $guid) : null;

if (!$entity instanceof ElggFile || !$entity->canEdit()) {
	return elgg_error_response(elgg_echo('gallery:tools:crop:error'));
}

$icon_sizes = get_icon_sizes($entity);
unset($icon_sizes['master']);

$result = IconHandler::makeIcons($entity, null, [
	'icon_sizes' => $icon_sizes,
]);

if (!$result) {
	return elgg_error_response(elgg_echo('gallery:tools:crop:error'));
}

return elgg_ok_response([], elgg_echo('gallery:tools:crop:success'));
