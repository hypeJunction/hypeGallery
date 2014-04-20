<?php

namespace hypeJunction\Gallery;

use hypeJunction\Filestore\UploadHandler;

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

$uploads = UploadHandler::handle('dropzone', array(
			'subtype' => 'hjalbumimage',
			'container_guid' => $album->guid,
		));

if (elgg_is_xhr()) {
	$errors = $success = array();

	$input_name = get_input('input_name');
	foreach ($uploads as $upload) {
		if ($upload->error) {
			register_error($upload->error);
			$errors[] = $upload->error;
			continue;
		} else {
			$success[] = elgg_echo('gallery:upload:success');
		}

		$image = $upload->file;
		if (!elgg_instanceof($image)) {
			register_error($elgg_echo('gallery:upload:error'));
			$errors[] = elgg_echo('gallery:upload:error');
			continue;
		}

		if ($image->simpletype !== 'image') {
			$image->delete();
			register_error($elgg_echo('gallery:upload:unsupportedtype'));
			$errors[] = elgg_echo('gallery:upload:unsupportedtype');
			continue;
		}
		
		if ($album) {
			$metadata = elgg_get_metadata(array(
				'guid' => $album->guid,
				'limit' => 0
			));

			$image->access_id = $album->access_id;

			foreach ($metadata as $md) {
				$names[] = $md->name;
			}

			$names = array_unique($names);

			foreach ($names as $name) {
				$image->$name = $album->$name;
			}
		} else {
			$image->access_id = ACCESS_PRIVATE;
		}

		if ($image->save()) {

			$guids[] = $image->getGUID();
			$html .= elgg_view('forms/edit/object/hjalbumimage', array(
				'entity' => $image
			));
		}
	}

	echo json_encode(array(
		'guids' => $guids,
		'html' => $html,
		'error' => $errors,
		'success' => $success
	));
}

forward(REFERER);
