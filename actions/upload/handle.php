<?php

namespace hypeJunction\Gallery;

use hypeJunction\Filestore\UploadHandler;

$failed = 0;

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

if (!elgg_instanceof($album, 'object', hjAlbum::SUBTYPE) || !$album->canWriteToContainer(0, 'object', hjAlbumImage::SUBTYPE)) {
	register_error('gallery:upload:error:noalbum');
	forward(REFERER);
}

// create timestamp reference for the river entry
$posted = get_input('batch_upload_time', time());

// images are being uploaded via collaborative function thus require approval
if (!$album->canEdit()) {
	$requires_approval = true;
}

// guids of files uploaded using filedrop
$guids = get_input('filedrop_guids', array());
if (!is_array($guids)) {
	$guids = array();
}

// files being uploaded via $_FILES
$uploads = UploadHandler::handle('gallery_files', array(
			'subtype' => hjAlbumImage::SUBTYPE,
			'container_guid' => $album->guid));

if ($uploads && count($uploads)) {
	foreach ($uploads as $upload) {
		if ($upload->guid) {
			$guids[] = $upload->guid;
		} else if ($upload->error && $upload->size) {
			$failed++;
		}
	}
}

$metadata = elgg_get_metadata(array(
	'guid' => $album->guid,
	'limit' => 0
		));

if ($guids) {

	foreach ($guids as $guid) {

		$image = get_entity($guid);
		
		if (!elgg_instanceof($image)) {
			continue;
		}
		
		$image->container_guid = $album->guid; // in case these were uploaded with filedrop

		if (!$image->title) {
			$image->title = $image->originalfilename;
		}
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

		if (!elgg_instanceof($image)) {
			$failed++;
			continue;
		}

		if (!elgg_instanceof($image, 'object', hjAlbumImage::SUBTYPE) || $image->simpletype != 'image') {
			$failed++;
			$image->delete();
			continue;
		}

		if ($requires_approval) {
			$images_pending[] = $image->getGUID();
			$image->posted = $posted;
			$image->disable('pending_approval');
		}
	}
}

if (count($images)) {
	system_message(elgg_echo('gallery:upload:imagesuploaded', array(count($images))));
}

if ($failed) {
	system_message(elgg_echo('gallery:upload:unsupportedtype', array($failed)));
}

if (count($images_pending)) {
	system_message(elgg_echo('gallery:upload:pending', array(count($images_pending))));
}

$metadata_id = create_metadata($album->guid, "river_$posted", serialize($images), '', $album->owner_guid, $album->access_id, true);

if (count($images) && !$requires_approval) {
	elgg_create_river_item(array(
		'view' => 'river/object/hjalbum/update',
		'action_type' => 'update',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $album->guid,
		'access_id' => $album->access_id,
		'posted' => $posted,
	));
} else {
	$metadata = elgg_get_metadata_from_id($metadata_id);
	// make sure we have sufficient privileges
	$ia = elgg_set_ignore_access(true);
	$metadata->disable();
	elgg_set_ignore_access($ia);
}

if (count($images_pending)) {
	$to = $album->owner_guid;
	$from = elgg_get_logged_in_user_guid();
	$subject = elgg_echo('gallery:upload:pending', array(count($images_pending)));

	$album_link = elgg_view('output/url', array(
		'text' => $album->title,
		'href' => $album->getURL(),
		'is_trusted' => true
	));

	$manage_link = elgg_view('output/url', array(
		'text' => elgg_echo('gallery:manage:album'),
		'href' => "gallery/manage/$album->guid",
		'is_trusted' => true
	));

	$message = elgg_echo('gallery:upload:pending:message', array(
		count($images_pending), $album_link, $manage_link,
	));

	notify_user($to, $from, $subject, $message);
}

if (elgg_is_xhr()) {
	print json_encode(array('album_guid' => $album->guid, 'image_guids' => $images));
}
