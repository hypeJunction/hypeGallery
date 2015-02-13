<?php

namespace hypeJunction\Gallery;

use ElggEntity;
use ElggFile;
use ElggObject;

/**
 * Register albums/image title buttons
 *
 * @param ElggObject $entity Album or image
 * @return null|boolean
 */
function register_entity_title_buttons($entity) {

	if (!elgg_instanceof($entity)) {
		return;
	}

	$items = array();

	switch ($entity->getSubtype()) {

		default :
			return true;

		case hjAlbum::SUBTYPE :

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-upload')) {
				$items['upload'] = array(
					'text' => elgg_echo('gallery:upload'),
					'href' => "gallery/upload/$entity->guid",
					'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 400
				);
			}

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-manage')) {
				$items['manage'] = array(
					'text' => elgg_echo('gallery:manage:album'),
					'href' => "gallery/manage/$entity->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'priority' => 400
				);
			}

			if ($entity->canEdit()) {
				$items['edit'] = array(
					'text' => elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995
				);

				$items['delete'] = array(
					'text' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'link_class' => 'elgg-button elgg-button-delete elgg-button-delete-entity',
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}
			break;

		case hjAlbumImage::SUBTYPE :

			$items['download'] = (HYPEGALLERY_DOWNLOADS && (elgg_is_logged_in() || HYPEGALLERY_PUBLIC)) ? array(
				'text' => elgg_echo('gallery:image:download'),
				'href' => $entity->getURL('download'),
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 50,
					) : NULL;

			$items['makeavatar'] = (HYPEGALLERY_AVATARS && elgg_is_logged_in()) ? array(
				'text' => elgg_echo('gallery:image:makeavatar'),
				'href' => "action/gallery/makeavatar?e=$entity->guid",
				'is_action' => true,
				'link_class' => 'elgg-button elgg-button-action',
				'priority' => 100,
					) : null;

			if ($entity->canEdit()) {
				$items['edit'] = array(
					'text' => elgg_echo('edit'),
					'href' => $entity->getURL('edit'),
					'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity',
					'data-guid' => $entity->guid,
					'priority' => 995
				);

				$items['delete'] = array(
					'text' => elgg_echo('delete'),
					'href' => $entity->getURL('delete'),
					'link_class' => 'elgg-button elgg-button-delete elgg-button-delete-entity',
					'confirm' => true,
					'data-guid' => $entity->guid,
					'priority' => 1000
				);
			}
			break;
	}

	$items = array_filter($items);
	
	if ($items) {
		foreach ($items as $name => $options) {
			$options['name'] = $name;
			elgg_register_menu_item('title', $options);
		}
	}

	return true;
}

/**
 * Register Dashboard title menu items
 *
 * @param string $dashboard Dashboard filter context
 * @return void
 */
