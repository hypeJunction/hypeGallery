<?php

elgg_register_plugin_hook_handler('init', 'form:edit:plugin:hypegallery', 'hj_gallery_init_plugin_settings_form');
elgg_register_plugin_hook_handler('init', 'form:edit:plugin:user:hypegallery', 'hj_gallery_init_plugin_user_settings_form');

elgg_register_plugin_hook_handler('init', 'form:edit:object:hjalbum', 'hj_gallery_init_album_form');
elgg_register_plugin_hook_handler('init', 'form:edit:object:hjalbumimage', 'hj_gallery_init_image_form');

elgg_register_plugin_hook_handler('process:upload', 'form:input:type:file', 'hj_gallery_process_gallery_image_files');

function hj_gallery_init_plugin_settings_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	$settings = array(
		'album_river',
		'image_river',
		'bookmarks',
		'favorites',
		'interface_location',
		'interface_calendar',
		'copyrights',
		'categories',
		'collaborative_albums'
	);


	foreach ($settings as $s) {
		$config['fields']["params[$s]"] = array(
			'input_type' => 'dropdown',
			'options_values' => array(
				0 => elgg_echo('disable'),
				1 => elgg_echo('enable')
			),
			'value' => $entity->$s
		);
	}

	$config['fields']['params[album_max]'] = array(
		'value' => $entity->album_max
	);
	$config['fields']['params[images_max]'] = array(
		'value' => $entity->images_max
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
				'value' => 'object'
			),
			'subtype' => array(
				'input_type' => 'hidden',
				'value' => 'hjalbum'
			),
			'title' => array(
				'value' => $entity->title,
				'required' => true,
				'label' => elgg_echo('hj:label:hjalbum:title')
			),
			'description' => array(
				'value' => $entity->description,
				'input_type' => 'longtext',
				'class' => 'elgg-input-longtext',
				'label' => elgg_echo('hj:label:hjalbum:description')
			),
			'category' => (HYPEGALLERY_CATEGORIES) ? array(
				'input_type' => 'categories',
				'value' => $entity->category,
				'label' => elgg_echo('hj:label:hjalbum:category')
					) : NULL,
			'location' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
				'input_type' => 'location',
				'value' => ($entity) ? $entity->getLocation() : '',
				'label' => elgg_echo('hj:label:hjalbum:location'),
				'required' => true
					) : NULL,
			'date' => (HYPEGALLERY_INTERFACE_CALENDAR) ? array(
				'input_type' => 'date',
				'value' => $entity->date,
				'label' => elgg_echo('hj:label:hjalbum:date'),
				'required' => true
					) : NULL,
			'tags' => array(
				'input_type' => 'tags',
				'value' => $entity->tags,
				'label' => elgg_echo('hj:label:hjalbum:tags')
			),
			'copyright' => (HYPEGALLERY_COPYRIGHTS) ? array(
				'value' => $entity->copyright,
				'label' => elgg_echo('hj:label:hjalbum:copyright'),
				'required' => true
					) : NULL,
			'permissions' => (HYPEGALLERY_COLLABORATIVE_ALBUMS) ? array(
				'input_type' => 'dropdown',
				'value' => $entity->permissions,
				'options_values' => array(
					'private' => elgg_echo('permission:value:private'),
					'friends' => elgg_echo('permission:value:friends'),
					'public' => elgg_echo('permission:value:public')
				),
				'label' => elgg_echo('hj:label:hjalbum:permissions')
					) : NULL,
			'gallery_image_files' => array(
				'input_type' => 'multifile',
				'allowedfiletypes' => array(
					'image/jpeg', 'image/jpg', 'image/png', 'image/gif'
				),
				'data-callback' => 'image:upload::framework:gallery',
				'label' => elgg_echo('hj:label:hjalbum:upload')
			),
			'access_id' => array(
				'value' => $entity->access_id,
				'input_type' => 'access',
				'label' => elgg_echo('hj:label:hjalbum:access_id')
			),
			'add_to_river' => (HYPEGALLERY_ALBUM_RIVER) ? array(
				'input_type' => 'hidden',
				'value' => ($entity) ? false : true
					) : null
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
			'image' => array(
				'input_type' => 'file',
				'value_type' => 'file',
				'value' => ($entity)
			),
			'category' => array(
				'input_type' => 'categories',
				'value' => ($entity) ? $entity->category : $container->category,
				'label' => elgg_echo('hj:label:hjalbumimage:category')
			),
			'location' => array(
				'input_type' => 'location',
				'value' => ($entity) ? $entity->getLocation() : ($container) ? $container->getLocation() : '',
				'label' => elgg_echo('hj:label:hjalbumimage:location')
			),
			'date' => array(
				'input_type' => 'date',
				'value' => ($entity) ? $entity->date : $container->date,
				'label' => elgg_echo('hj:label:hjalbumimage:date')
			),
			'tags' => array(
				'input_type' => 'tags',
				'value' => ($entity) ? $entity->tags : $container->tags,
				'label' => elgg_echo('hj:label:hjalbumimage:tags')
			),
			'copyright' => array(
				'value' => ($entity) ? $entity->copyright : $container->copyright,
				'label' => elgg_echo('hj:label:hjalbumimage:copyright')
			),
			'access_id' => array(
				'value' => ($entity) ? $entity->access_id : $container->access_id,
				'input_type' => 'hidden',
				'label' => elgg_echo('hj:label:hjalbum:access_id')
			),
			'add_to_river' => (HYPEGALLERY_IMAGE_RIVER) ? array(
				'input_type' => 'hidden',
				'value' => ($entity) ? false : true
					) : null
		)
	);

	return $config;
}

/**
 * Process filedrop fallback uploads
 *
 */
function hj_gallery_process_gallery_image_files($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	$name = elgg_extract('name', $params);
	$files = elgg_extract('files', $params);

	if ($name == 'gallery_image_files') {

		$guids = hj_framework_process_file_upload($name, $entity);

		if ($guids) {
			foreach ($guids as $name => $guid) {
				$return[] = $guid;
				$entity = get_entity($guid);
				if ($entity) {
					$entity->disable('temp_file_upload');
				}
			}
		}
		set_input('uploads', $return);
		return true;
	}

	return $return;
}
