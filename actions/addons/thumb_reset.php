<?php

namespace hypeJunction\Gallery;

use ElggFile;
use WideImage\Exception\Exception;
use WideImage\WideImage;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof ElggFile || !$entity->canEdit()) {
	register_error(elgg_echo('gallery:tools:crop:error'));
	forward(REFERER);
}

$icon_sizes = elgg_get_config('icon_sizes');
unset($icon_sizes['master']);

try {
	$master = new ElggFile();
	$master->owner_guid = $entity->owner_guid;
	if ($entity->mimetype == 'image/png') {
		$filename = "icons/" . $entity->getGUID() . "master.png";
	} else if ($entity->mimetype == 'image/gif') {
		$filename = "icons/" . $entity->getGUID() . "master.gif";
	} else {
		$filename = "icons/" . $entity->getGUID() . "master.jpg";
	}
	$master->setFilename($filename);

	$image = WideImage::load($master->getFilenameOnFilestore());

	foreach ($icon_sizes as $size => $thumb) {

		$resized = $image->resize($thumb['w'], $thumb['h'], 'outside', 'any')->crop('center', 'center', $thumb['w'], $thumb['h']);

		switch ($entity->mimetype) {
			default :
			case 'image/jpeg' :
				$mime = 'image/jpeg';
				$contents = $resized->asString('jpg', 80);
				$filename = "icons/" . $entity->getGUID() . $size . ".jpg";
				break;

			case 'image/gif' :
				$mime = 'image/gif';
				$filename = "icons/" . $entity->getGUID() . $size . ".gif";
				$contents = $resized->asString('gif');
				break;

			case 'image/png' :
				$mime = 'image/png';
				$contents = $resized->asString('png');
				$filename = "icons/" . $entity->getGUID() . $size . ".png";
				break;
		}

		$new_thumb = new ElggFile();
		$new_thumb->owner_guid = $entity->owner_guid;
		$new_thumb->setFilename($filename);
		$new_thumb->open('write');
		$new_thumb->write($contents);
		$new_thumb->close();
	}
} catch (Exception $e) {
	$exception = $e->getMessage();
}

if ($exception) {
	register_error($exception);
} else {
	foreach ($coords as $coord => $value) {
		$entity->$coord = $value;
	}
	$entity->icontime = time();
	system_message(elgg_echo('gallery:tools:crop:success'));
}

if (elgg_is_xhr) {
	print(json_encode($coords));
}
forward(REFERER);
