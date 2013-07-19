<?php

$form_name = 'edit:plugin:hypegallery';
$params = elgg_clean_vars($vars);

$form_settings = elgg_trigger_plugin_hook('init', "form:$form_name", $params, array());

$fields = elgg_extract('fields', $form_settings, array());

foreach ($fields as $name => $options) {
	if (!$options)
		continue;

	$input_type = elgg_extract('input_type', $options, 'text');
	$label = elgg_extract('label', $options, elgg_echo("$form_name:$name"));
	$hint = elgg_extract('hint', $options, elgg_echo("$form_name:$name:hint"));
	$fieldset = elgg_extract('fieldset', $options, 'default');

	unset($options['input_type']);
	unset($options['label']);
	unset($options['hint']);
	unset($options['fieldset']);

	$view = '<div>';
	$view .= ($label) ? "<label>$label</label>" : '';
	$view .= elgg_view("input/$input_type", $options);
	$view .= ($hint) ? "<span class=\"elgg-text-help\">$hint</span>" : '';
	$view .= '</div>';

	$fieldsets[$fieldset][] = $view;
}

foreach ($fieldsets as $name => $fields) {
	echo '<fieldset>';
	if ($name != 'default') {
		echo '<legend><h2>' . elgg_echo("$form_name:$name:set") . '</h2></legend>';
	}
	echo implode('', $fields);
	echo '</fiedlset>';
}

