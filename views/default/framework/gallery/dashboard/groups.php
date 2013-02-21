<?php

$stream = get_input('photostream', false);
$page_owner = elgg_get_page_owner_entity();

$groups = elgg_get_entities_from_relationship(array(
	'types' => 'group',
	'relationship' => 'member',
	'relationship_guid' => $page_owner->guid,
	'limit' => 0
		));

if (!$groups) {
	echo '<p>' . elgg_echo('hj:gallery:nogroups') . '</p>';
	return true;
}

foreach ($groups as $group) {
	$group_guids[] = $group->guid;
}

$getter_options['types'] = 'object';

$list_id = "gr$page_owner->guid";

if (!$stream) {

	$getter_options['subtypes'] = array('hjalbum');
	$getter_options['container_guids'] = $group_guids;

	$params = array(
		'list_id' => $list_id,
		'getter_options' => $getter_options
	);

	echo elgg_view('framework/gallery/list/albums', $params);
} else {

	if (!get_input("__ord_$list_id", false)) {
		set_input("__ord_$list_id", 'e.time_created');
		set_input("__dir_$list_id", 'DESC');
	}

	$getter_options['subtypes'] = array('hjalbumimage');
	$container_guids_in = implode(',', $group_guids);
	$dbprefix = elgg_get_config('dbprefix');
	$getter_options['joins'][] = "JOIN {$dbprefix}entities albcont ON e.container_guid = albcont.guid";
	$getter_options['wheres'][] = "(albcont.container_guid IN ($container_guids_in))";

	$params = array(
		'list_id' => $list_id,
		'getter_options' => $getter_options
	);

	echo elgg_view('framework/gallery/list/images', $params);
}