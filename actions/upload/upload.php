<?php

namespace hypeJunction\Gallery;

// Attributes
$guid = get_input('container_guid', null);
$album = get_entity($guid);

if (!$album) {
	return \elgg_error_response(\elgg_echo('gallery:upload:error:noalbum'));
}

include \elgg_get_root_path() . 'mod/hypegallery/actions/upload/handle.php';
include \elgg_get_root_path() . 'mod/hypegallery/actions/upload/describe.php';

return \elgg_ok_response([], '', $album->getURL());
