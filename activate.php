<?php

namespace hypeJunction\Gallery;

require_once __DIR__ . '/autoloader.php';

$subtypes = array(
	hjAlbum::SUBTYPE => get_class(new hjAlbum),
	hjAlbumImage::SUBTYPE => get_class(new hjAlbumImage),
	'hjimagetag' => ''
);

foreach ($subtypes as $subtype => $class) {
	if (!update_subtype('object', $subtype, $class)) {
		add_subtype('object', $subtype, $class);
	}
}