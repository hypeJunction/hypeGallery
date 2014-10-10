<?php

namespace hypeJunction\Gallery;

$priorities = get_input('elgg-object');

$i = 0;
foreach ($priorities as $priority => $guid) {
	$image = get_entity($guid);
	if (elgg_instanceof($image) && $image->canEdit()) {
		if (create_metadata($image->guid, 'priority', $i, 'int', $entity->owner_guid, ACCESS_PUBLIC)) {
			$reordered[$image->guid] = $image->priority;
			$i++;
		}
	}
}
if (elgg_is_xhr()) {
	print json_encode($reordered);
}

forward(REFERER);
