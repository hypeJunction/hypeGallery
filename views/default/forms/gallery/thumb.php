<?php
elgg_load_css('jquery.imgareaselect');
elgg_load_js('jquery.imgareaselect');
elgg_load_js('gallery.cropper.js');

$entity = elgg_extract('entity', $vars);

echo elgg_view('input/hidden', array('value' => $entity->guid, 'name' => 'guid'));

$master_img = elgg_view('output/img', array(
	'src' => $entity->getIconUrl('master'),
	'alt' => elgg_echo('avatar'),
	'class' => 'mrl',
	'id' => "hj-image-cropper"
		));

$preview_img = elgg_view('output/img', array(
	'src' => $vars['entity']->getIconUrl('master'),
	'alt' => elgg_echo('avatar'),
		));
?>
<div class="row-fluid">
	<div class="span9">
		<div id="hj-image-master">
			<?php echo $master_img; ?>
		</div>
	</div>
	<div class="offset1 span2">
		<div id="hj-image-preview-title"><label><?php echo elgg_echo('hj:album:image:preview'); ?></label></div>
		<div id="hj-image-preview"><?php echo $preview_img; ?></div>
	</div>
</div>
<div class="elgg-foot">
	<?php
	$coords = array('x1', 'x2', 'y1', 'y2');
	foreach ($coords as $coord) {
		echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
	}

	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));

	echo elgg_view('input/submit', array('value' => elgg_echo('hj:album:image:thumb:create')));
	echo elgg_view('output/url', array(
		'text' => elgg_echo('hj:album:image:thumb:reset'),
		'href' => "action/gallery/thumb_reset?guid=$entity->guid",
		'is_action' => true,
		'is_trusted' => true,
		'class' => 'elgg-button elgg-button-action'
	));
	echo elgg_view('input/button', array('value' => elgg_echo('cancel'), 'class' => 'elgg-button-cancel-trigger'));
	?>
</div>