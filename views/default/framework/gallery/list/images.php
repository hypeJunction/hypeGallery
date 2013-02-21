<?php

$list_id = elgg_extract('list_id', $vars, "imagelist");
$getter_options = elgg_extract('getter_options', $vars);

$list_type = get_input("__list_type_$list_id", 'gallery');

$filter_vars = array(
	'handler' => 'gallery',
	'items_handler' => 'images'
);

$filter_vars = array_merge($vars, $filter_vars);

switch (get_input('details')) {
	default :
		$list_class = 'hj-imagelist-thumbs';
		$limit_select_options = array(9, 18, 45, 90);
		if (!get_input("__lim_$list_id", false)) {
			set_input("__lim_$list_id", 9);
		}
		break;

	case 'summary' :
		$list_class = 'hj-imagelist-summary';
		break;

	case 'full' :
		$list_class = 'hj-imagelist-full';
		break;
}

$list_options = array(
	'list_type' => $list_type,
	'list_class' => $list_class,
	'list_view_options' => array(
		'table' => array(
			'head' => array(
				'icon' => array(
					'text' => '',
					'sortable' => false,
				),
				'image_details' => array(
					'colspan' => array(
						'title' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:title'),
							'sortable' => true,
							'sort_key' => 'oe.title'
						),
						'briefdescription' => array(
							'text' => '',
							'sortable' => false,
						),
						'copyright' => (HYPEGALLERY_COPYRIGHTS) ? array(
							'text' => '',
							'sortable' => false,
								) : null
					),
				),
				'album_metadata' => array(
					'colspan' => array(
						'author' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:owner'),
							'sortable' => true,
							'sort_key' => 'gallery.author'
						),
						'time_created' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:time_created'),
							'sortable' => true,
							'sort_key' => 'e.time_created'
						),
						'location' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:location'),
							'sortable' => true,
							'sort_key' => 'md.location'
								) : null,
						'date' => (HYPEGALLERY_INTERFACE_CALENDAR) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:date'),
							'sortable' => true,
							'sort_key' => 'md.date'
								) : null,
						'tags' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:tags'),
							'sortable' => false,
								) : null,
					),
				),
				'menu' => array(
					'text' => '',
					'sortable' => false
				),
			)
		)
	),
	'list_pagination' => true,
	'limit_select_options' => $limit_select_options,
	'filter' => elgg_view('framework/gallery/filters/list', $filter_vars)
);

$viewer_options = array(
	'full_view' => false,
	'list_type' => $list_type
);

if (!get_input("__ord_$list_id", false)) {
	set_input("__ord_$list_id", 'md.priority');
	set_input("__dir_$list_id", 'ASC');
}

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities');

echo elgg_view_module('gallery', '', $content);