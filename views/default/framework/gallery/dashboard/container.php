<?php

namespace hypeJunction\Gallery;

$page_owner = elgg_get_page_owner_entity();

if (!elgg_instanceof($page_owner, 'group')) {
	return;
}

$display = get_input('display', 'albums');

echo '<div id="gallery-dashboard-group">';

switch ($display) {

	default :
	case 'albums' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbum'),
			'container_guids' => $page_owner->guid,
			'full_view' => false,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
			'gallery_class' => 'gallery-photostream',
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-albums', 0),
			'offset_key' => 'offset-albums'
		));
		break;

	case 'photostream' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'container_guids' => $page_owner->guid,
			'list_type' => get_input('list_type', 'gallery'),
			'list_type_toggle' => true,
			'gallery_class' => 'gallery-photostream',
			'full_view' => false,
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset-photostream', 0),
			'offset_key' => 'offset-photostream'
		));
		break;
}

echo '</div>';
