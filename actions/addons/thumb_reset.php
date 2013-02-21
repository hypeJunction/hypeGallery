<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	register_error(elgg_echo('hj:gallery:thumb:reset:error'));
	forward(REFERER);
}

$result = hj_framework_generate_entity_icons($entity, null);

if ($result) {
	system_message(elgg_echo('hj:gallery:thumb:reset:success'));
} else {
	register_error(elgg_echo('hj:gallery:thumb:reset:error'));
}
forward(REFERER);
