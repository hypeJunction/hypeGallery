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
	echo elgg_view_entity_icon($file, 'large');
	return true;
}

if (get_input('list_type') == 'carousel') {
	echo '<div class="hj-gallery-carousel-image-wrapper">';
	echo elgg_view_entity_icon($file, 'full', array('class' => 'hj-gallery-carousel-image'));
	echo '</div>';
	echo elgg_view_title($entity->title);
	echo elgg_view('output/longtext', array(
		'value' => $entity->description
	));
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

if (!empty($subtitle)) {
	$subtitle = implode('<br />', $subtitle);
}

if ($full) {

	$params = elgg_clean_vars($vars);
	$params = hj_framework_extract_params_from_entity($entity, $params);
	$params['fbox_x'] = '900';
	$params['fbox_y'] = '800';
	$params['target'] = "full-elgg-object-$entity->guid";

	$header_menu = elgg_view_menu('hjentityhead', array(
		'entity' => $entity,
		'file_guid' => $file->guid,
		'handler' => 'hjfile',
		'class' => 'elgg-menu-hz hj-menu-hz',
		'sort_by' => 'priority',
		'params' => $params,
		'has_full_view' => false
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
	$tags_view = '<div class="clearfix">' . $tags_view . '</div>';
	
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
		$preview_image = '<div id="hj-image-master" class="hj-file-icon-preview hj-file-icon-master"><div class="hj-file-icon-background">' . elgg_view_entity_icon($file, 'master', array('class' => 'hj-gallery-taggable')) . $tags_map . '</div></div>';
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

	$icon = elgg_view_entity_icon($file, 'small');
	$intro = elgg_view_image_block($icon, $list_body);
	$col1 = $preview_image;
	$col2 = '<div id="hj-image-master-details">' . $intro . $tags_view . $full_description . elgg_view_comments($entity) .'</div>';

	//$view = elgg_view_layout('hj/dynamic', array('grid' => array(4,8), 'content' => array($col2, $col1)));

	echo "<div id=\"full-elgg-object-$entity->guid\">";
	echo elgg_view_image_block($col1, $col2);
	echo '<div class="clearfloat"></div>';
	echo '<div id="hj-gallery-image-edit"></div>';
	echo "</div>";
} else {
	$image = elgg_view_entity_icon($file, $thumb_size);

	$params = elgg_clean_vars($vars);
	$params = hj_framework_extract_params_from_entity($entity, $params);

	$params['full_view'] = true;
	$params['has_full_view'] = true;
	$params['target'] = "hj-gallery-image-full";
	$params['fbox_x'] = '900';
	unset($params['data-options']);

	$data = hj_framework_json_query($params);

	$fullview = array(
		'title' => "$entity->title $author_text",
		'text' => $image,
		'href' => "action/framework/entities/view?e=$entity->guid",
		'data-options' => htmlentities($data, ENT_QUOTES, 'UTF-8'),
		'class' => 'hj-ajaxed-view',
		'rel' => 'fancybox'
	);

	$image = elgg_view('output/url', $fullview);

	$gallery = "<div id=\"elgg-object-$file->guid\" class=\"elgg-item\">
	    <div class=\"hj-gallery-image elgg-image elgg-image-gallery\">$image</div>
	    </div>";

	echo $gallery;
}