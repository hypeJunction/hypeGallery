<?php
echo elgg_view('input/hidden', array('value' => 'crop', 'name' => 'type'));
echo elgg_view('input/hidden', array('value' => $vars['image']->guid, 'name' => 'image_guid'));

$entity = elgg_extract('entity', $vars);

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
<div class="clearfix">
	<div id="hj-image-master">
		<?php echo $master_img; ?>
	</div>
	<div id="hj-image-preview-title"><label><?php echo elgg_echo('hj:album:image:preview'); ?></label></div>
	<div id="hj-image-preview"><?php echo $preview_img; ?></div>
</div>
<div class="elgg-foot">
<?php
$coords = array('x1', 'x2', 'y1', 'y2');
foreach ($coords as $coord) {
	echo elgg_view('input/hidden', array('name' => $coord, 'value' => $vars['entity']->$coord));
}

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));

echo elgg_view('input/submit', array('value' => elgg_echo('hj:album:image:thumb:create')));

?>
</div>

<script type="text/javascript">
	elgg.register_hook_handler('success', 'hj:framework:ajax', hj.gallery.cropper.init);
	elgg.trigger_hook('success', 'hj:framework:ajax');
</script>