<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage') || !$entity->canEdit()) {
	return;
}

elgg_load_css('jquery.imgareaselect');
elgg_load_js('jquery.imgareaselect');
elgg_load_js('gallery.cropper.js');

echo elgg_view_form('gallery/thumb', array(), $vars);
