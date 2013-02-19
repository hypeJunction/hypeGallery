<?php

run_function_once('hj_gallery_1360950978');

function hj_gallery_1360950978() {

	$ia = elgg_set_ignore_access(true);
	$ha = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	ini_set('memory_limit', '512M');
	ini_set('max_execution_time', '500');

	$subtypeId = get_subtype_id('object', 'hjalbumimage');
	$dbprefix = elgg_get_config('dbprefix');

	$query = "	SELECT guid, msv.string AS file_guid FROM {$dbprefix}entities e
					JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid
					JOIN {$dbprefix}metastrings msn ON (msn.id = md.name_id AND msn.string = 'image')
					JOIN {$dbprefix}metastrings msv ON (msv.id = md.value_id)
					WHERE e.subtype = $subtypeId";

	$rows = get_data($query);

	foreach ($rows as $row) {
		$imagehandler = new hjAlbumImage($row->guid);
		$filehandler = new hjFile($row->file_guid);

		$filename = $filehandler->getFilenameOnFilestore();
		$imagehandler->setFilename($filename);
		$imagehandler->setMimeType($filehandler->getMimeType());
		$imagehandler->originalfilename = $filehandler->originalfilename;
		$imagehandler->filesize = $filehandler->filesize;

		$imagehandler->save();

		$icon_sizes = hj_framework_get_thumb_sizes($imagehandler->getSubtype());

		$old_prefix = "hjfile/$filehandler->container_guid/$filehandler->guid";
		$prefix = "icons/$imagehandler->guid";

		foreach ($icon_sizes as $size => $values) {
				$old_thumb = new ElggFile();
				$old_thumb->owner_guid = elgg_get_logged_in_user_guid();
				$old_thumb->setFilename("$old_prefix$size.jpg");

				if (!$content = $old_thumb->grabFile()) {
					$content = get_resized_image_from_existing_file($filehandler->getFilenameOnFilestore(), $values['w'], $values['h'], $values['square']);
				}
				$thumb = new ElggFile();
				$thumb->owner_guid = elgg_get_logged_in_user_guid();
				$thumb->setMimeType('image/jpeg');
				$thumb->setFilename("$prefix$size.jpg");
				$thumb->open("write");
				$thumb->write($content);
				$thumb->close();
		}

		$imagehandler->icontime = $filehandler->icontime;

		//$filehandler->delete();
	}

	elgg_set_ignore_access($ia);
	access_show_hidden_entities($ha);
}