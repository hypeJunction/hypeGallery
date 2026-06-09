<?php

namespace hypeJunction\Gallery;

$image_guid = get_input('e');
$image = $image_guid ? get_entity((int) $image_guid) : null;

if (!$image instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('gallery:tools:cover:error'));
}

$album = $image->container_guid ? get_entity((int) $image->container_guid) : null;
if (!$album instanceof \ElggEntity || !$album->canEdit()) {
	return elgg_error_response(elgg_echo('gallery:tools:cover:error'));
}

$album->cover = $image->guid;

return elgg_ok_response([], elgg_echo('gallery:tools:cover:success'));
