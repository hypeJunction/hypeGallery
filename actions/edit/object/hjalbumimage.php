<?php

namespace hypeJunction\Gallery;

$container_guid = get_input('guid');

require_once dirname(dirname(dirname(__FILE__))) . '/upload/describe.php';

forward("gallery/view/$container_guid");

