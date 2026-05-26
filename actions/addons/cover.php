<?php

namespace hypeJunction\Gallery;

$image_guid = get_input('e');
$image = get_entity($image_guid);

if (!$image) {
	return \elgg_error_response(\elgg_echo('gallery:tools:cover:error'));
}

$album = get_entity($image->container_guid);
if (!$album || !$album->canEdit()) {
	return \elgg_error_response(\elgg_echo('gallery:tools:cover:error'));
}

$album->cover = $image->guid;

return \elgg_ok_response([], \elgg_echo('gallery:tools:cover:success'));
