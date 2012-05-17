<?php

/**
 * File icon view
 *
 * @uses $vars['entity'] The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']   topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']   Optional override for link
 */
$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'full', 'preview', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$title = $entity->title;

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = "class=\"elgg-photo {$vars['class']}\"";

$id = "id=\"hj-entity-icon-{$entity->guid}\"";

$img_src = $entity->getIconURL($vars['size']);
$original_size = getimagesize($img_src);
$img_src = elgg_format_url($img_src);
$img = "<img $id $class src=\"$img_src\" alt=\"$title\"/>";
if ($vars['size'] == 'full' || $vars['size'] == 'master') {
	$img = "<img $id $class original-width=\"$original_size[0]\" original-height=\"$original_size[1]\" src=\"$img_src\" alt=\"$title\"/>";
}

$data = elgg_clean_vars($vars);
$data = hj_framework_extract_params_from_params($data);
$data['full_view'] = true;
//$data['fbox_x'] = '900';
$data['target'] = '';
$data = hj_framework_json_query($data);

if ($url) {
//		$img = elgg_view('output/url', array(
//			'href' => $url,
//			'text' => $img
//				));
		$img = elgg_view('output/url', array(
			'class' => 'hj-ajaxed-view',
			'href' => "action/framework/entities/view?e=$entity->guid",
			'is_action' => true,
			'data-options' => $data,
			'rel' => 'fancybox',
			'text' => $img
				));
}
echo $img;
