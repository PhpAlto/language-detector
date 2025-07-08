<?php

declare(strict_types=1);

/*
 * This file is part of the ALTO library.
 *
 * © 2025–present Simon André
 *
 * For full copyright and license information, please see
 * the LICENSE file distributed with this source code.
 */

namespace Alto\LanguageDetector\Tests;

use Alto\LanguageDetector\DetectionResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DetectionResult::class)]
final class DetectionResultTest extends TestCase
{
    #[Test]
    public function testCreateResult(): void
    {
        $result = DetectionResult::create('php', 0.8);

        $this->assertSame('php', $result->getLanguage());
        $this->assertSame(0.8, $result->getConfidence());
        $this->assertTrue($result->isConfident());
        $this->assertTrue($result->isConfident(0.7));
        $this->assertFalse($result->isConfident(0.9));
    }

    #[Test]
    public function testNoneResult(): void
    {
        $result = DetectionResult::none();

        $this->assertNull($result->getLanguage());
        $this->assertSame(0.0, $result->getConfidence());
        $this->assertFalse($result->isConfident());
    }

    #[Test]
    public function testDefinitiveResult(): void
    {
        $result = DetectionResult::definitive('javascript');

        $this->assertSame('javascript', $result->getLanguage());
        $this->assertSame(0.98, $result->getConfidence());
        $this->assertTrue($result->isConfident());
    }

    #[Test]
    public function testDefinitiveResultWithCustomConfidence(): void
    {
        $result = DetectionResult::definitive('typescript', 0.95);

        $this->assertSame('typescript', $result->getLanguage());
        $this->assertSame(0.95, $result->getConfidence());
        $this->assertTrue($result->isConfident());
    }

    #[Test]
    #[DataProvider('confidenceClampingProvider')]
    public function testConfidenceIsClamped(float $inputConfidence, float $expectedConfidence): void
    {
        $result = DetectionResult::create('test', $inputConfidence);

        $this->assertSame($expectedConfidence, $result->getConfidence());
    }

    public static function confidenceClampingProvider(): array
    {
        return [
            'negative confidence' => [-0.5, 0.0],
            'zero confidence' => [0.0, 0.0],
            'normal confidence' => [0.75, 0.75],
            'max confidence' => [1.0, 1.0],
            'above max confidence' => [1.5, 1.0],
        ];
    }

    #[Test]
    #[DataProvider('confidenceThresholdProvider')]
    public function testIsConfidentWithThresholds(float $confidence, float $threshold, bool $expected): void
    {
        $result = DetectionResult::create('test', $confidence);

        $this->assertSame($expected, $result->isConfident($threshold));
    }

    public static function confidenceThresholdProvider(): array
    {
        return [
            'high confidence, low threshold' => [0.8, 0.5, true],
            'equal confidence and threshold' => [0.6, 0.6, true],
            'low confidence, high threshold' => [0.3, 0.7, false],
            'zero confidence' => [0.0, 0.5, false],
            // Note: null language case is tested separately
        ];
    }

    #[Test]
    public function testNullLanguageIsNotConfident(): void
    {
        $result = DetectionResult::create(null, 0.9);

        $this->assertNull($result->getLanguage());
        $this->assertSame(0.9, $result->getConfidence());
        $this->assertFalse($result->isConfident()); // Should be false because language is null
    }
}
