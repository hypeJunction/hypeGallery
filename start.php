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

define('HYPEGALLERY_RELEASE', 1361396953);

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

/** @todo: Add quota logic */
//define('HYPEGALLERY_SITE_ALBUMS_QUOTA', elgg_get_plugin_setting('site_albums_quota', 'hypeGallery'));
//define('HYPEGALLERY_SITE_ALBUMS_QUOTA_VALUE', elgg_get_plugin_setting('site_albums_quota_value', 'hypeGallery'));
//define('HYPEGALLERY_IMAGES_MAX', elgg_get_plugin_setting('images_max', 'hypeGallery'));

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

	if (HYPEGALLERY_GROUP_ALBUMS) {
		// Add group option
		add_group_tool_option('albums', elgg_echo('hj:gallery:groupoption:enable'), true);
		elgg_extend_view('groups/tool_latest', 'framework/gallery/group_module');
	}
}
