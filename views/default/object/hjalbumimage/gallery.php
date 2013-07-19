<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbumimage')) {
	return true;
}

if ($full) {
	echo elgg_view('object/hjalbumimage/list', $vars);
	return true;
}

$title = elgg_view('output/url', array(
	'text' => $entity->title,
	'href' => $entity->getURL(),
	'title' => elgg_strip_tags($entity->description),
	'is_trusted' => true
		));

$icon = elgg_view_entity_icon($entity, '325');

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

$info_link = elgg_view('output/url', array(
	'text' => elgg_view_icon('info'),
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
));
$info .= elgg_view('object/hjalbumimage/meta', $vars);

$album = elgg_view_entity_icon($entity->getContainerEntity(), 'small');

$html = <<<__HTML
	<div class="gallery-media-summary">
		$icon
		<div class="gallery-media-meta">
			<div class="gallery-media-album">$album</div>
			<div class="gallery-media-author">$author</div>
			<div class="gallery-media-title">$title</div>
			<div class="gallery-media-info-link">$info_link</div>
		</div>
		<div id="gallery-info-$entity->guid" class="gallery-media-extras hidden">
			$info
		</div>
	</div>
__HTML;

echo $html;
