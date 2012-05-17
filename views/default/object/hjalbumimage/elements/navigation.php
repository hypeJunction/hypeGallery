<?php

$entity = elgg_extract('entity', $vars);

$next = hj_gallery_get_next_image($entity);
$prev = hj_gallery_get_prev_image($entity);

$data = elgg_clean_vars($vars);
$data = hj_framework_extract_params_from_params($data);
$data['target'] = "hj-gallery-replaceable-$entity->guid";

$data = hj_framework_json_query($data);

if ($next) {
	$next_link = elgg_view('output/url', array(
		'class' => 'hj-ajaxed-view hj-right',
		'href' => "action/framework/entities/view?e=$next->guid",
		'is_action' => true,
		'data-options' => $data,
		'text' => elgg_echo('next')
			));

}

if ($prev) {
	$prev_link = elgg_view('output/url', array(
		'class' => 'hj-ajaxed-view hj-left',
		'href' => "action/framework/entities/view?e=$prev->guid",
		'is_action' => true,
		'data-options' => $data,
		'text' => elgg_echo('previous')
			));
}

echo elgg_view_layout('hj/dynamic', array(
	'grid' => array(6,6),
	'content' => array($prev_link, $next_link)
));


