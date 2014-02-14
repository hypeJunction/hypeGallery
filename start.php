<?php

/**
 * Image Galleries for Elgg
 *
 * @package hypeJunction
 * @subpackage Gallery
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyright (c) 2011-2014, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

namespace hypeJunction\Gallery;

const PLUGIN_ID = PLUGIN_ID;
const PAGEHANDLER = 'gallery';

// Composer autoload
require_once __DIR__ . '/vendors/autoload.php';

// Dependency classes
if (!class_exists('hypeJunction\\Filestore\\UploadHandler')) {
	elgg_register_class('hypeJunction\\Filestore\\UploadHandler', __DIR__ . '/classes/hypeJunction/Filestore/UploadHandler.php');
}

// Load Gallery libraries
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/events.php';
require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/page_handlers.php';

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
define('HYPEGALLERY_EXIF', elgg_get_plugin_setting('exif', PLUGIN_ID));

define('HYPEGALLERY_ALBUM_IMAGE_QUOTA', 100);

elgg_set_config('gallery_icon_sizes', array());
elgg_set_config('gallery_allowed_dynamic_width', array('auto', 125, 325, 800));
elgg_set_config('gallery_allowed_dynamic_height', array(0, 200));


// Register event handlers
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init_groups');
elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrade');
elgg_register_event_handler('pagesetup', 'system', __NAMESPACE__ . '\\pagesetup');
elgg_register_event_handler('create', 'object', __NAMESPACE__ . '\\apply_exif_tags');

function init() {

	/**
	 * Register entities
	 */
	elgg_register_class('hypeJunction\\Gallery\\hjAlbum', __DIR__ . '/classes/hypeJunction/Filestore/hjAlbum.php');
	elgg_register_class('hypeJunction\\Gallery\\hjAlbumImage', __DIR__ . '/classes/hypeJunction/Filestore/hjAlbumImage.php');
	elgg_register_entity_type('object', 'hjalbum');
	elgg_register_entity_type('object', 'hjalbumimage');


	/**
	 * Register page handlers
	 */
	elgg_register_page_handler(PAGEHANDLER, __NAMESPACE__ . '\\page_handler');

	/**
	 * Register actions
	 */
	$actions_path = __DIR__ . '/actions/';

	elgg_register_action('edit/object/hjalbum', $actions_path . 'edit/object/hjalbum.php');
	elgg_register_action('edit/object/hjalbumimage', $actions_path . 'edit/object/hjalbumimage.php');

	elgg_register_action('gallery/delete/object', $actions_path . 'delete/object.php');

	elgg_register_action('gallery/order/images', $actions_path . 'order/images.php');

	elgg_register_action('gallery/upload', $actions_path . 'upload/upload.php');
	elgg_register_action('gallery/upload/filedrop', $actions_path . 'upload/filedrop.php');
	elgg_register_action('gallery/upload/handle', $actions_path . 'upload/handle.php');
	elgg_register_action('gallery/upload/describe', $actions_path . 'upload/describe.php');

	elgg_register_action('gallery/approve/image', $actions_path . 'approve/image.php');

	elgg_register_action('gallery/makeavatar', $actions_path . 'addons/avatar.php');
	elgg_register_action('gallery/makecover', $actions_path . 'addons/cover.php');
	elgg_register_action('gallery/phototag', $actions_path . 'addons/phototag.php');
	elgg_register_action('gallery/thumb', $actions_path . 'addons/thumb.php');
	elgg_register_action('gallery/thumb_reset', $actions_path . 'addons/thumb_reset.php');

	/**
	 * Register hooks
	 */
	// Permissions
	elgg_register_plugin_hook_handler('permissions_check', 'object', __NAMESPACE__ . '\\permissions_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', __NAMESPACE__ . '\\container_permissions_check');

	// Menus
	elgg_register_plugin_hook_handler('register', 'menu:entity', __NAMESPACE__ . '\\entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', __NAMESPACE__ . '\\owner_block_menu_setup');

	/**
	 * JS, CSS and Views
	 */
	// CSS
	elgg_extend_view('css/elgg', 'css/framework/gallery/base');

	// JS
	elgg_register_simplecache_view('js/framework/gallery/base');
	elgg_register_js('gallery.manage.js', elgg_get_simplecache_url('js', 'framework/gallery/manage'));

	elgg_register_simplecache_view('js/framework/gallery/popup');
	elgg_register_js('gallery.popup.js', elgg_get_simplecache_url('js', 'framework/gallery/popup'));
	elgg_register_ajax_view('object/hjalbum/slideshow');
	elgg_register_ajax_view('object/hjalbumimage/ajaxmeta');

	elgg_register_simplecache_view('js/framework/gallery/cropper');
	elgg_register_js('gallery.cropper.js', elgg_get_simplecache_url('js', 'framework/gallery/cropper'));
	elgg_register_ajax_view('framework/gallery/tools/cropper');

	elgg_register_simplecache_view('js/framework/gallery/tagger');
	elgg_register_js('gallery.tagger.js', elgg_get_simplecache_url('js', 'framework/gallery/tagger'));

	// Load fonts
	elgg_extend_view('page/elements/head', 'framework/fonts/font-awesome');
	elgg_extend_view('page/elements/head', 'framework/fonts/open-sans');


	// Extend metatags with EXIF tags
	elgg_extend_view('object/hjalbumimage/meta', 'object/hjalbumimage/exif');

	// Add widgets
	elgg_register_widget_type('photostream', elgg_echo("gallery:widget:photostream"), elgg_echo("gallery:widget:photostream:desc"));
	elgg_register_widget_type('albums', elgg_echo("gallery:widget:albums"), elgg_echo("gallery:widget:albums:desc"));
}

/**
 * Initialize group related functionality if the settings say so
 * @return void
 */
function init_groups() {
	if (!HYPEGALLERY_GROUP_ALBUMS) {
		return;
	}
	add_group_tool_option('albums', elgg_echo('gallery:groupoption:enable'), true);
	elgg_extend_view('groups/tool_latest', 'framework/gallery/group_module');
}
