<?php

namespace hypeJunction\Gallery;

// Attributes
$guid = get_input('container_guid', null);
$album = $guid ? get_entity((int) $guid) : null;

if (!$album instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('gallery:upload:error:noalbum'));
}

include elgg_get_root_path() . 'mod/hypegallery/actions/upload/handle.php';
include elgg_get_root_path() . 'mod/hypegallery/actions/upload/describe.php';

return elgg_ok_response([], '', $album->getURL());
