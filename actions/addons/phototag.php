<?php

$tag = new hjAnnotation();
$tag->annotation_name = 'hjimagetag';
$tag->annotation_value = get_input('title', 'phototag');
$tag->container_guid = get_input('container_guid');
$tag->name = get_input('name');
$tag->description = '';
$tag->width = get_input('w');
$tag->height = get_input('h');
$tag->x1 = get_input('x1');
$tag->x2 = get_input('x2');
$tag->y1 = get_input('y1');
$tag->y2 = get_input('y2');
$image = get_entity(get_input('image_guid'));
$tag->access_id = $image->access_id;

if ($tag->save(false)) {

	$master = new hjFile();
	$master->owner_guid = $tag->owner_guid;
	$master->setFilename("icons/{$tag->guid}master.jpg");

	$temp = new hjFile();
	$temp->owner_guid = $master->owner_guid;
	$temp->setFilename("temp/$tag->guid.jpg");
	$temp->setMimeType('image/jpeg');
	$temp->open('write');
	$temp->write($master->grabFile());
	$temp->close();

	$prefix = "icons/" . $tag->getContainerEntity()->getGUID();

	$icon_sizes_all = hj_framework_get_thumb_sizes('hjimagetag');

	$icon_sizes[] = $icon_sizes_all['tiny'];
	$icon_sizes[] = $icon_sizes_all['small'];
	$icon_sizes[] = $icon_sizes_all['medium'];

	foreach ($icon_sizes as $size => $values) {

		if (!$values) continue;

			$thumb_resized = get_resized_image_from_existing_file($temp->getFilenameOnFilestore(), $tag->width, $tag->height, false, $tag->x1, $tag->y1, $tag->x2, $tag->y2);

		if ($thumb_resized) {

			$thumb = new hjFile();
			$thumb->owner_guid = $tag->owner_guid;
			$thumb->setMimeType('image/jpeg');
			$thumb->setFilename($prefix . "$size.jpg");
			$thumb->open("write");
			$thumb->write($thumb_resized);
			$thumb->close();

			$icontime = true;
		}

	}

	if ($icontime) {
		$tag->icontime = $icontime;
	}

	$temp->delete();


	system_message(elgg_echo('hj:gallery:phototag:success'));
	$html['data'] = elgg_view_entity($tag, array(
		'return_type' => 'list'
	));

	print(json_encode($html));
	forward(REFERER);
} else {
	register_error(elgg_echo('hj:gallery:phototag:error'));
	forward(REFERER);
}