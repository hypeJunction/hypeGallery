<?php

$entity = elgg_extract('entity', $vars);

$owner = $entity->getOwnerEntity();
$by = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL(),
	'class' => 'hj-image-author'
		));
$by = elgg_echo('hj:gallery:album:author', array($by));

$container = $entity->getContainerEntity();
$album = elgg_view('output/url', array(
	'text' => $container->title,
	'href' => $container->getURL(),
	'class' => 'hj-image-container'
));
$album = elgg_echo('hj:gallery:image:container', array($album));

echo $by;
echo '<br />';
echo $album;
echo '<br />';
echo elgg_view('object/hjalbum/elements/subtitle', $vars);