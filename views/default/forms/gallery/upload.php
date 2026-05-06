<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);
$time = time();

echo elgg_view('input/gallery/filedrop', [
	'entity' => $entity,
	'batch_upload_time' => $time
]);

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', [
	'value' => elgg_echo('save'),
	'class' => 'elgg-button elgg-button-submit float-alt'
]);
echo elgg_view('input/hidden', [
	'name' => 'batch_upload_time',
	'value' => $time
]);
echo elgg_view('input/hidden', [
	'name' => 'container_guid',
	'value' => $entity->guid
]);
echo '</div>';
