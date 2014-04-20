<?php

namespace hypeJunction\Gallery;

$files = get_input('files', array());

// show hidden entities that might be pending approval
$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

foreach ($files as $guid => $details) {

	$image = get_entity($guid);
	
	if (!elgg_instanceof($image)) {
		continue;
	}
	
	foreach ($details as $name => $value) {
		if (in_array($name, array('tags', 'categories'))) {
			$value = string_to_tag_array($value);
		}
		$image->$name = $value;
		if ($name == 'location') {
			$coordinates = elgg_geocode_location($value);
			if ($coordinates) {
				$image->setLatLong($coordinates['lat'], $coordinates['long']);
			}
		}

		$image->save();
	}
}

access_show_hidden_entities($ha);