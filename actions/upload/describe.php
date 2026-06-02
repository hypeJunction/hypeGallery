<?php

namespace hypeJunction\Gallery;

$files = get_input('files', []);

// show hidden entities that might be pending approval
\elgg_push_context('show_hidden_entities');

foreach ($files as $guid => $details) {
	$image = get_entity($guid);
	
	if (!$image instanceof \ElggEntity) {
		continue;
	}
	
	foreach ($details as $name => $value) {
		if (in_array($name, ['tags', 'categories'])) {
			$value = \elgg_string_to_array($value);
		}

		$image->$name = $value;
		if ($name == 'location') {
			$coordinates = \elgg_trigger_event_results('geocode', 'location', ['location' => $value]);
			if ($coordinates) {
				$image->setLatLong($coordinates['lat'], $coordinates['long']);
			}
		}

		$image->save();
	}
}

\elgg_pop_context();
