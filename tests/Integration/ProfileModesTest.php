<?php

declare(strict_types=1);

namespace Alto\LanguageDetector\Tests\Integration;

use Alto\LanguageDetector\LanguageDetector;
use Alto\LanguageDetector\DetectionResult;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Integration tests comparing consolidated vs individual profile modes.
 */
#[CoversClass(LanguageDetector::class)]
#[CoversClass(DetectionResult::class)]
final class ProfileModesTest extends TestCase
{
    private LanguageDetector $consolidatedDetector;
    private LanguageDetector $individualDetector;

    protected function setUp(): void
    {
        parent::setUp();

        // Consolidated profiles (20+ languages)
        $this->consolidatedDetector = new LanguageDetector(__DIR__ . '/../../data/languages_signatures.php');

        // Individual profiles (8 enhanced languages)
        $this->individualDetector = new LanguageDetector(__DIR__ . '/../../data/language');
    }

    /**
     * Test that both modes can detect common languages correctly.
     */
    #[Test]
    #[DataProvider('commonLanguagesProvider')]
    public function testBothModesDetectCommonLanguages(string $code, string $expectedLanguage): void
    {
        $consolidatedResult = $this->consolidatedDetector->detect($code);
        $individualResult = $this->individualDetector->detect($code);

        // Both should detect the same language
        $this->assertSame(
            $expectedLanguage,
            $consolidatedResult->getLanguage(),
            "Consolidated mode failed to detect {$expectedLanguage}",
        );
        $this->assertSame(
            $expectedLanguage,
            $individualResult->getLanguage(),
            "Individual mode failed to detect {$expectedLanguage}",
        );

        // Both should have reasonable confidence
        $this->assertGreaterThan(
            0.25,
            $consolidatedResult->getConfidence(),
            "Consolidated mode confidence too low for {$expectedLanguage}",
        );
        $this->assertGreaterThan(
            0.25,
            $individualResult->getConfidence(),
            "Individual mode confidence too low for {$expectedLanguage}",
        );
    }

    /**
     * Provides test cases for languages supported by both modes.
     */
    public static function commonLanguagesProvider(): array
    {
        return [
            'PHP with tag' => ['<?php class Test { public $var = 42; function method() { return $this->var; } }', 'php'],
            'JavaScript const' => ['const message = "Hello"; function greet() { console.log(message); } export { greet };', 'javascript'],
            'TypeScript interface' => ['interface User { name: string; age: number; } function createUser(): User { return { name: "test", age: 25 }; }', 'typescript'],
            'HTML document' => ['<!DOCTYPE html><html><head><title>Test</title></head><body><div class="container"><p>Hello</p></div></body></html>', 'html'],
            'CSS styles' => ['.container { display: flex; padding: 1rem; } .header { background: #333; color: white; }', 'css'],
        ];
    }

    /**
     * Test that consolidated mode supports languages not in individual mode.
     */
    #[Test]
    #[DataProvider('consolidatedOnlyLanguagesProvider')]
    public function testConsolidatedOnlyLanguages(string $code, string $expectedLanguage): void
    {
        $consolidatedResult = $this->consolidatedDetector->detect($code);
        $individualResult = $this->individualDetector->detect($code);

        // Consolidated should detect the language
        $this->assertSame(
            $expectedLanguage,
            $consolidatedResult->getLanguage(),
            "Consolidated mode should detect {$expectedLanguage}",
        );
        $this->assertGreaterThan(0.25, $consolidatedResult->getConfidence());

        // Individual should not detect it (return null or different language)
        $this->assertNotSame(
            $expectedLanguage,
            $individualResult->getLanguage(),
            "Individual mode should not detect {$expectedLanguage}",
        );
    }

    /**
     * Provides test cases for languages only supported by consolidated mode.
     */
    public static function consolidatedOnlyLanguagesProvider(): array
    {
        return [
            'Java class' => ['public class HelloWorld {\n    private String message;\n    public HelloWorld() {\n        this.message = "Hello";\n    }\n    public static void main(String[] args) {\n        System.out.println("Hello World");\n    }\n}', 'java'],
            'Go package' => ['package main\n\nimport "fmt"\n\nfunc main() {\n    message := "Hello World"\n    fmt.Println(message)\n}\n\ntype User struct {\n    Name string\n    Age  int\n}', 'go'],
            'Ruby class' => ['class User\n  attr_accessor :name, :email\n  \n  def initialize(name, email)\n    @name = name\n    @email = email\n  end\n  \n  def greet\n    puts "Hello #{@name}"\n  end\nend', 'ruby'],
            'JSON object' => ['{\n  "name": "test",\n  "value": 42,\n  "active": true,\n  "data": {\n    "items": [1, 2, 3],\n    "config": null\n  }\n}', 'json'],
            'Bash script' => ['#!/bin/bash\nset -e\n\nNAME="World"\necho "Hello $NAME"\n\nif [ "$1" == "test" ]; then\n  echo "Test mode enabled"\n  export DEBUG=1\nfi\n\nfunction cleanup() {\n  echo "Cleaning up..."\n}\n\ntrap cleanup EXIT', 'bash'],
        ];
    }

