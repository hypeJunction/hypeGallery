<?php

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
function hj_gallery_container_permissions_check($hook, $type, $return, $params) {

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
function hj_gallery_permissions_check($hook, $type, $return, $params) {

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
