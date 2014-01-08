<?php

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

if (!elgg_instanceof($album, 'object', 'hjalbum')
		|| !$album->canWriteToContainer(0, 'object', 'hjalbumimage')) {
	register_error('hj:gallery:upload:error:noalbum');
	forward(REFERER);
}

// create timestamp reference for the river entry
$posted = get_input('batch_upload_time', time());

// images are being uploaded via collaborative function thus require approval
if (!$album->canEdit()) {
	$requires_approval = true;
}

// guids of files uploaded using filedrop
$filedrop_guids = get_input('filedrop_guids', array());
// files being uploaded via $_FILES
$guids = hj_gallery_process_file_upload('gallery_files', 'hjalbumimage', null, $album->guid);
if ($guids) {
	foreach ($guids as $name => $guid) {
		if (!$guid) {
			// upload has failed
			$failed[] = $name;
			unset($guids['name']);
		}
	}
}

if (is_array($guids)) {
	$guids = array_merge($guids, $filedrop_guids);
} else {
	$guids = $filedrop_guids;
}

$metadata = elgg_get_metadata(array(
	'guid' => $album->guid,
	'limit' => 0
		));

if ($guids) {

	foreach ($guids as $guid) {

		$image = get_entity($guid);

		if (!elgg_instanceof($image, 'object', 'hjalbumimage')
				|| $image->simpletype != 'image') {
			$failed[] = $image->getGUID();
			$image->delete();
			continue;
		}

		$image->container_guid = $album->guid; // in case these were uploaded with filedrop
		$image->title = $image->originalfilename;
		$image->access_id = $album->access_id;

		foreach ($metadata as $md) {
			$names[] = $md->name;
		}

		$names = array_unique($names);

		foreach ($names as $name) {
			$image->$name = $album->$name;
		}

		if ($image->save()) {
			$images[] = $image->getGUID();
		}

		if ($requires_approval) {
			$images_pending[] = $image->getGUID();
			$image->posted = $posted;
			$image->disable('pending_approval');
		}
	}
}

if (count($images)) {
	system_message(elgg_echo('hj:gallery:upload:imagesuploaded', array(count($images))));
}

if (count($failed)) {
	system_message(elgg_echo('hj:gallery:upload:unsupportedtype', array(count($failed))));
}

if (count($images_pending)) {
	system_message(elgg_echo('hj:gallery:upload:pending', array(count($images_pending))));
}

$metadata_id = create_metadata($album->guid, "river_$posted", serialize($images), '', $album->owner_guid, $album->access_id, true);

if (count($images) && !$requires_approval) {
	add_to_river('river/object/hjalbum/update', 'update', elgg_get_logged_in_user_guid(), $album->guid, $album->access_id, $posted);
} else {
	$metadata = get_metadata($metadata_id);
	// make sure we have sufficient privileges
	$ia = elgg_set_ignore_access(true);
	$metadata->disable();
	elgg_set_ignore_access($ia);
}

if (count($images_pending)) {
	$to = $album->owner_guid;
	$from = elgg_get_logged_in_user_guid();
	$subject = elgg_echo('hj:gallery:upload:pending', array(count($images_pending)));

	$album_link = elgg_view('output/url', array(
		'text' => $album->title,
		'href' => $album->getURL(),
		'is_trusted' => true
			));

	$manage_link = elgg_view('output/url', array(
		'text' => elgg_echo('hj:gallery:manage:album'),
		'href' => "gallery/manage/$album->guid",
		'is_trusted' => true
			));

	$message = elgg_echo('hj:gallery:upload:pending:message', array(
		count($images_pending), $album_link, $manage_link,
			));

	notify_user($to, $from, $subject, $message);
}

if (elgg_is_xhr()) {
	print json_encode(array('album_guid' => $album->guid, 'image_guids' => $images));
}