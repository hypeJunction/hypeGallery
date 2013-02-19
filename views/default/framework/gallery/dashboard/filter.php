<?php

$filter_context = elgg_extract('filter_context', $vars, 'site');

$tabs = array(
	'site' => array(
		'text' => elgg_echo('hj:gallery:albums:all'),
		'href' => 'gallery/dashboard/site',
		'selected' => ($filter_context == 'site'),
		'priority' => 100,
	),
	'mine' => array(
		'text' => elgg_echo('hj:gallery:myalbums'),
		'href' => 'gallery/dashboard/owner',
		'selected' => ($filter_context == 'mine'),
		'priority' => 200,
	),
	'friends' => array(
		'text' => elgg_echo('hj:gallery:albums:friends'),
		'href' => 'gallery/dashboard/friends',
		'selected' => ($filter_context == 'friends'),
		'priority' => 300,
	),
	'groups' => array(
		'text' => elgg_echo('hj:gallery:albums:groups'),
		'href' => 'gallery/dashboard/groups',
		'selected' => ($filter_context == 'groups'),
		'priority' => 400,
	),
	'bookmarks' => (HYPEGALLERY_BOOKMARKS) ? array(
		'text' => elgg_echo('hj:gallery:albums:bookmarks'),
		'href' => 'gallery/dashboard/bookmarks',
		'selected' => ($filter_context == 'bookmarks'),
		'priority' => 500,
	) : null,
);

foreach ($tabs as $name => $tab) {
	if ($tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));
