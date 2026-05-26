<?php

namespace hypeJunction\Gallery;

$container_guid = get_input('guid');

require_once dirname(dirname(dirname(__FILE__))) . '/upload/describe.php';

return \elgg_ok_response([], '', "gallery/view/{$container_guid}");
