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

define('HYPEGALLERY_RELEASE', 1369646725);

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

elgg_set_config('gallery_icon_sizes', array(
	'cover' => array('w' => 1000, 'h' => 1000, 'square' => false, 'upscale' => false)
));

/** @todo: Add quota logic */
//define('HYPEGALLERY_SITE_ALBUMS_QUOTA', elgg_get_plugin_setting('site_albums_quota', 'hypeGallery'));
//define('HYPEGALLERY_SITE_ALBUMS_QUOTA_VALUE', elgg_get_plugin_setting('site_albums_quota_value', 'hypeGallery'));
//define('HYPEGALLERY_IMAGES_MAX', elgg_get_plugin_setting('images_max', 'hypeGallery'));

elgg_register_event_handler('init', 'system', 'hj_gallery_init');

function hj_gallery_init() {

	elgg_register_classes(elgg_get_plugins_path() . 'hypeGallery/classes/');

	// Libraries
	$libraries = array(
		'base',
		'file',
		'forms',
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

	// Search
	elgg_register_entity_type('object', 'hjalbum');
	elgg_register_entity_type('object', 'hjalbumimage');

	if (HYPEGALLERY_GROUP_ALBUMS) {
		// Add group option
		add_group_tool_option('albums', elgg_echo('hj:gallery:groupoption:enable'), true);
		elgg_extend_view('groups/tool_latest', 'framework/gallery/group_module');
	}
}

elgg_register_event_handler('upgrade', 'system', 'hj_gallery_check_release');

function hj_gallery_check_release($event, $type, $params) {

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