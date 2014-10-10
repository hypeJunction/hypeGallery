<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);

$form_body .= elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $entity->guid
		));

$hidden = array('x1', 'x2', 'y1', 'y2', 'w', 'h');

foreach ($hidden as $field) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => $field,
		'value' => $vars['tag']->$field
	));
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'access_id',
	'value' => $entity->access_id
		));

$form_body .= '<label>' . elgg_echo('gallery:image:tag:keyword') . '</label>';
if (elgg_is_active_plugin('elgg_tokeninput')) {
	$form_body .= elgg_view('input/tokeninput', array(
		'strict' => false,
		'multiple' => true,
		'callback' => 'elgg_tokeninput_search_tags',
		'name' => 'title',
	));
} else {
	$form_body .= elgg_view('input/tags', array(
		'name' => 'title',
	));
}

$form_body .= '<label>' . elgg_echo('gallery:image:tag:friend') . '</label>';
if (elgg_is_active_plugin('elgg_tokeninput')) {
	$form_body .= elgg_view('input/tokeninput', array(
		'callback' => 'elgg_tokeninput_search_friends',
		'name' => 'relationship_guid',
	));
} else {
	$form_body .= elgg_view('input/autocomplete', array(
		'match_on' => 'friends',
		'name' => 'relationship_guid',
	));
}

$preview_img = elgg_view('output/img', array(
	'src' => $entity->getIconURL('taggable'),
	'width' => 550
		));

$area_tagger_preview = '<div class="gallery-tagger-area-preview">' . $preview_img . '</div>';
$help = '<span class="elgg-text-help">' . elgg_echo('gallery:image:tag:instructions') . '</span>';
echo elgg_view_image_block($area_tagger_preview, $help);
echo $form_body;
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
