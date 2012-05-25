<?php

/* hypeGallery
 *
 * User Gallery
 * @package hypeJunction
 * @subpackage hypeGallery
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011, Ismayil Khayredinov
 */

elgg_register_event_handler('init', 'system', 'hj_gallery_init', 508);

function hj_gallery_init() {

	$plugin = 'hypeGallery';

	if (!elgg_is_active_plugin('hypeFramework')) {
		register_error(elgg_echo('hj:framework:disabled', array($plugin, $plugin)));
		disable_plugin($plugin);
	}

	$shortcuts = hj_framework_path_shortcuts($plugin);

	elgg_register_classes($shortcuts['classes']);

// Libraries
	elgg_register_library('hj:gallery:base', $shortcuts['lib'] . 'gallery/base.php');
	elgg_register_library('hj:gallery:setup', $shortcuts['lib'] . 'gallery/setup.php');

// Load PHP library
	elgg_load_library('hj:gallery:base');

// Register albums and images in search
	elgg_register_entity_type('object', 'hjalbum');
	elgg_register_entity_type('object', 'hjalbumimage');
	
// Register pagehandlers for the gallery
	elgg_register_page_handler('gallery', 'hj_gallery_page_handler');
	elgg_register_entity_url_handler('object', 'hjalbum', 'hj_gallery_album_url');
	elgg_register_entity_url_handler('object', 'hjalbumimage', 'hj_gallery_image_url');

// Register new profile menu item
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'hj_gallery_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:page', 'hj_gallery_page_menu');

	elgg_register_menu_item('site', array(
		'name' => 'gallery',
		'text' => elgg_echo('gallery'),
		'href' => 'gallery/all',
	));

// Register new admin menu item
//elgg_register_admin_menu_item('administer', 'gallery', 'hj', 300);
// Register CSS and JS
	$css_url = elgg_get_simplecache_url('css', 'hj/gallery/base');
	elgg_register_css('hj.gallery.base', $css_url);

	$js_url = elgg_get_simplecache_url('js', 'hj/gallery/base');
	elgg_register_js('hj.gallery.base', $js_url);

	$js_url = elgg_get_simplecache_url('js', 'hj/gallery/cropper');
	elgg_register_js('hj.gallery.cropper', $js_url);

	$js_url = elgg_get_simplecache_url('js', 'hj/gallery/tagger');
	elgg_register_js('hj.gallery.tagger', $js_url);

// Allow writing to hjalbum containers
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'hj_gallery_container_permissions_check');

	elgg_register_widget_type('hjgallery', elgg_echo('hj:gallery:widget'), elgg_echo('hj:gallery:widgetdescription'), 'gallery', true);
	elgg_register_plugin_hook_handler('hj:framework:widget:types', 'all', 'hj_gallery_get_gallery_section_types_hook');
//Check if the initial setup has been performed, if not porform it
	if (!elgg_get_plugin_setting('hj:gallery:setup')) {
		elgg_load_library('hj:gallery:setup');
		if (hj_gallery_setup())
			system_message('hypeGallery was successfully configured');
	}

	elgg_register_plugin_hook_handler('entity:icon:url', 'all', 'hj_gallery_album_icon');
	elgg_register_plugin_hook_handler('hj:framework:form:iconsizes', 'file', 'hj_gallery_icon_sizes');

	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_gallery_entity_head_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_image_entity_head_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjsectionfoot', 'hj_gallery_section_foot_menu');

	elgg_register_plugin_hook_handler('hj:framework:form:multifile', 'all', 'hj_gallery_multifile_upload');
	//elgg_extend_view('page/components/list/prepend', 'hj/gallery/imageplaceholder');

	elgg_register_action('hj/gallery/add', $shortcuts['actions'] . 'hj/gallery/add.php');
	elgg_register_action('gallery/makeavatar', $shortcuts['actions'] . 'hj/gallery/avatar.php');
	elgg_register_action('hj/gallery/thumb', $shortcuts['actions'] . 'hj/gallery/thumb.php');
	elgg_register_action('gallery/makecover', $shortcuts['actions'] . 'hj/gallery/cover.php');
	elgg_register_action('hj/gallery/phototag', $shortcuts['actions'] . 'hj/gallery/phototag.php');

	elgg_register_ajax_view('object/hjalbum');
	elgg_register_ajax_view('icon/object/hjalbumimage');

	add_group_tool_option('gallery', elgg_echo('hj:gallery:enablegallery'), true);
	elgg_extend_view('groups/tool_latest', 'hj/gallery/group_module');

	run_function_once('hj_gallery_upgrade_186');
}

