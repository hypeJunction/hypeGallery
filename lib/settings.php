<?php

namespace hypeJunction\Gallery;

define('HYPEGALLERY_RELEASE', 1374851653);

define('HYPEGALLERY_ALBUM_RIVER', elgg_get_plugin_setting('album_river', PLUGIN_ID));
define('HYPEGALLERY_FAVORITES', elgg_get_plugin_setting('favorites', PLUGIN_ID));
define('HYPEGALLERY_INTERFACE_LOCATION', elgg_get_plugin_setting('interface_location', PLUGIN_ID));
define('HYPEGALLERY_INTERFACE_CALENDAR', elgg_get_plugin_setting('interface_calendar', PLUGIN_ID));
define('HYPEGALLERY_COPYRIGHTS', elgg_get_plugin_setting('copyrights', PLUGIN_ID));
define('HYPEGALLERY_CATEGORIES', elgg_get_plugin_setting('categories', PLUGIN_ID));
define('HYPEGALLERY_COLLABORATIVE_ALBUMS', elgg_get_plugin_setting('collaborative_albums', PLUGIN_ID));
define('HYPEGALLERY_GROUP_ALBUMS', elgg_get_plugin_setting('group_albums', PLUGIN_ID));
define('HYPEGALLERY_AVATARS', elgg_get_plugin_setting('avatars', PLUGIN_ID));
define('HYPEGALLERY_TAGGING', elgg_get_plugin_setting('tagging', PLUGIN_ID));
define('HYPEGALLERY_DOWNLOADS', elgg_get_plugin_setting('downloads', PLUGIN_ID));
define('HYPEGALLERY_PUBLIC_DOWNLOADS', elgg_get_plugin_setting('public_downloads', PLUGIN_ID));
define('HYPEGALLERY_EXIF', elgg_get_plugin_setting('exif', PLUGIN_ID));

define('HYPEGALLERY_ALBUM_IMAGE_QUOTA', 100);

elgg_set_config('gallery_icon_sizes', array(
	'800x200' => array(
		'w' => 800,
		'h' => 200,
		'square' => false,
		'upscale' => true,
	),
	'125' => array(
		'w' => 125,
		'h' => 125,
		'square' => false,
		'upscale' => true
	),
	'325x200' => array(
		'w' => 325,
		'h' => 200,
		'square' => false,
		'upscale' => true,
	),
	'taggable' => array(
		'w' => 550,
		'h' => 550,
		'square' => false,
		'upscale' => true,
	)
));

elgg_set_config('gallery_allowed_dynamic_width', array('auto', 125, 325, 550, 800));
elgg_set_config('gallery_allowed_dynamic_height', array(0, 200, 550));