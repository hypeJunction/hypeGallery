<?php

namespace hypeJunction\Gallery;

use ElggFile;

/**
 * Image entity class
 *
 * @package    Elgg
 * @subpackage Gallery
 */
class hjAlbumImage extends ElggFile {

	const SUBTYPE = 'hjalbumimage';

	/**
	 * Initialize attributes
	 * Set subtype
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Save entity
	 * Add default priority and update last action on an album
	 *
	 * @return integer|false
	 */
	public function save() {
		update_entity_last_action($this->container_guid);
		if (!isset($this->priority)) {
			$this->priority = 0;
		}
		return parent::save();
	}

	/**
	 * Get URL for a specific operation
	 *
	 * @param string $action Operation name
	 * @return string
	 */
	public function getURL($action = 'view') {
		switch ($action) {
			default :
			case 'view' :
				$friendly_title = elgg_get_friendly_title($this->title);
				return elgg_normalize_url("gallery/view/$this->guid/$friendly_title");

			case 'edit' :
				return elgg_normalize_url("gallery/manage/$this->container_guid#elgg-object-$this->guid");

			case 'delete' :
				return elgg_add_action_tokens_to_url(elgg_normalize_url("action/gallery/delete/object?guid=$this->guid"));

			case 'download' :
				return elgg_normalize_url("gallery/download/$this->guid");
		}
	}

	/**
	 * Get icon URL
	 *
	 * @param string $size Icon size
	 * @return string
	 */
	public function getIconURL($size = 'medium') {
		return elgg_normalize_url("gallery/icon/$this->guid/$size");
	}

	/**
	 * Delete image entity and clean up filestore
	 * 
	 * @return boolean
	 */
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

		// Tidypics Imported Images
		$thumbnail = $this->thumbnail;
		$smallthumb = $this->smallthumb;
		$largethumb = $this->largethumb;

		//delete standard thumbnail image
		if ($thumbnail) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($thumbnail);
			$delfile->delete();
		}
		//delete small thumbnail image
		if ($smallthumb) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($smallthumb);
			$delfile->delete();
		}
		//delete large thumbnail image
		if ($largethumb) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($largethumb);
			$delfile->delete();
		}

		return parent::delete();
	}

	/**
	 * Get EXIF data for this image
	 *
	 * @return array|false
	 */
	function getExif() {
		return get_exif($this);
	}

}