function hj_gallery_album_url($entity) {
	return "gallery/album/{$entity->guid}/";
}

function hj_gallery_image_url($entity) {
	$album = $entity->getContainerEntity();
	return "gallery/image/$entity->guid";
}

function hj_gallery_page_handler($page) {
	$plugin = 'hypeGallery';
	$shortcuts = hj_framework_path_shortcuts($plugin);
	$pages = $shortcuts['pages'] . 'gallery/';

	elgg_load_js('hj.comments.base');
	elgg_load_css('hj.comments.base');
	elgg_load_js('hj.likes.base');

	elgg_load_js('hj.framework.carousel');
	elgg_load_css('hj.framework.carousel');

	elgg_load_js('jquery.imgareaselect');
	elgg_load_css('jquery.imgareaselect');

	elgg_load_js('hj.gallery.cropper');
	elgg_load_js('hj.gallery.tagger');

	elgg_load_css('hj.gallery.base');

	elgg_push_breadcrumb(elgg_echo('items:object:hjalbum'), 'gallery/all');

// Check if the username was provided in the url
// If no username specified, display logged in user's gallery
	switch ($page[0]) {

		case 'add' :
			if (!elgg_is_logged_in()) {
				return false;
			}

			$title = elgg_echo('gallery:add');

			$container_guid = elgg_extract(1, $page, null);

			elgg_set_page_owner_guid($container_guid);
			
			elgg_push_breadcrumb($title);

			$params = array(
				'container_guid' => $container_guid,
				'owner_guid' => elgg_get_logged_in_user_guid(),
				'subject_guid' => null
			);

			$params = hj_framework_extract_params_from_params($params);

			$content = elgg_view_form('hj/gallery/add', array(
				'enctype' => 'multipart/form-data'
					), $params);

			$layout = elgg_view_layout('one_sidebar', array(
				'title' => $title,
				'content' => $content
					));

			echo elgg_view_page($title, $layout);

			break;

		case 'edit' :
			if (!elgg_is_logged_in()) {
				return false;
			}

			$entity_guid = elgg_extract(1, $page, null);
			$entity = get_entity($entity_guid);

			elgg_set_page_owner_guid($entity->owner_guid);

			if (!elgg_instanceof($entity)) {
				return false;
			}
			$title = elgg_echo('hj:gallery:album:edit', array($entity->title));

			elgg_push_breadcrumb($entity->title, $entity->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$params = hj_framework_extract_params_from_entity($entity);

			$content = elgg_view_form('hj/gallery/add', array(
				'enctype' => 'multipart/form-data'
					), $params);

			$layout = elgg_view_layout('one_sidebar', array(
				'title' => $title,
				'content' => $content
					));

			echo elgg_view_page($title, $layout);

			break;

		case 'group' :
			if (isset($page[1])) {
				set_input('group_guid', $page[1]);
			} else {
				return false;
			}
			elgg_set_page_owner_guid($page[1]);
			
			hj_gallery_register_title_buttons();
			include "{$pages}group.php";
			break;

		case 'owner' :
			if (isset($page[1])) {
				set_input('username', $page[1]);
			} elseif (elgg_is_logged_in()) {
				set_input('username', elgg_get_logged_in_user_entity()->username);
			} else {
				return false;
			}
			
			hj_gallery_register_title_buttons();
			include "{$pages}owner.php";
			break;

		case 'friends' :
			if (isset($page[1])) {
				set_input('username', $page[1]);
			} elseif (elgg_is_logged_in()) {
				set_input('username', elgg_get_logged_in_user_entity()->username);
			} else {
				return false;
			}
			hj_gallery_register_title_buttons();
			include "{$pages}friends.php";
			break;

		case 'album' :
			if (isset($page[1])) {
				set_input('e', $page[1]);
			} else {
				return false;
			}
			include "{$pages}album.php";
			break;


		case 'image' :
			if (isset($page[1])) {
				set_input('e', $page[1]);
			} else {
				return false;
			}
			include "{$pages}image.php";
			break;

		case 'all' :
		default :
			hj_gallery_register_title_buttons();
			include "{$pages}all.php";
			break;

	}

	return true;
}

function hj_gallery_album_icon($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	$entity = get_entity($entity->guid);
	$size = elgg_extract('size', $params, 'medium');

	if (elgg_instanceof($entity, 'object', 'hjalbum')) {
		if ($entity->cover) {
			$cover_image = get_entity($entity->cover);
			$cover_image = get_entity($cover_image->image);
		} else if ($images = $entity->getContainedFiles('hjalbumimage')) {
			$cover_image = $images[0];
			$cover_image = get_entity($cover_image->image);
		} else {
			return "mod/hypeFramework/graphics/folders/$size/photo.png";
		}
		if (elgg_instanceof($cover_image)) {
			return $cover_image->getIconURL($size);
		} else {
			return $return;
		}
	}
	
	if (elgg_instanceof($entity, 'object', 'hjalbumimage')) {
		$file = get_entity($entity->image);
		if ($file)
		return $file->getIconURL($size);
	}

	return $return;

}

function hj_gallery_get_gallery_section_types_hook($hook, $type, $return, $params) {
	$context = elgg_extract('context', $params, false);

	if ($context && $context == 'gallery') {
		$sections = hj_gallery_get_gallery_section_types();
		$return = array_merge($sections, $return);
	}
	return $return;
}

function hj_gallery_entity_head_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	$form = hj_framework_get_data_pattern('object', 'hjalbumimage');

	if ($entity && elgg_instanceof($entity, 'object', 'hjalbum') && $entity->canEdit()) {
		$params = array(
			'subtype' => 'hjalbumimage',
			'subject_guid' => null,
			'entity_guid' => null,
			'form_guid' => $form->guid,
			'container_guid' => $entity->guid,
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'context' => 'gallery',
			'handler' => 'hjalbum',
			'push_context' => 'gallery',
			'thumb_size' => 'medium',
			'list_type' => 'gallery',
			'full_view' => false,
			'event' => 'create',
			'target' => "hj-gallery-album-images-$entity->guid",
			'dom_order' => 'prepend',
		);

		$image_max = elgg_get_plugin_setting('image_max', 'hypeGallery');
		$count = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'hjalbumimage',
			'container_guid' => $entity->guid,
			'count' => true
				));

		if (!$image_max || $count < $image_max || $image_max == '0') {

			$data = hj_framework_extract_params_from_params($params);
			$data = hj_framework_json_query($params);

			$add = array(
				'name' => 'addimage',
				'title' => elgg_echo('hj:gallery:addimage'),
				'text' => elgg_echo('hj:gallery:addimage'),
				'href' => "action/framework/entities/edit",
				'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
				'is_action' => true,
				'rel' => 'fancybox',
				'class' => "hj-ajaxed-add",
				'priority' => 100,
				'section' => 'dropdown'
			);
			$return[] = ElggMenuItem::factory($add);
		}
	}
	return $return;
}

