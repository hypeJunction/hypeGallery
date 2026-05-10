<?php

namespace hypeJunction\Gallery;

$search_type = get_input('search_type');
$term = get_input('term');

$response = [];

switch ($search_type) {
	case 'tag':
		// In Elgg 3.0 metastrings table was removed; tag values are stored
		// directly in the metadata table value column.
		$tags = elgg_get_tags([
			'limit' => 20,
			'wheres' => [
				function (\Elgg\Database\QueryBuilder $qb) use ($term) {
					return $qb->compare('msv.value', 'LIKE', "{$term}%", ELGG_VALUE_STRING);
				},
			],
		]);

		foreach ($tags as $tag) {
			$response[] = [
				'label' => $tag->tag
			];
		}
		break;

	case 'friend':
		$logged_in = elgg_get_logged_in_user_entity();

		// In Elgg 3.0 users_entity subtable was removed; name is now on the
		// entities table.  Use the built-in search_name_value_pairs option.
		$users = elgg_get_entities([
			'types' => 'user',
			'limit' => 20,
			'relationship' => 'friend',
			'relationship_guid' => $logged_in->guid,
			'inverse_relationship' => false,
			'wheres' => [
				function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($term) {
					return $qb->compare("{$main_alias}.name", 'LIKE', "%{$term}%", ELGG_VALUE_STRING);
				},
			],
		]);

		if ($users) {
			foreach ($users as $user) {
				$response[] = [
					'icon' => $user->getIconURL('tiny'),
					'label' => $user->name,
					'value' => $user->guid
				];
			}
		}
		break;
}

header('Content-type:application/json');
print(json_encode($response));
exit;
