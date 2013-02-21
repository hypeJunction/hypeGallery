<?php

$stream = get_input('photostream', false);
$page_owner = elgg_get_page_owner_entity();

$friends = elgg_get_entities_from_relationship(array(
	'types' => 'user',
	'relationship' => 'friend',
	'relationship_guid' => $page_owner->guid,
	'inverse_relationship' => true,
	'limit' => 0
));

if (!$friends) {
	echo '<p>' . elgg_echo('hj:gallery:nofriends') . '</p>';
	return true;
}

foreach ($friends as $friend) {
	$owner_guids[] = $friend->guid;
}

$list_id = "fr$page_owner->guid";

if (!$stream) {

	$params = array(
		'list_id' => $list_id,
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => array('hjalbum'),
		'owner_guids' => $owner_guids
	);

	echo elgg_view('framework/gallery/list/albums', $params);
} else {

	if (!get_input("__ord_$list_id", false)) {
		set_input("__ord_$list_id", 'e.time_created');
		set_input("__dir_$list_id", 'DESC');
	}

	$params = array(
		'list_id' => $list_id,
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => array('hjalbumimage'),
		'owner_guids' => $owner_guids
	);

	echo elgg_view('framework/gallery/list/images', $params);
}