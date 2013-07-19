<?php

// Page handler
elgg_register_page_handler('gallery', 'hj_gallery_page_handler');

function hj_gallery_page_handler($page) {

	$path = elgg_get_plugins_path() . 'hypeGallery/pages/gallery/';

	elgg_load_css('gallery.base.css');
	elgg_load_js('gallery.base.js');

	elgg_push_breadcrumb(elgg_echo('gallery'), 'gallery/dashboard/site');

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
		case 'manage' :
			gatekeeper();

			list($action, $guid) = $page;

			set_input('guid', $guid);

			$include = "{$path}{$action}/object.php";

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

			if (!$entity)
				return false;

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

		case 'view' :
			if (!isset($page[1])) {
				return false;
			}
			$entity = get_entity($page[1]);

			if (!$entity)
				return false;

			$sidebar = elgg_view('framework/gallery/dashboard/sidebar', array('entity' => $entity));

			$layout = elgg_view_layout('content', array(
				'entity' => $entity,
				'title' => $entity->title,
				'content' => elgg_view_entity($entity, array(
					'full_view' => true
				)),
				'sidebar' => $sidebar,
					));
			echo elgg_view_page($entity->title, $layout, 'default', array(
				'entity' => $entity
			));
			break;

		case 'thumb' :
			if (!isset($page[1])) {
				return false;
			}
			$entity = get_entity($page[1]);

			if (!$entity || !$entity->canEdit())
				return false;

			$title = elgg_echo('hj:album:image:editthumb');
			$content = elgg_view_form('gallery/thumb', array(), array('entity' => $entity));

			echo elgg_view_page($title, elgg_view_layout('one_column', array('title' => $title, 'content' => $content)));
			break;

		case 'icon':
			set_input('guid', $page[1]);
			set_input('size', $page[2]);
			include "{$path}icon/icon.php";
			break;

		case 'download':
			set_input('guid', $page[1]);
			include "{$path}file/download.php";
			break;
	}

	return true;
}