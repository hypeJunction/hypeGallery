<?php

namespace hypeJunction\Gallery;

use ElggFile;

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

$requested_size = $size = strtolower(get_input('size', 'master'));

$config = elgg_get_config('icon_sizes');
$gallery_config = elgg_get_config('gallery_icon_sizes');

$config = array_merge($config, $gallery_config);

if (array_key_exists($requested_size, $config)) {

	$filename = "icons/" . $entity->getGUID() . $size . ".jpg";
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $entity->owner_guid;
	$filehandler->setFilename($filename);
	$filehandler->open('read');
	$contents = $filehandler->read($filehandler->size());

	if (!$contents) {
		$requested_w = $config[$size]['w'];
		$requested_h = $config[$size]['h'];
		$square = $config[$size]['square'];
	}

	$etag = md5($filehandler->icontime . $size);
} else {
	$fit = get_input('fit', 'inside');
	$scale = get_input('scale', 'any');
	list($requested_w, $requested_h) = explode('x', $requested_size);

	$requested_w = (int) $requested_w;
	$requested_h = (int) $requested_h;

	$etag = md5($filehandler->icontime . $requested_w . $requested_h . $fit . $scale);

	if (!is_numeric($requested_w)) {
		$requested_w = null;
	}
	if ((!$requested_w && !$requested_h) || ($requested_w && !in_array($requested_w, elgg_get_config('gallery_allowed_dynamic_width')) || ($requested_h && !in_array($requested_h, elgg_get_config('gallery_allowed_dynamic_height'))))
	) {
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
	}
}

//if (!$contents) {
//
//	$filehandler = $entity;
//
//	elgg_load_library('gallery:vendors:wideimage');
//
//	$image = WideImage::load($filehandler->getFilenameOnFilestore());
//	if ($requested_w && $requested_h) {
//		if ($requested_h > $requested_w) {
//			$resized = $image->resize(null, $requested_h, $fit, $scale);
//		} else {
//			$resized = $image->resize($requested_w, null, $fit, $scale);
//		}
//		$resized = $resized->crop('center', 'center', $requested_w, $requested_h);
//	} else {
//		$resized = $image->resize($requested_w, $requested_h, $fit, $scale);
//	}
//	switch ($entity->mimetype) {
//		default :
//		case 'image/jpeg' :
//			$mime = 'image/jpeg';
//			$contents = $resized->asString('jpg', 80);
//			break;
//
//		case 'image/gif' :
//			$mime = 'image/gif';
//			$contents = $resized->asString('gif');
//			break;
//
//		case 'image/png' :
//			$mime = 'image/png';
//			$contents = $resized->asString('png');
//			break;
//	}
//}

access_show_hidden_entities($ha);

header("Content-type: $mime");
header("Etag: $etag");
header('Expires: ' . date('r', time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

echo $contents;
