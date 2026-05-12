<?php

namespace hypeJunction\Gallery\Tests\Unit;

use hypeJunction\Gallery\Upgrades\EncodeRiverMetadataAsJson;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class EncodeRiverMetadataAsJsonTest extends TestCase {

    public function testGetVersionReturnsInteger() {
        $upgrade = new EncodeRiverMetadataAsJson();
        $this->assertIsInt($upgrade->getVersion());
        $this->assertEquals(2026041200, $upgrade->getVersion());
    }

    public function testShouldNotBeSkipped() {
        $upgrade = new EncodeRiverMetadataAsJson();
        $this->assertFalse($upgrade->shouldBeSkipped());
    }

    public function testNeedsIncrementOffsetIsFalse() {
        $upgrade = new EncodeRiverMetadataAsJson();
        $this->assertFalse($upgrade->needsIncrementOffset());
    }
}
