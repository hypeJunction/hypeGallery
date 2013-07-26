<?php

elgg_register_plugin_hook_handler('init', 'form:edit:plugin:hypegallery', 'hj_gallery_init_plugin_settings_form');
elgg_register_plugin_hook_handler('init', 'form:edit:plugin:user:hypegallery', 'hj_gallery_init_plugin_user_settings_form');

elgg_register_plugin_hook_handler('init', 'form:edit:object:hjalbum', 'hj_gallery_init_album_form');
elgg_register_plugin_hook_handler('init', 'form:edit:object:hjalbumimage', 'hj_gallery_init_image_form');

elgg_register_plugin_hook_handler('process:input', 'form:input:name:gallery_files', 'hj_gallery_process_gallery_files');

function hj_gallery_init_plugin_settings_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	$settings = array(
		'album_river',
		'favorites',
		'interface_location',
		'interface_calendar',
		'copyrights',
		'categories',
		'collaborative_albums',
		'group_albums',
//		'site_albums_quota',
		'avatars',
		'tagging',
		'downloads'
	);


	foreach ($settings as $s) {
		$config['fields']["params[$s]"] = array(
			'input_type' => 'dropdown',
			'options_values' => array(
				0 => elgg_echo('disable'),
				1 => elgg_echo('enable')
			),
			'value' => $entity->$s,
			'hint' => elgg_echo("edit:plugin:hypegallery:hint:$s")
		);
	}

	$config['fields']["params[leaflet_layer_uri]"] = array(
		'value' => $entity->$s,
		'hint' => elgg_echo("edit:plugin:hypegallery:hint:leaflet_layer_uri")
	);
	$config['fields']["params[leaflet_layer_uri]"] = array(
		'value' => $entity->$s,
		'hint' => elgg_echo("edit:plugin:hypegallery:hint:leaflet_layer_attribution")
	);

	$config['buttons'] = false;

	return $config;
}

function hj_gallery_init_plugin_user_settings_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$user = elgg_get_page_owner_entity();

	$config['fields'] = array(
	);

	$config['buttons'] = false;

	return $config;
}

function hj_gallery_init_album_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, null);
	$container_guid = ($entity) ? $entity->container_guid : elgg_extract('container_guid', $params, ELGG_ENTITIES_ANY_VALUE);
	$container = get_entity($container_guid);

	$config = array(
		'attributes' => array(
			'enctype' => 'multipart/form-data',
			'id' => 'form-edit-object-hjalbum',
			'action' => 'action/edit/object/hjalbum'
		),
		'fields' => array(
			'type' => array(
				'input_type' => 'hidden',
				'value' => 'object',
			),
			'subtype' => array(
				'input_type' => 'hidden',
				'value' => 'hjalbum'
			),
			'guid' => array(
				'input_type' => 'hidden',
				'value' => $entity->guid
			),
			'container_guid' => array(
				'input_type' => 'hidden',
				'value' => $container->guid,
			),
			'title' => array(
				'value' => $entity->title,
				'required' => true,
				'label' => elgg_echo('hj:label:hjalbum:title'),
			),
			'gallery_files' => array(
				'input_type' => 'gallery/filedrop',
				'label' => elgg_echo('hj:label:hjalbum:upload')
			),
			'description' => array(
				'value' => $entity->description,
				'input_type' => 'longtext',
				'class' => 'elgg-input-longtext',
				'label' => elgg_echo('hj:label:hjalbum:description'),
			),
			'categories' => (HYPEGALLERY_CATEGORIES) ? array(
				'input_type' => 'gallery/categories',
				'value' => $entity->category,
				'label' => elgg_echo('hj:label:hjalbum:category'),
					) : NULL,
			'location' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
				'input_type' => 'location',
				'value' => ($entity) ? $entity->getLocation() : '',
				'label' => elgg_echo('hj:label:hjalbum:location'),
					//'required' => true
					) : NULL,
			'date' => (HYPEGALLERY_INTERFACE_CALENDAR) ? array(
				'input_type' => 'date',
				'value' => $entity->date,
				'label' => elgg_echo('hj:label:hjalbum:date'),
					//'required' => true
					) : NULL,
			'tags' => array(
				'input_type' => 'tags',
				'value' => $entity->tags,
				'label' => elgg_echo('hj:label:hjalbum:tags'),
			),
			'copyright' => (HYPEGALLERY_COPYRIGHTS) ? array(
				'input_type' => 'gallery/copyright',
				'value' => $entity->copyright,
				'label' => elgg_echo('hj:label:hjalbum:copyright'),
					//'required' => true
					) : NULL,
			'permissions' => hj_gallery_get_permissions_options($entity, $container),
			'access_id' => array(
				'value' => (isset($entity->access_id)) ? $entity->access_id : get_default_access(),
				'input_type' => 'access',
				'label' => elgg_echo('hj:label:hjalbum:access_id')
			)
		)
	);

	return $config;
}

function hj_gallery_init_image_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, null);
	$container_guid = ($entity) ? $entity->container_guid : elgg_extract('container_guid', $params, ELGG_ENTITIES_ANY_VALUE);
	$container = get_entity($container_guid);

	$config = array(
		'attributes' => array(
			'enctype' => 'multipart/form-data',
			'id' => 'form-edit-object-hjalbumimage',
			'action' => 'action/edit/object/hjalbumimage'
		),
		'fields' => array(
			'type' => array(
				'input_type' => 'hidden',
				'value' => 'object'
			),
			'subtype' => array(
				'input_type' => 'hidden',
				'value' => 'hjalbumimage'
			),
			'title' => array(
				'value' => ($entity) ? $entity->title : $container->title,
				'required' => true,
				'label' => elgg_echo('hj:label:hjalbumimage:title')
			),
			'description' => array(
				'value' => ($entity) ? $entity->description : $container->description,
				'input_type' => 'longtext',
				'class' => 'elgg-input-longtext',
				'label' => elgg_echo('hj:label:hjalbumimage:description')
			),
			'categories' => (HYPEGALLERY_CATEGORIES) ? array(
				'input_type' => 'categories',
				'value' => $entity->categories,
				'label' => elgg_echo('hj:label:hjalbumimage:category')
					) : NULL,
			'location' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
				'input_type' => 'location',
				'value' => ($entity) ? $entity->getLocation() : '',
				'label' => elgg_echo('hj:label:hjalbumimage:location'),
				'required' => true
					) : NULL,
			'date' => (HYPEGALLERY_INTERFACE_CALENDAR) ? array(
				'input_type' => 'date',
				'value' => $entity->date,
				'label' => elgg_echo('hj:label:hjalbumimage:date'),
				'required' => true
					) : NULL,
			'tags' => array(
				'input_type' => 'tags',
				'value' => $entity->tags,
				'label' => elgg_echo('hj:label:hjalbumimage:tags')
			),
			'copyright' => (HYPEGALLERY_COPYRIGHTS) ? array(
				'value' => $entity->copyright,
				'label' => elgg_echo('hj:label:hjalbumimage:copyright'),
				'required' => true
					) : NULL,
		)
	);

	return $config;
}

/**
 * Process filedrop fallback uploads
 *
 */
function hj_gallery_process_gallery_files($hook, $type, $return, $params) {

	// prevent the action from processing this input
	// @see hj_gallery_handle_uploaded_files()
	return true;
}
