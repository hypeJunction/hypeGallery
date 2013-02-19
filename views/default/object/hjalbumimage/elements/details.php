<?php

$entity = elgg_extract('entity', $vars);

$title = elgg_view('framework/bootstrap/object/elements/title', $vars);
$description = elgg_view('framework/bootstrap/object/elements/briefdescription', $vars);

echo $title . $description;