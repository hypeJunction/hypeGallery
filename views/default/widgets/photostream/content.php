<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);
$owner = $entity->getOwnerEntity();

$options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'owner_guids' => (!elgg_in_context('dashboard')) ? $owner->guid : null,
	'limit' => $entity->num_display,
	'count' => true,
	'list_type' => get_input('list_type', 'gallery'),
	'gallery_class' => 'gallery-photostream',
	'full_view' => false,
	'pagination' => false,
	'size' => '125',
	'item_class' => 'elgg-photo mas',
);

elgg_push_context('activity');
$content = elgg_list_entities($options);
elgg_pop_context();

echo $content;

if ($content) {
	if (elgg_in_context('dashboard')) {
		$url = "gallery";
	} else {
		$url = "gallery/dahsboard/owner/" . $owner->username . "?display=photostream";
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
