<?php

declare(strict_types=1);

namespace Alto\LanguageDetector\Tests\Language;

use Alto\LanguageDetector\Tests\BaseDetectionCase;
use Alto\LanguageDetector\LanguageDetector;
use Alto\LanguageDetector\DetectionResult;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

/**
 * Tests focused on PHP language detection.
 */
#[CoversClass(LanguageDetector::class)]
#[CoversClass(DetectionResult::class)]
final class PhpDetectionTest extends BaseDetectionCase
{
    /**
     * Tests that valid PHP snippets are correctly identified.
     */
    #[Test]
    #[DataProvider('phpSnippetsProvider')]
    public function testDetectsPhpCorrectly(string $snippet, float $minConfidence): void
    {
        $result = $this->detector->detect($snippet);
        $this->assertSame('php', $result->getLanguage(), "Failed asserting that snippet is PHP: " . $snippet);
        $this->assertGreaterThanOrEqual($minConfidence, $result->getConfidence(), "Confidence too low for snippet: " . $snippet);
    }

    /**
     * Provides snippets of valid PHP code.
     */
    public static function phpSnippetsProvider(): array
    {
        return [
            // label => [snippet, minConfidence]
            'php echo statement' => ['<?php echo "Hello";', 0.9],
            'php variable assignment' => ['<?php $name = "Test";', 0.9],
            'php object operator with variable' => ['<?php $obj->method();', 0.9],
            'php static call with tag' => ['<?php MyClass::callStatic();', 0.9],
            'php namespace' => ['<?php namespace App\\Controller;', 0.8],
            'php class with methods' => ['<?php class MyClass { public function test() { return true; } }', 0.9],
            'php function with variable' => ['<?php function greet($name) { return $name; }', 0.9],
        ];
    }

    /**
     * Tests that PHP snippets are NOT misidentified as other specific languages.
     */
    #[Test]
    #[DataProvider('phpMisidentificationProvider')]
    public function testPhpIsNotMisidentified(string $snippet, string $potentialIncorrectLanguage): void
    {
        $result = $this->detector->detect($snippet);
        if ($result->getLanguage() === $potentialIncorrectLanguage) {
            $this->assertLessThan(0.4, $result->getConfidence(), "PHP snippet incorrectly identified as {$potentialIncorrectLanguage} with high confidence: " . $snippet);
        } else {
            $this->assertNotSame($potentialIncorrectLanguage, $result->getLanguage(), "PHP snippet incorrectly identified as {$potentialIncorrectLanguage}: " . $snippet);
        }
    }

    /**
     * Provides PHP snippets and languages they might be confused with.
     */
    public static function phpMisidentificationProvider(): array
    {
        return [
            // label => [snippet, potentialIncorrectLanguage]
            'php echo vs js' => ['<?php echo $var;', 'javascript'],
            'php function vs js' => ['function my_php($a){ global $b; return $a.$b; }', 'javascript'],
            'php array vs css' => ['$arr = ["color" => "red"];', 'css'],
        ];
    }
}
