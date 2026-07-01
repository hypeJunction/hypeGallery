<?php

namespace hypeJunction\Gallery;

use ElggObject;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps -- legacy subtype-name preserved for entity mapping
/**
 * hjAlbum class.
 */
class hjAlbum extends ElggObject {

	const SUBTYPE = 'hjalbum';

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
	 * countImages.
	 *
	 * @return mixed
	 */
	public function countImages() {
		return elgg_get_entities([
			'types' => 'object',
			'subtypes' => ['hjalbumimage'],
			'container_guids' => $this->guid,
			'count' => true
		]);
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
				return elgg_normalize_url("gallery/edit/$this->guid");

			case 'delete':
				return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/gallery/delete/object?guid=$this->guid");

			case 'manage':
				return elgg_normalize_url("gallery/manage/$this->guid");
		}
	}

	/**
	 * getContainedFiles.
	 *
	 * @param mixed $options options
	 *
	 * @return mixed
	 */
	public function getContainedFiles($options = []) {
		$options['container_guids'] = [$this->guid];
		return get_files($options);
	}

	/**
	 * getIconURL.
	 *
	 * @param array|string $params params
	 *
	 * @return string
	 */
	public function getIconURL(array|string $params = []): string {
		if ($this->cover) {
			$cover_image = get_entity((int) $this->cover);
		}

		if (!isset($cover_image) || !$cover_image) {
			$images = $this->getContainedFiles(['limit' => 1]);
			$cover_image = $images[0] ?? null;
		}

		if ($cover_image instanceof \ElggEntity) {
			return $cover_image->getIconURL($params);
		} else {
			return parent::getIconURL($params);
		}
	}
}
