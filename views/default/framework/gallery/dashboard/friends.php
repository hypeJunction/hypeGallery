<?php

namespace hypeJunction\Gallery;

$page_owner = elgg_get_page_owner_entity();

if (!elgg_instanceof($page_owner, 'user') || !$page_owner->canEdit()) {
	return;
}

$friends = elgg_get_entities_from_relationship(array(
	'types' => 'user',
	'relationship' => 'friend',
	'relationship_guid' => $page_owner->guid,
	'inverse_relationship' => true,
	'limit' => 0
		));

if (!$friends) {
	echo '<p>' . elgg_echo('gallery:nofriends') . '</p>';
	return true;
}

foreach ($friends as $friend) {
	$owner_guids[] = $friend->guid;
}


$display = get_input('display', 'albums');

echo '<div id="gallery-dashboard-friends">';

switch ($display) {

	default :
	case 'albums' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbum'),
			'owner_guids' => $owner_guids,
			'full_view' => false,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
			'gallery_class' => 'gallery-photostream',
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-albums', 0),
			'offset_key' => 'offset-albums'
		));
		break;

	case 'photostream' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'owner_guids' => $owner_guids,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
			'gallery_class' => 'gallery-photostream',
			'full_view' => false,
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-photostream', 0),
			'offset_key' => 'offset-photostream'
		));
		break;
}

echo '</div>';
