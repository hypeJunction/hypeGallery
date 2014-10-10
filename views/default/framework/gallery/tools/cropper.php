<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'object', hjAlbumImage::SUBTYPE) || !$entity->canEdit()) {
	return;
}

elgg_load_css('cropper');

echo elgg_view_form('gallery/thumb', array(), $vars);