function register_dashboard_title_buttons($dashboard = 'site') {

	switch ($dashboard) {

		case 'site' :
		case 'owner' :
			if (elgg_is_logged_in()) {
				$user = elgg_get_logged_in_user_entity();

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('gallery:create:album'),
					'href' => "gallery/create/album/$user->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}

			break;

		case 'group' :

			$group = elgg_get_page_owner_entity();

			if ($group->canWriteToContainer()) {

				elgg_register_menu_item('title', array(
					'name' => 'create:album',
					'text' => elgg_echo('gallery:create:album'),
					'href' => "gallery/create/album/$group->guid",
					'link_class' => 'elgg-button elgg-button-action',
					'priority' => 100
				));
			}
			break;
	}
}

/**
 * Process uploaded files
 *
 * @param string $name           Name of the HTML file input
 * @param string $subtype        Object subtype to be assigned to newly created objects
 * @param type   $guid           GUID of an existing object
 * @param type   $container_guid GUID of the container entity
 * @return array An associative array of original file names and guids (or false) of created object
 */
function process_file_upload($name, $subtype = hjAlbumImage::SUBTYPE, $guid = null, $container_guid = null) {

	// Normalize the $_FILES array
	if (is_array($_FILES[$name]['name'])) {
		$files = prepare_files_global($_FILES);
		$files = $files[$name];
	} else {
		$files = $_FILES[$name];
		$files = array($files);
	}

	foreach ($files as $file) {
		if (!is_array($file) || $file['error']) {
			continue;
		}

		$filehandler = new ElggFile($guid);
		$prefix = 'hjfile/';

		if ($guid) {
			$filename = $filehandler->getFilenameOnFilestore();
			if (file_exists($filename)) {
				unlink($filename);
			}
			$filestorename = $filehandler->getFilename();
			$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
		} else {
			$filehandler->subtype = $subtype;
			$filehandler->container_guid = $container_guid;
			$filestorename = elgg_strtolower(time() . $file['name']);
		}

		$filehandler->setFilename($prefix . $filestorename);
		$filehandler->title = $file['name'];

		$mime_type = ElggFile::detectMimeType($file['tmp_name'], $file['type']);

		// hack for Microsoft zipped formats
		$info = pathinfo($file['name']);
		$office_formats = array('docx', 'xlsx', 'pptx');
		if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
			switch ($info['extension']) {
				case 'docx':
					$mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					break;
				case 'xlsx':
					$mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					break;
				case 'pptx':
					$mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
					break;
			}
		}

		// check for bad ppt detection
		if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
			$mime_type = "application/vnd.ms-powerpoint";
		}

		$filehandler->setMimeType($mime_type);

		$filehandler->originalfilename = $file['name'];
		$filehandler->simpletype = get_simple_type($mime_type);
		$filehandler->filesize = $file['size'];

		$filehandler->open("write");
		$filehandler->close();

		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		if ($filehandler->save()) {

			// Generate icons for images
			if ($filehandler->simpletype == "image") {

				generate_entity_icons($filehandler);

				// the settings tell us not to keep the original image file, so downsizing to master
				if (elgg_get_plugin_setting('remove_original_files', 'hypeGallery')) {
					$icon_sizes = elgg_get_config('icon_sizes');
					$values = $icon_sizes['master'];
					$master = get_resized_image_from_existing_file(
							$filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square'], 0, 0, 0, 0, $values['upscale']);
					$filehandler->open('write');
					$filehandler->write($master);
					$filehandler->close();
				}
			}

			$return[$file['name']] = $filehandler->getGUID();
		} else {
			$return[$file['name']] = false;
		}
	}

	return $return;
}

/**
 * Normalize files global
 *
 * @param array   $_files Global files array
 * @param boolean $top    Top level?
 * @return array Normalized files array
 */
function prepare_files_global(array $_files, $top = TRUE) {

	$files = array();
	foreach ($_files as $name => $file) {
		if ($top) {
			$sub_name = $file['name'];
		} else {
			$sub_name = $name;
		}
		if (is_array($sub_name)) {
			foreach (array_keys($sub_name) as $key) {
				$files[$name][$key] = array(
					'name' => $file['name'][$key],
					'type' => $file['type'][$key],
					'tmp_name' => $file['tmp_name'][$key],
					'error' => $file['error'][$key],
					'size' => $file['size'][$key],
				);
				$files[$name] = prepare_files_global($files[$name], FALSE);
			}
		} else {
			$files[$name] = $file;
		}
	}
	return $files;
}

/**
 * Generate icons for an entity
 *
 * @param hjAlbumImage $entity      Entity
 * @param ElggFile     $filehandler Valid filehandler on Elgg filestore to grab the file from | can be null if $entity is instance of ElggFile
 * @param array        $coords      Coordinates for cropping
 * @return boolean
 */
function generate_entity_icons($entity, $filehandler = null, $coords = null) {

	$icon_sizes = elgg_get_config('icon_sizes');
	$gallery_icon_sizes = elgg_get_config('gallery_icon_sizes');

	$icon_sizes = array_merge($icon_sizes, $gallery_icon_sizes);

	if (!$filehandler && $entity instanceof ElggFile) {
		$filehandler = $entity;
	}

	if (!$filehandler) {
		return false;
	}

	$prefix = "icons/" . $entity->getGUID();

	foreach ($icon_sizes as $size => $values) {

		$w = elgg_extract('w', $values, 200);
		$h = elgg_extract('h', $values, 200);
		$square = elgg_extract('square', $values, true);
		$upscale = elgg_extract('upscale', $values, false);
		$filepath = $filehandler->getFilenameOnFilestore();

		if (is_array($coords) && in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large'))) {
			$x1 = elgg_extract('x1', $coords, 0);
			$y1 = elgg_extract('y1', $coords, 0);
			$x2 = elgg_extract('x2', $coords, 0);
			$y2 = elgg_extract('y2', $coords, 0);
			$thumb_resized = get_resized_image_from_existing_file($filepath, $w, $h, $square, $x1, $y1, $x2, $y2, $upscale);
		} else if (!is_array($coords)) {
			$thumb_resized = get_resized_image_from_existing_file($filepath, $w, $h, $square, 0, 0, 0, 0, $upscale);
		} else {
			continue;
		}

		if ($thumb_resized) {

			$thumb = new ElggFile();
			$thumb->owner_guid = $entity->owner_guid;
			$thumb->setMimeType('image/jpeg');
			$thumb->setFilename($prefix . "$size.jpg");
			$thumb->open("write");
			$thumb->write($thumb_resized);
			$thumb->close();

			$icontime = true;
		}
	}

	if ($icontime) {
		$entity->icontime = time();
		return true;
	}

	return false;
}

