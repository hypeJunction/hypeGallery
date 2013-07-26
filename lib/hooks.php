<?php

// Allow users to use albums as container entities
elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hj_gallery_container_permissions_check');

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
					$group = $container->getContainerEntity();

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
							if (elgg_instanceof($group, 'group')) {
								return $group->isMember($user);
							}
							break;
					}

					return $return;
					break;
			}
			break;
	}
}