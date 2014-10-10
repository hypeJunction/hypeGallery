<?php

namespace hypeJunction\Gallery;

$image_guid = get_input('e');
$image = get_entity($image_guid);

if (!$image) {
	register_error(elgg_echo('gallery:tools:cover:error'));
	forward(REFERER);
}
$album = get_entity($image->container_guid);
if (!$album || !$album->canEdit()) {
	register_error(elgg_echo('gallery:tools:cover:error'));
	forward(REFERER);
}
$album->cover = $image->guid;
system_message(elgg_echo('gallery:tools:cover:success'));
forward(REFERER);

