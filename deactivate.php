<?php

namespace hypeJunction\Gallery;

$subtypes = array(hjAlbum::SUBTYPE, hjAlbumImage::SUBTYPE);

foreach ($subtypes as $subtype) {
	update_subtype('object', $subtype);
}
