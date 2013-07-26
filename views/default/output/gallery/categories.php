<?php

if (elgg_view_exists('output/category')) {
	echo elgg_view('output/category', $vars);
} else {
	$vars['icon_class'] = 'hidden';
	echo elgg_view('output/tags', $vars);
}