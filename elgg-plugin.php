<?php

require_once __DIR__ . '/lib/functions.php';

return [
	'plugin' => [
		'name' => 'hypeGallery',
		'version' => '7.0.0',
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
		[
			'type' => 'object',
			'subtype' => 'hjimagetag',
		],
	],

	'capabilities' => [
		'searchable' => [
			'object:hjalbum' => true,
			'object:hjalbumimage' => true,
		],
		'river_emittable' => [
			'object:hjalbum' => true,
			'object:hjalbumimage' => true,
			'object:hjimagetag' => true,
		],
	],

	'actions' => [
		'edit/object/hjalbum' => [],
		'edit/object/hjalbumimage' => [],
		'gallery/delete/object' => ['filename' => __DIR__ . '/actions/delete/object.php'],
		'gallery/order/images' => ['filename' => __DIR__ . '/actions/order/images.php'],
		'gallery/upload' => ['filename' => __DIR__ . '/actions/upload/upload.php'],
		'gallery/upload/filedrop' => ['filename' => __DIR__ . '/actions/upload/filedrop.php'],
		'gallery/upload/handle' => ['filename' => __DIR__ . '/actions/upload/handle.php'],
		'gallery/upload/describe' => ['filename' => __DIR__ . '/actions/upload/describe.php'],
		'gallery/approve/image' => ['filename' => __DIR__ . '/actions/approve/image.php'],
		'gallery/makeavatar' => ['filename' => __DIR__ . '/actions/addons/avatar.php'],
		'gallery/makecover' => ['filename' => __DIR__ . '/actions/addons/cover.php'],
		'gallery/phototag' => ['filename' => __DIR__ . '/actions/addons/phototag.php'],
		'gallery/thumb' => ['filename' => __DIR__ . '/actions/addons/thumb.php'],
		'gallery/thumb_reset' => ['filename' => __DIR__ . '/actions/addons/thumb_reset.php'],
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
