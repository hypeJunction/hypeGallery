<?php

namespace hypeJunction\Gallery;

register_dashboard_title_buttons('group');

$group = elgg_get_page_owner_entity();
$title = elgg_echo('gallery:albums:owner', [$group->name]);

elgg_push_breadcrumb($group->name, "gallery/group/$group->guid");

$content = elgg_view('framework/gallery/dashboard/group');

$sidebar = elgg_view('framework/gallery/dashboard/sidebar', [
	'dashboard' => 'group'
]);

$layout = elgg_view_layout('content', [
	'title' => $title,
	'filter' => false,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'gallery-dashboard'
]);

echo elgg_view_page($title, $layout);
