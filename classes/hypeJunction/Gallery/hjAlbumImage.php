<?php

namespace hypeJunction\Gallery;

use ElggFile;

class hjAlbumImage extends ElggFile {

	const SUBTYPE = 'hjalbumimage';

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	public function save(): bool {
		if (!isset($this->priority)) {
			$this->priority = 0;
		}
		return parent::save();
	}

	public function getURL(): string {
		$friendly_title = elgg_get_friendly_title($this->title);
		return elgg_normalize_url("gallery/view/$this->guid/$friendly_title");
	}

	public function getActionURL(string $action): string {
		switch ($action) {
			default:
			case 'view':
				return $this->getURL();

			case 'edit':
				return elgg_normalize_url("gallery/manage/$this->container_guid#elgg-object-$this->guid");

			case 'delete':
				return elgg_add_action_tokens_to_url(elgg_normalize_url("action/gallery/delete/object?guid=$this->guid"));

			case 'download':
				return elgg_normalize_url("gallery/download/$this->guid");
		}
	}

	public function getIconURL(array|string $params = []): string {
		$size = is_string($params) ? $params : (elgg_extract('size', (array) $params, 'medium'));
		return elgg_normalize_url("gallery/icon/$this->guid/$size");
	}

	public function delete(bool $follow_symlinks = true): bool {
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

		$thumbnail = $this->thumbnail;
		$smallthumb = $this->smallthumb;
		$largethumb = $this->largethumb;

		if ($thumbnail) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($thumbnail);
			$delfile->delete();
		}
		if ($smallthumb) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($smallthumb);
			$delfile->delete();
		}
		if ($largethumb) {
			$delfile = new ElggFile();
			$delfile->owner_guid = $this->getOwnerGUID();
			$delfile->setFilename($largethumb);
			$delfile->delete();
		}

		return parent::delete($follow_symlinks);
	}

	function getExif() {
		return get_exif($this);
	}
}
