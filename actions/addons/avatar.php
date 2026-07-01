<?php

namespace hypeJunction\Gallery;

elgg_gatekeeper();

$file_guid = get_input('e');
$file = $file_guid ? get_entity((int) $file_guid) : null;
$owner = elgg_get_logged_in_user_entity();

if (!$file instanceof \ElggFile) {
	return elgg_error_response(elgg_echo('avatar:resize:fail'));
}

if (!$owner instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('avatar:resize:fail'));
}

// reset crop coordinates
$owner->x1 = 0;
$owner->x2 = 0;
$owner->y1 = 0;
$owner->y2 = 0;

if (!$owner->saveIconFromElggFile($file)) {
	return elgg_error_response(elgg_echo('avatar:resize:fail'));
}

if (elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
	$view = 'river/user/default/profileiconupdate';
	elgg_delete_river(['subject_guid' => $owner->guid, 'view' => $view]);
	elgg_create_river_item([
		'view' => $view,
		'action_type' => 'update',
		'subject_guid' => $owner->guid,
		'object_guid' => $owner->guid,
	]);
}

return elgg_ok_response(
	[],
	elgg_echo('avatar:upload:success'),
	elgg_generate_url('avatar:edit', ['username' => $owner->username])
);
