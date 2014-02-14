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

			if (!$entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
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
			$guid = elgg_extract(1, $page, 0);
			$size = elgg_extract(2, $page, 'medium');
			$fit = elgg_extract(3, $page, 'inside');
			$scale = elgg_extract(4, $page, 'any');

			set_input('guid', $guid);
			set_input('size', $size);
			set_input('fit', $fit);
			set_input('scale', $scale);
			include "{$path}icon/icon.php";
			break;

		case 'download':
			set_input('guid', $page[1]);
			include "{$path}file/download.php";
			break;

		case 'livesearch':
			set_input('search_type', $page[1]);
			include "{$path}search/livesearch.php";
			break;
	}

	return true;
}
