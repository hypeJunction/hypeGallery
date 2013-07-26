<?php

hj_gallery_register_dashboard_title_buttons('site');

$title = elgg_echo('hj:gallery:albums:all');

elgg_push_breadcrumb($title);

elgg_register_menu_item('photostream', array(
	'name' => 'albums',
	'text' => elgg_echo('hj:gallery:switch:albums'),
	'href' => 'gallery/dashboard/site?display=albums',
	'selected' => (get_input('display', 'albums') == 'albums'),
	'priority' => 100
));

elgg_register_menu_item('photostream', array(
	'name' => 'photostream',
	'text' => elgg_echo('hj:gallery:switch:photostream'),
	'href' => 'gallery/dashboard/site?display=photostream',
	'selected' => (get_input('display', 'albums') == 'photostream'),
	'priority' => 200
));


$filter = elgg_view('framework/gallery/dashboard/filter', array(
	'filter_context' => 'site'
));

$content .= elgg_view_menu('photostream', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz'
));

$content .= elgg_view('framework/gallery/dashboard/site');

$sidebar = elgg_view('framework/gallery/dashboard/sidebar', array(
	'dashboard' => 'site'
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'gallery-dashboard'
));

echo elgg_view_page($title, $layout);