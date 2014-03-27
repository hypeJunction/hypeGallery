<?php

namespace hypeJunction\Gallery;

use ElggFile;
use hypeJunction\Filestore\UploadHandler;
use WideImage\Exception\Exception;
use WideImage\WideImage;

$failed = 0;

$album_guid = get_input('container_guid');
$album = get_entity($album_guid);

if (!elgg_instanceof($album, 'object', 'hjalbum') || !$album->canWriteToContainer(0, 'object', 'hjalbumimage')) {
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
$filedrop_guids = get_input('filedrop_guids', array());

// files being uploaded via $_FILES
$uploads = UploadHandler::handle('gallery_files', array(
			'subtype' => 'hjalbumimage',
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

		if (empty($guid)) {
			continue;
		}

		$image = get_entity($guid);
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

		if (!elgg_instanceof($image, 'object', 'hjalbumimage') || $image->simpletype != 'image') {
			$failed++;
			$image->delete();
			continue;
		}

		// delete automatically generated thumbs and create new ones
		$icon_sizes = elgg_get_config('icon_sizes');
		$img = WideImage::load($image->getFilenameOnFilestore());

		foreach ($icon_sizes as $size => $thumb) {

			$old_thumb = new ElggFile();
			$old_thumb->owner_guid = $image->owner_guid;
			$old_thumb->setFilename("icons/" . $image->guid . $size . ".jpg");
			$old_thumb->open('write');
			$old_thumb->close();

			try {
				if ($size !== 'master' && $size !== 'taggable') {
					$resized = $img->resize($thumb['w'], $thumb['h'], 'outside', 'any')->crop('center', 'center', $thumb['w'], $thumb['h']);
				} else {
					$resized = $img->resize($thumb['w'], $thumb['h'], 'inside', 'down');
				}

				switch ($image->mimetype) {
					default :
					case 'image/jpeg' :
						$mime = 'image/jpeg';
						$contents = $resized->asString('jpg', 80);
						$filename = "icons/" . $image->getGUID() . $size . ".jpg";
						break;

					case 'image/gif' :
						$mime = 'image/gif';
						$old_thumb = new ElggFile();
						$old_thumb->owner_guid = $image->owner_guid;
						$filename = "icons/" . $image->getGUID() . $size . ".gif";
						$contents = $resized->asString('gif');
						break;

					case 'image/png' :
						$mime = 'image/png';
						$contents = $resized->asString('png');
						$filename = "icons/" . $image->getGUID() . $size . ".png";
						break;
				}

				$new_thumb = new ElggFile();
				$new_thumb->owner_guid = $image->owner_guid;
				$new_thumb->setFilename($filename);
				$new_thumb->open('write');
				$new_thumb->write($contents);
				$new_thumb->close();
			} catch (Exception $e) {
				$exceptions[] = $e->getMessage();
			}

			if ($new_thumb->getFilenameOnFilestore() !== $old_thumb->getFilenameOnFilestore()) {
				$old_thumb->delete();
			}
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