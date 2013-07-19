<?php

elgg_push_context('photostream');

$items = elgg_extract('items', $vars);
$list_class = elgg_extract('list_class', $vars, '');

if (is_array($items) && count($items) > 0) {
	foreach ($items as $item) {
		$list_body .= elgg_view('framework/grids/photostream/item', array(
			'entity' => $item,
			'class' => elgg_extract('item_class', $vars, '')
				));
	}
} else {

}

if (elgg_is_xhr()) {
	echo $list_body;
	exit;
}

$attributes = elgg_format_attributes(array(
	'id' => elgg_extract('list_id', $vars, false),
	'class' => trim("elgg-list $list_class"),
		));

$list_body = "<ul $attributes>$list_body</ul>";

$pagination = elgg_extract('pagination', $vars, false);
$position = elgg_extract('position', $vars, 'after');
$count = elgg_extract('count', $vars, 0);

if ($pagination && $count) {
	$nav = elgg_view('navigation/pagination', $vars);
}

if ($position == 'both') {
	echo $nav . $list_body . $nav;
} else if ($position == 'after') {
	echo $list_body . $nav;
} else if ($position == 'before') {
	echo $nav . $list_body;
}

elgg_pop_context();