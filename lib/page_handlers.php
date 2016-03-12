<?php

namespace hypeJunction\Gallery;

/**
 * Page handler
 *
 * @param array $page URL segments
 * @return boolean
 */
function page_handler($page) {

	$path = dirname(dirname(__FILE__)) . '/pages/gallery/';

	elgg_push_breadcrumb(elgg_echo('gallery'), PAGEHANDLER . '/dashboard/site');

	switch ($page[0]) {

		default :
		case 'dashboard' :

			$dashboard = elgg_extract(1, $page, 'site');
			set_input('dashboard', $dashboard);

			switch ($dashboard) {

				default :
				case 'site' :
					include "{$path}dashboard/site.php";
					break;

				case 'owner' :
				case 'friends' :
				case 'groups' :
				case 'favorites' :
					gatekeeper();

					if (isset($page[2])) {
						$owner = get_user_by_username($page[2]);
					}
					if (!$owner && isset($page[2])) {
						return false;
					}

					elgg_set_page_owner_guid($owner->guid);
					$include = "{$path}dashboard/{$dashboard}.php";

					if (!file_exists($include)) {
						return false;
					}
					include $include;
					break;
			}

			break;

		case 'group' :
			$group_guid = elgg_extract(1, $page, false);
			if (!$group_guid) {
				return false;
			}
			$group = get_entity($group_guid);

			if (!elgg_instanceof($group, 'group')) {
				return false;
			}

			elgg_set_page_owner_guid($group->guid);

			include "{$path}dashboard/group.php";
			break;

		case 'container' :
			$container_guid = elgg_extract(1, $page, false);
			if (!$container_guid) {
				return false;
			}
			$group = get_entity($container_guid);

			if (!elgg_instanceof($container_guid)) {
				return false;
			}

			elgg_set_page_owner_guid($group->guid);

			include "{$path}dashboard/container.php";
			break;

		case 'create' :
			gatekeeper();

			list($action, $subtype, $container_guid) = $page;

			if (!$subtype) {
				return false;
			}

			if (!$container_guid) {
				$container_guid = elgg_get_logged_in_user_guid();
			}

			elgg_set_page_owner_guid($container_guid);

			set_input('container_guid', $container_guid);

			$include = "{$path}create/{$subtype}.php";

			if (!file_exists($include)) {
				return false;
			}

			include $include;
			break;

		case 'edit' :
			gatekeeper();

		case 'view' :
			list($action, $guid) = $page;

			set_input('guid', $guid);

			$include = "{$path}{$action}/object.php";

			if (!file_exists($include)) {
				return false;
			}

			include $include;
			break;

		case 'manage' :
			gatekeeper();

			if (!isset($page[1])) {
				return false;
			}

			set_input('guid', $page[1]);

			$include = "{$path}manage/album.php";

			if (!file_exists($include)) {
				return false;
			}

			include $include;
			break;

		case 'upload' :
			gatekeeper();

			if (!isset($page[1])) {
				return false;
			}

			$entity = get_entity($page[1]);

			if (!$entity) {
				return false;
			}

			if (!$entity->canWriteToContainer(0, 'object', hjAlbumImage::SUBTYPE)) {
				return false;
			}

			set_input('container_guid', $entity->guid);

			$include = "{$path}upload/upload.php";

			if (!file_exists($include)) {
				return false;
			}
			include $include;

			break;

		case 'thumb' :
			if (!isset($page[1])) {
				return false;
			}
			$entity = get_entity($page[1]);

			if (!$entity || !$entity->canEdit()) {
				return false;
			}

			$title = elgg_echo('gallery:image:editthumb');
			$content = elgg_view('framework/gallery/tools/cropper', array(
				'entity' => $entity
			));

			echo elgg_view_page($title, elgg_view_layout('one_column', array(
				'title' => $title,
				'content' => $content))
			);
			break;

		case 'icon':
			array_shift($page);
			$guid = array_shift($page);
			$size = array_shift($page) ? : 'medium';

			$entity = get_entity($guid);
			if (!$entity) {
				return false;
			}
			
			forward($entity->getIconURL($size));
			break;

		case 'download':
			array_shift($page);
			if (!HYPEGALLERY_DOWNLOADS) {
				register_error(elgg_echo('gallery:download:error:disabled'));
				forward('', '403');
			}

			if (!elgg_is_logged_in() && !HYPEGALLERY_PUBLIC_DOWNLOADS) {
				register_error(elgg_echo('gallery:download:error:disabled_public'));
				forward('', '403');
			}

			$guid = array_shift($page);
			$entity = get_entity($guid);
			if (!$entity) {
				return false;
			}

			forward(elgg_get_download_url($entity, true));
			break;

		case 'livesearch':
			set_input('search_type', $page[1]);
			include "{$path}search/livesearch.php";
			break;
	}

	return true;
}
