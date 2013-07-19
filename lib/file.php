<?php

/**
 * Process uploaded files
 *
 * @param mixed $files		Uploaded files
 * @param mixed $entity		Guid of an hjAlbumImage to write the uploaded files into
 * @return void
 */
function hj_gallery_process_file_upload($name, $subtype = 'hjalbumimage', $guid = null, $container_guid = null) {

	// Normalize the $_FILES array
	if (is_array($_FILES[$name]['name'])) {
		$files = hj_gallery_prepare_files_global($_FILES);
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
		$filehandler->simpletype = hj_gallery_get_simple_type($mime_type);
		$filehandler->filesize = $file['size'];

		$filehandler->open("write");
		$filehandler->close();

		move_uploaded_file($file['tmp_name'], $filehandler->getFilenameOnFilestore());

		if ($filehandler->save()) {

			// Generate icons for images
			if ($filehandler->simpletype == "image") {

				hj_gallery_generate_entity_icons($filehandler);

				// the settings tell us not to keep the original image file, so downsizing to master
				if (elgg_get_plugin_setting('remove_original_files', 'hypeGallery')) {
					$icon_sizes = elgg_get_config('icon_sizes');
					$values = $icon_sizes['master'];
					$master = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square'], 0, 0, 0, 0, $values['upscale']);
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
 * Normalize $_FILES global
 * @param array $_files
 * @param bool $top
 * @return array
 */
function hj_gallery_prepare_files_global(array $_files, $top = TRUE) {

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
				$files[$name] = hj_gallery_prepare_files_global($files[$name], FALSE);
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
 * @param ElggEntity $entity
 * @param ElggFile $filehandler		Valid $filehandler on Elgg filestore to grab the file from | can be null if $entity is instance of ElggFile
 * @param array $coords				Coordinates for cropping
 * @return boolean
 */
function hj_gallery_generate_entity_icons($entity, $filehandler = null, $coords = array()) {

	$icon_sizes = elgg_get_config('icon_sizes');

	if (!$filehandler && $entity instanceof ElggFile) {
		$filehandler = $entity;
	}

	if (!$filehandler)
		return false;

	$prefix = "icons/" . $entity->getGUID();

	foreach ($icon_sizes as $size => $values) {

		if (!empty($coords) && in_array($size, array('topbar', 'tiny', 'small', 'medium', 'large'))) {
			$thumb_resized = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square'], $coords['x1'], $coords['y1'], $coords['x2'], $coords['y2'], $values['upscale']);
		} else if (empty($coords)) {
			$thumb_resized = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square'], 0, 0, 0, 0, $values['upscale']);
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
 * @param string $mimetype
 * @return string
 */
function hj_gallery_get_simple_type($mimetype) {

	switch ($mimetype) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
		case "application/ogg":
			return "audio";
			break;
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
