<?php

$tag = elgg_extract('entity', $vars);
$return_type = elgg_extract('return_type', $vars, 'map');

switch ($return_type) {

	default :
	case 'map' :
		$style = 'border:3px solid #e8e8e8;';
		$style .= 'filter:alpha(opacity=80);opacity:0.8;';
		$style .= 'top:' . $tag->y1 . 'px;';
		$style .= 'left:' . $tag->x1 . 'px;';
		$style .= 'width:' . $tag->width . 'px;';
		$style .= 'height:' . $tag->height . 'px;';
		$style .= 'z-index:' . $tag->guid;


		echo elgg_view('output/url', array(
			'class' => "hj-gallery-tag-$tag->guid",
			'title' => $tag->title,
			'href' => $tag->url,
			'data-uid' => $tag->guid,
			'style' => $style,
			'text' => '<span>' . $tag->annotation_value . '</span>',
		));
		break;

	case 'list' :

		if ($tag->canEdit()) {
			$delete_link = elgg_view('output/url', array(
				'text' => elgg_view_icon('delete'),
				'href' => $tag->getDeleteURL(),
				'class' => 'elgg-button-delete-entity',
				'is_action' => true,
					));
		}

		echo elgg_view('output/url', array(
			'href' => $tag->url,
			'text' => elgg_view_image_block('', $tag->annotation_value, array('image_alt' => $delete_link)),
			'data-uid' => $tag->guid,
			'class' => 'hj-gallery-tags-item-title'
		));

		break;
}
