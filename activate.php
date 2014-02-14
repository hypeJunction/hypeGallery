<?php

$subtypes = array(
	'hjalbum' => 'hypeJunction\\Gallery\\hjAlbum',
	'hjalbumimage' => 'hypeJunction\\Gallery\\hjAlbumImage',
	'hjimagetag' => ''
);

foreach ($subtypes as $subtype => $class) {
	if (get_subtype_id('object', $subtype)) {
		update_subtype('object', $subtype, $class);
	} else {
		add_subtype('object', $subtype, $class);
	}
}