/**
 * Copy of file_get_simple_type()
 * Redefined in case file plugin is disabled
 *
 * @param string $mimetype Mimetype
 * @return string
 */
function get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			return "document";
		case "application/pdf":
			return "document";
		case "application/ogg":
			return "audio";
	}

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

/**
 * Get album write permissions_array
 *
 * @param ElggObject $container Album
 * @return array
 */
function get_permissions_options($container) {

	if (elgg_instanceof($container, 'group')) {
		return array(
			'private' => elgg_echo('permission:value:private'),
			'group' => elgg_echo('permission:value:group')
		);
	}

	return array(
		'private' => elgg_echo('permission:value:private'),
		'friends' => elgg_echo('permission:value:friends'),
		'public' => elgg_echo('permission:value:public')
	);
}

/**
 * Get files ordered by priority
 *
 * @param array $options Getter options
 * @return ElggObect[]|false
 */
function get_files($options = array()) {

	$defaults = array(
		'types' => 'object',
		'count' => false,
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'metadata_names' => array('simpletype'),
		'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer')
	);

	$options = array_merge($defaults, $options);

	return elgg_get_entities_from_metadata($options);
}

/**
 * Get tag objects attached to a given image
 *
 * @param ElggEntity $entity Image
 * @return array|false
 */
function get_image_tags($entity) {

	$tag_params = array(
		'type' => 'object',
		'subtype' => 'hjimagetag',
		'container_guid' => $entity->guid,
		'limit' => 0,
		'order_by' => 'e.time_created asc'
	);

	$tags = elgg_get_entities($tag_params);

	return $tags;
}

/**
 * Deprecated function
 * @return boolean
 * @deprecated since version 2.0.0
 */
function handle_uploaded_files() {
	return false;
}

/**
 * Get ancestry for a given entity guid
 *
 * @param int $guid GUID of the child element
 * @return boolean|array
 */
function get_ancestry($guid) {

	$entity = get_entity($guid);

	if (!$entity) {
		return false;
	}

	// Build an hierarchy from [0]highest to [X]lowest
	$ancestry = array();
	$container = $entity->getContainerEntity();
	while (elgg_instanceof($container)) {
		array_unshift($ancestry, $container);
		$container = $container->getContainerEntity();
	}

	return $ancestry;
}

/**
 * Parse and format meaningful EXIF tags
 *
 * @param ElggFile $entity Entity
 * @return array|false
 */
