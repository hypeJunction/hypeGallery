<?php

namespace hypeJunction\Gallery;

if (\elgg_view_exists('input/dropzone')) {
	echo \elgg_view('input/dropzone', [
		'name' => 'gallery_files',
		'accept' => 'image/*',
		'max' => 100,
		'multiple' => true,
		'action' => 'action/gallery/upload/filedrop',
		'container_guid' => $vars['entity']->guid,
	]);
} else {
	echo \elgg_view('input/file', [
		'multiple' => true,
		'name' => 'gallery_files[]',
		'accept' => 'image/*',
	]);
}
