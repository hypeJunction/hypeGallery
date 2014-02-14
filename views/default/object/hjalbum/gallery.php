<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$size = elgg_extract('size', $vars, '325x200');

if (!elgg_instanceof($entity, 'object', 'hjalbum')) {
	return true;
}

$title = elgg_view('output/url', array(
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => elgg_strip_tags($entity->description),
	'is_trusted' => true
		));

$count = $entity->getContainedFiles(array('count' => true));
if ($count) {
	$cover = elgg_view_entity_icon($entity, $size);
	elgg_load_js('gallery.popup.js');
	$cover .= elgg_view('output/url', array(
		'text' => '<i class="gallery-icon-slideshow gallery-icon-small"></i>',
		'title' => elgg_echo('gallery:slideshow'),
		'class' => 'gallery-popup',
		'href' => '#',
		'data-guid' => $entity->guid
	));
} else {
	$cover = "<div class=\"gallery-album-cover-placeholder\" style=\"display:block;width:100%;height:200px;\"></div>";
}

$info_link = elgg_view('output/url', array(
	'text' => '<i class="gallery-icon-info icon-small"></i>',
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
		));
$info .= elgg_view('object/hjalbum/meta', $vars);
$info .= elgg_view('output/longtext', array(
	'value' => $entity->description,
	'class' => 'pam'
		));

$menu = elgg_view_menu('entity', array(
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'gallery-media-menu',
		));


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

if (!$full) {
	$html = <<<__HTML
	<div class="gallery-album-cover">
		$cover
		<div class="gallery-album-meta">
			<div class="gallery-album-count">$count</div>
			<div class="gallery-album-author">$author</div>
			<div class="gallery-album-title">$title</div>
			<div class="gallery-album-info-link">$info_link</div>
			
		</div>
		<div id="gallery-info-$entity->guid" class="gallery-media-extras hidden">
			$info$menu
		</div>
	</div>
__HTML;
} else {
	$html = <<<__HTML
	<div class="gallery-album-cover">
		$cover
		<div class="gallery-album-meta">
			<div class="gallery-album-count">$count</div>
			<div class="gallery-album-author">$author</div>
			<div class="gallery-album-title">$title</div>
			$info
		</div>
	</div>
__HTML;
}
echo $html;
