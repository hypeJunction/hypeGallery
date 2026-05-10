<?php

namespace hypeJunction\Gallery;

elgg_push_context('show_hidden_entities');

$guid = get_input('guid');
$entity = get_entity($guid);

$error = elgg_echo('gallery:approve:error');

$success = elgg_echo('gallery:approve:success');

if ($entity && !$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
	if ($entity->enable()) {
		$posted = $entity->posted;
		$river_posted = "river_$posted";

		$md = elgg_get_metadata([
			'guid' => $entity->container_guid,
			'metadata_names' => $river_posted,
			'limit' => 1
		]);

		if ($md) {
			$meta = $md[0];
			if ($meta->enable()) { // will return false if already enabled.. we want to perform this only once
				elgg_create_river_item([
					'view' => 'river/object/hjalbum/update',
					'action_type' => 'update',
					'subject_guid' => $entity->owner_guid,
					'object_guid' => $entity->container_guid,
					'access_id' => $entity->access_id,
					'posted' => $posted
				]);

				$to = $entity->owner_guid;
				$from = elgg_get_logged_in_user_guid();
				$subject = elgg_echo('gallery:upload:approved');

				$album_link = elgg_view('output/url', [
					'text' => $entity->getContainerEntity()->title,
					'href' => $entity->getContainerEntity()->getURL(),
					'is_trusted' => true
				]);

				$message = elgg_echo('gallery:upload:approved:message', [
					$album_link
				]);

				notify_user($to, $from, $subject, $message);
			}
		}

		$error = false;
	}
}

elgg_pop_context();

if ($error) {
	return elgg_error_response($error);
}

return elgg_ok_response(['guid' => $entity->getGUID()], $success);
