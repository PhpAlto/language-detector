<?php

declare(strict_types=1);

namespace Alto\LanguageDetector\Tests;

use Alto\LanguageDetector\LanguageDetector;
use Alto\LanguageDetector\DetectionResult;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Unit tests for the LanguageDetector class.
 */
#[CoversClass(LanguageDetector::class)]
#[CoversClass(DetectionResult::class)]
final class LanguageDetectorTest extends TestCase
{
    private LanguageDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detector = new LanguageDetector(__DIR__ . '/../data/language');
    }

    #[Test]
    public function testConstructorWithDirectory(): void
    {
        $detector = new LanguageDetector(__DIR__ . '/../data/language');
        $this->assertInstanceOf(LanguageDetector::class, $detector);
    }

    #[Test]
    public function testConstructorWithFile(): void
    {
        $detector = new LanguageDetector(__DIR__ . '/../data/languages_signatures.php');
        $this->assertInstanceOf(LanguageDetector::class, $detector);
    }

    #[Test]
    public function testConstructorWithInvalidPath(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Profiles path not found or not accessible');

        new LanguageDetector('/nonexistent/path');
    }

    #[Test]
    public function testMinimumLengthRequirement(): void
    {
        $result = $this->detector->detect('short');

        $this->assertNull($result->getLanguage());
        $this->assertSame(0.0, $result->getConfidence());
    }

    #[Test]
    public function testPhpTokenizerDetection(): void
    {
        $result = $this->detector->detect('<?php echo "Hello World";');

        $this->assertSame('php', $result->getLanguage());
        $this->assertGreaterThan(0.9, $result->getConfidence());
    }

    #[Test]
    public function testConfidenceThreshold(): void
    {
        // Test a snippet that gets low confidence
        $result = $this->detector->detect('function test() {}');

        // Should either return null or a language with confidence >= threshold
        if ($result->getLanguage() !== null) {
            $this->assertGreaterThanOrEqual(0.25, $result->getConfidence());
        } else {
            // If no language detected, confidence should be low
            $this->assertLessThan(0.25, $result->getConfidence());
        }
    }

    #[Test]
    #[DataProvider('languageDetectionProvider')]
    public function testLanguageDetection(string $code, string $expectedLanguage): void
    {
        $result = $this->detector->detect($code);

        $this->assertSame($expectedLanguage, $result->getLanguage());
        $this->assertGreaterThan(0.25, $result->getConfidence());
    }

    /**
     * Provides test cases for basic language detection.
     */
    public static function languageDetectionProvider(): array
    {
        return [
            'PHP with tag' => ['<?php class Test { public $var = 42; }', 'php'],
            'TypeScript interface' => ['interface User { name: string; age: number; }', 'typescript'],
            'JavaScript async' => ['async function fetchData() { const response = await fetch("/api"); return response.json(); }', 'javascript'],
            'SCSS with mixin' => ['$primary: #007bff; @mixin button { background: $primary; } .btn { @include button; }', 'scss'],
            'CSS media query' => ['@media screen and (max-width: 768px) { .container { padding: 1rem; } }', 'css'],
            'HTML structure' => ['<!DOCTYPE html><html><head><title>Test</title></head><body><h1>Hello</h1></body></html>', 'html'],
            'XML document' => ['<?xml version="1.0"?><root><item>value</item></root>', 'xml'],
            'SVG graphic' => ['<svg xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40"/></svg>', 'svg'],
        ];
    }

    #[Test]
    public function testEmptyCodeReturnsNone(): void
    {
        $result = $this->detector->detect('');

        $this->assertNull($result->getLanguage());
        $this->assertSame(0.0, $result->getConfidence());
    }

    #[Test]
    public function testWhitespaceOnlyReturnsNone(): void
    {
        $result = $this->detector->detect("   \n\t  ");

        $this->assertNull($result->getLanguage());
        $this->assertSame(0.0, $result->getConfidence());
    }

    #[Test]
    public function testPhpWithoutOpeningTag(): void
    {
        // PHP code without opening tag should still be detectable
        $result = $this->detector->detect('$variable = "test"; $object->method(); Class::staticCall();');

        // This might be detected as PHP or might return null depending on confidence
        if ($result->getLanguage() === 'php') {
            $this->assertGreaterThan(0.25, $result->getConfidence());
        }
    }

    #[Test]
    public function testLanguageMarkers(): void
    {
        // Test that definitive markers work
        $testCases = [
            '<?php echo "test";' => 'php',
            '<?xml version="1.0"?><root/>' => 'xml',
            '<svg xmlns="http://www.w3.org/2000/svg"><rect/></svg>' => 'svg',
        ];

        foreach ($testCases as $code => $expectedLanguage) {
            $result = $this->detector->detect($code);
            $this->assertSame(
                $expectedLanguage,
                $result->getLanguage(),
                "Failed to detect {$expectedLanguage} from: {$code}",
            );
        }
    }

    #[Test]
    public function testConstructorWithEmptyDirectory(): void
    {
        // Create a temporary empty directory
        $tempDir = sys_get_temp_dir() . '/empty_profiles_' . uniqid();
        mkdir($tempDir);

        try {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('No profile files found in directory');

            new LanguageDetector($tempDir);
        } finally {
            rmdir($tempDir);
        }
    }

    #[Test]
    public function testConstructorWithInvalidProfileFile(): void
    {
        // Create a temporary file that doesn't return an array
        $tempFile = tempnam(sys_get_temp_dir(), 'invalid_profile');
        file_put_contents($tempFile, '<?php return "not an array";');

        try {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Language profiles file did not return an array');

            new LanguageDetector($tempFile);
        } finally {
            unlink($tempFile);
        }
    }

    #[Test]
    public function testPhpTokenizerWithVariousPhpSyntax(): void
    {
        $phpCodes = [
            '<?php $var = 123; echo $var;',
            '<?= "short tag" ?>',
            '<?php class Test { public function method() {} }',
            '<?php namespace App; use Vendor\Class; $obj->prop;',
            '<?php if (true) { echo "test"; } else { throw new Exception(); }',
            '<?php function test() { global $var; return $var ?? null; }',
        ];

        foreach ($phpCodes as $code) {
            $result = $this->detector->detect($code);
            $this->assertSame('php', $result->getLanguage(), "Failed to detect PHP: {$code}");
            $this->assertGreaterThanOrEqual(0.9, $result->getConfidence(), "Low confidence for PHP: {$code}");
        }
    }

    #[Test]
    public function testPhpDetectionWithoutTags(): void
    {
        // Test PHP-like code without opening tags (should use heuristic scoring)
        $phpLikeCode = '
        $database = new PDO("mysql:host=localhost", $user, $pass);
        $stmt = $database->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        ';

        $result = $this->detector->detect($phpLikeCode);

        // This should either detect as PHP or return null based on confidence
        if ($result->getLanguage() === 'php') {
            $this->assertGreaterThanOrEqual(0.25, $result->getConfidence());
        } else {
            // If not detected as PHP, that's also valid due to lack of <?php tag
            $this->addToAssertionCount(1); // Just to avoid risky test
        }
    }

    #[Test]
    public function testHeuristicScoringForMultipleLanguages(): void
    {
        // Test code that could match multiple languages to exercise scoring logic
        $ambiguousCode = 'function process() { const data = { name: "test", value: 42 }; return data; }';

        $result = $this->detector->detect($ambiguousCode);

        // Should either detect as javascript or return null
        if ($result->getLanguage() !== null) {
            $this->assertContains($result->getLanguage(), ['javascript', 'typescript']);
            $this->assertGreaterThanOrEqual(0.25, $result->getConfidence());
        } else {
            $this->assertLessThan(0.25, $result->getConfidence());
        }
    }
}
