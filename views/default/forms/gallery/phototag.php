<?php

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

$form_body .= elgg_view('input/text', array(
	'maxlength' => '50',
	'name' => 'title'
));

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('hj:album:image:tag:create'), 'class' => 'hidden'));

echo $form_body;