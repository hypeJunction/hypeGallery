<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$size = elgg_extract('size', $vars, '325');

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

$cover = elgg_view_entity_icon($entity, $size);

if (!elgg_in_context('gallery-manage')) {
	elgg_load_js('gallery.popup.js');
	$cover .= elgg_view('output/url', array(
		'text' => '<i class="gallery-icon-slideshow gallery-icon-small"></i>',
		'title' => elgg_echo('hj:gallery:slideshow'),
		'class' => 'gallery-popup',
		'href' => '#',
		'data-guid' => $entity->guid
			));
}

$title = elgg_view('output/url', array(
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => elgg_strip_tags($entity->description),
	'is_trusted' => true
		));

$info_link = elgg_view('output/url', array(
	'text' => '<i class="gallery-icon-info icon-small"></i>',
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
		));

$menu = elgg_view_menu('entity', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'gallery-media-menu',
		));

if (elgg_in_context('gallery-manage')) {
	$info .= elgg_view('forms/edit/object/hjalbumimage', $vars);
	$alt_menu = $menu;
} else {
	$info .= elgg_view('object/hjalbum/meta', $vars);
	$info .= elgg_view('output/longtext', array(
		'value' => $entity->description,
		'class' => 'pam'
			));
}


$owner = get_entity($entity->owner_guid);
if ($owner) {
	$author = elgg_view('output/url', array(
		'text' => elgg_view('output/img', array(
			'src' => $owner->getIconURL('small'),
		)),
		'href' => $owner->getURL(),
		'title' => $owner->name
			));
}

$container = get_entity($entity->container_guid);
if (elgg_instanceof($container, 'object', 'hjalbum')) {
	$album = elgg_view_entity_icon($container, 'small');
}
if (!$full) {
	$html = <<<__HTML
	<div class="gallery-media-cover">
		$alt_menu$cover
		<div class="gallery-media-meta">
			<div class="gallery-media-album">$album</div>
			<div class="gallery-media-author">$author</div>
			<div class="gallery-media-title">$title</div>
			<div class="gallery-media-info-link">$info_link</div>

		</div>
		<div id="gallery-info-$entity->guid" class="gallery-media-extras hidden">
			$info$menu
		</div>
	</div>
__HTML;
} else {
	$html = <<<__HTML
	<div class="gallery-media-cover">
		$alt_menu$cover
		<div class="gallery-media-meta">
			<div class="gallery-media-album">$album</div>
			<div class="gallery-media-author">$author</div>
			<div class="gallery-media-title">$title</div>
			$info
		</div>
	</div>
__HTML;
}
echo $html;
