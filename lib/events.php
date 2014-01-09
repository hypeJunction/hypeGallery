<?php

/**
 * Apply EXIF tags to newly created image files
 * 
 * @param string $event			Equals 'create'
 * @param string $type			Equals 'object'
 * @param ElggFile $object		New file
 * @return boolean
 */
function hj_gallery_apply_exif_tags($event, $type, $object) {

	if (!$object instanceof ElggFile) {
		return true;
	}

	$exif = hj_gallery_get_exif($object);

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
						$object->setLocation($data['display_name']);
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
