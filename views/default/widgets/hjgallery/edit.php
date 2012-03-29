<?php
$widget = $vars['entity'];

$title_label = elgg_echo('hj:gallery:widget:title');
$title_input = elgg_view('input/text', array(
        'name' => 'params[title]',
        'value' => $vars['entity']->title
        ));

$subtype_label = elgg_echo('hj:gallery:widget:type');
$subtype_input = elgg_view('input/dropdown', array(
        'name' => 'params[section]',
        'value' => $vars['entity']->section,
        'options_values' => hj_gallery_get_gallery_section_types()
    ));

$options = <<<HTML
    <div>
        <span>$title_label</span><br />
        <span>$title_input</span>
    </div>
    <div>
        <span>$subtype_label</span>
        <span>$subtype_input</span>
HTML;

echo $options;
