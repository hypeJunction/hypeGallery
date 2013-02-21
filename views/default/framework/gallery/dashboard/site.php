<?php

$stream = get_input('photostream', false);


if (!$stream) {

	$params = array(
		'list_id' => "sitealbums",
		'getter_options' => array(
			'types' => 'object',
			'subtypes' => array('hjalbum'),
		)
	);

	echo elgg_view('framework/gallery/list/albums', $params);
} else {

	if (!get_input("__ord_sitephotostream", false)) {
		set_input("__ord_sitephotostream", 'e.time_created');
		set_input("__dir_sitephotostream", 'DESC');
	}

	$params = array(
		'list_id' => "sitephotostream",
		'getter_options' => array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage')
		)
	);

	echo elgg_view('framework/gallery/list/images', $params);
}
