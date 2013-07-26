<?php

// Site menu
elgg_register_menu_item('site', array(
	'name' => 'gallery',
	'text' => elgg_echo('gallery'),
	'href' => 'gallery/dashboard/site',
));

elgg_register_plugin_hook_handler('register', 'menu:entity', 'hj_gallery_entity_menu');
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
					'text' => '<i class="gallery-icon-upload"></i><span>' . elgg_echo('hj:gallery:upload') . '</span>',
					'title' => elgg_echo('hj:gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'class' => 'elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 400
				);
			}

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['manage'] = array(
					'text' => '<i class="gallery-icon-manage"></i><span>' . elgg_echo('hj:gallery:manage:album') . '</span>',
					'title' => elgg_echo('hj:gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'priority' => 400
				);
			}

			if ($entity->canEdit()) {
				$items['edit'] = array(
					'text' => '<i class="gallery-icon-edit"></i><span>' . elgg_echo('edit') . '</span>',
					'title' => elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'class' => 'elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995
				);
				$items['delete'] = array(
					'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',
					'title' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'class' => 'elgg-button-delete-entity',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}

			break;

		case 'hjalbumimage' :

			// Item is pending approval
			if (!$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
				$items['approve'] = array(
					'text' => '<i class="gallery-icon-approve"></i><span>' . elgg_echo('hj:gallery:approve') . '</span>',
					'title' => elgg_echo('hj:gallery:approve'),
					'href' => "action/gallery/approve/image?guid=$entity->guid",
					'is_action' => true,
					'class' => 'elgg-button-gallery-approve',
					'data-guid' => $entity->guid,
					'priority' => 990
				);

				$items['delete'] = array(
					'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',
					'title' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'class' => 'elgg-button-gallery-delete',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			} else {

				if ($entity->canEdit()) {
					$items['edit'] = array(
						'text' => '<i class="gallery-icon-edit"></i><span>' . elgg_echo('edit') . '</span>',
						'title' => elgg_echo('edit'),
						'href' => $entity->getURL('edit'),
						'class' => 'elgg-button-edit-entity',
						'data-guid' => $entity->guid,
						'priority' => 995
					);
					$items['delete'] = array(
						'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',
						'title' => elgg_echo('delete'),
						'href' => $entity->getURL('delete'),
						'class' => 'elgg-button-gallery-delete',
						'data-guid' => $entity->guid,
						'priority' => 1000
					);

					$items['makeavatar'] = (HYPEGALLERY_AVATARS) ? array(
						'text' => '<i class="gallery-icon-makeavatar"></i><span>' . elgg_echo('hj:gallery:image:makeavatar') . '</span>',
						'title' => elgg_echo('hj:gallery:image:makeavatar'),
						'href' => "action/gallery/makeavatar?e=$entity->guid",
						'is_action' => true,
						'priority' => 100,
							) : NULL;

					$items['cropper'] = array(
						'text' => '<i class="gallery-icon-cropper"></i><span>' . elgg_echo('hj:gallery:image:cropper') . '</span>',
						'title' => elgg_echo('hj:gallery:image:cropper'),
						'href' => "gallery/thumb/$entity->guid",
						'data-guid' => $entity->guid,
						'class' => 'elgg-button-gallery-cropper',
						'priority' => 990,
					);

					if ($entity->getContainerEntity()->canEdit()) {

						$items['drag'] = array(
							'text' => '<i class="gallery-icon-drag"></i><span>' . elgg_echo('hj:gallery:image:reorder') . '</span>',
							'title' => elgg_echo('hj:gallery:image:reorder'),
							'href' => "#elgg-object-$entity->guid",
							'class' => 'elgg-button-gallery-drag',
							'priority' => 10,
							'section' => 'drag'
						);

						$items['position'] = array(
							'text' => elgg_view('input/text', array(
								'name' => "files[$entity->guid][priority]",
								'value' => $entity->priority
							)),
							'title' => elgg_echo('hj:gallery:image:priority'),
							'href' => false,
							'class' => '',
							'priority' => 20,
							'section' => 'drag'
						);

						$items['makecover'] = array(
							'text' => '<i class="gallery-icon-makecover"></i><span>' . elgg_echo('hj:gallery:image:makecover') . '</span>',
							'title' => elgg_echo('hj:gallery:image:makecover'),
							'href' => "action/gallery/makecover?e=$entity->guid",
							'is_action' => true,
							'class' => 'elgg-button-gallery-makecover',
							'item_class' => ($entity->getContainerEntity()->cover == $entity->guid) ? 'hidden' : '',
							'data-guid' => $entity->guid,
							'priority' => 980,
						);
					}
				}

				if (elgg_is_logged_in()) {
					$items['download'] = (HYPEGALLERY_DOWNLOADS) ? array(
						'text' => '<i class="gallery-icon-download"></i><span>' . elgg_echo('hj:gallery:image:download') . '</span>',
						'title' => elgg_echo('hj:gallery:image:download'),
						'href' => $entity->getURL('download'),
						'priority' => 150,
							) : NULL;
				}
			}


			break;
	}

	if (elgg_in_context('gallery-manage')) {
		unset($items['access']);
		unset($items['makeavatar']);
		unset($items['download']);
		unset($items['edit']);
		unset($items['manage']);
	} else {
		unset($items['drag']);
		unset($items['position']);
		unset($items['delete']);
		unset($items['cropper']);
		unset($items['makecover']);
	}
	if ($items) {
		foreach ($items as $name => $item) {
			foreach ($return as $key => $val) {
				if (!$val instanceof ElggMenuItem) {
					unset($return[$key]);
				}
				if ($val instanceof ElggMenuItem && $val->getName() == $name) {
					unset($return[$key]);
				}
			}
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}

	return $return;
}

function hj_gallery_register_entity_title_buttons($entity) {

	if (!elgg_instanceof($entity))
		return true;

	switch ($entity->getSubtype()) {

		default :
			return true;
			break;

		case 'hjalbum' :

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-upload')) {
				$items['upload'] = array(
					'text' => elgg_echo('hj:gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
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

			if ($entity->canEdit()) {
				$items['edit'] = array(
					'text' => elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995
				);

				$items['delete'] = array(
					'text' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'class' => 'elgg-button elgg-button-delete elgg-button-delete-entity',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}

			break;

		case 'hjalbumimage' :

			$items['download'] = (HYPEGALLERY_DOWNLOADS) ? array(
				'text' => elgg_echo('hj:gallery:image:download'),
				'href' => $entity->getURL('download'),
				'class' => 'elgg-button elgg-button-action',
				'priority' => 50,
					) : NULL;

			$items['makeavatar'] = (HYPEGALLERY_AVATARS) ? array(
				'text' => elgg_echo('hj:gallery:image:makeavatar'),
				'href' => "action/gallery/makeavatar?e=$entity->guid",
				'is_action' => true,
				'class' => 'elgg-button elgg-button-action',
				'priority' => 100,
					) : null;

			if ($entity->canEdit()) {
				$items['edit'] = array(
					'text' => elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995
				);

				$items['delete'] = array(
					'text' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'class' => 'elgg-button elgg-button-delete elgg-button-delete-entity elgg-requires-confirmation',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}

			break;
	}

	if ($items) {
		foreach ($items as $name => $options) {
			$options['name'] = $name;
			elgg_register_menu_item('title', $options);
		}
	}

	return true;
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