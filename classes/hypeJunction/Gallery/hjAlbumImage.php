<?php

namespace hypeJunction\Gallery;

use ElggFile;

class hjAlbumImage extends ElggFile {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjalbumimage';
	}

	public function save() {
		update_entity_last_action($this->container_guid);
		if (!isset($this->priority)) {
			$this->priority = 0;
		}
		return parent::save();
	}

	public function getURL($action = 'view') {
		switch ($action) {
			default :
			case 'view' :
				$friendly_title = elgg_get_friendly_title($this->title);
				return elgg_normalize_url("gallery/view/$this->guid/$friendly_title");
				break;

			case 'edit' :
				return elgg_normalize_url("gallery/manage/$this->container_guid#elgg-object-$this->guid");
				break;

			case 'delete' :
				return elgg_add_action_tokens_to_url(elgg_normalize_url("action/gallery/delete/object?guid=$this->guid"));
				break;

			case 'download' :
				return elgg_normalize_url("gallery/download/$this->guid");
				break;
		}
	}

	public function getIconURL($size = 'medium') {
		return elgg_normalize_url("gallery/icon/$this->guid/$size");
	}

	public function delete() {

		$icon_sizes = elgg_get_config('icon_sizes');

		$prefix_old = "ElggFile/$this->container_guid/$this->guid";
		$prefix_old_alt = "ElggFile/$this->guid";
		$prefix = "icons/$this->guid";

		foreach ($icon_sizes as $size => $values) {
			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix$size.jpg");
			$thumb->delete();

			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix_old$size.jpg");
			$thumb->delete();

			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix_old_alt$size.jpg");
			$thumb->delete();
		}

		return parent::delete();
	}

	function getExif() {
		return get_exif($this);
	}

}
