<?php

$shortcuts = hj_framework_path_shortcuts('hypeGallery');

// Actions
elgg_register_action('edit/object/hjalbum', $shortcuts['actions'] . 'edit/object/hjalbum.php');
elgg_register_action('edit/object/hjalbumimage', $shortcuts['actions'] . 'edit/object/hjalbumimage.php');

elgg_register_action('gallery/order/images', $shortcuts['actions'] . 'order/images.php');

elgg_register_action('gallery/upload', $shortcuts['actions'] . 'upload/upload.php');