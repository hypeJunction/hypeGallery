<?php

// Site menu
elgg_register_menu_item('site', array(
	'name' => 'gallery',
	'text' => elgg_echo('gallery'),
	'href' => 'gallery/dashboard/site',
));

elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_gallery_entity_menu');
elgg_register_plugin_hook_handler('register', 'menu:title', 'hj_gallery_entity_title_menu');
elgg_register_plugin_hook_handler('register', 'menu:list_filter', 'hj_gallery_list_filter_menu');
elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'hj_gallery_owner_block_menu');

function hj_gallery_entity_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity))
		return $return;

	switch ($entity->getSubtype()) {

		default :
			return $return;
			break;

		case 'hjalbum' :

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['upload'] = array(
					'text' => elgg_echo('hj:gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'class' => 'elgg-button-edit-entity',
					'data-toggle' => 'dialog',
					'data-callback' => 'refresh:lists::framework',
					'data-uid' => $entity->guid,
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

			$items['options'] = array(
				'text' => elgg_view_icon('settings'),
				'href' => "#elgg-entity-$entity->guid",
				'priority' => 500
			);

			// Item is pending approval
			if (!$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
				$items['approve'] = array(
					'text' => elgg_echo('hj:gallery:approve'),
					'href' => "action/gallery/approve/image?guid=$entity->guid",
					'is_action' => true,
					'parent_name' => 'options',
					'class' => 'elgg-button-gallery-approve',
					'data-toggle' => 'dialog',
					'data-callback' => 'refresh:lists::framework',
					'data-uid' => $entity->guid,
					'priority' => 990
				);

				$items['delete'] = array(
					'text' => elgg_echo('delete'),
					'href' => $entity->getDeleteURL(),
					'parent_name' => 'options',
					'class' => 'elgg-button-delete-entity',
					'data-uid' => $entity->guid,
					'priority' => 1000
				);
			} else {

				if ($entity->canEdit()) {
					// have to add these again since hjAlbumImage is not instanceof hjObject
					$items['edit'] = array(
						'text' => elgg_echo('edit'),
						'href' => $entity->getEditURL(),
						'parent_name' => 'options',
						'class' => 'elgg-button-edit-entity',
						'data-toggle' => 'dialog',
						'data-callback' => 'refresh:lists::framework',
						'data-uid' => $entity->guid,
						'priority' => 995
					);
					$items['delete'] = array(
						'text' => elgg_echo('delete'),
						'href' => $entity->getDeleteURL(),
						'parent_name' => 'options',
						'class' => 'elgg-button-delete-entity',
						'data-uid' => $entity->guid,
						'priority' => 1000
					);
				}

				$items['makeavatar'] = (HYPEGALLERY_AVATARS) ? array(
					'text' => elgg_echo('hj:album:image:makeavatar'),
					'href' => "action/gallery/makeavatar?e=$entity->guid",
					'is_action' => true,
					'priority' => 100,
					'parent_name' => 'options'
						) : NULL;

				if (elgg_in_context('gallery-manage')) {
					$items['editthumb'] = array(
						'text' => elgg_echo('hj:album:image:editthumb'),
						'href' => "gallery/thumb/$entity->guid",
						'class' => 'elgg-button-gallery-editthumb',
						'priority' => 990,
						'parent_name' => 'options'
					);

					if ($entity->getContainerEntity()->canEdit()) {

						$items['makecover'] = array(
							'text' => elgg_echo('hj:album:image:makecover'),
							'href' => "action/gallery/makecover?e=$entity->guid",
							'is_action' => true,
							'class' => 'elgg-button-gallery-makecover',
							'item_class' => ($entity->getContainerEntity()->cover == $entity->guid) ? 'hidden' : '',
							'data-uid' => $entity->guid,
							'priority' => 980,
							'parent_name' => 'options'
						);
					}
				}
			}
			break;
	}

	if ($items) {
		foreach ($items as $name => $item) {
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}

	return $return;
}

function hj_gallery_entity_title_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity))
		return $return;

	switch ($entity->getSubtype()) {

		default :
			return $return;
			break;

		case 'hjalbum' :

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-upload')) {
				$items['upload'] = array(
					'text' => elgg_echo('hj:gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-toggle' => 'dialog',
					'data-callback' => 'refresh:lists::framework',
					'data-uid' => $entity->guid,
					'priority' => 400
				);
			}

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-manage')) {
				$items['manage'] = array(
					'text' => elgg_echo('hj:gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'class' => 'elgg-button elgg-button-action',
					'priority' => 400
				);
			}

			break;

		case 'hjalbumimage' :

			if ($entity->canEdit()) {
// have to add these again since hjAlbumImage is not instanceof hjObject
				$items = array(
					'edit' => array(
						'text' => elgg_echo('edit'),
						'href' => $entity->getEditURL(),
						'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
						'data-toggle' => 'dialog',
						'data-uid' => $entity->guid,
						'priority' => 995
					),
					'delete' => array(
						'text' => elgg_echo('delete'),
						'href' => $entity->getDeleteURL(),
						'class' => 'elgg-button elgg-button-delete elgg-button-delete-entity',
						'data-uid' => $entity->guid,
						'priority' => 1000
					)
				);
			}


			$items['makeavatar'] = (HYPEGALLERY_AVATARS) ? array(
				'text' => elgg_echo('hj:album:image:makeavatar'),
				'href' => "action/gallery/makeavatar?e=$entity->guid",
				'is_action' => true,
				'class' => 'elgg-button elgg-button-action',
				'priority' => 100,
					) : null;

			if (HYPEGALLERY_TAGGING) {
				$items['starttagging'] = array(
					'text' => elgg_echo('hj:album:image:startagging'),
					'href' => "#",
					'class' => 'elgg-button elgg-button-action',
					'data-uid' => $entity->guid,
					'priority' => 355,
					'section' => 'dropdown'
				);

				$items['stoptagging'] = array(
					'text' => elgg_echo('hj:album:image:stoptagging'),
					'href' => "#",
					'class' => 'elgg-button elgg-button-action',
					'item_class' => 'hidden',
					'data-uid' => $entity->guid,
					'priority' => 355,
					'section' => 'dropdown'
				);
			}

			break;
	}

	if ($items) {
		foreach ($items as $name => $item) {
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}

	return $return;
}

