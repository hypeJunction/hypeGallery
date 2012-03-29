<?php

$max_albums_label = "Maximum number of albums per user (0 for unlimited)";
$max_albums = elgg_view('input/text', array(
	'name' => 'params[album_max]',
	'value' => $vars['entity']->album_max
));

$max_images_label = "Maximum number of images per album (0 for unlimited)";
$max_images = elgg_view('input/text', array(
	'name' => 'params[images_max]',
	'value' => $vars['entity']->images_max
));

$settings = <<<__HTML
    <h3>Max values</h3>
    <div>
        <p><i>$max_albums_label</i><br>$max_albums</p>
        <p><i>$max_images_label</i><br>$max_images</p>
    </div>
    <hr>
    
</div>
__HTML;

echo $settings;