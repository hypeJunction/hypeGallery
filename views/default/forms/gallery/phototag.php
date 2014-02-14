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

$form_body .= '<div class="gallery-autocomplete"><i class="gallery-icon-tag"></i><span>' . elgg_view('input/text', array(
			'maxlength' => '50',
			'name' => 'title',
			'class' => 'gallery-tag-autocomplete'
		)) . '</span></div>';


$form_body .= '<div class="gallery-autocomplete"><i class="gallery-icon-friend"></i><span><img class="tagged-user-preview"/>' . elgg_view('input/text', array(
			'maxlength' => '50',
			'class' => 'gallery-friend-autocomplete',
		)) . elgg_view('input/hidden', array(
			'name' => 'relationship_guid',
		)) . '</span></div>';

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('gallery:image:tag:create')));

echo $form_body;
