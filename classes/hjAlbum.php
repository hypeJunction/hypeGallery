<?php

class hjAlbum extends hjObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjalbum';
	}

	public function countImages() {

		return elgg_get_entities(array(
					'types' => 'object',
					'subtypes' => array('hjalbumimage'),
					'container_guids' => $this->guid,
					'count' => true
				));
	}

	public function getContainedFiles($limit = 10) {
		$options = array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'container_guids' => $this->guid,
			'limit' => $limit
		);

		$options = hj_framework_get_order_by_clause('md.priority', 'ASC', $options);

		return elgg_get_entities($options);
	}

	public function getURL() {
		$friendly_title = elgg_get_friendly_title($this->title);
		return "gallery/view/$this->guid/$friendly_title";
	}

	public function getEditURL() {
		return "gallery/edit/$this->guid";
	}

	public function getIconURL($size = 'medium') {

		if ($this->cover) {
			$cover_image = get_entity($this->cover);
		} else if ($images = $this->getContainedFiles(1)) {
			$cover_image = $images[0];
		}

		if (elgg_instanceof($cover_image)) {
			return $cover_image->getIconURL($size);
		} else {
			return parent::getIconURL($size);
		}

	}

}