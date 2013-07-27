<?php

if (elgg_view_exists('output/categories')) {
	echo elgg_view('output/categories', $vars);
} else {
	$vars['icon_class'] = 'hidden';
	echo elgg_view('output/tags', $vars);
}