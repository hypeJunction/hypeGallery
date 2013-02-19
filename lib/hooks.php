<?php

// Add album image subtype to the list of file subtypes
elgg_register_plugin_hook_handler('file:subtypes', 'framework:config', 'hj_gallery_extend_file_subtypes');

// Custom order by clauses
elgg_register_plugin_hook_handler('order_by_clause', 'framework:lists', 'hj_gallery_order_by_clauses');

// Custom search clause
elgg_register_plugin_hook_handler('custom_sql_clause', 'framework:lists', 'hj_gallery_filter_forum_list');

// Allow users to use albums as container entities
elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hj_gallery_container_permissions_check');

function hj_gallery_extend_file_subtypes($hook, $type, $return, $params) {
	$return[] = 'hjalbumimage';
	return $return;
}

function hj_gallery_order_by_clauses($hook, $type, $options, $params) {

	$order_by = $params['order_by'];
	$direction = $params['direction'];

	list($prefix, $column) = explode('.', $order_by);

	if (!$prefix || !$column) {
		return $options;
	}

	if ($prefix !== 'gallery') {
		return $options;
	}

	$prefix = sanitize_string($prefix);
	$column = sanitize_string($column);
	$direction = sanitize_string($direction);

	$dbprefix = elgg_get_config('dbprefix');

	$order_by_prev = elgg_extract('order_by', $options, false);

	switch ($column) {

		case 'image_count' :
			$options['selects'][] = "COUNT(image.guid) AS image_count";
			$access_sql = get_access_sql_suffix('image');
			$subtype_id = get_subtype_id('object', 'hjalbumimage');
			$options['joins'][] = "LEFT JOIN {$dbprefix}entities image ON (e.guid = image.container_guid AND $access_sql AND image.subtype = $subtype_id)";
			$options['group_by'] = 'e.guid';
			$options['order_by'] = "image_count $direction, e.last_action DESC";

			break;

		case 'author' :
			$options['joins'][] = "JOIN {$dbprefix}users_entity ue ON ue.guid = e.owner_guid";
			$options['order_by'] = "ue.name $direction";
			break;
	}

	if ($order_by_prev) {
		$options['order_by'] = "$order_by_prev, {$options['order_by']}";
	}

	return $options;
}

/**
 * Bypass default permission to allow users to add images to albums
 */
function hj_gallery_container_permissions_check($hook, $type, $return, $params) {

	$container = elgg_extract('container', $params, false);
	$user = elgg_extract('user', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if (!$container || !$user || !$subtype)
		return $return;

	switch ($container->getSubtype()) {

		default :
			return $return;
			break;

		case 'hjalbum' :

			switch ($subtype) {

				default :
					return $return;
					break;

				case 'hjalbumimage' :
					if ($container->canEdit()) {
						return true;
					}

					$owner = $container->getOwnerEntity();

					$permissions = $container->permissions;

					switch ($permissions) {

						default :
						case 'private' :
							return $return;
							break;

						case 'friends' :
							return $owner->isFriendsWith($user->guid);
							break;

						case 'public' :
							return true;
							break;
					}

					return $return;
					break;

			}
			break;

	}
}