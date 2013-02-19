<?php

/* hypeGallery
 *
 * User Gallery
 * @package hypeJunction
 * @subpackage hypeGallery
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011-2013, Ismayil Khayredinov
 */

define('HYPEGALLERY_RELEASE', 1360950978);

define('HYPEGALLERY_ALBUM_RIVER', elgg_get_plugin_setting('album_river', 'hypeGallery'));
define('HYPEGALLERY_IMAGE_RIVER', elgg_get_plugin_setting('image_river', 'hypeGallery'));
define('HYPEGALLERY_BOOKMARKS', elgg_get_plugin_setting('bookmarks', 'hypeGallery'));
define('HYPEGALLERY_FAVORITES', elgg_get_plugin_setting('favorites', 'hypeGallery'));
define('HYPEGALLERY_INTERFACE_LOCATION', elgg_get_plugin_setting('interface_location', 'hypeGallery'));
define('HYPEGALLERY_INTERFACE_CALENDAR', elgg_get_plugin_setting('interface_calendar', 'hypeGallery'));
define('HYPEGALLERY_COPYRIGHTS', elgg_get_plugin_setting('copyrights', 'hypeGallery'));
define('HYPEGALLERY_CATEGORIES', elgg_get_plugin_setting('categories', 'hypeGallery'));
define('HYPEGALLERY_COLLABORATIVE_ALBUMS', elgg_get_plugin_setting('collaborative_albums', 'hypeGallery'));
define('HYPEGALLERY_ALBUM_MAX', elgg_get_plugin_setting('album_max', 'hypeGallery'));
define('HYPEGALLERY_IMAGES_MAX', elgg_get_plugin_setting('images_max', 'hypeGallery'));

elgg_register_event_handler('init', 'system', 'hj_gallery_init');

function hj_gallery_init() {

	$plugin = 'hypeGallery';

	// Make sure hypeFramework is active and precedes hypeGallery in the plugin list
	if (!is_callable('hj_framework_path_shortcuts')) {
		register_error(elgg_echo('framework:error:plugin_order', array($plugin)));
		disable_plugin($plugin);
		forward('admin/plugins');
	}

	// Run upgrade scripts
	hj_framework_check_release($plugin, HYPEGALLERY_RELEASE);

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Helper Classes
	elgg_register_classes($shortcuts['classes']);

	// Libraries
	$libraries = array(
		'base',
		'forms',
		'page_handlers',
		'actions',
		'assets',
		'views',
		'menus',
		'hooks'
	);

	foreach ($libraries as $lib) {
		$path = "{$shortcuts['lib']}{$lib}.php";
		if (file_exists($path)) {
			elgg_register_library("gallery:library:$lib", $path);
			elgg_load_library("gallery:library:$lib");
		}
	}

	// Search
	elgg_register_entity_type('object', 'hjalbum');
	elgg_register_entity_type('object', 'hjalbumimage');

}

