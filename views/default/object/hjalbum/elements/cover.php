<?php

$entity = elgg_extract('entity', $vars);
$thumb_size = elgg_extract('thumb_size', $vars, 'large');
$list_type = elgg_extract('list_type', $vars, 'list');
$full = elgg_extract('full_view', $vars, false);

if (elgg_in_context('activity') || elgg_in_context('main') || elgg_in_context('widgets')) {
	$thumb_size = 'medium';
}
$icon = elgg_view_entity_icon($entity, $thumb_size, array(
	'class' => 'hj-album-cover-thumb'
));

$summary = elgg_view('object/hjalbum/elements/summary', $vars);
$subtitle = elgg_view('object/hjalbum/elements/subtitle', $vars);

if (elgg_in_context('activity') || elgg_in_context('main') || elgg_in_context('widgets')) {
	$html = elgg_view_image_block($icon, $summary . $subtitle, array(
		'class' => 'hj-album-cover-image-block'
	));
} else if ($list_type == 'gallery') {
	$html = <<<__COVER
		<div class="hj-album-cover-gallery">
			$icon
			$summary
			$subtitle
		</div>
__COVER;
} else {
	$html = $summary . $subtitle;
}

echo $html;