<?php

/**
 * Image Galleries for Elgg
 *
 * @package hypeJunction
 * @subpackage Gallery
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2011-2014, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */
namespace hypeJunction\Gallery;

const PLUGIN_ID = 'hypegallery';
const PAGEHANDLER = 'gallery';

// Load Gallery libraries
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/events.php';
require_once __DIR__ . '/hooks.php';
require_once __DIR__ . '/settings.php';
// Register event handlers
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\init');
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\init_groups');
elgg_register_event_handler('create', 'object', __NAMESPACE__ . '\apply_exif_tags');
/**
 * Initialize the plugin on 'init','system'
 * @return void
 */
function init()
{
    // Permissions
    elgg_register_event_handler('permissions_check', 'object', __NAMESPACE__ . '\permissions_check');
    elgg_register_event_handler('container_permissions_check', 'object', __NAMESPACE__ . '\container_permissions_check');
    elgg_register_event_handler('get_sql', 'access', __NAMESPACE__ . '\filter_access_sql');

    // Menus
    elgg_register_event_handler('register', 'menu:entity', __NAMESPACE__ . '\entity_menu_setup');
    elgg_register_event_handler('register', 'menu:manage_album_image', __NAMESPACE__ . '\manage_album_image_menu_setup');
    elgg_register_event_handler('register', 'menu:owner_block', __NAMESPACE__ . '\owner_block_menu_setup');

    // Icon sizes
    elgg_register_event_handler('entity:icon:sizes', 'object', __NAMESPACE__ . '\entity_icon_sizes');
}
/**
 * Initialize group related functionality if the settings say so
 * @return void
 */
function init_groups()
{
    if (!HYPEGALLERY_GROUP_ALBUMS) {
        return;
    }
    elgg()->group_tools->register('albums', [
        'label' => elgg_echo('gallery:groupoption:enable'),
        'default_on' => true,
    ]);
    elgg_extend_view('groups/tool_latest', 'framework/gallery/group_module');
}
/**
 * Run unit tests
 *
 * @param string $hook   Equals 'unit_test'
 * @param string $type   Equals 'system'
 * @param array  $value  An array of unit test locations
 * @param array  $params Additional params
 * @return string[] Updated array of unit test locations
 */
// unit_test hook removed in Elgg 3.0 — tests are run via PHPUnit in tests/phpunit.
