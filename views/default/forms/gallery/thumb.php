<?php
$entity = elgg_extract('entity', $vars);

echo '<div class="gallery-crop-master-wrapper">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('master'),
	'id' => "gallery-crop-master"
));
echo '</div>';

echo '<div class="gallery-crop-preview-wrapper clearfix">';
echo '<span class="elgg-text-help">' . elgg_echo('hj:gallery:tools:crop:instructions') . '</span>';

echo '<div class="elgg-col elgg-col-1of2">';
echo '<label>' . elgg_echo('hj:gallery:tools:crop:preview') . '</label>';
echo '<div id="gallery-crop-preview">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('master')
));
echo '</div>';
echo '</div>';

echo '<div class="elgg-col elgg-col-1of2">';
echo '<label>' . elgg_echo('hj:gallery:tools:crop:current') . '</label>';
echo '<div id="gallery-crop-current">';
echo elgg_view('output/img', array(
	'src' => $entity->getIconUrl('medium')
));
echo '</div>';
echo '</div>';

echo '</div>';
?>

<div class="elgg-foot">
	<?php

	$coords = array('x1', 'x2', 'y1', 'y2');
	foreach ($coords as $coord) {
		echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
	}
	
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $entity->guid));

	echo elgg_view('input/submit', array('value' => elgg_echo('hj:gallery:tools:crop')));

	echo elgg_view('output/url', array(
		'text' => elgg_echo('hj:gallery:image:thumb:reset'),
		'href' => "action/gallery/thumb_reset?guid=$entity->guid",
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg-button elgg-button-action elgg-button-gallery-reset-thumb',
	));

	?>
</div>