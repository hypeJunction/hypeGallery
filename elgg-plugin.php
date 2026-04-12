<?php

return [
	'upgrades' => [
		\hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson::class,
	],

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
