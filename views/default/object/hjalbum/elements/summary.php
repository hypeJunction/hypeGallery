<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');

$context = elgg_get_context();

if (!$full || $list_type == 'gallery') {
	$title = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
		'is_trusted' => true,
		'class' => 'hj-album-title'
			));
} else {
	$title = elgg_view_title($entity->title, array(
		'class' => 'hj-album-title'
	));
}
$owner = $entity->getOwnerEntity();
$by = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL(),
	'class' => 'hj-album-author'
		));
$by = elgg_echo('hj:gallery:album:author', array($by));

$count = elgg_view('output/text', array(
	'value' => elgg_echo('hj:gallery:album:photos', array(elgg_extract('image_count', $vars, 0)))
		));

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
	<div class="hj-album-photo-count">
		$count
	</div>
	<div class="hj-album-description">
		$description
	</div>
HTML;

$params = elgg_clean_vars($vars);
$params = hj_framework_extract_params_from_entity($entity, $params);

$params['target'] = "hj-gallery-album-full";
$params['has_full_view'] = false;

$header_menu = elgg_view_menu('hjentityhead', array(
	'entity' => $entity,
	'current_view' => $full,
	'class' => 'elgg-menu-hz hj-menu-hz',
	'sort_by' => 'priority',
	'params' => $params
		));

$footer_menu = elgg_view_menu('hjentityfoot', array(
	'entity' => $entity,
	'current_view' => $full,
	'class' => 'elgg-menu-hz hj-menu-hz',
	'sort_by' => 'priority',
	'params' => $params,
		));

$params = array(
	'entity' => $entity,
	'title' => $title,
	'metadata' => $header_menu,
	'subtitle' => $by,
	'content' => $content . $footer_menu
);

if ($list_type != 'list') {
	$params['metadata'] = false;
}
$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo $list_body;