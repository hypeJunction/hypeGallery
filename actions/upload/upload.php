<?php

$result = hj_framework_edit_object_action();

if ($result) {
	$entity = elgg_extract('entity', $result);

	/**
	 * Convert temporary file uploads in album images
	 */
	$subtypeIdOld = get_subtype_id('object', 'hjforum');
	$subtypeIdNew = get_subtype_id('object', 'hjalbumimage');

	$uploads = get_input('uploads');

	$dbprefix = elgg_get_config('dbprefix');

	$metadata = elgg_get_metadata(array(
		'guid' => $entity->guid,
		'limit' => 0
			));

	if ($uploads) {
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		foreach ($uploads as $guid) {

			invalidate_cache_for_entity($guid);

			$query = "	UPDATE {$dbprefix}entities e
				SET e.subtype = $subtypeIdNew, e.container_guid = $entity->guid
				WHERE e.guid = $guid";
			update_data($query);

			$image = get_entity($guid);

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

				$images[] = $image->save();
			}
		}
		access_show_hidden_entities($ha);
	}

	print json_encode(array('guid' => $entity->guid, 'images' => $images));
	forward("gallery/manage/$entity->guid");
} else {
	forward(REFERER);
}
