<?php

namespace hypeJunction\Gallery;

$priorities = get_input('elgg-object');

$i = 0;
$reordered = [];
if (is_array($priorities)) {
	foreach ($priorities as $priority => $guid) {
		$image = get_entity($guid);
		if ($image instanceof \ElggEntity && $image->canEdit()) {
			if (create_metadata($image->guid, 'priority', $i, 'int', $entity->owner_guid, ACCESS_PUBLIC)) {
				$reordered[$image->guid] = $image->priority;
				$i++;
			}
		}
	}
}

return elgg_ok_response($reordered);
