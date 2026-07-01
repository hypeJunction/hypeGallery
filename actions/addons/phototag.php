<?php

namespace hypeJunction\Gallery;

$logged_in = elgg_get_logged_in_user_entity();
$guid = get_input('container_guid', false);
$image = (is_scalar($guid) && $guid) ? get_entity((int) $guid) : null;
$user_guid = get_input('relationship_guid', false);
$title = get_input('title', false);

if (!$image instanceof \ElggEntity || !$image->canEdit()) {
	return elgg_error_response(elgg_echo('gallery:phototag:error'));
}

if (is_array($title)) {
	$title = implode(', ', $title);
}

if (is_array($user_guid)) {
	$user_guid = $user_guid[0];
}

if (is_numeric($user_guid)) {
	$user = get_entity($user_guid);
} else {
	// fallback for default userpicker
	$user = elgg_get_user_by_username($user_guid);
}

if (!$title && !$user) {
	return elgg_error_response(elgg_echo('gallery:phototag:error'));
}

$tag = new \ElggObject();
$tag->subtype = 'hjimagetag';
$tag->owner_guid = $user ? $user->guid : $logged_in->guid;
// tagged user is owner, so can delete the tag
$tag->container_guid = $image->guid;
// image owner will be able to manage tags via container
$tag->title = $title;
$tag->description = '';
$tag->width = get_input('w');
$tag->height = get_input('h');
$tag->x1 = get_input('x1');
$tag->x2 = get_input('x2');
$tag->y1 = get_input('y1');
$tag->y2 = get_input('y2');
$tag->access_id = get_input('access_id');

// need to bypass the access system so that we can save the tag with the tagged user being the owner
$saved = elgg_call(ELGG_IGNORE_ACCESS, function () use ($tag) {
	return $tag->save();
});

if (!$saved) {
	return elgg_error_response(elgg_echo('gallery:phototag:error'));
}

if ($user instanceof \ElggUser && $user->guid != $logged_in->guid) {
	// don't notify self
	$subject = elgg_echo('gallery:user:tagged');
	$image_link = elgg_view('output/url', ['href' => $image->getURL(), 'is_trusted' => true]);
	$message = elgg_echo('gallery:user:tagged:message', [$image_link]);
	elgg_notify_user($user, 'gallery:phototag', $image, [
		'subject' => $subject,
		'body' => $message,
	], $logged_in);
}

$tags = elgg_string_to_array($title);
if (count($tags)) {
	foreach ($tags as $t) {
		$image->setMetadata('tags', $t, '', true);
	}
}

elgg_create_river_item([
	'view' => 'framework/river/stream/phototag',
	'action_type' => 'stream:phototag',
	'subject_guid' => elgg_get_logged_in_user_guid(),
	'object_guid' => $tag->guid,
	'target_guid' => $tag->container_guid,
	'access_id' => $tag->access_id,
	'annotation_id' => -1,
]);

return elgg_ok_response(
	['html' => elgg_view_entity($tag)],
	elgg_echo('gallery:phototag:success')
);