function get_exif($entity) {

	if (!($entity instanceof ElggFile) || !is_callable('exif_imagetype')) {
		return false;
	}

	// File is too small for exif to identify the type
	// or xxif is not supported on this file type
	if (filesize($entity->getFilenameOnFilestore()) <= 11 || !exif_imagetype($entity->getFilenameOnFilestore())) {
		return false;
	}

	$exif = exif_read_data($entity->getFilenameOnFilestore(), NULL, true);

	$tags = array();

	foreach ($exif as $section => $data) {

		foreach ($data as $key => $value) {

			if (is_string($value) && !trim($value)) {
				continue;
			}

			switch ($key) {

				case 'Model' :
				case 'LensInfo' :
				case 'LensModel' :
				case 'LensSerialNumber' :
				case 'XResolution' :
				case 'YResolution' :
				case 'Copyright' :
				case 'ImageDescription' :
				case 'Software' :
				case 'ModifyDate' :
				case 'FNumber' :
				case 'ExposureTime' :
				case 'ISO' :
				case 'ISOSpeedRatings' :
				case 'SensitivityType' :
				case 'SpectralSensitivity' :
				case 'RecommendedExposureIndex' :
				case 'DateTimeOriginal' :
				case 'DateTimeDigitized' :
				case 'CompressedBitsPerPixel' :
				case 'ShutterSpeedValue' :
				case 'ApertureValue' :
				case 'BrightnessValue' :
				case 'ExposureBiasValue' :
				case 'MaxApertureValue' :
				case 'SubjectDistance' :
				case 'FocalLength' :
				case 'UserComment' :
				case 'SubsecTime' :
				case 'SubsecTimeOriginal' :
				case 'SubsecTimeDigitized' :
				case 'Color Space' :
				case 'PixelXDimension' :
				case 'PixelYDimension' :
				case 'FlashEnergy' :
				case 'SpatialFrequencyResponse' :
				case 'FocalPlaneXResolution' :
				case 'FocalPlaneYResolution' :
				case 'ExposureIndex' :
				case 'SceneType' :
				case 'DigitalZoomRatio' :
				case 'FocalLengthIn35mmFilm' :
				case 'DeviceSettingDescription' :
				case 'ImageUniqueID' :
				case 'GPSAltitude' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => $value,
					);
					break;

				case 'ExifVersion' :
				case 'FlashpixVersion' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => number_format((int) $value / 100, 2),
					);
					break;

				case 'ExposureProgram' :
				case 'ComponentsConfiguration' :
				case 'MeteringMode' :
				case 'LightSource' :
				case 'Flash' :
				case 'Resolution Unit' :
				case 'FocalPlaneResolutionUnit' :
				case 'SensingMethod' :
				case 'CFAPattern' :
				case 'CustomRendered' :
				case 'ExposureMode' :
				case 'WhiteBalance' :
				case 'SceneCaptureType' :
				case 'GainControl' :
				case 'Contrast' :
				case 'Saturation' :
				case 'Sharpness' :
				case 'SubjectDistanceRange' :
				case 'GPSAltitudeRef' :
					if (is_numeric($value)) {
						$tags[$key] = array(
							'label' => elgg_echo("exif.$key"),
							'raw' => $value,
							'clean' => elgg_echo("exif.$key.$value"),
						);
					}
					break;

				case 'SubjectArea' :
				case 'SubjectLocation' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => (is_array($value)) ? implode(' ', $value) : $value,
					);
					break;

				case 'GPSVersionID' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => (is_array($value)) ? implode('.', $value) : $value,
					);
					break;

				case 'GPSLatitude' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => exif_getGps($value, $data['GPSLatitudeRef']),
					);
					break;

				case 'GPSLongitude' :
					$tags[$key] = array(
						'label' => elgg_echo("exif.$key"),
						'raw' => $value,
						'clean' => exif_getGps($value, $data['GPSLongitudeRef']),
					);
					break;
			}
		}
	}

	return elgg_trigger_plugin_hook('format:exif', 'framework:gallery', array('entity' => $entity, 'exif' => $exif), $tags);
}

/**
 * Helper function to convert exif GPS to proper coords
 * @link http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
 *
 * @param array  $exifCoord Exif coords
 * @param string $hemi      Hemisphere
 * @return float
 */
function exif_getGps($exifCoord, $hemi) {

	$degrees = count($exifCoord) > 0 ? exif_gps2Num($exifCoord[0]) : 0;
	$minutes = count($exifCoord) > 1 ? exif_gps2Num($exifCoord[1]) : 0;
	$seconds = count($exifCoord) > 2 ? exif_gps2Num($exifCoord[2]) : 0;

	$flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
}

/**
 * Helper function to convert exif GPS to proper coords
 * @link http://stackoverflow.com/questions/2526304/php-extract-gps-exif-data
 *
 * @param string $coordPart GPS coords
 * @return int
 */
function exif_gps2Num($coordPart) {

	$parts = explode('/', $coordPart);

	if (count($parts) <= 0) {
		return 0;
	}

	if (count($parts) == 1) {
		return $parts[0];
	}

	return floatval($parts[0]) / floatval($parts[1]);
}

/**
 * Get an array of icon sizes for this entity
 *
 * @param ElggObject $entity Entity
 * @return array
 */
function get_icon_sizes($entity) {

	$config = elgg_get_config('icon_sizes');
	$config = elgg_trigger_plugin_hook('entity:icon:sizes', 'object', array(
		'entity' => $entity,
			), $config);

	return $config;
}
