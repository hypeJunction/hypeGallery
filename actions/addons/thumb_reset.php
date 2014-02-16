<?php

namespace hypeJunction\Gallery;

use ElggFile;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	register_error(elgg_echo('gallery:thumb:reset:error'));
	forward(REFERER);
}

$master = new ElggFile();
$master->owner_guid = $entity->owner_guid;
$master->setFilename("icons/{$entity->guid}master.jpg");

$coords = array(
	'x1' => (int) get_input('x1', 0),
	'y1' => (int) get_input('y1', 0),
	'x2' => (int) get_input('x2', 0),
	'y2' => (int) get_input('y2', 0),
);

$result = generate_entity_icons($entity, $master, $coords);

if ($result) {
	foreach ($coords as $coord => $value) {
		$entity->$coord = $value;
	}

	system_message(elgg_echo('gallery:thumb:reset:success'));
} else {
	register_error(elgg_echo('gallery:thumb:reset:error'));
}
if (elgg_is_xhr) {
	print(json_encode($coords));
}
forward(REFERER);
