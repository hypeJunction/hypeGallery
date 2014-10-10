<?php

namespace hypeJunction\Gallery;

// Attributes
$guid = get_input('container_guid', null);
$album = get_entity($guid);

if (!$album) {
	register_error(elgg_echo('gallery:upload:error:noalbum'));
	forward(REFERER);
}

include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/handle.php';
include elgg_get_root_path() . 'mod/hypeGallery/actions/upload/describe.php';

forward($album->getURL());
