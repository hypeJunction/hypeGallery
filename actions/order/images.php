<?php

$priorities = get_input('elgg-entity');

for($i=0; $i<count($priorities);$i++) {
	$image = get_entity($priorities[$i]);
	if (elgg_instanceof($image) && $image->canEdit()) {
		$image->priority = $i*10+1;
		$reordered[$image->guid] = $image->priority;
	}
}
print json_encode($reordered);
forward(REFERER);