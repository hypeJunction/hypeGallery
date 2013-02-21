<?php

$shortcuts = hj_framework_path_shortcuts('hypeGallery');

// Actions
elgg_register_action('edit/object/hjalbum', $shortcuts['actions'] . 'edit/object/hjalbum.php');
elgg_register_action('edit/object/hjalbumimage', $shortcuts['actions'] . 'edit/object/hjalbumimage.php');

elgg_register_action('gallery/order/images', $shortcuts['actions'] . 'order/images.php');

elgg_register_action('gallery/upload', $shortcuts['actions'] . 'upload/upload.php');

elgg_register_action('gallery/approve/image', $shortcuts['actions'] . 'approve/image.php');

elgg_register_action('gallery/makeavatar', $shortcuts['actions'] . 'addons/avatar.php');
elgg_register_action('gallery/makecover', $shortcuts['actions'] . 'addons/cover.php');
elgg_register_action('gallery/phototag', $shortcuts['actions'] . 'addons/phototag.php');
elgg_register_action('gallery/thumb', $shortcuts['actions'] . 'addons/thumb.php');
elgg_register_action('gallery/thumb_reset', $shortcuts['actions'] . 'addons/thumb_reset.php');