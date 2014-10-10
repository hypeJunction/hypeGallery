<?php

namespace hypeJunction\Gallery;

$page_owner = elgg_get_page_owner_entity();

echo '<div id="gallery-dashboard-favorites">';

echo elgg_list_entities_from_annotations(array(
	'types' => 'object',
	'subtypes' => array('hjalbum', 'hjalbumimage'),
	'annotation_names' => array('likes'),
	'annotation_owner_guids' => $page_owner->guid,
	'full_view' => false,
	'list_type' => get_input('list_type', 'gallery'),
	'list_type_toggle' => true,
	'gallery_class' => 'gallery-photostream',
	'pagination' => true,
	'limit' => get_input('limit', 20),
	'offset' => get_input('offset-albums', 0),
	'offset_key' => 'offset-albums'
));

echo '</div>';
