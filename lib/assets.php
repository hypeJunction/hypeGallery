<?php

// CSS and JS
elgg_register_css('gallery.base.css', elgg_get_simplecache_url('css', 'framework/gallery/base'));
elgg_register_simplecache_view('css/framework/gallery/base');

elgg_register_js('gallery.base.js', elgg_get_simplecache_url('js', 'framework/gallery/base'));
elgg_register_simplecache_view('js/framework/gallery/base');

elgg_register_js('gallery.cropper.js', elgg_get_simplecache_url('js', 'framework/gallery/cropper'));
elgg_register_simplecache_view('js/framework/gallery/cropper');

elgg_register_js('gallery.tagger.js', elgg_get_simplecache_url('js', 'framework/gallery/tagger'));
elgg_register_simplecache_view('js/framework/gallery/tagger');
