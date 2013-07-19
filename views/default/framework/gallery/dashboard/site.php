<?php

$display = get_input('display', 'albums');

echo '<div id="gallery-dashboard">';

switch ($display) {

	default :
	case 'albums' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbum'),
			'full_view' => false,
			'list_type' => get_input('list_type', 'gallery'),
			'gallery_class' => 'gallery-photostream',
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset_albums', 0),
			'offset_key' => 'offset_albums'
		));
		break;

	case 'photostream' :
		echo elgg_list_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'list_type' => get_input('list_type', 'gallery'),
			'gallery_class' => 'gallery-photostream',
			'full_view' => false,
			'pagination' => true,
			'limit' => get_input('limit', 20),
			'offset' => get_input('offset_photostream', 0),
			'offset_key' => 'offset_photostream'
		));
		break;
}

echo '</div>';