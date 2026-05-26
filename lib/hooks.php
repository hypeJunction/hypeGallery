<?php

namespace hypeJunction\Gallery;

use ElggMenuItem;

/**
 * Bypass default access controls
 * - Allow users to add images to shared albums
 */
function container_permissions_check(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$container = $hook->getParam('container', false);
	$user = $hook->getParam('user', false);
	$subtype = $hook->getParam('subtype', false);

	if (!$container instanceof \ElggEntity || !$user instanceof \ElggUser || !$subtype) {
		return $return;
	}

	switch ($container->getSubtype()) {

		case hjAlbum::SUBTYPE :

			switch ($subtype) {

				case hjAlbumImage::SUBTYPE :

					$owner = $container->getOwnerEntity();

					$permission = $container->permission;

					switch ($permission) {

						case 'friends' :
							return $owner->isFriendsWith($user->guid);

						case 'public' :
							return true;

						case 'group' :
							$group = $container->getContainerEntity();
							if ($group instanceof \ElggGroup) {
								return $group->isMember($user);
							}
							break;
					}

					return $return;
			}
			break;
	}

	return $return;
}

/**
 * Bypass default editing permissions
 * - Allow users to edit tags that have been added to photos they own
 */
function permissions_check(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$entity = $hook->getParam('entity', false);
	$user = $hook->getParam('user', false);

	if (!$entity instanceof \ElggObject || !$user instanceof \ElggUser) {
		return $return;
	}

	switch ($entity->getSubtype()) {

		default :
			return $return;

		case 'hjimagetag' :

			$image = $entity->getContainerEntity();
			if ($image->owner_guid == $user->guid) {
				return true;
			}
			return $return;
	}
}

/**
 * Filter access sql to display disabled entities (Elgg 4.x: no-op — use elgg_call(ELGG_SHOW_DISABLED_ENTITIES))
 */
function filter_access_sql(\Elgg\Hook $hook) {
	return $hook->getValue();
}

/**
 * Update entity menus
 */
