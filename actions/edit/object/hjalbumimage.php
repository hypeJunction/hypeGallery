<?php

$container_guid = get_input('guid');

action('gallery/upload/describe', false);

forward("gallery/view/$container_guid");

