<?php

elgg_load_js('leaflet.js');
elgg_load_js('gallery.leaflet.js');
elgg_load_css('leaflet.css');
elgg_load_css('gallery.leaflet.css');

$value = elgg_extract('value', $vars);
$coordinates = elgg_geocode_location($value);

$attr = elgg_format_attributes(array(
	'title' => $value,
	'class' => 'leaflet-map leaflet-map-gallery',
	'id' => 'map' . microtime(),
	'data-lat' => $coordinates['lat'],
	'data-long' => $coordinates['long']
		));

echo "<div $attr>$value</div>";