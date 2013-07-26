<?php

$files = get_input('files', array());

foreach ($files as $guid => $details) {

	$image = get_entity($guid);

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