<?php

$entity = elgg_extract('entity', $vars);

$count = $entity->countImages();

echo '<div class="hj-album-image-count">';
echo elgg_echo('hj:gallery:album:photos', array($count));
echo '</div>';