<?php

class hjAlbum extends hjFileFolder {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$attributes['subtype'] = 'hjalbum';
	}

}