<?php

$enabled = getenv('TRAVIS') != ''; //are we on Travis?

if (!$enabled) {
	echo "This script should be run only in Travis CI test environment.\n";
	exit(1);
}

if (PHP_SAPI !== 'cli') {
	echo "You must use the command line to run this script.\n";
	exit(2);
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/start.php');

$admin = get_user_by_username('admin');
login($admin);

$plugin = elgg_get_plugin_from_id('hypeFilestore');
if ($plugin->activate()) {
	echo "hypeFilestore has been activated [guid = {$plugin->getGUID()}]";
}

$plugin = elgg_get_plugin_from_id('hypeGallery');
if ($plugin->activate()) {
	echo "hypeGallery has been activated [guid = {$plugin->getGUID()}]";
}

logout($admin);