function entity_menu_setup(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$entity = $hook->getParam('entity', false);

	if (!$entity instanceof \ElggEntity) {
		return $return;
	}

	$items = [];

	switch ($entity->getSubtype()) {

		default :
			return $return;

		case hjAlbum::SUBTYPE :

			if ($entity->canWriteToContainer(0, 'object', hjAlbumImage::SUBTYPE)) {
				$items[] = ElggMenuItem::factory([
					'name' => 'upload',
					'text' => \elgg_echo('gallery:upload'),
					'title' => \elgg_echo('gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'link_class' => 'elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 400,
				]);
				$items[] = ElggMenuItem::factory([
					'name' => 'manage',
					'text' => \elgg_echo('gallery:manage:album'),
					'title' => \elgg_echo('gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'priority' => 400,
				]);
			}

			if ($entity->canEdit()) {
				$items[] = ElggMenuItem::factory([
					'name' => 'edit',
					'text' => \elgg_view_icon('edit'),
					'title' => \elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'link_class' => 'elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995,
				]);
				$items[] = ElggMenuItem::factory([
					'name' => 'delete',
					'text' => \elgg_view_icon('delete'),
					'title' => \elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'link_class' => 'elgg-button-delete-entity',
					'data-guid' => $entity->guid,
					'priority' => 1000,
				]);
			}

			break;

		case hjAlbumImage::SUBTYPE :

			if (\elgg_in_context('gallery-manage')) {

				if (defined('HYPEGALLERY_AVATARS') && HYPEGALLERY_AVATARS && \elgg_is_logged_in()) {
					$items[] = ElggMenuItem::factory([
						'name' => 'makeavatar',
						'text' => \elgg_echo('gallery:image:makeavatar'),
						'title' => \elgg_echo('gallery:image:makeavatar'),
						'href' => "action/gallery/makeavatar?e=$entity->guid",
						'is_action' => true,
						'priority' => 100,
					]);
				}

			} else {

				if (\elgg_is_logged_in()) {

					if (defined('HYPEGALLERY_DOWNLOADS') && HYPEGALLERY_DOWNLOADS &&
						(\elgg_is_logged_in() || (defined('HYPEGALLERY_PUBLIC_DOWNLOADS') && HYPEGALLERY_PUBLIC_DOWNLOADS))) {
						$items[] = ElggMenuItem::factory([
							'name' => 'download',
							'text' => \elgg_echo('gallery:image:download'),
							'title' => \elgg_echo('gallery:image:download'),
							'href' => $entity->getURL('download'),
							'priority' => 150,
						]);
					}

					$items[] = ElggMenuItem::factory([
						'name' => 'edit',
						'text' => \elgg_view_icon('edit'),
						'title' => \elgg_echo('edit'),
						'href' => $entity->getURL('edit'),
						'data-guid' => $entity->guid,
						'priority' => 995,
					]);

					$items[] = ElggMenuItem::factory([
						'name' => 'delete',
						'text' => \elgg_view_icon('delete'),
						'title' => \elgg_echo('delete'),
						'href' => $entity->getURL('delete'),
						'data-guid' => $entity->guid,
						'priority' => 1000,
					]);
				}
			}

			break;
	}

	foreach ($items as $item) {
		if (!$item instanceof ElggMenuItem) {
			continue;
		}
		if ($return instanceof \Elgg\Collections\Collection) {
			$return->add($item);
		} elseif (is_array($return)) {
			$return[$item->getName()] = $item;
		}
	}

	return $return;
}

/**
 * Album/image manage menu items
 */
function manage_album_image_menu_setup(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$entity = $hook->getParam('entity', false);

	if (!$entity instanceof hjAlbumImage) {
		return $return;
	}

	$items = [];

	if (!$entity->isEnabled() && $entity->disable_reason == 'pending_approval' && $entity->getContainerEntity()->canEdit()) {
		$items[] = ElggMenuItem::factory([
			'name' => 'approve',
			'text' => '<i class="gallery-icon-approve"></i><span>' . \elgg_echo('gallery:approve') . '</span>',
			'title' => \elgg_echo('gallery:approve'),
			'href' => "action/gallery/approve/image?guid=$entity->guid",
			'is_action' => true,
			'link_class' => 'elgg-button-gallery-approve',
			'data-guid' => $entity->guid,
			'priority' => 990,
		]);
		$items[] = ElggMenuItem::factory([
			'name' => 'delete',
			'text' => '<i class="gallery-icon-delete"></i><span>' . \elgg_echo('delete') . '</span>',
			'title' => \elgg_echo('delete'),
			'href' => $entity->getURL('delete'),
			'link_class' => 'elgg-button-gallery-delete',
			'data-guid' => $entity->guid,
			'priority' => 1000,
		]);
	}

	if ($entity->canEdit()) {

		$items[] = ElggMenuItem::factory([
			'name' => 'delete',
			'text' => '<i class="gallery-icon-delete"></i><span>' . \elgg_echo('delete') . '</span>',
			'title' => \elgg_echo('delete'),
			'href' => $entity->getURL('delete'),
			'link_class' => 'elgg-button-gallery-delete',
			'data-guid' => $entity->guid,
			'priority' => 1000,
		]);

		$items[] = ElggMenuItem::factory([
			'name' => 'cropper',
			'text' => '<i class="gallery-icon-cropper"></i><span>' . \elgg_echo('gallery:image:cropper') . '</span>',
			'title' => \elgg_echo('gallery:image:cropper'),
			'href' => "gallery/thumb/$entity->guid",
			'data-guid' => $entity->guid,
			'link_class' => 'elgg-button-gallery-cropper',
			'priority' => 990,
		]);
	}

	$container = $entity->getContainerEntity();
	if ($container && $container->canEdit()) {

		$items[] = ElggMenuItem::factory([
			'name' => 'drag',
			'text' => '<i class="gallery-icon-drag"></i><span>' . \elgg_echo('gallery:image:reorder') . '</span>',
			'title' => \elgg_echo('gallery:image:reorder'),
			'href' => "#elgg-object-$entity->guid",
			'link_class' => 'elgg-button-gallery-drag',
			'priority' => 10,
			'section' => 'drag',
		]);

		$items[] = ElggMenuItem::factory([
			'name' => 'position',
			'text' => \elgg_view('input/text', [
				'name' => "files[$entity->guid][priority]",
				'value' => $entity->priority,
			]),
			'title' => \elgg_echo('gallery:image:priority'),
			'href' => false,
			'link_class' => '',
			'priority' => 20,
			'section' => 'drag',
		]);

		$items[] = ElggMenuItem::factory([
			'name' => 'makecover',
			'text' => '<i class="gallery-icon-makecover"></i><span>' . \elgg_echo('gallery:image:makecover') . '</span>',
			'title' => \elgg_echo('gallery:image:makecover'),
			'href' => "action/gallery/makecover?e=$entity->guid",
			'is_action' => true,
			'link_class' => 'elgg-button-gallery-makecover',
			'item_class' => ($entity->getContainerEntity()->cover == $entity->guid) ? 'hidden' : '',
			'data-guid' => $entity->guid,
			'priority' => 980,
		]);
	}

	foreach ($items as $item) {
		if (!$item instanceof ElggMenuItem) {
			continue;
		}
		if ($return instanceof \Elgg\Collections\Collection) {
			$return->add($item);
		} elseif (is_array($return)) {
			$return[$item->getName()] = $item;
		}
	}

	return $return;
}

/**
 * Add gallery related items to owner block menu
 */
function owner_block_menu_setup(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$entity = $hook->getParam('entity');

	$group_albums = defined('HYPEGALLERY_GROUP_ALBUMS') ? HYPEGALLERY_GROUP_ALBUMS : false;

	if ($group_albums && $entity instanceof \ElggGroup && $entity->albums_enable !== 'no') {
		$item = ElggMenuItem::factory([
			'name' => 'group:albums',
			'text' => \elgg_echo('gallery:albums:groups'),
			'href' => "gallery/group/$entity->guid",
		]);
	} elseif ($entity instanceof \ElggUser) {
		$item = ElggMenuItem::factory([
			'name' => 'user:albums',
			'text' => \elgg_echo('gallery:albums'),
			'href' => "gallery/dashboard/owner/$entity->username",
		]);
	} else {
		return $return;
	}

	if ($return instanceof \Elgg\Collections\Collection) {
		$return->add($item);
	} elseif (is_array($return)) {
		$return[] = $item;
	}

	return $return;
}

/**
 * Icon size config
 */
function entity_icon_sizes(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$entity = $hook->getParam('entity');

	if (!$entity instanceof hjAlbumImage) {
		return $return;
	}

	$gallery_config = \elgg_get_config('gallery_icon_sizes');
	return (is_array($return)) ? array_merge($return, $gallery_config) : $gallery_config;
}
