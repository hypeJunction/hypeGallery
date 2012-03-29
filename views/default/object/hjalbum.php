<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!$entity) {
	return true;
}
elgg_push_context('album');

$images = $entity->getContainedFiles('hjalbumimage');
$owner = $entity->getOwnerEntity();

//$title = elgg_view('output/url', array(
//	'text' => $entity->title,
//	'href' => $entity->getURL()
//		));

$title = $entity->title;

$by = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL()
));

$by = elgg_echo('hj:gallery:album:author', array($by));

$content = elgg_get_excerpt($entity->description, 50);

if ($entity->location && $entity->location !== '0,0') {
	$subtitle[] = elgg_view('output/location', array('entity' => $entity));
}

if ($entity->date) {
	$subtitle[] = elgg_view('output/date', array('value' => $entity->date));
}

if ($full) {
	if ($entity->copyright) {
		$subtitle[] = elgg_view('output/text', array('value' => $$entity->copyright));
	}
	if ($entity->friend_tags) {
		$subtitle[] = elgg_view('output/relationship_tags', array('value' => $entity->friend_tags, 'entity' => $entity));
	}
}

$subtitle = implode('<br />', $subtitle);

$icon = elgg_view_entity_icon($entity, 'large', array('href' => false));

if ($full) {

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

	$icons = elgg_view_entity_list($images, array(
		'list_type' => 'gallery',
		'gallery_class' => 'hj-gallery-album-images',
		'thumb_size' => 'large',
		'full_view' => false,
		'list_id' => "album-images-$entity->guid",
		'data-options' => htmlentities(json_encode($params), ENT_QUOTES, 'UTF-8')
			));

	if ($entity->canWriteToContainer()) {
		//$icons .= elgg_view('hj/gallery/imageplaceholder', $params);
	}

	$params = array(
		'entity' => $entity,
		'title' => $title,
		'metadata' => $header_menu,
		'subtitle' => $by . '<br />' . $subtitle,
		'content' => $content . $icons,
		'class' => 'hj-portfolio-widget'
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block(null, $list_body);
} else if (!elgg_in_context('activity')) {
	$html = <<<HTML
	<span class="hj-album-title">$title</span><br />
	<span class="hj-album-icon">$icon</span><br />
	<span class="hj-album-subtitle">$subtitle</span><br />
	<span class="hj-album-description">$content</span>
HTML;

	if (elgg_in_context('gallery') || elgg_is_xhr()) {
		$params = elgg_clean_vars($vars);
		$params = hj_framework_extract_params_from_entity($entity, $params);

		$params['target'] = "hj-gallery-album-full";
		$params['full_view'] = true;
		$params['has_full_view'] = false;

		$data = hj_framework_json_query($params);

		$fullview = array(
			'title' => $entity->title,
			'text' => $html,
			'href' => "action/framework/entities/view?e=$entity->guid",
			'data-options' => $data,
			'class' => 'hj-ajaxed-view'
		);

		$html = elgg_view('output/url', $fullview);
	}

	echo $html;
} else {
	$icons = elgg_view_entity_list($images, array(
		'list_type' => 'gallery',
		'gallery_class' => 'hj-gallery-album-images',
		'thumb_size' => 'small',
		'full_view' => false,
		'limit' => 5,
		'pagination' => false
			));

	$html = <<<HTML
	<span class="hj-album-title">$title</span><br /><span class="hj-album-author">$by</span><br />
	<span class="hj-album-description">$content</span><br />
        <span class="hj-album-icon">$icons</span>
HTML;

	echo $html;
}

elgg_pop_context();