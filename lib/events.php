<?php

namespace hypeJunction\Gallery;

use ElggFile;

/**
 * Add some menu items during page setup
 *
 * @return void
 */
function pagesetup() {

	elgg_register_menu_item('site', array(
		'name' => 'gallery',
		'text' => elgg_echo('gallery'),
		'href' => 'gallery/dashboard/site',
	));

	// embed support
	elgg_register_menu_item('embed', array(
		'name' => 'albumimages',
		'text' => elgg_echo('embed:albumimages'),
		'priority' => 50,
		'data' => array(
			'options' => array(
				'type' => 'object',
				'subtype' => hjAlbumImage::SUBTYPE,
			),
		),
	));
}

/**
 * Apply EXIF tags to newly created image files
 */
function apply_exif_tags(\Elgg\Event $event) {

	$object = $event->getObject();

	if (!$object instanceof ElggFile) {
		return;
	}

	$exif = get_exif($object);

	if ($exif) {

		if (!$object->description) {
			$description = '';
			if (isset($exif['ImageDescription'])) {
				$description = $exif['ImageDescription']['clean'];
			}
			if (isset($exif['UserComment'])) {
				$description .= $exif['UserComment']['clean'];
			}
			if ($description) {
				$object->description = $description;
			}
		}

		if (!$object->copyright) {
			if (isset($exif['Copyright'])) {
				$object->copyright = $exif['Copyright']['clean'];
			}
		}

		if (!$object->location) {

			if (isset($exif['GPSLatitude']) && isset($exif['GPSLongitude'])) {

				$params = array(
					'lat' => $exif['GPSLatitude']['clean'],
					'lon' => $exif['GPSLongitude']['clean'],
					'zoom' => 15,
					'addressdetails' => false,
					'format' => 'json',
					'email' => elgg_get_config('siteemail'),
				);

				$query = http_build_query($params);

				$url = "http://nominatim.openstreetmap.org/reverse?$query";

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
				$json_data = curl_exec($curl);
				curl_close($curl);

				if ($data = json_decode($json_data, true)) {
					if (!isset($data['error'])) {
						$object->osm_id = $data['osm_id'];
						$object->setSearchLocation($data['display_name']);
						$object->setLatLong($data['lat'], $data['lon']);
					}
				}
			}
		}

		if (!$object->date) {
			if (isset($exif['DateTimeOriginal'])) {
				$object->date = strtotime($exif['DateTimeOriginal']['clean']);
			}
		}

		if (!$object->tags) {
			$tags = array();
			if (isset($exif['Model'])) {
				$tags[] = $exif['Model']['clean'];
			}
			if (isset($exif['LensModel'])) {
				$tags[] = $exif['LensModel']['clean'];
			}
			if ($tags) {
				$object->tags = $tags;
			}
		}
	}

}
