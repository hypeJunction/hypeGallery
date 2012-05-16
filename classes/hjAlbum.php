<?php

class hjAlbum extends hjFileFolder {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjalbum';
	}

}