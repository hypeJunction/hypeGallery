<?php

namespace hypeJunction\Gallery;

register_dashboard_title_buttons('friends');

$viewer = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();

if ($page_owner->guid == $viewer->guid) {
	$title = elgg_echo('gallery:albums:friends');
} else {
	$title = elgg_echo('gallery:albums:friends:owner', array($page_owner->name));
}

elgg_push_breadcrumb($title);

$filter = elgg_view('framework/gallery/dashboard/filter', array(
	'filter_context' => 'friends'
		));

$content = elgg_view('framework/gallery/dashboard/friends');

$sidebar = elgg_view('framework/gallery/dashboard/sidebar', array(
	'dashboard' => 'friends'
		));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => $filter,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'gallery-dashboard'
		));

echo elgg_view_page($title, $layout);
