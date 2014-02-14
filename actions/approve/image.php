<?php

$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

$guid = get_input('guid');
$entity = get_entity($guid);

$error = elgg_echo('gallery:approve:error');

if ($entity && !$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
	if ($entity->enable()) {

		$posted = $entity->posted;
		$river_posted = "river_$posted";

		$md = elgg_get_metadata(array(
			'guid' => $entity->container_guid,
			'metadata_names' => $river_posted,
			'limit' => 1
				));

		if ($md) {
			$meta = $md[0];
			if ($meta->enable()) { // will return false if already enabled.. we want to perform this only once
				add_to_river('river/object/hjalbum/update', 'update', $entity->owner_guid, $entity->container_guid, $entity->access_id, $posted);

				$to = $entity->owner_guid;
				$from = elgg_get_logged_in_user_guid();
				$subject = elgg_echo('gallery:upload:approved');

				$album_link = elgg_view('output/url', array(
					'text' => $entity->getContainerEntity()->title,
					'href' => $entity->getContainerEntity()->getURL(),
					'is_trusted' => true
						));

				$message = elgg_echo('gallery:upload:approved:message', array(
					$album_link
						));

				notify_user($to, $from, $subject, $message);
			}
		}

		$error = false;

		$success = elgg_echo('gallery:approve:success');
	}
}

if ($error) {
	register_error($error);
} else {
	system_message($success);
	if (elgg_is_xhr()) {
		print json_encode(array('guid' => $entity->getGUID()));
	}
}

access_show_hidden_entities($ha);

forward(REFERER);