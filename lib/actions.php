<?php

$path = elgg_get_plugins_path() . 'hypeGallery/actions/';

elgg_register_action('edit/object/hjalbum', $path . 'edit/object/hjalbum.php');
elgg_register_action('edit/object/hjalbumimage', $path . 'edit/object/hjalbumimage.php');

elgg_register_action('gallery/delete/object', $path . 'delete/object.php');

elgg_register_action('gallery/order/images', $path . 'order/images.php');

elgg_register_action('gallery/upload', $path . 'upload/upload.php');
elgg_register_action('gallery/upload/filedrop', $path . 'upload/filedrop.php');
elgg_register_action('gallery/upload/handle', $path . 'upload/handle.php');
elgg_register_action('gallery/upload/describe', $path . 'upload/describe.php');

elgg_register_action('gallery/approve/image', $path . 'approve/image.php');

elgg_register_action('gallery/makeavatar', $path . 'addons/avatar.php');
elgg_register_action('gallery/makecover', $path . 'addons/cover.php');
elgg_register_action('gallery/phototag', $path . 'addons/phototag.php');
elgg_register_action('gallery/thumb', $path . 'addons/thumb.php');
elgg_register_action('gallery/thumb_reset', $path . 'addons/thumb_reset.php');