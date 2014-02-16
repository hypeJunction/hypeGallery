<?php

namespace hypeJunction\Gallery;

use ElggMenuItem;

/**
 * Bypass default access controls
 * - Allow users to add images to shared albums
 *
 * @param string $hook			Equals 'container_permissions_check'
 * @param string $type			Equals 'object'
 * @param boolean $return		Current permission
 * @param array $params			Additional params
 * @return boolean				Updated permission
 */
function container_permissions_check($hook, $type, $return, $params) {

	$container = elgg_extract('container', $params, false);
	$user = elgg_extract('user', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if (!elgg_instanceof($container) || !elgg_instanceof($user, 'user') || !$subtype) {
		return $return;
	}

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
					$container = $container->getContainerEntity();

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

						case 'group' :
							if (elgg_instanceof($container, 'group')) {
								return $container->isMember($user);
							}
							break;
					}

					return $return;
					break;
			}
			break;
	}
}

/**
 * Bypass default editing permissions
 * - Allow users to edit tags that have been added to photos they own
 *
 * @param string $hook		Equals 'permissions_check'
 * @param string $type		Equals 'object'
 * @param boolena $return	Current permission
 * @param array $params		Additional params
 * @return boolean			Updated permission
 */
function permissions_check($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$user = elgg_extract('user', $params, false);

	if (!elgg_instanceof($entity, 'object') || !elgg_instanceof($user, 'user')) {
		return $return;
	}

	switch ($entity->getSubtype()) {

		default :
			return $return;
			break;

		case 'hjimagetag' :

			$image = $entity->getContainerEntity();
			if ($image->owner_guid == $user->guid) {
				return true;
			}
			return $return;
			break;
	}
}

/**
 * Update entity menus
 *
 * @param string $hook			Equals 'register'
 * @param string $type			Equals 'menu:entity'
 * @param array $return			Current menu array
 * @param array $params			Additional params
 * @return array				Updated menu
 */
