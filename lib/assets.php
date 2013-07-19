<?php

// CSS and JS
elgg_register_simplecache_view('css/framework/gallery/base');
elgg_register_css('gallery.base.css', elgg_get_simplecache_url('css', 'framework/gallery/base'));

elgg_register_simplecache_view('js/framework/gallery/base');
elgg_register_js('gallery.base.js', elgg_get_simplecache_url('js', 'framework/gallery/base'));

elgg_register_simplecache_view('js/framework/gallery/popup');
elgg_register_js('gallery.popup.js', elgg_get_simplecache_url('js', 'framework/gallery/popup'));

elgg_register_simplecache_view('js/framework/gallery/cropper');
elgg_register_js('gallery.cropper.js', elgg_get_simplecache_url('js', 'framework/gallery/cropper'));

elgg_register_simplecache_view('js/framework/gallery/tagger');
elgg_register_js('gallery.tagger.js', elgg_get_simplecache_url('js', 'framework/gallery/tagger'));

// FILEDROP
elgg_register_js('jquery.filedrop.js', 'mod/hypeGallery/vendors/filedrop/jquery.filedrop.js', 'footer');

elgg_register_simplecache_view('js/framework/gallery/filedrop');
elgg_register_js('gallery.filedrop.js', elgg_get_simplecache_url('js', 'framework/gallery/filedrop'), 'footer');

elgg_register_simplecache_view('css/framework/gallery/filedrop');
elgg_register_css('gallery.filedrop.css', elgg_get_simplecache_url('css', 'framework/gallery/filedrop'));