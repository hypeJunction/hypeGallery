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

	public function getEditURL() {
		return "gallery/edit/$this->guid";
	}

	public function getDeleteURL() {
		return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/framework/delete/object?guid=$this->guid");
	}

	public function getDownloadURL() {
		return elgg_normalize_url("framework/download/$this->guid");
	}
}