<?php

namespace hypeJunction\Gallery;

use hypeJunction\Filestore\UploadHandler;

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

$uploads = UploadHandler::handle('dropzone', array(
			'subtype' => hjAlbumImage::SUBTYPE,
			'container_guid' => $album->guid,
			'icon_sizes' => get_icon_sizes(new hjAlbumImage),
		));

$output = array();

if (elgg_is_xhr()) {
	$name = get_input('input_name');
	foreach ($uploads as $upload) {

		$messages = array();
		$success = true;

		if ($upload->error) {
			$messages[] = $upload->error;
			$success = false;
			$guid = false;
		} else {
			$messages[] = elgg_echo('gallery:upload:success');
		}

		$image = $upload->file;
		if (!elgg_instanceof($image)) {
			$messages[] = elgg_echo('gallery:upload:error');
			$success = false;
			$guid = false;
		} else if ($image->simpletype !== 'image') {
			$image->delete();
			$messages[] = elgg_echo('gallery:upload:unsupportedtype');
			$success = false;
			$guid = false;
		}

		if ($success) {
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
				$guid = $image->getGUID();
				$html = elgg_view('forms/edit/object/hjalbumimage', array(
					'entity' => $image
				));
			}
		}

		$file_output = array(
			'messages' => $messages,
			'success' => $success,
			'guid' => $guid,
			'html' => $html,
		);

		$output[] = elgg_trigger_plugin_hook('upload:after', 'dropzone', array(
			'upload' => $upload,
				), $file_output);
	}

	echo json_encode($output);
}

forward(REFERER);