//{
//
//	elgg_register_widget_type('hjgallery', elgg_echo('hj:gallery:widget'), elgg_echo('hj:gallery:widgetdescription'), 'gallery', true);
//	elgg_register_plugin_hook_handler('hj:framework:widget:types', 'all', 'hj_gallery_get_gallery_section_types_hook');
////Check if the initial setup has been performed, if not porform it
//	if (!elgg_get_plugin_setting('hj:gallery:setup')) {
//		elgg_load_library('hj:gallery:setup');
//		if (hj_gallery_setup())
//			system_message('hypeGallery was successfully configured');
//	}
//
//	elgg_register_plugin_hook_handler('entity:icon:url', 'all', 'hj_gallery_album_icon');
//	elgg_register_plugin_hook_handler('hj:framework:form:iconsizes', 'file', 'hj_gallery_icon_sizes');
//
//	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_gallery_entity_head_menu');
//	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_image_entity_head_menu');
//	elgg_register_plugin_hook_handler('register', 'menu:hjsectionfoot', 'hj_gallery_section_foot_menu');
//
//	elgg_register_plugin_hook_handler('hj:framework:form:multifile', 'all', 'hj_gallery_multifile_upload');
//	//elgg_extend_view('page/components/list/prepend', 'hj/gallery/imageplaceholder');
//
//	elgg_register_action('hj/gallery/add', $shortcuts['actions'] . 'hj/gallery/add.php');
//	elgg_register_action('gallery/makeavatar', $shortcuts['actions'] . 'hj/gallery/avatar.php');
//	elgg_register_action('hj/gallery/thumb', $shortcuts['actions'] . 'hj/gallery/thumb.php');
//	elgg_register_action('gallery/makecover', $shortcuts['actions'] . 'hj/gallery/cover.php');
//	elgg_register_action('hj/gallery/phototag', $shortcuts['actions'] . 'hj/gallery/phototag.php');
//
//	elgg_register_ajax_view('object/hjalbum');
//	elgg_register_ajax_view('icon/object/hjalbumimage');
//
//	add_group_tool_option('gallery', elgg_echo('hj:gallery:enablegallery'), true);
//	elgg_extend_view('groups/tool_latest', 'hj/gallery/group_module');
//
//	if (elgg_is_admin_logged_in()) {
//		run_function_once('hj_gallery_upgrade_186');
//	}
//}
//
//function hj_gallery_album_url($entity) {
//	return "gallery/album/{$entity->guid}/";
//}
//
//function hj_gallery_image_url($entity) {
//	$album = $entity->getContainerEntity();
//	return "gallery/image/$entity->guid";
//}
//
//function hj_gallery_album_icon($hook, $type, $return, $params) {
//	$entity = elgg_extract('entity', $params);
//	$entity = get_entity($entity->guid);
//	$size = elgg_extract('size', $params, 'medium');
//
//	if (elgg_instanceof($entity, 'object', 'hjalbum')) {
//		if ($entity->cover) {
//			$cover_image = get_entity($entity->cover);
//			$cover_image = get_entity($cover_image->image);
//		} else if ($images = $entity->getContainedFiles('hjalbumimage')) {
//			$cover_image = $images[0];
//			$cover_image = get_entity($cover_image->image);
//		} else {
//			return "mod/hypeFramework/graphics/folders/$size/photo.png";
//		}
//		if (elgg_instanceof($cover_image)) {
//			return $cover_image->getIconURL($size);
//		} else {
//			return $return;
//		}
//	}
//
//	if (elgg_instanceof($entity, 'object', 'hjalbumimage')) {
//		$file = get_entity($entity->image);
//		if ($file)
//			return $file->getIconURL($size);
//	}
//
//	return $return;
//}
//
//function hj_gallery_get_gallery_section_types_hook($hook, $type, $return, $params) {
//	$context = elgg_extract('context', $params, false);
//
//	if ($context && $context == 'gallery') {
//		$sections = hj_gallery_get_gallery_section_types();
//		$return = array_merge($sections, $return);
//	}
//	return $return;
//}
//
//function hj_gallery_entity_head_menu($hook, $type, $return, $params) {
//
//	$entity = elgg_extract('entity', $params);
//
//	$form = hj_framework_get_data_pattern('object', 'hjalbumimage');
//
//	if ($entity && elgg_instanceof($entity, 'object', 'hjalbum') && $entity->canEdit()) {
//		$params = array(
//			'subtype' => 'hjalbumimage',
//			'subject_guid' => null,
//			'entity_guid' => null,
//			'form_guid' => $form->guid,
//			'container_guid' => $entity->guid,
//			'owner_guid' => elgg_get_logged_in_user_guid(),
//			'context' => 'gallery',
//			'handler' => 'hjalbum',
//			'push_context' => 'gallery',
//			'thumb_size' => 'medium',
//			'list_type' => 'gallery',
//			'full_view' => false,
//			'event' => 'create',
//			'target' => "hj-gallery-album-images-$entity->guid",
//			'dom_order' => 'prepend',
//		);
//
//		$image_max = elgg_get_plugin_setting('image_max', 'hypeGallery');
//		$count = elgg_get_entities(array(
//			'type' => 'object',
//			'subtype' => 'hjalbumimage',
//			'container_guid' => $entity->guid,
//			'count' => true
//				));
//
//		if (!$image_max || $count < $image_max || $image_max == '0') {
//
//			$data = hj_framework_extract_params_from_params($params);
//			$data = hj_framework_json_query($params);
//
//			$add = array(
//				'name' => 'addimage',
//				'title' => elgg_echo('hj:gallery:addimage'),
//				'text' => elgg_echo('hj:gallery:addimage'),
//				'href' => "action/framework/entities/edit",
//				'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
//				'is_action' => true,
//				'rel' => 'fancybox',
//				'class' => "hj-ajaxed-add",
//				'priority' => 100,
//				'section' => 'dropdown'
//			);
//			$return[] = ElggMenuItem::factory($add);
//		}
//	}
//	return $return;
//}
//
//function hj_image_entity_head_menu($hook, $type, $return, $params) {
//
//	$entity = elgg_extract('entity', $params, false);
//	$handler = elgg_extract('handler', $params);
//
//	if (elgg_instanceof($entity, 'object', 'hjalbumimage') && $handler == 'hjfile' && $entity->canEdit()) {
//		$file = get_entity($params['file_guid']);
//
//		$options = array(
//			'name' => 'makeavatar',
//			'text' => elgg_echo('hj:album:image:makeavatar'),
//			'href' => "action/gallery/makeavatar?e=$file->guid",
//			'is_action' => true,
//			'priority' => 100,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//
//		$new['params'] = array(
//			'file_guid' => $file->guid,
//			'entity_guid' => $entity->guid,
//			'target' => "full-elgg-object-$entity->guid #hj-gallery-image-edit"
//		);
//
//		$options = array(
//			'name' => 'editthumb',
//			'text' => elgg_echo('hj:album:image:editthumb'),
//			'href' => "action/hj/gallery/thumb?type=get&image_guid=$entity->guid",
//			'is_action' => true,
//			'class' => 'hj-ajaxed-view',
//			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
//			'priority' => 200,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//
//		$options = array(
//			'name' => 'makecover',
//			'text' => elgg_echo('hj:album:image:makecover'),
//			'href' => "action/gallery/makecover?e=$entity->guid",
//			'is_action' => true,
//			'class' => 'hj-ajaxed-view',
//			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
//			'priority' => 300,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//
//		$options = array(
//			'name' => 'starttagging',
//			'text' => elgg_echo('hj:album:image:startagging'),
//			'href' => "javascript:void(0);",
//			'is_action' => false,
//			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
//			'priority' => 355,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//
//		$options = array(
//			'name' => 'stoptagging',
//			'text' => elgg_echo('hj:album:image:stoptagging'),
//			'href' => "javascript:void(0);",
//			'class' => 'hidden',
//			'is_action' => false,
//			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
//			'priority' => 356,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//	}
//
//	if (elgg_instanceof($entity, 'object', 'hjalbum') && $entity->canEdit()) {
//		$options = array(
//			'name' => 'edit',
//			'text' => elgg_echo('hj:album:editandupload'),
//			'href' => "gallery/edit/$entity->guid",
//			'is_action' => false,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($options);
//	}
//
//	return $return;
//}
//
//function hj_gallery_section_foot_menu($hook, $type, $return, $params) {
//
//	$handler = elgg_extract('handler', $params['params'], null);
//
//	if ($handler !== 'hjalbum') {
//		return $return;
//	}
//
//	$owner_guid = elgg_extract('owner_guid', $params['params']);
//	$owner = get_entity($owner_guid);
//
//	$section = elgg_extract('subtype', $params['params']);
//
//	$data = hj_framework_json_query($params);
//	if (elgg_instanceof($owner, 'user') && $owner->canEdit()) {
//		$add = array(
//			'name' => 'add',
//			'title' => elgg_echo('hj:gallery:addnew'),
//			'text' => elgg_view('input/button', array('value' => elgg_echo('hj:gallery:addnew'), 'class' => 'elgg-button-action')),
//			'href' => "action/framework/entities/edit",
//			'data-options' => $data,
//			'is_action' => true,
//			'rel' => 'fancybox',
//			'class' => "hj-ajaxed-add",
//			'priority' => 200,
//			'section' => 'dropdown'
//		);
//		$return[] = ElggMenuItem::factory($add);
//	}
//
//	return $return;
//}
//
//function hj_gallery_container_permissions_check($hook, $type, $return, $params) {
//	$container = elgg_extract('container', $params, false);
//	$subtype = elgg_extract('subtype', $params, false);
//
//	if (elgg_instanceof($container, 'object', 'hjalbum')) {
//		$user = elgg_get_logged_in_user_entity();
//		switch ($container->permissions) {
//			default :
//			case 'private' :
//				if ($container->getOwnerEntity()->guid == $user->guid) {
//					return true;
//				}
//				break;
//
//			case 'friends' :
//				$friend = $container->getOwnerEntity()->isFriend();
//				if ($friend) {
//					return true;
//				}
//				break;
//
//			case 'public' :
//				if ($user) {
//					return true;
//				}
//		}
//	}
//
//	return $return;
//}
//
//function hj_gallery_owner_block_menu($hook, $type, $return, $params) {
//	if (elgg_instanceof($params['entity'], 'user')) {
//		$url = "gallery/owner/{$params['entity']->username}";
//		$return[] = new ElggMenuItem('gallery', elgg_echo('hj:gallery:menu:owner_block'), $url);
//	}
//	return $return;
//}
//
//function hj_gallery_page_menu($hook, $type, $return, $params) {
//	if ($params['context'] == 'gallery') {
//		$all = array(
//			'name' => 'all',
//			'title' => elgg_echo('hj:gallery:allalbums'),
//			'text' => elgg_echo('hj:gallery:allalbums'),
//			'href' => "gallery/all",
//			'priority' => 500
//		);
//		$return[] = ElggMenuItem::factory($all);
//
//		$mine = array(
//			'name' => 'mine',
//			'title' => elgg_echo('hj:gallery:myalbums'),
//			'text' => elgg_echo('hj:gallery:myalbums'),
//			'href' => "gallery/owner",
//			'priority' => 600
//		);
//		$return[] = ElggMenuItem::factory($mine);
//	}
//	return $return;
//}
//
//function hj_gallery_multifile_upload($hook, $type, $return, $params) {
//	$entity = elgg_extract('entity', $params, false);
//	$file_guid = elgg_extract('file_guid', $params, false);
//
//	if (!elgg_instanceof($entity, 'object', 'hjalbum') && $entity->subtype !== 'hjalbum') {
//		return $return;
//	}
//
//	$img = new hjAlbumImage();
//	$img->owner_guid = $entity->owner_guid;
//	$img->container_guid = $entity->guid;
//	$img->access_id = $entity->access_id;
//	$img->title = $entity->title;
//	$img->description = $entity->desription;
//	$img->location = $entity->location;
//	$img->date = $entity->date;
//	$img->image = $file_guid;
//	$img->save();
//
//	hj_framework_set_entity_priority($img);
//
//	$file = new hjFile((int) $file_guid);
//	$file->container_guid = $img->guid;
//	$file->owner_guid = $img->owner_guid;
//	$file->access_id = $img->access_id;
//	$file->save();
//
//	return true;
//}
//
//function hj_gallery_upgrade_186() {
//
//	$form = hj_framework_get_data_pattern('object', 'hjalbum');
//
//	$form->addField(array(
//		'input_type' => 'multifile',
//		'name' => 'photos'
//	));
//
//	return true;
//}