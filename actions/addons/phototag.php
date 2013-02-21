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

	system_message(elgg_echo('hj:gallery:phototag:success'));

	$html = array(
		'list' => "<li class=\"elgg-item\" data-uid=\"$tag->guid\">" . elgg_view_entity($tag, array(
			'return_type' => 'list'
		)) . '</li>',
		'map' => "<li class=\"elgg-item\" data-uid=\"$tag->guid\">" . elgg_view_entity($tag, array(
			'return_type' => 'map'
		)) . '</li>'
	);

	print(json_encode($html));
	forward(REFERER);
} else {
	register_error(elgg_echo('hj:gallery:phototag:error'));
	forward(REFERER);
}