<?php

namespace hypeJunction\Gallery\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\Batch;
use Elgg\Upgrade\Result;

/**
 * Re-encode hjalbum `river_<timestamp>` metadata previously written by
 * serialize() as JSON.
 *
 * The runtime readers in views/object/hjalbum/river.php and
 * views/river/object/hjalbum/update.php still accept legacy serialize()
 * payloads as a backward-compat fallback. Once this upgrade has run on
 * every site, the fallback can be removed in a future release.
 */
class EncodeRiverMetadataAsJson implements Batch {

	public function getVersion(): int {
		return 2026041200;
	}

	public function shouldBeSkipped(): bool {
		return false;
	}

	public function needsIncrementOffset(): bool {
		return false;
	}

	public function countItems(): int {
		return Batch::UNKNOWN_COUNT;
	}

	public function run(Result $result, $offset): Result {
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

		foreach ($rows as $row) {
			$raw = (string) $row->value;

			$decoded = json_decode($raw, true);
			if (is_array($decoded)) {
				continue;
			}

			$decoded = @unserialize($raw, ['allowed_classes' => false]);
			if (!is_array($decoded)) {
				$result->addFailures();
				$result->addError("hypeGallery: metadata id={$row->id} is not parseable");
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
			$result->addSuccesses();
		}

		$result->markComplete();
		return $result;
	}
}
