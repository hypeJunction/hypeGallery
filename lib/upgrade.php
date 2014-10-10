<?php

use hypeJunction\Gallery\hjAlbumImage;

ini_set('memory_limit', '512M');
set_time_limit(0);

$ia = elgg_set_ignore_access(true);
$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

run_function_once('hj_gallery_1361394670');
run_function_once('hj_gallery_1361396953');
run_function_once('hj_gallery_1361394680');
run_function_once('hj_gallery_1369646725');
run_function_once('hj_gallery_1374851653');

run_function_once('hj_gallery_tidypics_albums');

elgg_set_ignore_access($ia);
access_show_hidden_entities($ha);

function hj_gallery_1361394670() {

	$subtypeId = get_subtype_id('object', 'hjalbumimage');
	if (!$subtypeId) {
		return true;
	}
	$dbprefix = elgg_get_config('dbprefix');

	$query = "	SELECT guid, msv.string AS file_guid FROM {$dbprefix}entities e
					JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid
					JOIN {$dbprefix}metastrings msn ON (msn.id = md.name_id AND msn.string = 'image')
					JOIN {$dbprefix}metastrings msv ON (msv.id = md.value_id)
					WHERE e.subtype = $subtypeId";

	$rows = get_data($query);

	foreach ($rows as $row) {
		$imagehandler = new hjAlbumImage($row->guid);

		try {
			$filehandler = new ElggFile($row->file_guid);
		} catch (Exception $e) {
			continue;
		}

		$filestorename = $filehandler->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen("ElggFile/"));

		$temp = new ElggFile();
		$temp->setFilename("ElggFile/" . $filestorename);
		$temp->owner_guid = $filehandler->owner_guid;
		$temp->open('read');
		$content = $temp->grabFile();
		$temp->close();

		$filehandler->setFilename("ElggFile/{$filehandler->getGUID()}.temp");
		$filehandler->save();

		$imagehandler->owner_guid = $filehandler->owner_guid;
		$imagehandler->setFilename("ElggFile/" . $filestorename);
		$imagehandler->setMimeType($filehandler->getMimeType());
		$imagehandler->originalfilename = $filehandler->originalfilename;
		$imagehandler->filesize = $filehandler->filesize;

		$imagehandler->open('write');
		$imagehandler->write($content);
		$imagehandler->close();

		unset($imagehandler->image);

		if ($imagehandler->save()) {
			\hypeJunction\Gallery\generate_entity_icons($imagehandler);
		}

		$temp->delete();
		$filehandler->delete();
	}
}

function hj_gallery_1361396953() {

	$subtypeIdImage = get_subtype_id('object', 'hjalbumimage');
	if (!$subtypeIdImage) {
		return true;
	}
	$dbprefix = elgg_get_config('dbprefix');

	$files = elgg_get_entities(array(
		'types' => 'object',
		'subtypes' => 'hjfile',
		'joins' => array(
			"JOIN {$dbprefix}entities ce"
		),
		'wheres' => array("(ce.guid = e.container_guid AND ce.subtype = $subtypeIdImage)"),
		'limit' => 0
	));

	foreach ($files as $filehandler) {

		$imagehandler = new hjAlbumImage($filehandler->container_guid);

		$filestorename = $filehandler->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen("ElggFile/"));

		if (!$filestorename || empty($filestorename)) {
			if (isset($filehandler->originalfilename)) {
				$filestorename = $filehandler->originalfilename;
			} else if (isset($filehandler->title)) {
				$filestorename = $filehandler->title;
			} else {
				continue;
			}
		}

		$temp = new ElggFile();
		$temp->setFilename("ElggFile/" . $filestorename);
		$temp->owner_guid = $filehandler->owner_guid;
		$temp->open('read');
		$content = $temp->grabFile();
		$temp->close();

		$imagehandler->owner_guid = $filehandler->owner_guid;
		$imagehandler->setFilename("ElggFile/" . $filestorename);
		$imagehandler->setMimeType($filehandler->getMimeType());
		$imagehandler->originalfilename = $filehandler->originalfilename;
		$imagehandler->filesize = $filehandler->filesize;

		$imagehandler->open('write');
		$imagehandler->write($content);
		$imagehandler->close();

		unset($imagehandler->image);

		if ($imagehandler->save()) {
			\hypeJunction\Gallery\generate_entity_icons($imagehandler);
		}

		$temp->delete();
		$filehandler->delete();
	}
}

function hj_gallery_1361379980() {

	// set priority metadata on images
	$subtypeId = get_subtype_id('object', 'hjalbumimage');
	if (!$subtypeId) {
		return true;
	}
	$dbprefix = elgg_get_config('dbprefix');
	$query = "SELECT guid, owner_guid
				FROM {$dbprefix}entities e
				WHERE e.subtype IN ($subtypeId)";

	$data = get_data($query);

	foreach ($data as $e) {
		create_metadata($e->guid, 'priority', 0, '', $e->owner_guid, ACCESS_PUBLIC);
	}
}

function hj_gallery_1369646725() {

	$subtypeId = get_subtype_id('object', 'hjalbumimage');
	if (!$subtypeId) {
		return true;
	}

	$dbprefix = elgg_get_config('dbprefix');
	$query = "	SELECT guid, owner_guid, time_created FROM {$dbprefix}entities e
					WHERE NOT EXISTS (
						SELECT 1 FROM {$dbprefix}metadata md
						WHERE md.entity_guid = e.guid
							AND md.name_id = 'icontime'
								) AND e.subtype = $subtypeId";

	$rows = get_data($query);

	foreach ($rows as $row) {
		create_metadata($row->guid, 'icontime', $row->time_created, '', $row->owner_guid, ACCESS_PUBLIC);
	}
}

function hj_gallery_1374851653() {
	$dbprefix = elgg_get_config('dbprefix');

	$subtypeIdAnnotation = get_subtype_id('object', 'hjannotation');
	if (!$subtypeIdAnnotation) {
		return true;
	}

	add_subtype('object', 'hjimagetag');
	$subtypeIdTag = get_subtype_id('object', 'hjimagetag');

	$query = "	UPDATE {$dbprefix}entities e
				JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid
				JOIN {$dbprefix}metastrings msn ON msn.id = md.name_id
				JOIN {$dbprefix}metastrings msv ON msv.id = md.value_id
				SET e.subtype = $subtypeIdTag
				WHERE e.subtype = $subtypeIdAnnotation AND msn.string = 'handler' AND msv.string = 'hjimagetag'	";

	update_data($query);
}