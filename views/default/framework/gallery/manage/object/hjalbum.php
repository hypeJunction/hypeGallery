<?php

$entity = elgg_extract('entity', $vars);

$title = $entity->getTitle();

$params = array(
	'entity' => $entity,
	'class' => 'elgg-menu-hjentityhead elgg-menu-hz elgg-menu-album',
	'sort_by' => 'priority',
	'handler' => 'album',
	'dropdown' => false
);

$menu = elgg_view_menu('hjentityhead', $params);

$title = elgg_view_image_block('', $title, array(
	'image_alt' => $menu
		));

$content = elgg_view('framework/bootstrap/object/elements/description', $vars);

$list_id = "ai$entity->guid";

$dbprefix = elgg_get_config('dbprefix');
$getter_options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'container_guid' => $entity->guid,
);

$list_options = array(
	'list_type' => get_input("__list_type_$list_id", 'table'),
	'item_class' => 'hj-draggable-element',
	'list_class' => 'gallery-manage-album',
	'list_view_options' => array(
		'table' => array(
			'head' => array(
				'draggable' => ($entity->canEdit()) ? array(
					'text' => '',
					'sortable' => false
				) : null,
				'icon' => array(
					'text' => '',
					'sortable' => false,
				),
				'image_details' => array(
					'colspan' => array(
						'title' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:title'),
						),
						'description' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:description')
						),
						'copyright' => (HYPEGALLERY_COPYRIGHTS) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:copyright')
								) : null
					),
				),
				'album_metadata' => array(
					'colspan' => array(
						'author' => array(
							'text' => elgg_echo('hj:label:hjalbumimage:owner'),
						),
						'location' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:location'),
								) : null,
						'date' => (HYPEGALLERY_INTERFACE_CALENDAR) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:date'),
								) : null,
						'tags' => (HYPEGALLERY_INTERFACE_LOCATION) ? array(
							'text' => elgg_echo('hj:label:hjalbumimage:tags'),
								) : null,
					),
				),
				'menu' => array(
					'text' => '',
				),
			)
		)
	),
	'pagination' => true
);

$viewer_options = array(
	'full_view' => true,
	'context' => 'manage'
);

if (!get_input("__ord_$list_id", false)) {
	set_input("__ord_$list_id", 'md.priority');
	set_input("__dir_$list_id", 'ASC');
}

$content .= hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities');

$module = elgg_view_module('gallery-album', $title, $content);

echo $module;