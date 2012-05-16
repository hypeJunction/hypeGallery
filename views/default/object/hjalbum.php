<?php

$entity = elgg_extract('entity', $vars, false);
if (!$entity) {
	$entity = get_entity(get_input('guid'));
}
$full = elgg_extract('full_view', $vars, false);

if (!$entity) {
	return true;
}
//elgg_push_context('album');

$images = $entity->getContainedFiles('hjalbumimage');
$owner = $entity->getOwnerEntity();

$title = elgg_view('output/url', array(
	'text' => $entity->title,
	'href' => $entity->getURL()
		));

$by = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL()
		));

$by = elgg_echo('hj:gallery:album:author', array($by));

$content = $entity->description . '<br />';

$subtitle = array();
if ($entity->location && $entity->location !== '0,0') {
	$subtitle[] = elgg_view('output/location', array('entity' => $entity));
}

if ($entity->date) {
	$subtitle[] = elgg_view('output/date', array('value' => $entity->date));
}

if ($entity->copyright) {
	$subtitle[] = elgg_view('output/text', array('value' => $$entity->copyright));
}
if ($entity->friend_tags) {
	$subtitle[] = elgg_view('output/relationship_tags', array('value' => $entity->friend_tags, 'entity' => $entity));
}


$subtitle = implode('<br />', $subtitle);

if ($full) {
	$icon = elgg_view_entity_icon($entity, 'large', array('href' => false, 'class' => 'hj-gallery-album-cover-large'));
} else {
	$icon = elgg_view_entity_icon($entity, 'medium', array('href' => false));
	$data = elgg_clean_vars($vars);
	$data = hj_framework_extract_params_from_params($data);
	$data['full_view'] = true;
	$data['fbox_x'] = '900';
	$data['target'] = '';
	$data = hj_framework_json_query($data);

	$icon = elgg_view('output/url', array(
		'class' => 'hj-ajaxed-view',
		'href' => "action/framework/entities/view?e=$entity->guid",
		'is_action' => true,
		'data-options' => $data,
		'rel' => 'fancybox',
		'text' => $icon
			));
}
$icon_small = elgg_view_entity_icon($entity, 'medium', array('href' => false));

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

$target = "hj-gallery-album-images-$entity->guid";
$params['target'] = $target;
$icons = elgg_view_entity_list($images, array(
	'list_type' => 'gallery',
	'gallery_class' => 'hj-gallery-album-images',
	'thumb_size' => 'medium',
	'full_view' => false,
	'list_id' => $target,
	'data-options' => $params,
	'pagination' => true,
	'limit' => 5,
	'position' => 'both'
		));

if ($entity->canWriteToContainer()) {
	//$icons .= elgg_view('hj/gallery/imageplaceholder', $params);
}


$album_cover = '<div>' . $icon . '</div>' . $content . $subtitle;

if ($full) {
	$params = array(
		'entity' => $entity,
		'title' => $title,
		'metadata' => $header_menu,
		'subtitle' => $by,
		'content' => elgg_view_image_block($album_cover, $icons)
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
} else {
	$params = array(
		'entity' => $entity,
		'title' => $title,
		'metadata' => $header_menu,
		'subtitle' => $by,
		'content' => elgg_view_image_block($icon, $content . $subtitle)
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	if (elgg_in_context('activity') || elgg_in_context('main')) {
		echo $icons;
	}
}
echo '<div class="clearfix">';
echo $list_body;
echo '</div>';
//elgg_pop_context();