function entity_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	if (!elgg_instanceof($entity)) {
		return $return;
	}

	switch ($entity->getSubtype()) {

		default :
			return $return;
			break;

		case 'hjalbum' :

			// Add images
			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['upload'] = array(
					'text' => '<i class="gallery-icon-upload"></i><span>' . elgg_echo('gallery:upload') . '</span>',
					'title' => elgg_echo('gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'class' => 'elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 400
				);
			}

			// Manage album
			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
				$items['manage'] = array(
					'text' => '<i class="gallery-icon-manage"></i><span>' . elgg_echo('gallery:manage:album') . '</span>',
					'title' => elgg_echo('gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'priority' => 400
				);
			}

			// Edit and/or Delete
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

			// Is item is pending approval?
			if (!$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
				// Approve
				$items['approve'] = array(
					'text' => '<i class="gallery-icon-approve"></i><span>' . elgg_echo('gallery:approve') . '</span>',
					'title' => elgg_echo('gallery:approve'),
					'href' => "action/gallery/approve/image?guid=$entity->guid",
					'is_action' => true,
					'class' => 'elgg-button-gallery-approve',
					'data-guid' => $entity->guid,
					'priority' => 990
				);
				// Delete
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

					// Edit
					$items['edit'] = array(
						'text' => '<i class="gallery-icon-edit"></i><span>' . elgg_echo('edit') . '</span>',
						'title' => elgg_echo('edit'),
						'href' => $entity->getURL('edit'),
						'class' => 'elgg-button-edit-entity',
						'data-guid' => $entity->guid,
						'priority' => 995
					);

					// Delete
					$items['delete'] = array(
						'text' => '<i class="gallery-icon-delete"></i><span>' . elgg_echo('delete') . '</span>',
						'title' => elgg_echo('delete'),
						'href' => $entity->getURL('delete'),
						'class' => 'elgg-button-gallery-delete',
						'data-guid' => $entity->guid,
						'priority' => 1000
					);

					// Use this image as avatar
					$items['makeavatar'] = (HYPEGALLERY_AVATARS) ? array(
						'text' => '<i class="gallery-icon-makeavatar"></i><span>' . elgg_echo('gallery:image:makeavatar') . '</span>',
						'title' => elgg_echo('gallery:image:makeavatar'),
						'href' => "action/gallery/makeavatar?e=$entity->guid",
						'is_action' => true,
						'priority' => 100,
							) : NULL;

					// Crop
					$items['cropper'] = array(
						'text' => '<i class="gallery-icon-cropper"></i><span>' . elgg_echo('gallery:image:cropper') . '</span>',
						'title' => elgg_echo('gallery:image:cropper'),
						'href' => "gallery/thumb/$entity->guid",
						'data-guid' => $entity->guid,
						'class' => 'elgg-button-gallery-cropper',
						'priority' => 990,
					);
				}

				$container = $entity->getContainerEntity();
				if ($container && $container->canEdit()) {

					// Reorder drag handle
					$items['drag'] = array(
						'text' => '<i class="gallery-icon-drag"></i><span>' . elgg_echo('gallery:image:reorder') . '</span>',
						'title' => elgg_echo('gallery:image:reorder'),
						'href' => "#elgg-object-$entity->guid",
						'class' => 'elgg-button-gallery-drag',
						'priority' => 10,
						'section' => 'drag'
					);

					// Reorder input
					$items['position'] = array(
						'text' => elgg_view('input/text', array(
							'name' => "files[$entity->guid][priority]",
							'value' => $entity->priority
						)),
						'title' => elgg_echo('gallery:image:priority'),
						'href' => false,
						'class' => '',
						'priority' => 20,
						'section' => 'drag'
					);

					// Make is image an album cover
					$items['makecover'] = array(
						'text' => '<i class="gallery-icon-makecover"></i><span>' . elgg_echo('gallery:image:makecover') . '</span>',
						'title' => elgg_echo('gallery:image:makecover'),
						'href' => "action/gallery/makecover?e=$entity->guid",
						'is_action' => true,
						'class' => 'elgg-button-gallery-makecover',
						'item_class' => ($entity->getContainerEntity()->cover == $entity->guid) ? 'hidden' : '',
						'data-guid' => $entity->guid,
						'priority' => 980,
					);
				}

				if (elgg_is_logged_in()) {

					// Download if allowed
					$items['download'] = (HYPEGALLERY_DOWNLOADS) ? array(
						'text' => '<i class="gallery-icon-download"></i><span>' . elgg_echo('gallery:image:download') . '</span>',
						'title' => elgg_echo('gallery:image:download'),
						'href' => $entity->getURL('download'),
						'priority' => 150,
							) : NULL;
				}
			}


			break;
	}

	if (elgg_in_context('gallery-manage')) {
		$return = array(
			'drag' => $items['drag'],
			'position' => $items['position'],
			'delete' => $items['delete'],
			'cropper' => $items['cropper'],
			'makecover' => $items['makecover']
		);
		foreach ($return as $name => $item) {
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
		return $return;
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

/**
 * Add gallery related items to owner block menu
 *
 * @param string $hook		Equals 'register'
 * @param string $type		Equals 'menu:owner_block'
 * @param array $return		Current menu items
 * @param array $params		Additional params
 * @return array			Updated menu
 */
function owner_block_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (HYPEGALLERY_GROUP_ALBUMS && elgg_instanceof($entity, 'group') && $entity->albums_enable !== 'no') {
		$return[] = ElggMenuItem::factory(array(
					'name' => 'group:albums',
					'text' => elgg_echo('gallery:albums:groups'),
					'href' => "gallery/group/$entity->guid"
		));
	} else if (elgg_instanceof($entity, 'user')) {
		$return[] = ElggMenuItem::factory(array(
					'name' => 'user:albums',
					'text' => elgg_echo('gallery:albums'),
					'href' => "gallery/dashboard/owner/$entity->username"
		));
	}

	return $return;
}