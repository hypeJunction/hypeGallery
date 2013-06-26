<?php

function hj_gallery_get_image_tags($entity) {

	$tag_params = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'container_guid' => $entity->guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => 'hjimagetag'),
			array('name' => 'annotation_value', 'value' => '', 'operand' => '!=')
		),
		'limit' => 0,
		'order_by' => 'e.time_created asc'
	);

	$tags = elgg_get_entities_from_metadata($tag_params);

	return $tags;
}

function hj_gallery_handle_uploaded_files($entity) {

	$images = array();
	$images_pending = array();
	$failed = array();

	// create timestamp reference for river
	$posted = time();
	
	// images are being uploaded via collaborative function thus require approval
	if (!$entity->canEdit()) {
		$requires_approval = true;
	}

	$guids = hj_framework_process_file_upload('gallery_image_files', $entity);

	$filedrop_uploads = get_input('uploads', array());
	$uploads = array();

	if ($guids) {
		foreach ($guids as $name => $guid) {
			if (!$guid)
				continue;
			$uploads[] = $guid;
			$file = get_entity($guid);
			if ($file) {
				$file->disable('temp_file_upload');
			}
		}
	}

	$uploads = array_merge($filedrop_uploads, $uploads);

	/**
	 * Convert temporary file uploads in album images
	 */
	$subtypeIdNew = get_subtype_id('object', 'hjalbumimage');

	$dbprefix = elgg_get_config('dbprefix');

	$metadata = elgg_get_metadata(array(
		'guid' => $entity->guid,
		'limit' => 0
			));

	if ($uploads) {
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		foreach ($uploads as $guid) {

			if ((int)$guid <= 0)
				continue;
			
			$query = "UPDATE {$dbprefix}entities e
				SET e.subtype = $subtypeIdNew, e.container_guid = $entity->guid
				WHERE e.guid = $guid";
			update_data($query);

			$image = get_entity($guid);

			if ($image->simpletype != 'image') {
				$failed[] = $image->getGUID();
				$image->delete();
				continue;
			}

			if (elgg_instanceof($image)) {

				$image->enable();

				$image->title = $entity->title;
				$image->description = $entity->description;
				$image->access_id = $entity->access_id;

				foreach ($metadata as $md) {
					$names[] = $md->name;
				}

				$names = array_unique($names);

				foreach ($names as $name) {
					$image->$name = $entity->$name;
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
		access_show_hidden_entities($ha);
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

	$metadata_id = create_metadata($entity->guid, "river_$posted", serialize($images), '', $entity->owner_guid, $entity->access_id, true);
	if (get_input('add_to_river') && count($images) && !$requires_approval) {
		add_to_river('river/object/hjalbum/update', 'update', elgg_get_logged_in_user_guid(), $entity->guid, $entity->access_id, $posted);
	} else {
		$metadata = get_metadata($metadata_id);
		// make sure we have sufficient privileges
		$ia = elgg_set_ignore_access(true);
		$metadata->disable();
		elgg_set_ignore_access($ia);
	}

	if (count($images_pending)) {
		$to = $entity->owner_guid;
		$from = elgg_get_logged_in_user_guid();
		$subject = elgg_echo('hj:gallery:upload:pending', array(count($images_pending)));

		$album_link = elgg_view('output/url', array(
			'text' => $entity->title,
			'href' => $entity->getURL(),
			'is_trusted' => true
		));

		$manage_link = elgg_view('output/url', array(
			'text' => elgg_echo('hj:gallery:manage:album'),
			'href' => "gallery/manage/$entity->guid",
			'is_trusted' => true
		));

		$message = elgg_echo('hj:gallery:upload:pending:message', array(
			count($images_pending), $album_link, $manage_link,
		));

		notify_user($to, $from, $subject, $message);

	}

	return $images;
}