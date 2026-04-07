<?php

return [
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'hjalbum',
			'class' => \hypeJunction\Gallery\hjAlbum::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'hjalbumimage',
			'class' => \hypeJunction\Gallery\hjAlbumImage::class,
			'searchable' => true,
		],
	],
];
