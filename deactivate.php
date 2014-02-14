<?php

$subtypes = array('hjalbum', 'hjalbumimage');

foreach ($subtypes as $subtype) {
	update_subtype('object', $subtype);
}
