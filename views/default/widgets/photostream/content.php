<?php

namespace hypeJunction\Gallery;

$entity = elgg_extract('entity', $vars);
$owner = $entity->getOwnerEntity();
if (elgg_in_context('dashboard')) {
	$owner_guids = ELGG_ENTITIES_ANY_VALUE;
	$container_guids = ELGG_ENTITIES_ANY_VALUE;
	$more_url = "/gallery";
} else if ($owner instanceof ElggUser) {
	$owner_guids = $owner->guid;
	$container_guids = ELGG_ENTITIES_ANY_VALUE;
	$more_url = "/gallery/dahsboard/owner/$owner->username?display=photostream";
} else {
	$owner_guids = ELGG_ENTITIES_ANY_VALUE;
	$container_guids = $owner->guid;
	$more_url = "/gallery/group/$owner->guid";
}

$options = array(
	'types' => 'object',
	'subtypes' => array(hjAlbumImage::SUBTYPE),
	'owner_guids' => $owner_guids,
	'container_guids' => $container_guids,
	'limit' => $entity->num_display,
	'count' => true,
	'list_type' => get_input('list_type', 'gallery'),
	'gallery_class' => 'gallery-photostream',
	'full_view' => false,
	'pagination' => false,
	'size' => '125',
	//'item_class' => 'elgg-photo mas',
);

elgg_push_context('activity');
$content = elgg_list_entities($options);
elgg_pop_context();

echo $content;

if ($content) {
	$more_link = elgg_view('output/url', array(
		'href' => $more_url,
		'text' => elgg_echo('gallery:widget:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('gallery:widget:none');
}
