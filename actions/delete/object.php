<?php

/**
 * Delete an entity
 *
 * @uses $guid	guid of an entity to be deleted
 * @return str json encoded string
 */
$guid = get_input('guid');
$entity = get_entity($guid);

if (!elgg_instanceof($entity)) {
	register_error(elgg_echo('hj:gallery:delete:error:notentity'));
	forward(REFERER);
}

$container = $entity->getContainerEntity();
if ($entity->canEdit() && $entity->delete()) {
	if (elgg_is_xhr()) {
		print json_encode(array('guid' => $guid));
	}
	system_message(elgg_echo('hj:gallery:delete:success'));
	forward($container->getURL(), 'action');
} else {
	register_error(elgg_echo('hj:gallery:delete:error:unknown'));
	forward(REFERER, 'action');
}