    /**
     * Test that individual mode supports languages not in consolidated mode.
     */
    #[Test]
    #[DataProvider('individualOnlyLanguagesProvider')]
    public function testIndividualOnlyLanguages(string $code, string $expectedLanguage): void
    {
        $consolidatedResult = $this->consolidatedDetector->detect($code);
        $individualResult = $this->individualDetector->detect($code);

        // Individual should detect the language
        $this->assertSame(
            $expectedLanguage,
            $individualResult->getLanguage(),
            "Individual mode should detect {$expectedLanguage}",
        );
        $this->assertGreaterThan(0.25, $individualResult->getConfidence());

        // Consolidated should not detect it specifically (might detect as xml/html)
        $this->assertNotSame(
            $expectedLanguage,
            $consolidatedResult->getLanguage(),
            "Consolidated mode should not detect {$expectedLanguage} specifically",
        );
    }

    /**
     * Provides test cases for languages only supported by individual mode.
     */
    public static function individualOnlyLanguagesProvider(): array
    {
        return [
            'SVG graphic' => ['<svg xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40"/></svg>', 'svg'],
            'Twig template' => ['{{ user.name|upper }} {% if user.active %}Active{% endif %}', 'twig'],
            'XML document' => ['<?xml version="1.0"?><root><item>value</item></root>', 'xml'],
        ];
    }

    /**
     * Test that individual mode has enhanced detection for supported languages.
     */
    #[Test]
    public function testIndividualModeEnhancedDetection(): void
    {
        // SCSS code that should be better detected in individual mode
        $scssCode = '$primary: #007bff; @mixin button { background: $primary; } .btn { @include button; }';

        $consolidatedResult = $this->consolidatedDetector->detect($scssCode);
        $individualResult = $this->individualDetector->detect($scssCode);

        // Both should detect CSS/SCSS, but individual might have higher confidence
        $this->assertContains($consolidatedResult->getLanguage(), ['css', 'scss', null]);
        $this->assertSame('scss', $individualResult->getLanguage());

        // Individual mode should have higher confidence for SCSS-specific syntax
        $this->assertGreaterThanOrEqual(
            $consolidatedResult->getConfidence(),
            $individualResult->getConfidence(),
        );
    }

    /**
     * Test PHP tokenizer works in both modes.
     */
    #[Test]
    public function testPhpTokenizerInBothModes(): void
    {
        $phpCode = '<?php namespace App; class Test { public function method() { return $this->property; } }';

        $consolidatedResult = $this->consolidatedDetector->detect($phpCode);
        $individualResult = $this->individualDetector->detect($phpCode);

        // Both should use PHP tokenizer and have high confidence
        $this->assertSame('php', $consolidatedResult->getLanguage());
        $this->assertSame('php', $individualResult->getLanguage());

        // Both should have very high confidence due to tokenizer
        $this->assertGreaterThan(0.9, $consolidatedResult->getConfidence());
        $this->assertGreaterThan(0.9, $individualResult->getConfidence());
    }

    /**
     * Test that both modes handle ambiguous code appropriately.
     */
    #[Test]
    public function testAmbiguousCodeHandling(): void
    {
        // Very generic code that could be multiple languages
        $ambiguousCode = 'function test() { return true; }';

        $consolidatedResult = $this->consolidatedDetector->detect($ambiguousCode);
        $individualResult = $this->individualDetector->detect($ambiguousCode);

        // Both should either detect a language with reasonable confidence or return null
        if ($consolidatedResult->getLanguage() !== null) {
            $this->assertGreaterThanOrEqual(0.25, $consolidatedResult->getConfidence());
        }

        if ($individualResult->getLanguage() !== null) {
            $this->assertGreaterThanOrEqual(0.25, $individualResult->getConfidence());
        }
    }
}
