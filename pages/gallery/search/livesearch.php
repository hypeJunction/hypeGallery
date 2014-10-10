<?php

namespace hypeJunction\Gallery;

$search_type = get_input('search_type');
$term = sanitize_string(get_input('term'));

$dbprefix = elgg_get_config('dbprefix');

$response = array();

switch ($search_type) {

	case 'tag' :
		$tags = elgg_get_tags(array(
			'limit' => 20,
			'wheres' => array("msv.string LIKE '$term%'")
		));

		foreach ($tags as $tag) {
			$response[] = array(
				'label' => $tag->tag
			);
		}
		break;

	case 'friend' :

		$logged_in = elgg_get_logged_in_user_entity();

		$users = elgg_get_entities(array(
			'types' => 'user',
			'limit' => 20,
			'joins' => array(
				"JOIN {$dbprefix}entity_relationships er ON e.guid = er.guid_two",
				"JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid",
			),
			'wheres' => array(
				"((er.relationship = 'friend' AND er.guid_one = $logged_in->guid) OR ue.guid = $logged_in->guid)",
				"ue.name LIKE '%$term%'"
			)
		));

		if ($users) {
			foreach ($users as $user) {
				$response[] = array(
					'icon' => $user->getIconURL('tiny'),
					'label' => $user->name,
					'value' => $user->guid
				);
			}
		}

		break;
}

header("Content-type:application/json");
print(json_encode($response));
exit;
