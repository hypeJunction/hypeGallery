<?php

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$username = get_input('username');
$user = get_user_by_username($username);

if (!elgg_instanceof($user, 'user')) {
	$user = elgg_get_logged_in_user_entity();
	forward("gallery/friends/$user->username");
}

//elgg_set_page_owner_guid($user->guid);
elgg_push_breadcrumb($user->name, "gallery/owner/$user->username");
elgg_push_breadcrumb(elgg_echo('hj:gallery:albums:friends', array($user->name)));

$friends = $user->getFriends('', 0, 0);
if ($friends) {
	foreach ($friends as $friend) {
		$owner_guids[] = $friend->guid;
	}
} else {
	register_error(elgg_echo('hj:gallery:albums:friends:none'));
	forward("gallery/all");
}
$data_options = array(
	'type' => 'object',
	'subtype' => 'hjalbum',
	'owner_guids' => $owner_guids,
	'limit' => $limit,
	'offset' => $offset,
	'count' => true
);

$count = elgg_get_entities($data_options);
$data_options['count'] = false;

$albums = elgg_get_entities($data_options);

$target = "hj-list-albums";

$view_params = array(
	'full_view' => true,
	'list_type' => 'list',
	'list_id' => $target,
	'list_class' => 'hj-album-list',
	'item_class' => 'hj-view-entity elgg-state-draggable',
	'pagination' => true,
	'offset' => $offset,
	'limit' => $limit,
	'count' => $count,
	'base_url' => 'hj/sync',
	'data-options' => $data_options
);

$content = elgg_view_entity_list($albums, $view_params);

$title = elgg_echo('hj:gallery:albums:friends', array($user->name));

$content = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter_context' => 'owner',
		));

echo elgg_view_page($title, $content);