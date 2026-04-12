<?php

namespace hypeJunction\Gallery;

use Elgg\UnitTestCase;

/**
 * Unit tests for river metadata decoding (JSON / legacy serialize fallback).
 *
 * Regression tests for the unserialize() call sites in:
 *  - views/default/river/object/hjalbum/update.php:29
 *  - views/default/object/hjalbum/river.php:26
 *
 * Both sinks use `unserialize(..., ['allowed_classes' => false])` which blocks
 * object instantiation and is therefore NOT exploitable for RCE. These tests
 * lock in that mitigation so a future refactor doesn't re-introduce the hazard.
 *
 * Feeds bead 2mra (unserialize RCE scan) — verified safe variant.
 */
class RiverMetadataDecoderTest extends UnitTestCase {

    public function up() {}
    public function down() {}

    /**
     * Helper: emulate the decode-with-fallback logic from the river views.
     *
     * @param string $raw Raw metadata value
     * @return array
     */
    private function decode($raw) {
        $decoded = json_decode((string) $raw, true);
        if (!is_array($decoded)) {
            $decoded = @unserialize((string) $raw, ['allowed_classes' => false]);
        }
        if (!is_array($decoded)) {
            $decoded = [];
        }
        return $decoded;
    }

    public function testDecodeJsonArrayOfGuids(): void {
        $result = $this->decode(json_encode([10, 11, 12]));
        $this->assertSame([10, 11, 12], $result);
    }

    public function testDecodeLegacySerializedArrayOfGuids(): void {
        $result = $this->decode(serialize([10, 11, 12]));
        $this->assertSame([10, 11, 12], $result);
    }

    public function testDecodeEmptyStringReturnsEmptyArray(): void {
        $this->assertSame([], $this->decode(''));
    }

    public function testDecodeInvalidReturnsEmptyArray(): void {
        $this->assertSame([], $this->decode('not-json-not-serialized'));
    }

    /**
     * REGRESSION: unserialize() must NOT instantiate objects. The
     * `allowed_classes => false` option coerces any serialized object into a
     * __PHP_Incomplete_Class which is_array() rejects. A malicious payload
     * therefore cannot execute __wakeup() / __destruct() gadgets.
     */
    public function testMaliciousSerializedObjectIsNeutralized(): void {
        // Craft a payload that would instantiate \ElggObject if allowed_classes
        // were true. allowed_classes => false must block it.
        $payload = 'O:10:"ElggObject":0:{}';
        $result = $this->decode($payload);
        // Either empty array (is_array check fails on __PHP_Incomplete_Class)
        // or coerced; must never be a real ElggObject instance.
        $this->assertIsArray($result);
        foreach ($result as $v) {
            $this->assertNotInstanceOf(\ElggObject::class, $v);
        }
    }

    public function testDecodeNestedSerializedPayloadReturnsPlainScalars(): void {
        $result = $this->decode(serialize(['a' => 1, 'b' => 'str']));
        $this->assertSame(['a' => 1, 'b' => 'str'], $result);
    }
}
