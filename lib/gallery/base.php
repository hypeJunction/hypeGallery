<?php

/**
 * Get user's gallery
 *
 * @param ElggUser $user
 * @return hjGallery
 */
function hj_gallery_find_user_galleries($user) {
	$galleries = hj_framework_get_entities_by_priority('object', 'hjgallery', $user->guid);
	return $galleries;
}

/**
 * Get available gallery sections
 *
 * @return array
 */
function hj_gallery_get_gallery_section_types() {
	$sections = get_plugin_setting('hj:framework:sections:gallery', 'hypeFramework');
	if (!$sections) {
		$sections = array('hjalbum');
	} else {
		$sections = explode(',', $sections);
	}

	$sections = elgg_trigger_plugin_hook('hj:gallery:sections', 'hjgallery', array('sections' => $sections), $sections);

	set_plugin_setting('hj:framework:sections:gallery', implode(',', $sections), 'hypeFramework');

	foreach ($sections as $section) {
		$return[$section] = elgg_echo("hj:gallery:$section");
	}

	return $return;
}

//function hj_gallery_prepare_relationship_tags() {
//	$options = array(
//		'relationship' => 'friend',
//		'relationship_guid' => elgg_get_logged_in_user_guid(),
//		'inverse_relationship' => FALSE,
//		'types' => user,
//		'limit' => 0
//	);
//
//	$clauses = elgg_get_entity_relationship_where_sql('e.guid', $options['relationship'], $options['relationship_guid'], $options['inverse_relationship']);
//
//	if ($clauses) {
//		// merge wheres to pass to get_entities()
//		if (isset($options['wheres']) && !is_array($options['wheres'])) {
//			$options['wheres'] = array($options['wheres']);
//		} elseif (!isset($options['wheres'])) {
//			$options['wheres'] = array();
//		}
//
//		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);
//
//		// merge joins to pass to get_entities()
//		if (isset($options['joins']) && !is_array($options['joins'])) {
//			$options['joins'] = array($options['joins']);
//		} elseif (!isset($options['joins'])) {
//			$options['joins'] = array();
//		}
//
//		$options['joins'] = array_merge($options['joins'], $clauses['joins']);
//
//		if (isset($options['selects']) && !is_array($options['selects'])) {
//			$options['selects'] = array($options['selects']);
//		} elseif (!isset($options['selects'])) {
//			$options['selects'] = array();
//		}
//
//		$select = array('r.*');
//
//		$options['selects'] = array_merge($options['selects'], $select);
//	}
//
//	return $options;
//}

function hj_gallery_get_filefolder_name() {
	$params = json_decode(get_input('params'), true);
	$container = $params['container'];

	return $container->title;
}

function hj_gallery_prepare_permissions_array() {

	$options = array(
		'private' => elgg_echo('permission:value:private'),
		'friends' => elgg_echo('permission:value:friends'),
		'public' => elgg_echo('permission:value:public')
	);

	$options = elgg_trigger_plugin_hook('hj:gallery:permission', 'all', null, $options);

	return $options;
}