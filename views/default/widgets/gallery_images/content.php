<?php

$entity = elgg_extract('entity', $vars);

$options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	//'owner_guids' => $entity->owner_guid,
	'limit' => $entity->num_display,
	'count' => true,
	'list_type' => get_input('list_type', 'gallery'),
	'gallery_class' => 'gallery-photostream',
	'full_view' => false,
	'pagination' => false,
	'size' => 'small',
	'item_class' => 'elgg-photo mas',
	'order_by' => 'e.time_created ASC'
);

elgg_push_context('activity');
$content = elgg_list_entities($options);
elgg_pop_context();

echo $content;

if ($content) {
	$url = "gallery/dahsboard/owner/" . $entity->getOwnerEntity()->username . "?display=photostream";
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('hj:gallery:widget:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('hj:gallery:widget:none');
}
