<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$owner = $entity->getOwnerEntity();
$full = elgg_extract('full_view', $vars, false);
$list_type = elgg_extract('list_type', $vars, 'list');
$thumb_size = elgg_extract('thumb_size', $vars, 'large');
$file = get_entity($entity->image);

if (elgg_in_context('activity') || elgg_in_context('main')) {
	echo elgg_view_entity_icon($file, 'medium');
	return true;
}

$title = $entity->title;
$author_link = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $author_link
		));
$author_text = elgg_echo('hj:gallery:image:author', array($owner->name));
$author_link = elgg_echo('hj:gallery:image:author', array($author_link));

$content = elgg_get_excerpt($entity->description);

if ($entity->location && $entity->location !== '0,0') {
	$subtitle[] = elgg_view('output/location', array('entity' => $entity));
}

if ($entity->date) {
	$subtitle[] = elgg_view('output/date', array('value' => $entity->date));
}

if ($full) {
	if ($entity->copyright) {
		$subtitle[] = elgg_view('output/text', array('value' => $$entity->copyright));
	}
	if ($entity->friend_tags) {
		$subtitle[] = elgg_view('output/relationship_tags', array('value' => $entity->friend_tags, 'entity' => $entity));
	}
}

$subtitle = implode('<br />', $subtitle);

if ($full) {

	$params = elgg_clean_vars($vars);
	$params = hj_framework_extract_params_from_entity($entity, $params);
	$params['fbox_x'] = '900';
	$params['fbox_y'] = '800';
	$params['has_full_view'] = false;
	$params['target'] = "full-elgg-object-$entity->guid";

	$header_menu = elgg_view_menu('hjentityhead', array(
		'entity' => $entity,
		'file_guid' => $file->guid,
		'handler' => 'hjfile',
		'class' => 'elgg-menu-hz hj-menu-hz',
		'sort_by' => 'priority',
		'params' => $params
			));

	$footer_menu = elgg_view_menu('hjentityfoot', array(
		'entity' => $entity,
		'file_guid' => $file->guid,
		'handler' => 'hjfile',
		'class' => 'elgg-menu-hz hj-menu-hz',
		'sort_by' => 'priority',
		'params' => $params,
			));


	$tag_params = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'container_guid' => $entity->guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => 'hjimagetag'),
			array('name' => 'annotation_value', 'value' => '', 'operand' => '!=')
		),
		'limit' => 0,
		'order_by' => 'e.time_created asc'
	);

	$tags = elgg_get_entities_from_metadata($tag_params);

	$tags_view .= elgg_view_entity_list($tags, array(
		'return_type' => 'list',
		'list_type' => 'gallery',
		'list_id' => "hj-gallery-tags-list-$entity->guid",
		'gallery_class' => 'hj-gallery-tags-list',
			));

	$tag_form_params = array(
		'target' => "hj-gallery-tags-list-$entity->guid"
	);

	if (!elgg_in_context('fancybox')) {
		$tags_view .= elgg_view_form('hj/gallery/phototag', array('id' => "hj-gallery-tagger-form", 'class' => 'hj-gallery-tag-save hidden'), array('entity' => $file, 'image' => $entity, 'params' => $tag_form_params));
	}
	$tags_map = '<style type="text/css">';
	foreach ($tags as $tag) {
		$tags_map .= elgg_view_entity($tag, array('return_type' => 'styles'));
	}
	$tags_map .= '</style>';

	$tags_map .= elgg_view_entity_list($tags, array(
		'return_type' => 'map',
		'list_id' => "hj-gallery-tags-map-$entity->guid",
		'list_class' => 'hj-gallery-tags-map',
			));

	if ($file->simpletype == 'image') {
		$preview_image = '<div id="hj-image-master" class="hj-file-icon-preview hj-file-icon-master">' . elgg_view_entity_icon($file, 'master', array('class' => 'hj-gallery-taggable')) . $tags_map . '</div>';
	}
	$full_description = $entity->description;

	$params = array(
		'entity' => $entity,
		'title' => $title,
		'metadata' => $header_menu,
		'subtitle' => $author_link . '<br />' . $subtitle,
		'tags' => true,
		'content' => $footer_menu
	);

	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	$icon = elgg_view_entity_icon($file, 'medium');

	$intro = elgg_view_image_block($icon, $list_body);

	if ($entity->canEdit()) {
		elgg_register_menu_item('hjalbumimage', array(
			'name' => 'makeavatar',
			'text' => elgg_echo('hj:album:image:makeavatar'),
			'href' => "action/gallery/makeavatar?e=$file->guid",
			'is_action' => true,
			'priority' => 100
		));


		$new['params'] = array(
			'entity_guid' => $file->guid,
			'target' => "hj-gallery-image-edit"
		);

		elgg_register_menu_item('hjalbumimage', array(
			'name' => 'editthumb',
			'text' => elgg_echo('hj:album:image:editthumb'),
			'href' => "action/hj/gallery/thumb?type=get&image_guid=$entity->guid",
			'is_action' => true,
			'class' => 'hj-ajaxed-view',
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 200
		));

		elgg_register_menu_item('hjalbumimage', array(
			'name' => 'makecover',
			'text' => elgg_echo('hj:album:image:makecover'),
			'href' => "action/gallery/makecover?e=$entity->guid",
			'is_action' => true,
			'class' => 'hj-ajaxed-view',
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 300
		));

		elgg_register_menu_item('hjalbumimage', array(
			'name' => 'starttagging',
			'text' => elgg_echo('hj:album:image:startagging'),
			'href' => "javascript:void(0);",
			'is_action' => false,
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 400
		));
		elgg_register_menu_item('hjalbumimage', array(
			'name' => 'stoptagging',
			'text' => elgg_echo('hj:album:image:stoptagging'),
			'href' => "javascript:void(0);",
			'class' => 'hidden',
			'is_action' => false,
			'data-options' => htmlentities(json_encode($new), ENT_QUOTES, 'UTF-8'),
			'priority' => 500
		));
	}

	$col1 = elgg_view_menu('hjalbumimage', array(
		'entity' => $entity,
		'sort_by' => 'priority'
			));

	$intro .= $col1 . $full_description;
	$col2 = $preview_image . $tags_view . elgg_view_comments($entity);
	echo "<div id=\"full-elgg-object-$entity->guid\">";
	echo elgg_view_layout('hj/dynamic', array('grid' => array(4, 8), 'content' => array($intro, $col2)));
	echo '<div id="hj-gallery-image-edit"></div>';
	echo "</div>";
} else {
	$image = elgg_view_entity_icon($file, $thumb_size);

	$params = elgg_clean_vars($vars);
	$params = hj_framework_extract_params_from_entity($entity, $params);

	$params['full_view'] = true;
	$params['has_full_view'] = true;
	$params['target'] = "hj-gallery-image-full";
	unset($params['data-options']);

	$data = hj_framework_json_query($params);

	$fullview = array(
		'title' => "$entity->title $author_text",
		'text' => $image,
		'href' => "action/framework/entities/view?e=$entity->guid",
		'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
		'class' => 'hj-ajaxed-view',
	);

	$image = elgg_view('output/url', $fullview);

	$gallery = "<li id=\"elgg-object-$file->guid\" class=\"elgg-item\">
	    <div class=\"hj-gallery-image elgg-image elgg-image-gallery\">$image</div>
	    </li>";

	echo $gallery;
}