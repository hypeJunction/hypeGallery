<?php

namespace hypeJunction\Gallery;

use hypeJunction\Filestore\UploadHandler;

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

$uploads = UploadHandler::handle('dropzone', [
	'subtype' => hjAlbumImage::SUBTYPE,
	'container_guid' => $album->guid,
	'icon_sizes' => get_icon_sizes(new hjAlbumImage),
]);

$output = [];

if (elgg_is_xhr()) {
	$name = get_input('input_name');
	foreach ($uploads as $upload) {
		$messages = [];
		$success = true;

		if ($upload->error) {
			$messages[] = $upload->error;
			$success = false;
			$guid = false;
		} else {
			$messages[] = elgg_echo('gallery:upload:success');
		}

		$image = $upload->file;
		if (!$image instanceof \ElggEntity) {
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
				$metadata = elgg_get_metadata([
					'guid' => $album->guid,
					'limit' => 0
				]);

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
				$html = elgg_view('forms/edit/object/hjalbumimage', [
					'entity' => $image
				]);
			}
		}

		$file_output = [
			'messages' => $messages,
			'success' => $success,
			'guid' => $guid,
			'html' => $html,
		];

		$output[] = elgg_trigger_event_results('upload:after', 'dropzone', [
			'upload' => $upload,
		], $file_output);
	}
}

return elgg_ok_response($output ?? []);
