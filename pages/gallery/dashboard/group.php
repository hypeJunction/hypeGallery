<?php

hj_forum_register_dashboard_title_buttons('group');

$title = elgg_echo('hj:forum:dashboard:group', array($group->name));

elgg_push_breadcrumb($title);

$content = elgg_view('framework/forum/dashboard/group');

$sidebar = elgg_view('framework/forum/dashboard/sidebar', array(
	'dashboard' => 'group'
));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => false,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'hj-forum-dashboard'
));

echo elgg_view_page($title, $layout);