<?php

namespace hypeJunction\Gallery;

if (!HYPEGALLERY_TAGGING) {
	return;
}

elgg_load_js('jquery.form');
elgg_load_js('gallery.tagger.js');


$entity = elgg_extract('entity', $vars);


echo '<div class="gallery-media-tags">';

if ($entity->canEdit()) {
	echo elgg_view('output/url', array(
		'text' => '<i class="icon-tags"></i>',
		'title' => elgg_echo('gallery:tools:tagger:start'),
		'href' => '#',
		'class' => 'elgg-button-gallery-tagger'
	));

	echo '<div class="tagger-float-template hidden">';
	echo '<div class="tagger-circle"></div>';
	echo '<div class="tagger-close hidden"></div>';
	echo elgg_view_form('gallery/phototag', array(), array(
		'entity' => $entity
	));
	echo '</div>';
}

echo "<div class=\"elgg-gallery gallery-tags-list\" data-guid=\"$entity->guid\">";
$tags = get_image_tags($entity);
if ($tags) {
	echo '<label>' . elgg_echo('gallery:inthisphoto') . '</label>';
	foreach ($tags as $tag) {
		echo elgg_view_entity($tag);
	}
}
echo '</div>';
echo '</div>';
