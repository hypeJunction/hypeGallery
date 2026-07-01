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
	if (!$entity instanceof \ElggEntity) {
		return;
	}

	$items = [];
	switch ($entity->getSubtype()) {
		default:
			return true;
		case hjAlbum::SUBTYPE:
			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-upload')) {
				$items['upload'] = ['text' => elgg_echo('gallery:upload'), 'href' => "gallery/upload/{$entity->guid}", 'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity', 'data-guid' => $entity->guid, 'priority' => 400];
			}

			if ($entity->canWriteToContainer(0, 'object', 'hjalbumimage') && !elgg_in_context('gallery-manage')) {
				$items['manage'] = ['text' => elgg_echo('gallery:manage:album'), 'href' => "gallery/manage/{$entity->guid}", 'link_class' => 'elgg-button elgg-button-action', 'priority' => 400];
			}

			if ($entity->canEdit()) {
				$items['edit'] = ['text' => elgg_echo('edit'), 'href' => $entity->getActionURL('edit'), 'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity', 'data-guid' => $entity->guid, 'priority' => 995];
				$items['delete'] = ['text' => elgg_echo('delete'), 'href' => $entity->getActionURL('delete'), 'link_class' => 'elgg-button elgg-button-delete elgg-button-delete-entity', 'data-guid' => $entity->guid, 'priority' => 1000];
			}
			break;
		case hjAlbumImage::SUBTYPE:
			$items['download'] = HYPEGALLERY_DOWNLOADS && (elgg_is_logged_in() || HYPEGALLERY_PUBLIC) ? ['text' => elgg_echo('gallery:image:download'), 'href' => $entity->getActionURL('download'), 'link_class' => 'elgg-button elgg-button-action', 'priority' => 50] : null;
			$items['makeavatar'] = HYPEGALLERY_AVATARS && elgg_is_logged_in() ? ['text' => elgg_echo('gallery:image:makeavatar'), 'href' => "action/gallery/makeavatar?e={$entity->guid}", 'is_action' => true, 'link_class' => 'elgg-button elgg-button-action', 'priority' => 100] : null;
			if ($entity->canEdit()) {
				$items['edit'] = ['text' => elgg_echo('edit'), 'href' => $entity->getActionURL('edit'), 'link_class' => 'elgg-button elgg-button-action elgg-button-edit-entity', 'data-guid' => $entity->guid, 'priority' => 995];
				$items['delete'] = ['text' => elgg_echo('delete'), 'href' => $entity->getActionURL('delete'), 'link_class' => 'elgg-button elgg-button-delete elgg-button-delete-entity elgg-requires-confirmation', 'data-guid' => $entity->guid, 'priority' => 1000];
			}
			break;
	}

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
		case 'site':
		case 'owner':
			if (elgg_is_logged_in()) {
				$user = elgg_get_logged_in_user_entity();
				elgg_register_menu_item('title', ['name' => 'create:album', 'text' => elgg_echo('gallery:create:album'), 'href' => "gallery/create/album/{$user->guid}", 'link_class' => 'elgg-button elgg-button-action', 'priority' => 100]);
			}
			break;
		case 'group':
			$group = elgg_get_page_owner_entity();
			if ($group->canWriteToContainer(0, 'object', 'hjalbum')) {
				elgg_register_menu_item('title', ['name' => 'create:album', 'text' => elgg_echo('gallery:create:album'), 'href' => "gallery/create/album/{$group->guid}", 'link_class' => 'elgg-button elgg-button-action', 'priority' => 100]);
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
		$files = [$files];
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
			$filehandler->setSubtype($subtype);
			$filehandler->container_guid = $container_guid;
			$filestorename = elgg_strtolower(time() . $file['name']);
		}

		$filehandler->setFilename($prefix . $filestorename);
		$filehandler->title = $file['name'];
		$mime_type = $file['type'];
		if (file_exists($file['tmp_name'])) {
			$detected = mime_content_type($file['tmp_name']);
			if ($detected) {
				$mime_type = $detected;
			}
		}

		// hack for Microsoft zipped formats
		$info = pathinfo($file['name']);
		$office_formats = ['docx', 'xlsx', 'pptx'];
		if ($mime_type == 'application/zip' && in_array($info['extension'], $office_formats)) {
			switch ($info['extension']) {
				case 'docx':
					$mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
					break;
				case 'xlsx':
					$mime_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
					break;
				case 'pptx':
					$mime_type = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
					break;
			}
		}

		// check for bad ppt detection
		if ($mime_type == 'application/vnd.ms-office' && $info['extension'] == 'ppt') {
			$mime_type = 'application/vnd.ms-powerpoint';
		}

		$filehandler->setMimeType($mime_type);
		$filehandler->originalfilename = $file['name'];
		$filehandler->simpletype = get_simple_type($mime_type);
		$filehandler->filesize = $file['size'];
		$filehandler->open('write');
		$filehandler->close();
		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());
		if ($filehandler->save()) {
			// Generate icons for images
			if ($filehandler->simpletype == 'image') {
				// IconHandler generates the full size set (including 'master')
				// from the configured icon sizes, honouring the
				// 'remove_original_files' plugin setting internally.
				generate_entity_icons($filehandler);
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
function prepare_files_global(array $_files, $top = true) {
	$files = [];
	foreach ($_files as $name => $file) {
		if ($top) {
			$sub_name = $file['name'];
		} else {
			$sub_name = $name;
		}

		if (is_array($sub_name)) {
			foreach (array_keys($sub_name) as $key) {
				$files[$name][$key] = ['name' => $file['name'][$key], 'type' => $file['type'][$key], 'tmp_name' => $file['tmp_name'][$key], 'error' => $file['error'][$key], 'size' => $file['size'][$key]];
				$files[$name] = prepare_files_global($files[$name], false);
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
	$icon_sizes = (array) elgg_get_config('icon_sizes');
	$gallery_icon_sizes = (array) elgg_get_config('gallery_icon_sizes');
	$icon_sizes = array_merge($icon_sizes, $gallery_icon_sizes);

	if (!$filehandler && $entity instanceof ElggFile) {
		$filehandler = $entity;
	}

	if (!$filehandler) {
		return false;
	}

	$config = [
		'icon_sizes' => $icon_sizes,
	];

	if (is_array($coords)) {
		$config['coords'] = $coords;
	}

	return (bool) \hypeJunction\Filestore\IconHandler::makeIcons($entity, $filehandler, $config);
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
		case 'application/msword':
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			return 'document';
		case 'application/pdf':
			return 'document';
		case 'application/ogg':
			return 'audio';
	}

	if (substr_count($mimetype, 'text/')) {
		return 'document';
	}

	if (substr_count($mimetype, 'audio/')) {
		return 'audio';
	}

	if (substr_count($mimetype, 'image/')) {
		return 'image';
	}

	if (substr_count($mimetype, 'video/')) {
		return 'video';
	}

	if (substr_count($mimetype, 'opendocument')) {
		return 'document';
	}

	return 'general';
}

/**
 * Get album write permissions_array
 *
 * @param ElggObject $container Album
 * @return array
 */
function get_permissions_options($container) {
	if ($container instanceof \ElggGroup) {
		return ['private' => elgg_echo('permission:value:private'), 'group' => elgg_echo('permission:value:group')];
	}

	return ['private' => elgg_echo('permission:value:private'), 'friends' => elgg_echo('permission:value:friends'), 'public' => elgg_echo('permission:value:public')];
}

/**
 * Get files ordered by priority
 *
 * @param array $options Getter options
 * @return ElggObect[]|false
 */
function get_files($options = []) {
	$defaults = ['types' => 'object', 'count' => false, 'limit' => get_input('limit', 10), 'offset' => get_input('offset', 0), 'metadata_names' => ['simpletype'], 'order_by_metadata' => ['name' => 'priority', 'direction' => 'ASC', 'as' => 'integer']];
	$options = array_merge($defaults, $options);
	return elgg_get_entities($options);
}

/**
 * Get tag objects attached to a given image
 *
 * @param ElggEntity $entity Image
 * @return array|false
 */
function get_image_tags($entity) {
	// TODO(elgg7): limit=0 may be clamped in Elgg 7.x — verify max tags per image or switch to batch mode
	$tag_params = ['type' => 'object', 'subtype' => 'hjimagetag', 'container_guid' => $entity->guid, 'limit' => 0, 'order_by' => 'e.time_created asc'];
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
	$entity = $guid ? get_entity((int) $guid) : null;
	if (!$entity) {
		return false;
	}

	// Build an hierarchy from [0]highest to [X]lowest
	$ancestry = [];
	$container = $entity->getContainerEntity();
	while ($container instanceof \ElggEntity) {
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
	if (!$entity instanceof ElggFile || !is_callable('exif_imagetype')) {
		return false;
	}

	// File is too small for exif to identify the type
	// or xxif is not supported on this file type
	if (filesize($entity->getFilenameOnFilestore()) <= 11 || !exif_imagetype($entity->getFilenameOnFilestore())) {
		return false;
	}

	$exif = exif_read_data($entity->getFilenameOnFilestore(), null, true);
	$tags = [];
	foreach ($exif as $section => $data) {
		foreach ($data as $key => $value) {
			if (is_string($value) && !trim($value)) {
				continue;
			}

			switch ($key) {
				case 'Model':
				case 'LensInfo':
				case 'LensModel':
				case 'LensSerialNumber':
				case 'XResolution':
				case 'YResolution':
				case 'Copyright':
				case 'ImageDescription':
				case 'Software':
				case 'ModifyDate':
				case 'FNumber':
				case 'ExposureTime':
				case 'ISO':
				case 'ISOSpeedRatings':
				case 'SensitivityType':
				case 'SpectralSensitivity':
				case 'RecommendedExposureIndex':
				case 'DateTimeOriginal':
				case 'DateTimeDigitized':
				case 'CompressedBitsPerPixel':
				case 'ShutterSpeedValue':
				case 'ApertureValue':
				case 'BrightnessValue':
				case 'ExposureBiasValue':
				case 'MaxApertureValue':
				case 'SubjectDistance':
				case 'FocalLength':
				case 'UserComment':
				case 'SubsecTime':
				case 'SubsecTimeOriginal':
				case 'SubsecTimeDigitized':
				case 'Color Space':
				case 'PixelXDimension':
				case 'PixelYDimension':
				case 'FlashEnergy':
				case 'SpatialFrequencyResponse':
				case 'FocalPlaneXResolution':
				case 'FocalPlaneYResolution':
				case 'ExposureIndex':
				case 'SceneType':
				case 'DigitalZoomRatio':
				case 'FocalLengthIn35mmFilm':
				case 'DeviceSettingDescription':
				case 'ImageUniqueID':
				case 'GPSAltitude':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => $value];
					break;
				case 'ExifVersion':
				case 'FlashpixVersion':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => number_format((int) $value / 100, 2)];
					break;
				case 'ExposureProgram':
				case 'ComponentsConfiguration':
				case 'MeteringMode':
				case 'LightSource':
				case 'Flash':
				case 'Resolution Unit':
				case 'FocalPlaneResolutionUnit':
				case 'SensingMethod':
				case 'CFAPattern':
				case 'CustomRendered':
				case 'ExposureMode':
				case 'WhiteBalance':
				case 'SceneCaptureType':
				case 'GainControl':
				case 'Contrast':
				case 'Saturation':
				case 'Sharpness':
				case 'SubjectDistanceRange':
				case 'GPSAltitudeRef':
					if (is_numeric($value)) {
						$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => elgg_echo("exif.{$key}.{$value}")];
					}
					break;
				case 'SubjectArea':
				case 'SubjectLocation':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => is_array($value) ? implode(' ', $value) : $value];
					break;
				case 'GPSVersionID':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => is_array($value) ? implode('.', $value) : $value];
					break;
				case 'GPSLatitude':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => exif_getGps($value, $data['GPSLatitudeRef'])];
					break;
				case 'GPSLongitude':
					$tags[$key] = ['label' => elgg_echo("exif.{$key}"), 'raw' => $value, 'clean' => exif_getGps($value, $data['GPSLongitudeRef'])];
					break;
			}
		}
	}

	return elgg_trigger_event_results('format:exif', 'framework:gallery', ['entity' => $entity, 'exif' => $exif], $tags);
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
	$config = elgg_trigger_event_results('entity:icon:sizes', 'object', ['entity' => $entity], $config);
	return $config;
}
