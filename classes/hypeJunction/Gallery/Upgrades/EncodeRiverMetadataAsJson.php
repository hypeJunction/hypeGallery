<?php

namespace hypeJunction\Gallery\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * EncodeRiverMetadataAsJson class.
 */
class EncodeRiverMetadataAsJson extends AsynchronousUpgrade {

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
		return self::UNKNOWN_COUNT;
	}

	public function run(Result $result, $offset): Result {
		$db = elgg()->db;
		$prefix = $db->prefix;

		$rows = $db->getConnection('read')->executeQuery("
			SELECT m.id, m.value
			FROM {$prefix}metadata m
			INNER JOIN {$prefix}entities e ON e.guid = m.entity_guid
			WHERE m.name LIKE 'river_%'
			AND e.type = 'object'
			AND e.subtype = 'hjalbum'
		")->fetchAllAssociative();

		$write = $db->getConnection('write');

		foreach ($rows as $row) {
			$raw = (string) $row['value'];

			$decoded = json_decode($raw, true);
			if (is_array($decoded)) {
				continue;
			}

			$decoded = @unserialize($raw, ['allowed_classes' => false]);
			if (!is_array($decoded)) {
				// A serialized OBJECT (these rows hold 2016-era serialized ElggBatch
				// caches). allowed_classes:false decodes it to __PHP_Incomplete_Class,
				// so it can never become JSON. Counting it as a failure made Elgg reject
				// the whole upgrade promise, and every upgrade queued behind this one —
				// including core's MigratePageTop — stayed pending forever.
				//
				// The row is dead cache and an object-injection vector. Drop it.
				$write->executeStatement(
					"DELETE FROM {$prefix}metadata WHERE id = :id",
					['id' => (int) $row['id']]
				);
				elgg_log("hypeGallery: dropped unparseable river metadata id={$row['id']} (serialized object)", \Psr\Log\LogLevel::NOTICE);
				$result->addSuccesses();
				continue;
			}

			$write->executeStatement(
				"UPDATE {$prefix}metadata SET value = :value WHERE id = :id",
				[
					'value' => json_encode($decoded),
					'id' => (int) $row['id'],
				]
			);
			$result->addSuccesses();
		}

		$result->markComplete();

		return $result;
	}
}
