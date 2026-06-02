<?php

namespace hypeJunction\Gallery\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;

/**
 * EncodeRiverMetadataAsJson class.
 */
class EncodeRiverMetadataAsJson extends AsynchronousUpgrade {

	/**
     * @return int
     */
    public function getVersion(): int {
		return 2026041200;
	}

	/**
     * @return bool
     */
    public function shouldBeSkipped(): bool {
		return false;
	}

	/**
     * @return bool
     */
    public function needsIncrementOffset(): bool {
		return false;
	}

	/**
     * @return int
     */
    public function countItems(): int {
		return self::UNKNOWN_COUNT;
	}

	/**
     * @param int $count
     * @return bool
     */
    public function run(int $count): bool {
		$db = elgg()->db;
		$prefix = $db->prefix;

		$rows = $db->getData("
			SELECT m.id, m.value
			FROM {$prefix}metadata m
			INNER JOIN {$prefix}entities e ON e.guid = m.entity_guid
			WHERE m.name LIKE 'river_%'
			AND e.type = 'object'
			AND e.subtype = 'hjalbum'
		");

		$upgrade = $this->getUpgrade();

		foreach ($rows as $row) {
			$raw = (string) $row->value;

			$decoded = json_decode($raw, true);
			if (is_array($decoded)) {
				continue;
			}

			$decoded = @unserialize($raw, ['allowed_classes' => false]);
			if (!is_array($decoded)) {
				$upgrade->addFailures();
				$upgrade->addError("hypeGallery: metadata id={$row->id} is not parseable");
				continue;
			}

			$db->updateData(
				"UPDATE {$prefix}metadata SET value = :value WHERE id = :id",
				false,
				[
					':value' => json_encode($decoded),
					':id' => (int) $row->id,
				]
			);
			$upgrade->addSuccesses();
		}

		$upgrade->markComplete();
		return true;
	}
}
