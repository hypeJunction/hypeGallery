<?php

namespace hypeJunction\Gallery;

/**
 * Add some menu items during page setup
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
				'subtype' => 'hjalbumimage',
			),
		),
	));
}

/**
 * Run upgrade scripts
 */
function upgrade() {

	if (!elgg_is_admin_logged_in()) {
		return true;
	}

	$release = HYPEGALLERY_RELEASE;
	$old_release = elgg_get_plugin_setting('release', PLUGIN_ID);

	if ($release > $old_release) {

		include_once dirname(dirname(__FILE__)) . '/lib/upgrade.php';
		elgg_set_plugin_setting('release', $release, PLUGIN_ID);
	}

	return true;
}

/**
 * Apply EXIF tags to newly created image files
 * 
 * @param string $event			Equals 'create'
 * @param string $type			Equals 'object'
 * @param ElggFile $object		New file
 * @return boolean
 */
function apply_exif_tags($event, $type, $object) {

	if (!$object instanceof ElggFile) {
		return true;
	}

	$exif = get_exif($object);

	if ($exif) {

		if (!$object->description) {
			if (isset($exif['ImageDescription'])) {
				$description = $exif['ImageDescription']['clean'];
			}
			if (isset($exif['UserComment'])) {
				$description .= $exif['UserComment']['clean'];
			}
			$object->description = $description;
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

	return true;
}
