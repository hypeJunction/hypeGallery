<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);

if (!elgg_instanceof($entity, 'object', 'hjalbum')) {
	return true;
}

if ($full) {
	echo elgg_view('object/hjalbum/list', $vars);
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
	$cover = elgg_view_entity_icon($entity, '325x200');
} else {
	$cover = "<div class=\"gallery-album-cover-placeholder\" style=\"display:block;width:325px;height:200px;\"></div>";
}

$info_link = elgg_view('output/url', array(
	'text' => elgg_view_icon('info'),
	'href' => "#gallery-info-$entity->guid",
	'rel' => 'toggle'
));
$info .= elgg_view('object/hjalbum/meta', $vars);


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
			$info
		</div>
	</div>
__HTML;

echo $html;
