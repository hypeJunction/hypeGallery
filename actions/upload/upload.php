<?php

$guid = get_input('guid');
$entity = get_entity($guid);

$images = hj_gallery_handle_uploaded_files($entity);

print json_encode(array('guid' => $entity->guid, 'images' => $images));
forward("gallery/manage/$entity->guid");
