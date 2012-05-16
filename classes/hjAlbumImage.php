<?php

class hjAlbumImage extends ElggObject {

    protected function initializeAttributes() {
        parent::initializeAttributes();
		$this->attributes['subtype'] = 'hjalbumimage';
    }

}