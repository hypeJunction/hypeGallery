<?php

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

$requested_size = $size = strtolower(get_input('size'));

$config = elgg_get_config('icon_sizes');
if (!array_key_exists($requested_size, $config)) {
	$size = 'master';
}

$filename = "icons/" . $entity->getGUID() . $size . ".jpg";

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->owner_guid;
$filehandler->setFilename($filename);

$gallery_config = elgg_get_config('gallery_icon_sizes');
if (array_key_exists($requested_size, $gallery_config)) {
	$values = elgg_extract($requested_size, $gallery_config);
	$requested_w = $values['w'];
	$requested_h = $values['h'];
} else if (!array_key_exists($requested_size, $config)) {
	list($requested_w, $requested_h) = explode('x', $requested_size);
}

if ($requested_w) {
	$dim = getimagesize($filehandler->getFilenameOnFilestore());
	$original_w = $dim[0];
	$original_h = $dim[1];

	if (!$requested_h) {
		$requested_h = $original_h * ($requested_w / $original_w);
	}
	$x1 = $x2 = $y1 = $y2 = 0;

	if ($requested_w <= $original_w && $requested_h <= $original_h) {
		$x1 = ($original_w / 2) - ($requested_w / 2);
		$x2 = ($original_w / 2) + ($requested_w / 2);

		$y1 = ($original_h / 2) - ($requested_h / 2);
		$y2 = ($original_h / 2) + ($requested_h / 2);
	} else if ($requested_w > $original_w) {
		$x1 = 0;
		$x2 = $original_w;
		$prop = $original_w / $requested_w;
		$y1 = ($original_h / 2) - ($requested_h * $prop / 2);
		$y2 = ($original_h / 2) + ($requested_h * $prop / 2);
	} else if ($requested_h > $original_h) {
		$y1 = 0;
		$y2 = $original_h;
		$prop = $original_h / $requested_h;
		$x1 = ($original_w / 2) - ($requested_w * $prop / 2);
		$x2 = ($original_w / 2) + ($requested_w * $prop / 2);
	} else {
		$x1 = $x2 = $y1 = $y2 = 0;
	}

	$contents = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $requested_w, $requested_h, false, $x1, $y1, $x2, $y2, true);
} else {
	$filehandler->open('read');
	$contents = $filehandler->read($filehandler->size());
}

access_show_hidden_entities($ha);

header("Content-type: image/jpeg");
header('Expires: ' . date('r', time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
echo $contents;
