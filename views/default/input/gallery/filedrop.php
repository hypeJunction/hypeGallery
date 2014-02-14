<?php

namespace hypeJunction\Gallery;

if (elgg_view_exists('input/dropzone')) {
	echo elgg_view('input/dropzone', array(
		'name' => 'gallery-files',
		'accept' => "image/*",
		'max' => 100,
		'multiple' => true,
	));
} else {
	echo elgg_view('input/file', array(
		'multiple' => true,
		'name' => 'gallery_files[]',
		'accept' => "image/*",
	));
}
