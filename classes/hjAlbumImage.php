<?php

class hjAlbumImage extends hjFile {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjalbumimage';
	}

	public function save() {
		if ($guid = parent::save()) {
			update_entity_last_action($this->container_guid);
			if (!isset($this->priority)) {
				$this->priority = 0;
			}
		}
		return $guid;
	}

	public function getURL() {
		$friendly_title = elgg_get_friendly_title($this->title);
		return elgg_normalize_url("gallery/view/$this->guid/$friendly_title");
	}

}