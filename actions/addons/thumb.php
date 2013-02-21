<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	register_error(elgg_echo('hj:gallery:thumb:resize:fail'));
	forward(REFERER);
}

$master = new hjFile();
$master->owner_guid = $entity->owner_guid;
$master->setFilename("icons/{$entity->guid}master.jpg");

$entity_dimensions = getimagesize($entity->getFilenameOnFilestore());
$master_dimensions = getimagesize($master->getFilenameOnFilestore());

$x_proportion = round($entity_dimensions[0] / $master_dimensions[0]);
$y_proportion = round($entity_dimensions[1] / $master_dimensions[0]);

$coords = array(
	'x1' => $x_proportion * (int) get_input('x1', 0),
	'y1' => $y_proportion * (int) get_input('y1', 0),
	'x2' => $x_proportion * (int) get_input('x2', 0),
	'y2' => $y_proportion * (int) get_input('y2', 0),
);

$temp = new hjFile();
$temp->owner_guid = $master->owner_guid;
$temp->setFilename("temp/$entity->guid.jpg");
$temp->setMimeType('image/jpeg');
$temp->open('write');
$temp->write($master->grabFile());
$temp->close();

$result = hj_framework_generate_entity_icons($entity, $source, $coords);

$temp->delete();

if ($result) {
	system_message(elgg_echo('hj:gallery:thumb:crop:success'));
} else {
	register_error(elgg_echo('hj:gallery:thumb:resize:fail'));
}

forward(REFERER);
