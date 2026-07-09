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

	'routes' => [
		// Dashboard landing — the gallery root the site menu points at.
		'collection:object:hjalbum:site' => ['path' => '/gallery', 'file' => __DIR__ . '/pages/gallery/dashboard/site.php'],
		'collection:object:hjalbum:dashboard' => ['path' => '/gallery/dashboard/site', 'file' => __DIR__ . '/pages/gallery/dashboard/site.php'],
		'collection:object:hjalbum:owner' => ['path' => '/gallery/dashboard/owner/{username}', 'file' => __DIR__ . '/pages/gallery/dashboard/owner.php'],
		'collection:object:hjalbum:friends' => ['path' => '/gallery/dashboard/friends/{username}', 'file' => __DIR__ . '/pages/gallery/dashboard/friends.php'],
		'collection:object:hjalbum:groups' => ['path' => '/gallery/dashboard/groups/{username}', 'file' => __DIR__ . '/pages/gallery/dashboard/groups.php'],
		'collection:object:hjalbum:favorites' => ['path' => '/gallery/dashboard/favorites/{username}', 'file' => __DIR__ . '/pages/gallery/dashboard/favorites.php'],
		'collection:object:hjalbum:group' => ['path' => '/gallery/group/{guid}', 'file' => __DIR__ . '/pages/gallery/dashboard/group.php'],
		'collection:object:hjalbum:container' => ['path' => '/gallery/container/{guid}', 'file' => __DIR__ . '/pages/gallery/dashboard/container.php'],
		'view:object:hjalbum' => ['path' => '/gallery/view/{guid}/{title?}', 'file' => __DIR__ . '/pages/gallery/view/object.php'],
		'edit:object:hjalbum' => ['path' => '/gallery/edit/{guid}', 'file' => __DIR__ . '/pages/gallery/edit/object.php', 'middleware' => [\Elgg\Router\Middleware\Gatekeeper::class]],
		'collection:object:hjalbum:manage' => ['path' => '/gallery/manage/{guid}', 'file' => __DIR__ . '/pages/gallery/manage/album.php', 'middleware' => [\Elgg\Router\Middleware\Gatekeeper::class]],
		'add:object:hjalbum' => ['path' => '/gallery/create/album/{container_guid}', 'file' => __DIR__ . '/pages/gallery/create/album.php', 'middleware' => [\Elgg\Router\Middleware\Gatekeeper::class]],
		'collection:object:hjalbumimage:upload' => ['path' => '/gallery/upload/{container_guid}', 'file' => __DIR__ . '/pages/gallery/upload/upload.php', 'middleware' => [\Elgg\Router\Middleware\Gatekeeper::class]],
		'view:object:hjalbumimage:thumb' => ['path' => '/gallery/thumb/{guid}', 'file' => __DIR__ . '/pages/gallery/thumb/thumb.php', 'middleware' => [\Elgg\Router\Middleware\Gatekeeper::class]],
		'view:object:hjalbumimage:icon' => ['path' => '/gallery/icon/{guid}/{size?}', 'file' => __DIR__ . '/pages/gallery/icon/icon.php', 'defaults' => ['size' => 'master']],
		'view:object:hjalbumimage:download' => ['path' => '/gallery/download/{guid}', 'file' => __DIR__ . '/pages/gallery/file/download.php'],
		'gallery:livesearch' => ['path' => '/gallery/livesearch', 'file' => __DIR__ . '/pages/gallery/search/livesearch.php'],
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
