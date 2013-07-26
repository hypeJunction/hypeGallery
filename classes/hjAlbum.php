<?php

class hjAlbum extends ElggObject {

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

	public function getURL($action = 'view') {
		switch ($action) {

			default :
			case 'view' :
				$friendly_title = elgg_get_friendly_title($this->title);
				return "gallery/view/$this->guid/$friendly_title";
				break;

			case 'edit' :
				return "gallery/edit/$this->guid";
				break;

			case 'delete' :
				return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/gallery/delete/object?guid=$this->guid");
				break;

			case 'manage' :
				return "gallery/manage/$this->guid";
				break;
		}
	}

	public function getContainedFiles($options = array()) {
		$options['container_guids'] = array($this->guid);
		return hj_gallery_get_files($options);
	}

	public function getIconURL($size = 'medium') {

		if ($this->cover) {
			$cover_image = get_entity($this->cover);
		} 
		if (!$cover_image) {
			$images = $this->getContainedFiles(array('limit' => 1));
			$cover_image = $images[0];
		}

		if (elgg_instanceof($cover_image)) {
			return $cover_image->getIconURL($size);
		} else {
			return parent::getIconURL($size);
		}
	}

}