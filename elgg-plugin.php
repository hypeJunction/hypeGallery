<?php

return [
	'plugin' => [
		'name' => 'hypeGallery',
		'version' => '4.0.0',
		'activate_on_install' => false,
	],

	'bootstrap' => \hypeJunction\Gallery\Bootstrap::class,

	'upgrades' => [
		\hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson::class,
	],

	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'hjalbum',
			'class' => \hypeJunction\Gallery\hjAlbum::class,
		],
		[
			'type' => 'object',
			'subtype' => 'hjalbumimage',
			'class' => \hypeJunction\Gallery\hjAlbumImage::class,
		],
	],

	'capabilities' => [
		'searchable' => [
			'object:hjalbum' => true,
			'object:hjalbumimage' => true,
		],
	],

	'actions' => [
		'edit/object/hjalbum' => [],
		'edit/object/hjalbumimage' => [],
		'gallery/delete/object' => [],
		'gallery/order/images' => [],
		'gallery/upload' => [],
		'gallery/upload/filedrop' => [],
		'gallery/upload/handle' => [],
		'gallery/upload/describe' => [],
		'gallery/approve/image' => [],
		'gallery/makeavatar' => [],
		'gallery/makecover' => [],
		'gallery/phototag' => [],
		'gallery/thumb' => [],
		'gallery/thumb_reset' => [],
	],

	'widgets' => [
		'photostream' => [
			'context' => ['profile', 'dashboard', 'groups'],
		],
		'albums' => [
			'context' => ['profile', 'dashboard', 'groups'],
		],
	],

	'view_extensions' => [
		'elgg.css' => [
			'css/framework/gallery/base' => [],
		],
		'object/hjalbumimage/meta' => [
			'object/hjalbumimage/exif' => [],
		],
		'framework/gallery/sidebar' => [
			'framework/gallery/tools/tagger' => [],
		],
	],
];
