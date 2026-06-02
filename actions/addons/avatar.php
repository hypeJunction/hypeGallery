<?php

namespace hypeJunction\Gallery;

use ElggFile;

\elgg_gatekeeper();

$file_guid = get_input('e');
$file = get_entity($file_guid);
$owner = \elgg_get_logged_in_user_entity();

$filename = $file->getFilenameOnFilestore();

$icon_sizes = \elgg_get_config('icon_sizes');

// get the images and save their file handlers into an array
// so we can do clean up if one fails.
$files = [];
foreach ($icon_sizes as $name => $sinfo) {
	//@todo Make these actual entities.  See exts #348.
	$resized_file = new ElggFile();
	$resized_file->owner_guid = $owner->guid;
	$resized_file->setFilename("profile/{$owner->guid}{$name}.jpg");
	// touch the file so the filestore resolves a stable path, then resize directly into it
	$resized_file->open('write');
	$resized_file->close();

	$resized = \_elgg_services()->imageService->resize($filename, $resized_file->getFilenameOnFilestore(), [
		'w' => $sinfo['w'],
		'h' => $sinfo['h'],
		'square' => $sinfo['square'],
		'upscale' => $sinfo['upscale'],
	]);

	if ($resized) {
		$files[] = $resized_file;
	} else {
		// cleanup on fail
		foreach ($files as $file) {
			$file->delete();
		}

		return \elgg_error_response(\elgg_echo('avatar:resize:fail'));
	}
}

// reset crop coordinates
$owner->x1 = 0;
$owner->x2 = 0;
$owner->y1 = 0;
$owner->y2 = 0;

$owner->icontime = time();
if (\elgg_trigger_event('profileiconupdate', $owner->type, $owner)) {
	$view = 'river/user/default/profileiconupdate';
	\elgg_delete_river(['subject_guid' => $owner->guid, 'view' => $view]);
	\elgg_create_river_item([
		'view' => $view,
		'action_type' => 'update',
		'subject_guid' => $owner->guid,
		'object_guid' => $owner->guid,
	]);
}

return \elgg_ok_response(
	[],
	\elgg_echo('avatar:upload:success'),
	\elgg_generate_url('avatar:edit', ['username' => $owner->username])
);
