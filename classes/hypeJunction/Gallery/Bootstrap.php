<?php

namespace hypeJunction\Gallery;

use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function load() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {
		require_once $this->plugin->getPath() . 'lib/start.php';
	}

	/**
	 * {@inheritdoc}
	 */
	public function init() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function ready() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function shutdown() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function activate() {
		require_once $this->plugin->getPath() . 'autoloader.php';

		$subtypes = [
			hjAlbum::SUBTYPE => get_class(new hjAlbum()),
			hjAlbumImage::SUBTYPE => get_class(new hjAlbumImage()),
			'hjimagetag' => '',
		];
		foreach ($subtypes as $subtype => $class) {
			if (!elgg_set_entity_class('object', $subtype, $class)) {
				elgg_set_entity_class('object', $subtype, $class);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function deactivate() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function upgrade() {

	}
}
