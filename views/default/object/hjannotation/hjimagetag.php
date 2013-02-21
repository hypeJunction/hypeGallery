<?php

$tag = elgg_extract('entity', $vars);
$return_type = elgg_extract('return_type', $vars, 'map');

switch ($return_type) {

	case 'styles' :
		$output .= '.hj-gallery-tags-map a.hj-gallery-tag-' . $tag->guid . ' { ';
		//$output .= 'border:1px solid #000;';
		$output .= 'border:3px solid #e8e8e8;';
		$output .= 'filter:alpha(opacity=80);opacity:0.8;';
		$output .= 'top:' . $tag->y1 . 'px;';
		$output .= 'left:' . $tag->x1 . 'px;';
		$output .= 'width:' . $tag->width . 'px;';
		$output .= 'height:' . $tag->height . 'px;';
		$output .= 'z-index:' . $tag->guid;
		//$output .= 'width:62px;';
		//$output .= 'height:62px;';
		$output .= '}';
		echo $output;
		break;

	default :
	case 'map' :
		echo '<a class="hj-gallery-tag-' . $tag->guid . '" title="' . $tag->description . '"><span><b>' . $tag->annotation_value . '</b></span></a></li>';
		break;

	case 'list' :

		if ($tag->canEdit()) {
			$delete_link = elgg_view('output/url', array(
				'text' => elgg_view_icon('remove'),
				'href' => $tag->getDeleteURL(),
				'class' => 'elgg-button-delete-entity',
				'is_action' => true,
				'id' => "hj-ajaxed-remove-{$tag->guid}"
					));
		}

		echo '<li>' . elgg_view('output/url', array(
			'href' => $tag->url,
			'text' => "$tag->annotation_value$delete_link",
			'data-uid' => $tag->guid,
			'class' => 'hj-gallery-tags-item-title'
		)) . '</li>';

		break;
}
