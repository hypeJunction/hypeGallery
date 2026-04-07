<?php

use hypeJunction\Gallery\hjAlbum;
use hypeJunction\Gallery\hjAlbumImage;

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => hjAlbum::SUBTYPE,
			'class' => hjAlbum::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => hjAlbumImage::SUBTYPE,
			'class' => hjAlbumImage::class,
			'searchable' => true,
		],
	],
];
