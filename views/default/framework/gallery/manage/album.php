<?php

namespace hypeJunction\Gallery;

elgg_load_css('cropper');

$entity = elgg_extract('entity', $vars);
$summary = elgg_view('object/hjalbum/gallery', array(
	'size' => '800x200',
	'full_view' => true,
	'entity' => $entity
		));

$collab_album = false;
if ($entity->owner_guid != elgg_get_logged_in_user_guid()) {
	$collab_album = true;
	$summary .= '<div class="gallery-manage-instructions">';
	$summary .= elgg_echo('gallery:manage:instructions');
	$summary .= '</div>';
}

$limit = get_input('limit', 100);
$offset = get_input("offset-images-$entity->guid", 0);

$options = array(
	'types' => 'object',
	'subtypes' => array('hjalbumimage'),
	'owner_guids' => ($collab_album) ? elgg_get_logged_in_user_guid() : ELGG_ENTITIES_ANY_VALUE,
	'container_guids' => $entity->guid,
	'limit' => $limit,
	'offset' => $offset,
	'count' => true,
	'order_by_metadata' => array('name' => 'priority', 'direction' => 'ASC', 'as' => 'integer')
);


if (!$entity->canEdit() && $entity->canWriteToContainer(0, 'object', 'hjalbumimage')) {
	$options['owner_guids'] = elgg_get_logged_in_user_guid(); // for collaborative albums/only show images uploaded by the user
}

$ha = access_get_show_hidden_status();
if ($entity->canEdit()) {
	access_show_hidden_entities(true);
}

$count = elgg_get_entities_from_metadata($options);
$options['count'] = false;
$images = elgg_get_entities_from_metadata($options);

$ha = access_get_show_hidden_status();

$body = elgg_view_entity_list($images, array(
	'list_type' => 'gallery',
	'gallery_class' => 'gallery-photostream gallery-manage-album',
	'full_view' => true,
	'pagination' => true,
	'offset_key' => "offset-images-$entity->guid",
		));

$body .= elgg_view('navigation/pagination', array(
	'limit' => $limit,
	'offset' => $offset,
	'offset_key' => "offset-images-$entity->guid",
	'count' => $count
		));

$body .= '<div class="elgg-foot">';
$body .= elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'class' => 'elgg-button elgg-button-submit float-alt'
		));
$body .= '</div>';

if ($count) {
	$form = elgg_view('input/form', array(
		'action' => 'action/gallery/upload/describe',
		'body' => $body,
		'class' => 'elgg-form-gallery-manage'
	));
}

echo '<div class="gallery-full">';
echo "$summary$form";
echo '</div>';
