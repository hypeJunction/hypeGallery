<?php

// Site menu
elgg_register_menu_item('site', array(
	'name' => 'gallery',
	'text' => elgg_echo('gallery'),
	'href' => 'gallery/dashboard/site',
));

elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_gallery_entity_menu');
elgg_register_plugin_hook_handler('register', 'menu:list_filter', 'hj_gallery_list_filter_menu');

function hj_gallery_entity_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity)) return $return;

	switch ($entity->getSubtype()) {

		case 'hjalbum' :

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['upload'] = array(
					'text' => elgg_echo('hj:gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'parent_name' => 'options',
					'priority' => 400
				);
			}

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['manage'] = array(
					'text' => elgg_echo('hj:gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'parent_name' => 'options',
					'priority' => 400
				);
			}

			break;

		case 'hjalbumimage' :

			break;
	}

	foreach ($items as $name => $item) {
		$item['name'] = $name;
		$return[$name] = ElggMenuItem::factory($item);
	}

	return $return;
}


function hj_gallery_list_filter_menu($hook, $type, $return, $params) {

	$handler = elgg_extract('handler', $params);
	$list_id = elgg_extract('list_id', $params);

	if ($handler != 'gallery') {
		return $return;
	}

	$url = full_url();

	$items[] = array(
		'name' => 'toggle:depth:albums',
		'text' => elgg_echo('hj:gallery:switch:albums'),
		'href' => elgg_http_remove_url_query_element($url, 'photostream'),
		'section' => 'depth_toggle',
		'selected' => (!get_input('photostream')),
		'priority' => 200
	);

	$items[] = array(
		'name' => 'toggle:depth:images',
		'text' => elgg_echo('hj:gallery:switch:photostream'),
		'href' => elgg_http_add_url_query_elements($url, array('photostream' => true)),
		'section' => 'depth_toggle',
		'selected' => (get_input('photostream')),
		'priority' => 200
	);

	$list_type = get_input("__list_type_$list_id", 'gallery');

	if ($list_type == 'gallery') {
		$items[] = array(
			'name' => 'toggle:details:thumbs',
			'text' => elgg_echo('hj:gallery:switch:thumbs'),
			'href' => elgg_http_remove_url_query_element($url, 'details'),
			'section' => 'details_toggle',
			'selected' => (!get_input('details')),
			'priority' => 300
		);
		$items[] = array(
			'name' => 'toggle:details:more',
			'text' => elgg_echo('hj:gallery:switch:details'),
			'href' => elgg_http_add_url_query_elements($url, array('details' => true)),
			'section' => 'details_toggle',
			'selected' => (get_input('details')),
			'priority' => 300
		);
	}

	$list_types = array('gallery', 'table');

	if (HYPEGALLERY_INTERFACE_LOCATION && elgg_view_exists('page/components/grids/map')) {
		$list_types[] = 'map';
	}

	foreach ($list_types as $lt) {
		$items[] = array(
			'name' => "toggle:list_type:$lt",
			'text' => elgg_echo("hj:gallery:list_type_toggle:$lt"),
			'href' => elgg_http_add_url_query_elements($url, array("__list_type_$list_id" => $lt)),
			'section' => 'list_type_toggle',
			'selected' => ($lt == $list_type),
			'priority' => 400
		);
	}

	foreach ($items as $item) {
		$return[] = ElggMenuItem::factory($item);
	}

	return $return;
}

function hj_gallery_register_dashboard_title_buttons($dashboard = 'site') {

	switch ($dashboard) {

		case 'site' :
		case 'owner' :
		case 'friends' :
			if (elgg_is_logged_in()) {
				$user = elgg_get_logged_in_user_entity();

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('hj:gallery:create:album'),
					'href' => "gallery/create/album/$user->guid",
					'class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}

			break;

		case 'group' :

			$group = elgg_get_page_owner_entity();

			if ($group->canWriteToContainer()) {

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('hj:gallery:create:album'),
					'href' => "gallery/create/album/$group->guid",
					'class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}
			break;
	}
}
