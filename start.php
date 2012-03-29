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

elgg_register_event_handler('init', 'system', 'hj_gallery_init');

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

	// Register pagehandlers for the gallery
	elgg_register_page_handler('gallery', 'hj_gallery_page_handler');
	elgg_register_entity_url_handler('object', 'hjalbum', 'hj_gallery_album_url');
	elgg_register_entity_url_handler('object', 'hjalbumimage', 'hj_gallery_image_url');

	// Register new profile menu item
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'hj_gallery_owner_block_menu');

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

	elgg_register_plugin_hook_handler('register', 'menu:hjentityhead', 'hj_gallery_entity_head_menu');
	elgg_register_plugin_hook_handler('register', 'menu:hjsectionfoot', 'hj_gallery_section_foot_menu');

	elgg_extend_view('page/components/gallery/prepend', 'hj/gallery/imageplaceholder');

	elgg_register_action('gallery/makeavatar', $shortcuts['actions'] . 'hj/gallery/avatar.php');
	elgg_register_action('hj/gallery/thumb', $shortcuts['actions'] . 'hj/gallery/thumb.php');
	elgg_register_action('gallery/makecover', $shortcuts['actions'] . 'hj/gallery/cover.php');
	elgg_register_action('hj/gallery/phototag', $shortcuts['actions'] . 'hj/gallery/phototag.php');
}

function hj_gallery_album_url($entity) {
	return "gallery/album/{$entity->guid}";
}

function hj_gallery_image_url($entity) {
	$album = $entity->getContainerEntity();
	return "gallery/album/$album->guid/$entity->guid";
}

function hj_gallery_page_handler($page) {
	$plugin = 'hypeGallery';
	$shortcuts = hj_framework_path_shortcuts($plugin);
	$pages = $shortcuts['pages'] . 'gallery/';

// Check if the username was provided in the url
// If no username specified, display logged in user's gallery
	switch ($page[0]) {
		case 'owner' :
			if (isset($page[1])) {
				set_input('username', $page[1]);
			} elseif (elgg_is_logged_in()) {
				set_input('username', elgg_get_logged_in_user_entity()->username);
			} else {
				return false;
			}

			include "{$pages}owner.php";
			break;

		case 'album' :
			if (isset($page[1])) {
				set_input('e', $page[1]);
			}
			if (isset($page[2])) {
				set_input('im', $page[2]);
			}
			include "{$pages}album.php";

			break;

		case 'all' :
			include "{$pages}all.php";
			break;

		default :
			return false;
	}
	return true;
}

function hj_gallery_album_icon($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'medium');

	if ($entity->getSubtype() == 'hjalbum') {
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

	// Extract available parameters
	$entity = elgg_extract('entity', $params);
	$handler = elgg_extract('handler', $params);
	$section = elgg_extract('subtype', $obj_params);

	$form = hj_framework_get_data_pattern('object', 'hjalbumimage');

	if ($entity && elgg_instanceof($entity) && $entity->canEdit()) {
		if ($handler == 'hjgallery') {
			$add = array(
				'name' => 'add',
				'title' => elgg_echo('hj:gallery:addphoto'),
				'text' => elgg_view_icon('hj hj-icon-add'),
				'href' => "action/framework/entities/edit?f=$form->guid&c=$entity->guid",
				'is_action' => true,
				'rel' => 'fancybox',
				'id' => "hj-ajaxed-add-{$section}",
				'class' => "hj-ajaxed-add",
				'target' => "#elgg-widget-{$widget->guid} #hj-section-{$section}-add",
				'priority' => 200
			);
			$return[] = ElggMenuItem::factory($add);
		}
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
			'priority' => 200
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