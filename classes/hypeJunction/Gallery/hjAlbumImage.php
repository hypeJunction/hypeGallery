<?php

namespace hypeJunction\Gallery;

use ElggFile;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps -- legacy subtype-name preserved for entity mapping
/**
 * hjAlbumImage class.
 */
class hjAlbumImage extends ElggFile {

	const SUBTYPE = 'hjalbumimage';

	/**
	 * initializeAttributes.
	 *
	 * @return mixed
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * save.
	 *
	 * @return bool
	 */
	public function save(): bool {
		if (!isset($this->priority)) {
			$this->priority = 0;
		}

		return parent::save();
	}

	/**
	 * getURL.
	 *
	 * @return string
	 */
	public function getURL(): string {
		$friendly_title = elgg_get_friendly_title($this->title);
		return elgg_normalize_url("gallery/view/$this->guid/$friendly_title");
	}

	/**
	 * getActionURL.
	 *
	 * @param string $action action
	 *
	 * @return string
	 */
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

	/**
	 * getIconURL.
	 *
	 * @param array|string $params params
	 *
	 * @return string
	 */
	public function getIconURL(array|string $params = []): string {
		$size = is_string($params) ? $params : (elgg_extract('size', (array) $params, 'medium'));
		return elgg_normalize_url("gallery/icon/$this->guid/$size");
	}

	/**
	 * Match ElggFile::delete() 6.x signature.
	 *
	 * @param bool      $recursive  Was $follow_symlinks pre-6.x
	 * @param bool|null $persistent New 6.x parameter — pass through unchanged
	 *
	 * @return bool
	 */
	public function delete(bool $recursive = true, ?bool $persistent = null): bool {
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

		return parent::delete($recursive, $persistent);
	}

	/**
	 * getExif.
	 *
	 * @return mixed
	 */
	public function getExif() {
		return get_exif($this);
	}
}
