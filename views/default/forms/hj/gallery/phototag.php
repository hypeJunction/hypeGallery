<?php
echo elgg_view('input/hidden', array('value' => $vars['image']->guid, 'name' => 'image_guid'));
echo elgg_view('input/hidden', array('value' => htmlentities(json_encode($vars['params']), ENT_QUOTES, 'UTF-8'), 'name' => 'params'));

$entity = elgg_extract('entity', $vars);

$hidden = array('x1', 'x2', 'y1', 'y2', 'w', 'h');

foreach ($hidden as $field) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => $field,
		'value' => $vars['tag']->$field
	));
}

$form_body .= elgg_view('input/text', array(
	'maxlength' => '50',
	'name' => 'title'
));

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('hj:album:image:tag:create'), 'class' => 'hidden'));

echo $form_body;
?>
<script type="text/javascript">
	elgg.register_hook_handler('success', 'hj:framework:ajax', hj.gallery.tagger.init);
	elgg.trigger_hook('success', 'hj:framework:ajax');
</script>