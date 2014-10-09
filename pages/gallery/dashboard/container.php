<?php

namespace hypeJunction\Gallery;

register_dashboard_title_buttons('container');

$container = elgg_get_page_owner_entity();
$name = (elgg_instanceof($container, 'object')) ? $container->title : $container->name;
$title = elgg_echo('gallery:albums:owner', array($name));

elgg_push_breadcrumb($container->name, "gallery/container/$container->guid");

$content = elgg_view('framework/gallery/dashboard/container');

$sidebar = elgg_view('framework/gallery/dashboard/sidebar', array(
	'dashboard' => 'container'
		));

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'filter' => false,
	'content' => $content,
	'sidebar' => $sidebar,
	'class' => 'gallery-dashboard'
		));

echo elgg_view_page($title, $layout);
