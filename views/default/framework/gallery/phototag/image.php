<?php

elgg_load_css('jquery.imgareaselect');
elgg_load_js('jquery.imgareaselect');
elgg_load_js('gallery.tagger.js');

$entity = elgg_extract('entity', $vars);

$master = new ElggFile();
$master->owner_guid = $entity->owner_guid;
$master->setFilename("icons/{$entity->guid}master.jpg");
$master_dimensions = getimagesize($master->getFilenameOnFilestore());

$params = array(
	'src' => $entity->getIconURL('master'),
	'class' => 'elgg-photo centered elgg-state-taggable',
	'style' => 'display:block;',
	'data-uid' => $entity->guid,
	'data-originalwidth' => $master_dimensions[0],
	'data-originalheight' => $master_dimensions[1]
);

echo '<div class="hj-gallery-tagger-wrapper">';

echo elgg_view('output/img', $params);

echo elgg_view_form('gallery/phototag', array(
	'class' => 'hj-gallery-tag-save hidden',
	'data-uid' => $entity->guid
		), $vars);

echo elgg_view('framework/gallery/phototag/elements/map', $vars);

echo '</div>';

echo elgg_view('framework/gallery/phototag/elements/list', $vars);