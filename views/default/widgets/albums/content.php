<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);
$owner = $entity->getOwnerEntity();

$options = array(
	'types' => 'object',
	'subtypes' => array('hjalbum'),
	'owner_guids' => (!elgg_in_context('dashboard')) ? $owner->guid : null,
	'limit' => $entity->num_display,
	'count' => true,
	'full_view' => false,
	'pagination' => false,
	'size' => '125',
);

$content = elgg_list_entities($options);

echo $content;

if ($content) {
	if (elgg_in_context('dashboard')) {
		$url = "gallery";
	} else {
		$url = "gallery/dahsboard/owner/" . $owner->username . "?display=albums";
	}
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('gallery:widget:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('gallery:widget:none');
}