function hj_image_entity_head_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$handler = elgg_extract('handler', $params);

	if (elgg_instanceof($entity, 'object', 'hjalbumimage') && $handler == 'hjfile' && $entity->canEdit()) {
		$file = get_entity($params['file_guid']);

		$options = array(
			'name' => 'makeavatar',
			'text' => elgg_echo('hj:album:image:makeavatar'),
			'href' => "action/gallery/makeavatar?e=$file->guid",
			'is_action' => true,
			'priority' => 100,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);

		$new['params'] = array(
			'file_guid' => $file->guid,
			'entity_guid' => $entity->guid,
			'target' => "full-elgg-object-$entity->guid #hj-gallery-image-edit"
		);

		$options = array(
			'name' => 'editthumb',
			'text' => elgg_echo('hj:album:image:editthumb'),
			'href' => "action/hj/gallery/thumb?type=get&image_guid=$entity->guid",
			'is_action' => true,
			'class' => 'hj-ajaxed-view',
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 200,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);

		$options = array(
			'name' => 'makecover',
			'text' => elgg_echo('hj:album:image:makecover'),
			'href' => "action/gallery/makecover?e=$entity->guid",
			'is_action' => true,
			'class' => 'hj-ajaxed-view',
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 300,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);

		$options = array(
			'name' => 'starttagging',
			'text' => elgg_echo('hj:album:image:startagging'),
			'href' => "javascript:void(0);",
			'is_action' => false,
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 355,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);

		$options = array(
			'name' => 'stoptagging',
			'text' => elgg_echo('hj:album:image:stoptagging'),
			'href' => "javascript:void(0);",
			'class' => 'hidden',
			'is_action' => false,
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 356,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);
	}

	if (elgg_instanceof($entity, 'object', 'hjalbum') && $entity->canEdit()) {
		$options = array(
			'name' => 'edit',
			'text' => elgg_echo('hj:album:editandupload'),
			'href' => "gallery/edit/$entity->guid",
			'is_action' => false,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

function hj_gallery_section_foot_menu($hook, $type, $return, $params) {

	$handler = elgg_extract('handler', $params['params'], null);

	if ($handler !== 'hjalbum') {
		return $return;
	}

	$owner_guid = elgg_extract('owner_guid', $params['params']);
	$owner = get_entity($owner_guid);

	$section = elgg_extract('subtype', $params['params']);

	$data = hj_framework_json_query($params);
	if (elgg_instanceof($owner, 'user') && $owner->canEdit()) {
		$add = array(
			'name' => 'add',
			'title' => elgg_echo('hj:gallery:addnew'),
			'text' => elgg_view('input/button', array('value' => elgg_echo('hj:gallery:addnew'), 'class' => 'elgg-button-action')),
			'href' => "action/framework/entities/edit",
			'data-options' => $data,
			'is_action' => true,
			'rel' => 'fancybox',
			'class' => "hj-ajaxed-add",
			'priority' => 200,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($add);
	}

	return $return;
}

function hj_gallery_container_permissions_check($hook, $type, $return, $params) {
	$container = elgg_extract('container', $params, false);
	$subtype = elgg_extract('subtype', $params, false);

	if (elgg_instanceof($container, 'object', 'hjalbum')) {
		$user = elgg_get_logged_in_user_entity();
		switch ($container->permissions) {
			default :
			case 'private' :
				if ($container->getOwnerEntity()->guid == $user->guid) {
					return true;
				}
				break;

			case 'friends' :
				$friend = $container->getOwnerEntity()->isFriend();
				if ($friend) {
					return true;
				}
				break;

			case 'public' :
				if ($user) {
					return true;
				}
		}
	}

	return $return;
}

function hj_gallery_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "gallery/owner/{$params['entity']->username}";
		$return[] = new ElggMenuItem('gallery', elgg_echo('hj:gallery:menu:owner_block'), $url);
	}
	return $return;
}

function hj_gallery_page_menu($hook, $type, $return, $params) {
	if ($params['context'] == 'gallery') {
		$all = array(
			'name' => 'all',
			'title' => elgg_echo('hj:gallery:allalbums'),
			'text' => elgg_echo('hj:gallery:allalbums'),
			'href' => "gallery/all",
			'priority' => 500
		);
		$return[] = ElggMenuItem::factory($all);

		$mine = array(
			'name' => 'mine',
			'title' => elgg_echo('hj:gallery:myalbums'),
			'text' => elgg_echo('hj:gallery:myalbums'),
			'href' => "gallery/owner",
			'priority' => 600
		);
		$return[] = ElggMenuItem::factory($mine);
	}
	return $return;
}

function hj_gallery_multifile_upload($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);
	$file_guid = elgg_extract('file_guid', $params, false);

	if (!elgg_instanceof($entity, 'object', 'hjalbum') && $entity->subtype !== 'hjalbum') {
		return $return;
	}

	$img = new hjAlbumImage();
	$img->owner_guid = $entity->owner_guid;
	$img->container_guid = $entity->guid;
	$img->access_id = $entity->access_id;
	$img->title = $entity->title;
	$img->description = $entity->desription;
	$img->location = $entity->location;
	$img->date = $entity->date;
	$img->image = $file_guid;
	$img->save();

	hj_framework_set_entity_priority($img);

	$file = new hjFile((int) $file_guid);
	$file->container_guid = $img->guid;
	$file->owner_guid = $img->owner_guid;
	$file->access_id = $img->access_id;
	$file->save();

	return true;
}

function hj_gallery_upgrade_186 () {

	$form = hj_framework_get_data_pattern('object', 'hjalbum');

	$form->addField(array(
		'input_type' => 'multifile',
		'name' => 'photos'
	));

	return true;
}