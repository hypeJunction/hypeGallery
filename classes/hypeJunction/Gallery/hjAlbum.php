<?php

namespace hypeJunction\Gallery;

use ElggObject;

/**
 * Album Class
 *
 * @package    Elgg
 * @subpackage Gallery	
 */
class hjAlbum extends ElggObject {

	const SUBTYPE = 'hjalbum';

	/**
	 * Initialize attributes
	 * Set subtype
	 * 
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Count images contained in an album
	 * 
	 * @return integer
	 */
	public function countImages() {

		return elgg_get_entities(array(
			'types' => 'object',
			'subtypes' => array('hjalbumimage'),
			'container_guids' => $this->guid,
			'count' => true
		));
	}

	/**
	 * Get album URL
	 * 
	 * @param string $action Operation
	 * @return string
	 */
	public function getURL($action = 'view') {
		switch ($action) {

			default :
			case 'view' :
				return parent::getURL();

			case 'edit' :
				return "gallery/edit/$this->guid";

			case 'delete' :
				return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/gallery/delete/object?guid=$this->guid");

			case 'manage' :
				return "gallery/manage/$this->guid";
		}
	}

	/**
	 * Get files contained in this album
	 *
	 * @param array $options Getter options
	 * @return ElggObject[]|false
	 */
	public function getContainedFiles($options = array()) {
		$options['container_guids'] = array($this->guid);
		return get_files($options);
	}

	/**
	 * Get icon URL
	 *
	 * @param string $size Icon size
	 * @return string
	 */
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
