<?php

$result = hj_framework_edit_object_action();

if ($result) {
	$entity = elgg_extract('entity', $result);

	$images = hj_gallery_handle_uploaded_files($entity);

	foreach ($images as $image) {
		$image_guids[] = $image->guid;
	}

	if ($image_guids) {
		$posted = time();
		$entity->$posted = serialize($image_guids);
		add_to_river('river/object/hjalbum/create', 'create', $entity->owner_guid, $entity->guid, $entity->access_id, $posted);
	}

	if (elgg_is_xhr()) {
		print json_encode(array('guid' => $entity->guid, 'images' => $images));
	}
	forward("gallery/manage/$entity->guid");
} else {
	forward(REFERER);
}
