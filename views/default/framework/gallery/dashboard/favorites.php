<?php

$page_owner = elgg_get_page_owner_entity();

$list_id = "fav$page_owner->guid";

set_input('photostream', true);
set_input('details', 'summary');
set_input("__list_type_$list_id", 'gallery');

$getter_options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'annotation_names' => array('likes'),
	'annotation_owner_guids' => $page_owner->guid
);

$filter_vars = array_merge($vars, $filter_vars);

$list_options = array(
	'list_type' => $list_type,
	'list_class' => $list_class,
	'list_pagination' => true,
);

$viewer_options = array(
	'full_view' => false,
	'list_type' => 'gallery'
);

if (!get_input("__ord_$list_id", false)) {
	set_input("__ord_$list_id", 'e.time_created');
	set_input("__dir_$list_id", 'DESC');
}

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities_from_annotations');

echo elgg_view_module('gallery', '', $content);