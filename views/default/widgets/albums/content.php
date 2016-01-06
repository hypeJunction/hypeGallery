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
	$more_url = "/gallery/dahsboard/owner/$owner->username?display=albums";
} else {
	$owner_guids = ELGG_ENTITIES_ANY_VALUE;
	$container_guids = $owner->guid;
	$more_url = "/gallery/group/$owner->guid";
}

$options = array(
	'types' => 'object',
	'subtypes' => array(hjAlbum::SUBTYPE),
	'owner_guids' => $owner_guids,
	'container_guids' => $container_guids,
	'limit' => $entity->num_display,
	'count' => true,
	'full_view' => false,
	'pagination' => false,
	'size' => '125',
);

$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$more_link = elgg_view('output/url', array(
		'href' => $more_link,
		'text' => elgg_echo('gallery:widget:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('gallery:widget:none');
}
