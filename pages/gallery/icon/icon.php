<?php

namespace hypeJunction\Gallery;

use ElggFile;
use WideImage\WideImage;

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

if (!$entity) {
	return false;
}

$requested_size = $size = strtolower(get_input('size', 'master'));

$config = get_icon_sizes($entity);

if ($entity->mimetype == 'image/png') {
	$filename = "icons/" . $entity->guid . $size . ".png";
} else if ($entity->mimetype == 'image/gif') {
	$filename = "icons/" . $entity->guid . $size . ".gif";
} else {
	$filename = "icons/" . $entity->guid . $size . ".jpg";
}

$etag = md5($filehandler->icontime . $size);

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->owner_guid;
$filehandler->setFilename($filename);
$filehandler->open('read');
$contents = $filehandler->grabFile();
$filehandler->close();

if (!$contents) {
	if (array_key_exists($requested_size, $config)) {
		$requested_w = $config[$size]['w'];
		$requested_h = $config[$size]['h'];
		$square = $config[$size]['square'];
	} else {
		list($requested_w, $requested_h) = explode('x', $requested_size);
		if (($requested_w && !in_array($requested_w, elgg_get_config('gallery_allowed_dynamic_width'))) || ($requested_h && !in_array($requested_h, elgg_get_config('gallery_allowed_dynamic_height')))) {
			exit;
		}
	}

	$requested_w = (int) $requested_w;
	$requested_h = (int) $requested_h;

	if (!is_numeric($requested_w)) {
		$requested_w = null;
	}

	$crop_w = ($requested_w) ? $requested_w : '100%';
	$crop_h = ($requested_h) ? $requested_h : '100%';

	if (!$requested_w && !$requested_h) {
		$requested_w = $config['master']['w'];
		$requested_h = $config['master']['h'];
	} else if ($requested_w && !$requested_h) {
		$requested_w = $requested_w;
		if ($square) {
			$requested_h = $requested_w;
		} else {
			$requested_h = null;
		}
	} else if ($requested_h && !$requested_w) {
		$requested_h = $requested_h;
		if ($square) {
			$requested_w = $requested_h;
		} else {
			$requested_w = null;
		}
	} else if ($requested_w > $requested_h) {
		$requested_h = null;
	} else if ($requested_h > $requested_w) {
		$requested_w = null;
	}

	$image = WideImage::load($entity->getFilenameOnFilestore());
	$resized = $image;

//	if (array_key_exists($size, $config) && $entity->x1 && $entity->y1 && $entity->x2 && $entity->y2) {
//		$resized = $resized->crop($entity->x1, $entity->y1, $entity->x2 - $entity->x1, $entity->y2 - $entity->y1);
//	}

	if ($size !== 'master' && $size !== 'taggable') {
		$resized = $resized->resize($requested_w, $requested_h, 'outside', 'any')->crop('center', 'center', $crop_w, $crop_h);
	} else {
		$resized = $resized->resize($requested_w, $requested_h, 'inside', 'down');
	}

	switch ($entity->mimetype) {
		default :
		case 'image/jpeg' :
			$mime = 'image/jpeg';
			$contents = $resized->asString('jpg', 80);
			break;

		case 'image/gif' :
			$mime = 'image/gif';
			$contents = $resized->asString('gif');
			break;

		case 'image/png' :
			$mime = 'image/png';
			$contents = $resized->asString('png');
			break;
	}

	// save the thumb
	$thumb = new ElggFile();
	$thumb->owner_guid = $entity->owner_guid;
	$thumb->setMimeType($mimetype);
	$thumb->setFilename($filename);
	$thumb->open('write');
	$thumb->write($contents);
	$thumb->close();
}

access_show_hidden_entities($ha);

header("Content-type: $mime");
header("Etag: $etag");
header('Expires: ' . date('r', time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

echo $contents;
