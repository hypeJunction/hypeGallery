<?php

$list_id = elgg_extract('list_id', $vars);
$guid = str_replace('album-images-', '', $list_id);
$entity = get_entity($guid);

if (!elgg_instanceof($entity, 'object', 'hjalbum')) {
    return true;
}

if (!$entity->canWriteToContainer()) {
	return true;
}
$gallery_params = array('items', 'offset', 'limit', 'count', 'base_url', 'pagination', 'offset_key', 'position', 'gallery_class', 'gallery_id', 'full_view');
foreach ($gallery_params as $gallery_param) {
    if (isset($vars[$gallery_param])) {
	unset($vars[$gallery_param]);
    }
}

$clean_vars = elgg_clean_vars($vars);
$form = hj_framework_get_data_pattern('object', 'hjalbumimage');

$params = array(
    'subtype' => 'hjalbumimage',
    'subject_guid' => null,
    'entity_guid' => null,
    'form_guid' => $form->guid,
    'container_guid' => $entity->guid,
	'owner_guid' => elgg_get_logged_in_user_guid(),
    'context' => 'gallery',
    'handler' => 'hjalbum',
    'push_context' => 'gallery',
    'full_view' => false,
    'event' => 'create',
    'target' => "album-images-$entity->guid"
);

$params = array_merge($clean_vars, $params);
$params = hj_framework_extract_params_from_params($params);

unset($params['data-options']);

$data = hj_framework_json_query($params);

$params = array(
	    'title' => elgg_echo('hj:gallery:addimage'),
	    'text' => elgg_echo('hj:gallery:addimage'),
	    'href' => "action/framework/entities/edit",
	    'data-options' => $data,
	    'is_action' => true,
	    'rel' => 'fancybox',
	    'class' => "hj-ajaxed-add"
	);

$add = elgg_view('output/url', $params);

$html = <<<HTML
    <li class="elgg-item hj-gallery-imageplaceholder">
	<div class="hj-gallery-addimage-placeholder">
	    <span>$add</span>
	</div>
    </li>
HTML;

echo $html;