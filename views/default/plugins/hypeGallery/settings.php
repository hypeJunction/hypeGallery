<?php

$entity = elgg_extract('entity', $params);

$settings = array(
	'album_river',
	'favorites',
	'interface_location',
	'interface_calendar',
	'copyrights',
	'categories',
	'collaborative_albums',
	'group_albums',
	'avatars',
	'tagging',
	'downloads',
);


foreach ($settings as $s) {
	echo '<div>';
	echo '<label>' . elgg_echo("edit:plugin:hypegallery:$s") . '</label>';
	echo elgg_view('input/dropdown', array(
		'name' => "params[$s]",
		'options_values' => array(
			0 => elgg_echo('disable'),
			1 => elgg_echo('enable')
		),
		'value' => $entity->$s
	));
	echo '<span class="elgg-text-help">' . elgg_echo("edit:plugin:hypegallery:hint:$s") . '</span>';
	echo '</div>';
}

echo '<div>';
echo '<label>' . elgg_echo("edit:plugin:hypegallery:leaflet_layer_uri") . '</label>';
echo elgg_view('input/text', array(
	'name' => "params[leaflet_layer_uri]",
	'value' => $entity->leaflet_layer_uri
));
echo '<span class="elgg-text-help">' . elgg_echo("edit:plugin:hypegallery:hint:leaflet_layer_uri") . '</span>';
echo '</div>';

echo '<div>';
echo '<label>' . elgg_echo("edit:plugin:hypegallery:leaflet_layer_attribution") . '</label>';
echo elgg_view('input/text', array(
	'name' => "params[leaflet_layer_attribution]",
	'value' => $entity->leaflet_layer_attribution
));
