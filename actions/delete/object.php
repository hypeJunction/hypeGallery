<?php

/**
 * Delete an entity
 *
 * @uses $guid guid of an entity to be deleted
 */

namespace hypeJunction\Gallery;

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('gallery:delete:error:notentity'));
}

$container = $entity->getContainerEntity();
if ($entity->canEdit() && $entity->delete()) {
	return elgg_ok_response(
		['guid' => $guid],
		elgg_echo('gallery:delete:success'),
		$container ? $container->getURL() : REFERRER
	);
}

return elgg_error_response(elgg_echo('gallery:delete:error:unknown'));
