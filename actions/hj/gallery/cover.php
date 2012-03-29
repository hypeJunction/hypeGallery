<?php

$image_guid = get_input('e');
$image = get_entity($image_guid);

if (!$image) {
	register_error(elgg_echo('hj:album:cover:error'));
	return true;
}
$album = get_entity($image->container_guid);

if ($album->cover = $image->guid) {
	$html['data'] = '';
	print(json_encode($html));
	system_message(elgg_echo('hj:album:cover:success'));
}

return true;


