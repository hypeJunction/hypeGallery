<?php

$entity = elgg_extract('entity', $vars);

$tags = hj_gallery_get_image_tags($entity);

$tags_view .= elgg_view_entity_list($tags, array(
	'return_type' => 'list',
	'list_type' => 'gallery',
	'list_id' => "hj-gallery-tags-list-$entity->guid",
	'gallery_class' => 'hj-gallery-tags-list',
		));

$tag_form_params = array(
	'target' => "hj-gallery-tags-list-$entity->guid"
);

if (!elgg_in_context('fancybox')) {
	$tags_view .= elgg_view_form('hj/gallery/phototag', array('id' => "hj-gallery-tagger-form", 'class' => 'hj-gallery-tag-save hidden'), array('entity' => $file, 'image' => $entity, 'params' => $tag_form_params));
}
$tags_view = '<div class="clearfix">' . $tags_view . '</div>';

echo $tags_view;