function hj_gallery_list_filter_menu($hook, $type, $return, $params) {

	$handler = elgg_extract('handler', $params);
	$items_handler = elgg_extract('items_handler', $params);
	$list_id = elgg_extract('list_id', $params);

	if ($handler != 'gallery') {
		return $return;
	}

	$url = full_url();

	if (!elgg_in_context('album-full-view')) {
		$items[] = array(
			'name' => 'toggle:depth:albums',
			'text' => elgg_echo('hj:gallery:switch:albums'),
			'href' => elgg_http_remove_url_query_element($url, 'photostream'),
			'section' => 'depth_toggle',
			'selected' => (!get_input('photostream')),
			'priority' => 210
		);

		$items[] = array(
			'name' => 'toggle:depth:images',
			'text' => elgg_echo('hj:gallery:switch:photostream'),
			'href' => elgg_http_add_url_query_elements($url, array('photostream' => true)),
			'section' => 'depth_toggle',
			'selected' => (get_input('photostream')),
			'priority' => 220
		);
	}

	$list_type = get_input("__list_type_$list_id", 'gallery');

	if ($list_type == 'gallery') {
		$items[] = array(
			'name' => 'toggle:details:thumbs',
			'text' => elgg_echo('hj:gallery:switch:thumbs'),
			'href' => elgg_http_remove_url_query_element($url, 'details'),
			'section' => 'details_toggle',
			'selected' => (!get_input('details')),
			'priority' => 310
		);
		$items[] = array(
			'name' => 'toggle:details:more',
			'text' => elgg_echo('hj:gallery:switch:details'),
			'href' => elgg_http_add_url_query_elements($url, array('details' => 'summary')),
			'section' => 'details_toggle',
			'selected' => (get_input('details') == 'summary'),
			'priority' => 320
		);
		if ($items_handler == 'images') {
			$items[] = array(
				'name' => 'toggle:details:full',
				'text' => elgg_echo('hj:gallery:switch:detail_full'),
				'href' => elgg_http_add_url_query_elements($url, array('details' => 'full')),
				'section' => 'details_toggle',
				'selected' => (get_input('details') == 'full'),
				'priority' => 330
			);
		}
	}

	$list_types = array('gallery', 'table');

	if (HYPEGALLERY_INTERFACE_LOCATION && elgg_view_exists('page/components/grids/map')) {
		$list_types[] = 'map';
	}

	$i = 0;
	foreach ($list_types as $lt) {
		$i++;
		$items[] = array(
			'name' => "toggle:list_type:$lt",
			'text' => elgg_echo("hj:gallery:list_type_toggle:$lt"),
			'href' => elgg_http_add_url_query_elements($url, array("__list_type_$list_id" => $lt)),
			'section' => 'list_type_toggle',
			'selected' => ($lt == $list_type),
			'priority' => 400 + $i * 10
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

function hj_gallery_owner_block_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);

	if (HYPEGALLERY_GROUP_ALBUMS && elgg_instanceof($entity, 'group') && $entity->albums_enable !== 'no') {
		$return[] = ElggMenuItem::factory(array(
					'name' => 'group:albums',
					'text' => elgg_echo('hj:gallery:albums:groups'),
					'href' => "gallery/group/$entity->guid"
				));
	} else if (elgg_instanceof($entity, 'user')) {
		$return[] = ElggMenuItem::factory(array(
					'name' => 'user:albums',
					'text' => elgg_echo('hj:gallery:albums'),
					'href' => "gallery/dashboard/owner/$entity->username"
				));
	}

	return $return;
}