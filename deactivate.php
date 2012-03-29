<?php

$subtypes = array(
	'hjalbum' => 'hjAlbum',
	'hjalbumimage' => 'hjAlbumImage'
);

foreach ($subtypes as $subtype => $class) {
	update_subtype('object', $subtype);
}
