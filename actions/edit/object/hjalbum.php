<?php

$result = hj_framework_edit_object_action();

if ($result) {
	$entity = elgg_extract('entity', $result);

	$images = hj_gallery_handle_uploaded_files($entity);
	
	print json_encode(array('guid' => $entity->guid, 'images' => $images));
	forward("gallery/manage/$entity->guid");
} else {
	forward(REFERER);
}
