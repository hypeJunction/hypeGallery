<?php

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

$guids = hj_gallery_process_file_upload('filedrop_files', 'hjalbumimage', null, $album->guid);

$guid = reset($guids);

$image = get_entity($guid);

if (!elgg_instanceof($image, 'object', 'hjalbumimage')) {
	$failed = true;
} else if ($image->simpletype != 'image') {
	$failed = true;
	$image->delete();
} else {

	if ($album) {
		$metadata = elgg_get_metadata(array(
			'guid' => $album->guid,
			'limit' => 0
				));

		$image->title = $album->title;
		$image->description = $album->description;
		$image->access_id = $album->access_id;

		foreach ($metadata as $md) {
			$names[] = $md->name;
		}

		$names = array_unique($names);

		foreach ($names as $name) {
			$image->$name = $album->$name;
		}
	} else {
		$image->title = elgg_echo('hj:album:untitled');
		$image->access_id = ACCESS_PRIVATE;
	}

	$image->save();
}

if (!$failed) {
	system_message(elgg_echo('hj:gallery:upload:success'));
} else {
	register_error(elgg_echo('hj:gallery:upload:error'));
	forward(REFERER);
}

if (elgg_is_xhr()) {
	print json_encode(array(
				'album_guid' => $album->guid,
				'image_guid' => $image->guid,
				'form' => elgg_view('forms/edit/object/hjalbumimage', array(
					'entity' => $image
				))
			));
}

forward($image->getURL());