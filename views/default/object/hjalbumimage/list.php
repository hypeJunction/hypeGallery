<?php

$entity = elgg_extract('entity', $vars, false);
$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');

if (!elgg_instanceof($entity)) {
	return true;
}

if (!isset($vars['icon_size']) && (elgg_in_context('activity') || elgg_in_context('widgets'))) {
	$vars['icon_size'] = 'medium';
}

if ($full && $list_type !== 'carousel') {
	$nav = elgg_view('object/hjalbumimage/elements/navigation', $vars);
}
$summary = elgg_view('object/hjalbumimage/elements/summary', $vars);
$tags = elgg_view('object/hjalbumimage/elements/tags', $vars);
$tags_map = elgg_view('object/hjalbumimage/elements/tags_map', $vars);
$comments = elgg_view('object/hjalbumimage/elements/comments', $vars);

$icon_full = elgg_view_entity_icon($entity, 'master', array('class' => 'hj-gallery-taggable', 'href' => false));

if ($full) {
	$html = <<<HTML
	$summary
	$nav
	$tags
	<div id="hj-image-master" class="hj-file-icon-preview hj-file-icon-master">
		<div class="hj-file-icon-background">
			$icon_full
			$tags_map
		</div>
	</div>
	$comments
HTML;
} else {
	$html = $summary;
}

if ($full) {
	echo "<div id=\"hj-gallery-replaceable-$entity->guid\">$html</div>";
} else {
	echo $html;
}