<?php

namespace hypeJunction\Gallery;

$image_guid = get_input('e');
$image = get_entity($image_guid);

if (!$image) {
	elgg_register_error_message(elgg_echo('gallery:tools:cover:error'));
	forward(REFERER);
}
$album = get_entity($image->container_guid);
if (!$album || !$album->canEdit()) {
	elgg_register_error_message(elgg_echo('gallery:tools:cover:error'));
	forward(REFERER);
}
$album->cover = $image->guid;
elgg_register_success_message(elgg_echo('gallery:tools:cover:success'));
forward(REFERER);

