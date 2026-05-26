<?php

namespace hypeJunction\Gallery;

$entity = \elgg_extract('entity', $vars);

if (!$entity instanceof hjAlbumImage || !$entity->canEdit()) {
	return;
}

\elgg_load_css('cropper');

echo \elgg_view_form('gallery/thumb', [], $vars);
