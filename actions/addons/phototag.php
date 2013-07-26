<?php

$guid = get_input('container_guid', false);
$image = get_entity($guid);

$user_guid = get_input('relationship_guid', false);
$user = get_entity($user_guid);

if (!$image) {
	register_error(elgg_echo('hj:gallery:phototag:error'));
	forward(REFERER);
}

$tag = new ElggObject();
$tag->subtype = 'hjimagetag';
$tag->owner_guid = ($user) ? ($user->guid) : elgg_get_logged_in_user_guid(); // tagged user is owner, so can delete the tag
$tag->container_guid = $image->guid; // image owner will be able to manage tags via container
$tag->title = get_input('title');
$tag->description = '';
$tag->width = get_input('w');
$tag->height = get_input('h');
$tag->x1 = get_input('x1');
$tag->x2 = get_input('x2');
$tag->y1 = get_input('y1');
$tag->y2 = get_input('y2');
$tag->access_id = get_input('access_id');

if ($tag->save(false)) {

	if ($user) {
		$to = $user->guid;
		$from = elgg_get_logged_in_user_guid();
		$subject = elgg_echo('hj:gallery:user:tagged');

		$album_link = elgg_view('output/url', array(
			'text' => $tag->title,
			'href' => $tag->getContainerEntity()->getURL(),
			'is_trusted' => true
				));

		$message = elgg_echo('hj:gallery:user:tagged:message', array(
			$album_link
				));

		notify_user($to, $from, $subject, $message);
	}

	add_to_river('framework/river/stream/phototag', 'stream:phototag', elgg_get_logged_in_user_guid(), $tag->guid, $tag->access_id, time(), -1);
	
	system_message(elgg_echo('hj:gallery:phototag:success'));

	$html = elgg_view_entity($tag);

	if (elgg_is_xhr()) {
		print $html;
	}

	forward(REFERER);
} else {

	register_error(elgg_echo('hj:gallery:phototag:error'));
	forward(REFERER);
}