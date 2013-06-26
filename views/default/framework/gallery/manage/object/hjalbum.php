<?php

$entity = elgg_extract('entity', $vars);

$details .= elgg_view('object/hjalbum/elements/tags', $vars);
if (HYPEGALLERY_CATEGORIES) {
	$details .= elgg_view('object/hjalbum/elements/categories', $vars);
}
if (HYPEGALLERY_COPYRIGHTS) {
	$details .= elgg_view('object/hjalbum/elements/copyright', $vars);
}
if (HYPEGALLERY_INTERFACE_LOCATION) {
	$details .= elgg_view('object/hjalbum/elements/location', $vars);
}
if (HYPEGALLERY_INTERFACE_CALENDAR) {
	$details .= elgg_view('object/hjalbum/elements/date', $vars);
}

$content = elgg_view('framework/bootstrap/object/elements/description', $vars);
$content .= $details;

$list_id = "ai$entity->guid";

$dbprefix = elgg_get_config('dbprefix');
$getter_options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'container_guid' => $entity->guid,
);

if (!$entity->canEdit() && $entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
	$getter_options['owner_guids'] = elgg_get_logged_in_user_guid(); // for collaborative albums/only show images uploaded by the user
}

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

if ($entity->canEdit()) {
	$ha = access_get_show_hidden_status();
	access_show_hidden_entities(true);
}
$content .= hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities');

if ($entity->canEdit()) {
	access_show_hidden_entities($ha);
}

$module = elgg_view_module('gallery-album', '', $content);

if ($entity->owner_guid != elgg_get_logged_in_user_guid() ) {
	echo '<div class="hj-gallery-manage-instructions">';
	echo elgg_echo('hj:gallery:manage:instructions');
	echo '</div>';
}

echo $module;