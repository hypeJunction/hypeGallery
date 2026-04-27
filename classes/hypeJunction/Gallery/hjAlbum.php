<?php

namespace hypeJunction\Gallery;

use ElggObject;

class hjAlbum extends ElggObject {

	const SUBTYPE = 'hjalbum';

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	public function countImages() {
		return elgg_get_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'container_guids' => $this->guid,
			'count' => true
		));
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
				return elgg_normalize_url("gallery/edit/$this->guid");

			case 'delete':
				return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/gallery/delete/object?guid=$this->guid");

			case 'manage':
				return elgg_normalize_url("gallery/manage/$this->guid");
		}
	}

	public function getContainedFiles($options = array()) {
		$options['container_guids'] = array($this->guid);
		return get_files($options);
	}

	public function getIconURL(array|string $params = []): string {
		if ($this->cover) {
			$cover_image = get_entity($this->cover);
		}
		if (!isset($cover_image) || !$cover_image) {
			$images = $this->getContainedFiles(array('limit' => 1));
			$cover_image = $images[0] ?? null;
		}

		if ($cover_image instanceof \ElggEntity) {
			return $cover_image->getIconURL($params);
		} else {
			return parent::getIconURL($params);
		}
	}
}
