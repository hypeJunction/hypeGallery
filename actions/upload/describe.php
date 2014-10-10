<?php

namespace hypeJunction\Gallery;

$files = get_input('files', array());

// show hidden entities that might be pending approval
elgg_push_context('show_hidden_entities');

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
			$coordinates = elgg_trigger_plugin_hook('geocode', 'location', array('location' => $value));
			if ($coordinates) {
				$image->setLatLong($coordinates['lat'], $coordinates['long']);
			}
		}

		$image->save();
	}
}

elgg_pop_context();