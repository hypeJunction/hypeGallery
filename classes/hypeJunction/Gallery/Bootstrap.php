<?php

namespace hypeJunction\Gallery;

use Elgg\PluginBootstrap;

/**
 * Bootstrap class.
 */
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
		\elgg_register_event_handler('seeds', 'database', [Seeder::class, 'addSeed']);
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
		// Entity class mappings are registered declaratively via the
		// 'entities' key in elgg-plugin.php.
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
