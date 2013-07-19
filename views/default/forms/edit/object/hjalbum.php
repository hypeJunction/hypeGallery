<?php

$form_name = 'edit:object:hjalbum';
$params = elgg_clean_vars($vars);

$form_settings = elgg_trigger_plugin_hook('init', "form:$form_name", $params, array());

$fields = elgg_extract('fields', $form_settings, array());

foreach ($fields as $name => $options) {
	
	if (!$options)
		continue;

	$input_type = elgg_extract('input_type', $options, 'text');
	$label = elgg_extract('label', $options, elgg_echo("$form_name:$name"));
	$hint = elgg_extract('hint', $options, false);
	$required = elgg_extract('required', $options, false);
	if (!isset($options['name'])) {
		$options['name'] = $name;
	}
	
	unset($options['input_type']);
	unset($options['label']);
	unset($options['hint']);
	unset($options['fieldset']);

	if ($input_type !== 'hidden') {
		$view = '<div class="elgg-input-wrapper">';
		$view .= ($label) ? "<label>$label</label>" : '';
		$view .= ($required) ? '<i class="elgg-input-required"></i>' : '';
		$view .= elgg_view("input/$input_type", $options);
		$view .= ($hint) ? "<span class=\"elgg-text-help\">$hint</span>" : '';
		$view .= '</div>';
	} else {
		$view = elgg_view('input/hidden', $options);
	}

	if (!in_array($name, array('title', 'gallery_files'))) {
		$details[] = $view;
	} else {
		$main[] = $view;
	}
}

echo '<div class="gallery-edit-form">';
echo implode('', $main);
echo elgg_view('output/url', array(
	'text' => elgg_echo('hj:gallery:edit:details'),
	'href' => '#gallery-hidden-details',
	'rel' => 'toggle'
));
echo '<div id="gallery-hidden-details" class="hidden">';
echo implode('', $details);
echo '</div>';
echo '</div>';