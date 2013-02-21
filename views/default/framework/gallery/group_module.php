<?php

$group = elgg_get_page_owner_entity();

if ($group->albums_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "gallery/group/$group->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
set_input('details', false);

$options = array(
	'types' => 'object',
	'subtypes' => array('hjalbum'),
	'container_guids' => $group->guid,
	'full_view' => false,
	'pagination' => false,
	'list_type' => 'gallery'
);

$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('hj:framework:list:empty') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "gallery/create/album/$group->guid",
	'text' => elgg_echo('hj:gallery:create:album'),
	'is_trusted' => true,
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('hj:gallery:albums:groups'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
