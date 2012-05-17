<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');

$file = get_entity($entity->image);

if ($full) {
	$subtitle = elgg_view('object/hjalbumimage/elements/subtitle', $vars);
}

if (!$full || $list_type == 'gallery') {
	$title = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
		'is_trusted' => true,
		'class' => 'hj-image-title'
			));
} else {
	$title = elgg_view_title($entity->title, array(
		'class' => 'hj-image-title'
	));
}

if ($full) {
	$description = $entity->description;
} else {
	//$description = elgg_get_excerpt($entity->description, 100);
	$description = '';
}

$description = elgg_view('output/longtext', array(
	'value' => $description
		));

$content = <<<HTML
	<div class="hj-image-container">
		$album
	</div>
	<div class="hj-album-description">
		$description
	</div>
HTML;

$params = elgg_clean_vars($vars);
$params = hj_framework_extract_params_from_entity($entity, $params);

$params['target'] = "hj-image-full";

$header_menu = elgg_view_menu('hjentityhead', array(
	'entity' => $entity,
	'handler' => 'hjfile',
	'file_guid' => $file->guid,
	'current_view' => $full,
	'class' => 'elgg-menu-hz hj-menu-hz',
	'sort_by' => 'priority',
	'params' => $params,
	'has_full_view' => false
		));

$footer_menu = elgg_view_menu('hjentityfoot', array(
	'entity' => $entity,
	'handler' => 'hjfile',
	'file_guid' => $file->guid,
	'current_view' => $full,
	'class' => 'elgg-menu-hz hj-menu-hz',
	'sort_by' => 'priority',
	'params' => $params,
		));

$params = array(
	'entity' => $entity,
	'title' => $title,
	'metadata' => $header_menu,
	'subtitle' => $by . $subtitle,
	'content' => $content . $footer_menu
);

if ($list_type != 'list') {
	$params['metadata'] = false;
}
$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

if (!$full) {
	$icon = elgg_view_entity_icon($entity, elgg_extract('icon_size', $vars, 'small'));
}
echo elgg_view_image_block($icon, $list_body);