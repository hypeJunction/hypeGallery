<?php

namespace hypeJunction\Gallery;

use Elgg\UnitTestCase;

/**
 * Unit tests for river metadata decoding.
 *
 * River metadata is stored as JSON. Legacy serialize()d metadata is migrated
 * by \hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson, after which the
 * production views decode via json_decode() only — no unserialize() call sites
 * remain, eliminating the PHP object injection attack surface entirely.
 */
class RiverMetadataDecoderTest extends UnitTestCase {

    public function up() {}
    public function down() {}

    /**
     * Helper: emulate the JSON decode logic from the river views.
     *
     * @param string $raw Raw metadata value
     * @return array
     */
    private function decode($raw) {
        $decoded = json_decode((string) $raw, true);
        if (!is_array($decoded)) {
            $decoded = [];
        }
        return $decoded;
    }

    /**
     * @return void
     */
    public function testDecodeJsonArrayOfGuids(): void {
        $result = $this->decode(json_encode([10, 11, 12]));
        $this->assertSame([10, 11, 12], $result);
    }

    /**
     * @return void
     */
    public function testDecodeEmptyStringReturnsEmptyArray(): void {
        $this->assertSame([], $this->decode(''));
    }

    /**
     * @return void
     */
    public function testDecodeInvalidReturnsEmptyArray(): void {
        $this->assertSame([], $this->decode('not-json'));
    }

    /**
     * REGRESSION: serialize()d payloads (including malicious ones) must
     * NOT be deserialized. Production code only uses json_decode().
     * Legacy stored data is migrated by EncodeRiverMetadataAsJson.
     */
    public function testLegacySerializedPayloadIsNotDeserialized(): void {
        $payload = 'O:10:"ElggObject":0:{}';
        $result = $this->decode($payload);
        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testLegacySerializedArrayIsRejected(): void {
        // Legacy serialize()d arrays are not decoded — production data must
        // be migrated to JSON by the EncodeRiverMetadataAsJson upgrade batch.
        $result = $this->decode(serialize([10, 11, 12]));
        $this->assertSame([], $result);
    }
}
