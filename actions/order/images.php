<?php

namespace hypeJunction\Gallery;

$priorities = get_input('elgg-object');

$i = 0;
$reordered = [];
if (is_array($priorities)) {
	foreach ($priorities as $priority => $guid) {
		$image = $guid ? get_entity((int) $guid) : null;
		if ($image instanceof \ElggEntity && $image->canEdit()) {
			if ($image->setMetadata('priority', $i, 'integer')) {
				$reordered[$image->guid] = $image->priority;
				$i++;
			}
		}
	}
}

return elgg_ok_response($reordered);
