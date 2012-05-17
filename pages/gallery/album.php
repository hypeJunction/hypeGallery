<?php

$limit = get_input('limit', 0);
$offset = get_input('offset', 0);

$selected_album_guid = get_input('e');
$selected_album = get_entity($selected_album_guid);

if (!elgg_instanceof($selected_album, 'object', 'hjalbum')) {
	forward('gallery/all');
}

elgg_set_page_owner_guid($selected_album->owner_guid);
elgg_push_breadcrumb($selected_album->title);

elgg_push_context('full_album_view');
$content = elgg_view_entity($selected_album, array('full_view' => true));
elgg_pop_context();

$page = elgg_view_layout('one_sidebar', array(
    'content' => $content,
	'class' => 'hj-gallery-page'
));

echo elgg_view_page($title, $page);
