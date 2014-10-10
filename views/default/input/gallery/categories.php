<?php

namespace hypeJunction\Gallery;

if (elgg_view_exists('input/categories')) {
	echo elgg_view('input/categories', $vars);
} else {
	echo elgg_view('input/tags', $vars);
}