<?php

namespace hypeJunction\Gallery;

use ElggFile;

gatekeeper();

$file_guid = get_input('e');
$file = get_entity($file_guid);
$owner = elgg_get_logged_in_user_entity();

$filename = $file->getFilenameOnFilestore();

$icon_sizes = elgg_get_config('icon_sizes');

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
$files = array();
foreach ($icon_sizes as $name => $sinfo) {
	$resized = get_resized_image_from_existing_file($filename, $sinfo['w'], $sinfo['h'], $sinfo['square'], 0, 0, 0, 0, $sinfo['upscale']);

	if ($resized) {
		//@todo Make these actual entities.  See exts #348.
		$file = new ElggFile();
		$file->owner_guid = $guid;
		$file->setFilename("profile/{$owner->guid}{$name}.jpg");
		$file->open('write');
		$file->write($resized);
		$file->close();
		$files[] = $file;
	} else {
		// cleanup on fail
		foreach ($files as $file) {
			$file->delete();
		}

		return elgg_error_response(elgg_echo('avatar:resize:fail'));
	}
}

// reset crop coordinates
$owner->x1 = 0;
$owner->x2 = 0;
$owner->y1 = 0;
$owner->y2 = 0;

$owner->icontime = time();
if (elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
	$view = 'river/user/default/profileiconupdate';
	elgg_delete_river(array('subject_guid' => $owner->guid, 'view' => $view));
	elgg_create_river_item(array(
		'view' => $view,
		'action_type' => 'update',
		'subject_guid' => $owner->guid,
		'object_guid' => $owner->guid,
	));
}

return elgg_ok_response(
	[],
	elgg_echo('avatar:upload:success'),
	elgg_generate_url('avatar:edit', ['username' => $owner->username])
);
