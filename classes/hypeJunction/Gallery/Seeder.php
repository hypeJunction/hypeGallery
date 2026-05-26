<?php

namespace hypeJunction\Gallery;

use Elgg\Database\Seeds\Seed;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * Seeds gallery album and album image entities for development and testing.
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		while ($this->seedsCount() < $this->getCount()) {
			$owner = $this->getRandomUser();

			$album = new hjAlbum();
			$album->owner_guid = $owner->guid;
			$album->container_guid = $owner->guid;
			$album->title = $this->faker->sentence(4);
			$album->description = $this->faker->paragraph();

			if (!$album->save()) {
				continue;
			}

			// Seed a few album image stubs inside the album
			$image_count = $this->faker->numberBetween(2, 5);
			for ($i = 0; $i < $image_count; $i++) {
				$image = new hjAlbumImage();
				$image->owner_guid = $owner->guid;
				$image->container_guid = $album->guid;
				$image->title = $this->faker->sentence(3);
				$image->description = $this->faker->sentence();
				$image->priority = $i;
				$image->save();
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		$images = \elgg_get_entities([
			'type' => 'object',
			'subtype' => hjAlbumImage::SUBTYPE,
			'limit' => false,
			'batch' => true,
		]);

		foreach ($images as $image) {
			$image->delete();
			$this->advance();
		}

		$albums = \elgg_get_entities([
			'type' => 'object',
			'subtype' => hjAlbum::SUBTYPE,
			'limit' => false,
			'batch' => true,
		]);

		foreach ($albums as $album) {
			$album->delete();
			$this->advance();
		}
	}

	/**
	 * Register this seeder with the seeds event.
	 *
	 * @param \Elgg\Event $event seeds,database event
	 * @return array
	 */
	public static function addSeed(\Elgg\Event $event) {
		$seeds = $event->getValue();
		$seeds[] = self::class;
		return $seeds;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType(): string {
		return 'hjalbum';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions(): array {
		return [
			'type' => 'object',
			'subtype' => hjAlbum::SUBTYPE,
		];
	}

}
