<?php

/**
 * Image Galleries for Elgg
 *
 * @package hypeJunction
 * @subpackage hypeGallery
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyright (c) 2011-2014, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

namespace hypeJunction\Gallery;

define('HYPEGALLERY_RELEASE', 1374851653);

define('HYPEGALLERY_ALBUM_RIVER', elgg_get_plugin_setting('album_river', 'hypeGallery'));
define('HYPEGALLERY_FAVORITES', elgg_get_plugin_setting('favorites', 'hypeGallery'));
define('HYPEGALLERY_INTERFACE_LOCATION', elgg_get_plugin_setting('interface_location', 'hypeGallery'));
define('HYPEGALLERY_INTERFACE_CALENDAR', elgg_get_plugin_setting('interface_calendar', 'hypeGallery'));
define('HYPEGALLERY_COPYRIGHTS', elgg_get_plugin_setting('copyrights', 'hypeGallery'));
define('HYPEGALLERY_CATEGORIES', elgg_get_plugin_setting('categories', 'hypeGallery'));
define('HYPEGALLERY_COLLABORATIVE_ALBUMS', elgg_get_plugin_setting('collaborative_albums', 'hypeGallery'));
define('HYPEGALLERY_GROUP_ALBUMS', elgg_get_plugin_setting('group_albums', 'hypeGallery'));
define('HYPEGALLERY_AVATARS', elgg_get_plugin_setting('avatars', 'hypeGallery'));
define('HYPEGALLERY_TAGGING', elgg_get_plugin_setting('tagging', 'hypeGallery'));
define('HYPEGALLERY_DOWNLOADS', elgg_get_plugin_setting('downloads', 'hypeGallery'));
define('HYPEGALLERY_EXIF', elgg_get_plugin_setting('exif', 'hypeGallery'));

define('HYPEGALLERY_ALBUM_IMAGE_QUOTA', 100);

elgg_set_config('gallery_icon_sizes', array());
elgg_set_config('gallery_allowed_dynamic_width', array('auto', 125, 325, 800));
elgg_set_config('gallery_allowed_dynamic_height', array(0, 200));

/**
 * Initialize the plugin on 'init','system'
 */
function hj_gallery_init() {

	// Libraries
	elgg_register_library('gallery:vendors:wideimage', elgg_get_plugins_path() . 'hypeGallery/vendors/wideimage/WideImage.php');

	$libraries = array(
		'base',
		'events',
		'page_handlers',
		'actions',
		'assets',
		'views',
		'menus',
		'hooks'
	);

	foreach ($libraries as $lib) {
		$path = elgg_get_plugins_path() . "hypeGallery/lib/{$lib}.php";
		if (file_exists($path)) {
			elgg_register_library("gallery:library:$lib", $path);
			elgg_load_library("gallery:library:$lib");
		}
	}

	// Register classes
	elgg_register_classes(elgg_get_plugins_path() . 'hypeGallery/classes/');

	// Page handler
	elgg_register_page_handler('gallery', 'hj_gallery_page_handler');

	// Permissions
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'hj_gallery_permissions_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hj_gallery_container_permissions_check');

	// Site menu
	elgg_register_menu_item('site', array(
		'name' => 'gallery',
		'text' => elgg_echo('gallery'),
		'href' => 'gallery/dashboard/site',
	));

	// Menus
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'hj_gallery_entity_menu');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'hj_gallery_owner_block_menu');

	// Search
	elgg_register_entity_type('object', 'hjalbum');
	elgg_register_entity_type('object', 'hjalbumimage');

	// Add group option
	if (HYPEGALLERY_GROUP_ALBUMS) {
		add_group_tool_option('albums', elgg_echo('hj:gallery:groupoption:enable'), true);
		elgg_extend_view('groups/tool_latest', 'framework/gallery/group_module');
	}

	// Extend metatags with EXIF tags
	if (HYPEGALLERY_EXIF) {
		elgg_extend_view('object/hjalbumimage/meta', 'object/hjalbumimage/exif');
	}

	// Apply certain EXIF tags to new files
	elgg_register_event_handler('create', 'object', 'hj_gallery_apply_exif_tags');

	// Add widgets
	elgg_register_widget_type('photostream', elgg_echo("hj:gallery:widget:photostream"), elgg_echo("hj:gallery:widget:photostream:desc"));
	elgg_register_widget_type('albums', elgg_echo("hj:gallery:widget:albums"), elgg_echo("hj:gallery:widget:albums:desc"));
}

/**
 * Run upgrade scripts
 */
function hj_gallery_check_release() {

	if (!elgg_is_admin_logged_in()) {
		return true;
	}

	$release = HYPEGALLERY_RELEASE;
	$old_release = elgg_get_plugin_setting('release', 'hypeGallery');

	if ($release > $old_release) {

		elgg_register_library("gallery:library:upgrade", elgg_get_plugins_path() . 'hypeGallery/lib/upgrade.php');
		elgg_load_library("gallery:library:upgrade");

		elgg_set_plugin_setting('release', $release, 'hypeGallery');
	}

	return true;
}

// Register event handlers
elgg_register_event_handler('init', 'system', 'hj_gallery_init');
elgg_register_event_handler('upgrade', 'system', 'hj_gallery_check_release');
