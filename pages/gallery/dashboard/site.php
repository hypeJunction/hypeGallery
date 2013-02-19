<?php

hj_gallery_register_dashboard_title_buttons('site');

$title = elgg_echo('hj:gallery:albums:all');

elgg_push_breadcrumb($title);

$filter = elgg_view('framework/gallery/dashboard/filter', array(
	'filter_context' => 'site'
));

$content = elgg_view('framework/gallery/dashboard/site');

$sidebar = elgg_view('framework/gallery/dashboard/sidebar', array(
	'dashboard' => 'site'
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'hj-gallery-dashboard'
));

echo elgg_view_page($title, $layout);