<?php

if (elgg_view_exists('input/category')) {
	echo elgg_view('input/category', $vars);
} else {
	echo elgg_view('input/tags', $vars);
}