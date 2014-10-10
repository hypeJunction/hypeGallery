<?php

namespace hypeJunction\Gallery;

$filter_context = elgg_extract('filter_context', $vars, 'site');

$viewer = elgg_get_page_owner_entity();
if (!elgg_instanceof($viewer, 'user')) {
	$viewer = elgg_get_logged_in_user_entity();
}

if (elgg_is_logged_in()) {
	$tabs = array(
		'mine' => array(
			'text' => elgg_echo('gallery:albums:mine'),
			'href' => "gallery/dashboard/owner/$viewer->username",
			'selected' => ($filter_context == 'owner'),
			'priority' => 200,
		),
		'friends' => array(
			'text' => elgg_echo('gallery:albums:friends'),
			'href' => "gallery/dashboard/friends/$viewer->username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 300,
		),
		'groups' => (HYPEGALLERY_GROUP_ALBUMS) ? array(
			'text' => elgg_echo('gallery:albums:groups'),
			'href' => "gallery/dashboard/groups/$viewer->username",
			'selected' => ($filter_context == 'groups'),
			'priority' => 400,
				) : NULL,
		'favorites' => (HYPEGALLERY_FAVORITES) ? array(
			'text' => elgg_echo('gallery:albums:favorites'),
			'href' => "gallery/dashboard/favorites/$viewer->username",
			'selected' => ($filter_context == 'favorites'),
			'priority' => 500,
				) : NULL,
	);
}
$tabs['site'] = array(
	'text' => elgg_echo('gallery:albums:all'),
	'href' => 'gallery/dashboard/site',
	'selected' => ($filter_context == 'site'),
	'priority' => 100,
);

foreach ($tabs as $name => $tab) {
	if ($tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
