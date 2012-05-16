<?php

$form = hj_framework_get_data_pattern('object', 'hjalbum');

$vars['form_guid'] = $form->guid;

echo elgg_view_entity($form, $vars);
