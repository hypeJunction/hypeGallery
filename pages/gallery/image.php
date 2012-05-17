<?php

$limit = get_input('limit', 0);
$offset = get_input('offset', 0);

$selected_image_guid = get_input('e');
$selected_image = get_entity($selected_image_guid);

if (!elgg_instanceof($selected_image, 'object', 'hjalbumimage')) {
	forward('gallery/all');
}

elgg_set_page_owner_guid($selected_image->owner_guid);
elgg_push_breadcrumb($selected_image->getContainerEntity()->title, $selected_image->getContainerEntity()->getURL());
elgg_push_breadcrumb($selected_image->title);

elgg_push_context('full_image_view');
$content = elgg_view_entity($selected_image, array('full_view' => true));
elgg_pop_context();

$page = elgg_view_layout('one_sidebar', array(
    'content' => $content,
	'class' => 'hj-image-page'
));

echo elgg_view_page($title, $page);
