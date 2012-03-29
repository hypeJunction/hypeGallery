<?php

$type = get_input('type', 'get');
$image_guid = get_input('image_guid');
$image = get_entity($image_guid);
$album = get_entity($image->container_guid);

switch ($type) {
	case 'get' :
		$params = get_input('params');
		$file = get_entity($params['entity_guid']);

		$form = elgg_view_form('hj/gallery/thumb', array('id' => "hj-image-cropper-form-$file->guid"), array('entity' => $file, 'image' => $image));
		$html['data'] = elgg_view_module('aside', elgg_echo('hj:album:image:editthumb'), $form);
		print(json_encode($html));
		return true;
		break;

	case 'crop' :
		$guid = get_input('guid');
		$file = get_entity($guid);
		$master_filename = $file->masterthumb;

		$master = new ElggFile();
		$master->owner_guid = $file->owner_guid;
		$master->setFilename($master_filename);
		$filename = $master->getFilenameOnFilestore();

		$x1 = (int) get_input('x1', 0);
		$y1 = (int) get_input('y1', 0);
		$x2 = (int) get_input('x2', 0);
		$y2 = (int) get_input('y2', 0);

		$icon_sizes = array(
			'tiny' => 16,
			'small' => 25,
			'medium' => 40,
			'large' => 100,
			'preview' => 250,
		);

		$files = array();
		foreach ($icon_sizes as $name => $size_info) {
			$resized = get_resized_image_from_existing_file($filename, $size_info, $size_info, true, $x1, $y1, $x2, $y2, true);
			$thumb_meta = "{$name}thumb";
			$thumb_filename = $file->$thumb_meta;
			$thumb_filename = "hjfile/$file->container_guid/{$file->guid}{$name}.jpg";

			if ($resized) {
				//@todo Make these actual entities.  See exts #348.
				$thumb = new ElggFile();
				$thumb->owner_guid = $file->owner_guid;
				$thumb->setFilename($thumb_filename);
				$thumb->open('write');
				$thumb->write($resized);
				$thumb->close();
				$file->$thumb_meta = $thumb->getFilename();

				$files[] = $thumb;
			} else {
				// cleanup on fail
				foreach ($files as $thumb) {
					$thumb->delete();
				}

				system_message(elgg_echo('hj:gallery:thumb:resize:fail'));
				forward(REFERER);
			}
		}

		$file->icontime = time();

		$file->x1 = $x1;
		$file->x2 = $x2;
		$file->y1 = $y1;
		$file->y2 = $y2;

		system_message(elgg_echo('hj:gallery:thumb:crop:success'));
		forward("gallery/album/$album->guid/$image->guid");

		break;
	default :
		return true;
}
return true;