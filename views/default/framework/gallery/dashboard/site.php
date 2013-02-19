<?php

$stream = get_input('photostream', false);

if (!$stream) {

	$params = array(
		'list_id' => "sitealbums",
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => array('hjalbum')
	);

	echo elgg_view('framework/gallery/list/albums', $params);

} else {

	$params = array(
		'list_id' => "sitephotostream",
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => array('hjalbumimage')
	);

	elgg_push_context('photostream');
	echo elgg_view('framework/gallery/list/images', $params);
	elgg_pop_context();
	
}