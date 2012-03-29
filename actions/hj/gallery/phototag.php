<?php

$tag = new hjAnnotation();
$tag->annotation_name = 'hjimagetag';
$tag->annotation_value = get_input('title', 'phototag');
$tag->container_guid = get_input('image_guid');
$tag->name = get_input('name');
$tag->description = get_input('description', get_input('title'));
$tag->width = get_input('w');
$tag->height = get_input('h');
$tag->x1 = get_input('x1');
$tag->x2 = get_input('x2');
$tag->y1 = get_input('y1');
$tag->y2 = get_input('y2');
$image = get_entity(get_input('image_guid'));
$tag->access_id = $image->access_id;

if ($tag->save()) {
	system_message(elgg_echo('hj:gallery:phototag:success'));
	$html['data'] = "<li id=\"elgg-object-{$tag->getGUID()}\" class=\"elgg-item\" data-timestamp=\"$tag->time_created\">" . elgg_view_entity($tag, array('return_type' => 'list')) . '</li>';
	print(json_encode($html));
	return true;
} else {
	register_error(elgg_echo('hj:gallery:phototag:error'));
	return true;
}