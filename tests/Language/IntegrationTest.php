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

namespace Alto\LanguageDetector\Tests\Language;

use Alto\LanguageDetector\DetectionResult;
use Alto\LanguageDetector\LanguageDetector;
use Alto\LanguageDetector\Tests\BaseDetectionCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(LanguageDetector::class)]
#[CoversClass(DetectionResult::class)]
final class IntegrationTest extends BaseDetectionCase
{
    #[Test]
    #[DataProvider('languageSnippetsProvider')]
    public function testDetectsLanguagesCorrectly(string $snippet, string $expectedLanguage): void
    {
        $result = $this->detector->detect($snippet);
        $this->assertSame(
            $expectedLanguage,
            $result->getLanguage(),
            "Failed detecting {$expectedLanguage}: {$snippet}",
        );
        $this->assertGreaterThan(
            0.25,
            $result->getConfidence(),
            "Confidence too low for {$expectedLanguage}: ".$result->getConfidence(),
        );
    }

    public static function languageSnippetsProvider(): array
    {
        return [
            ['<?php echo "Hello World"; $name = "test"; $obj->method();', 'php'],
            ['<?php class MyClass { public function test() { return true; } }', 'php'],

            // JavaScript
            ['function getData() { const result = await fetch("/api"); return result.json(); }', 'javascript'],
            ['let items = [1, 2, 3]; items.forEach(item => console.log(item));', 'javascript'],

            // TypeScript
            ['interface User { name: string; age: number; } const user: User = { name: "John", age: 25 };', 'typescript'],
            ['function processData<T>(data: T[]): T[] { return data.filter(item => item !== null); }', 'typescript'],

            // CSS
            ['@media (max-width: 768px) { .container { padding: 10px; } .header { font-size: 14px; } }', 'css'],
            ['.btn { background: #007bff; color: white; padding: 10px 20px; border-radius: 4px; }', 'css'],

            // SCSS
            ['$primary: #007bff; $padding: 1rem; @mixin btn { background: $primary; padding: $padding; } .button { @include btn; }', 'scss'],
            ['@mixin button-style($bg) { background: $bg; &:hover { opacity: 0.8; } } .btn { @include button-style(#blue); }', 'scss'],

            // HTML
            ['<!DOCTYPE html><html><head><title>Page</title></head><body><div class="content"><h1>Title</h1><p>Text</p></div></body></html>', 'html'],

            // XML
            ['<?xml version="1.0" encoding="UTF-8"?><root><item id="1"><name>Test</name></item></root>', 'xml'],

            // SVG
            ['<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="red"/></svg>', 'svg'],
            ['<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg"><rect x="10" y="10" width="80" height="80" fill="blue"/></svg>', 'svg'],
        ];
    }

    #[Test]
    #[DataProvider('disambiguationProvider')]
    public function testLanguageDisambiguation(string $snippet, string $correctLanguage, string $incorrectLanguage): void
    {
        $result = $this->detector->detect($snippet);

        $this->assertSame(
            $correctLanguage,
            $result->getLanguage(),
            "Should detect {$correctLanguage}, not {$incorrectLanguage}: {$snippet}",
        );
    }

    public static function disambiguationProvider(): array
    {
        return [
            // TypeScript vs JavaScript
            ['interface Config { apiUrl: string; } const config: Config = { apiUrl: "/api" };', 'typescript', 'javascript'],

            // SCSS vs CSS
            ['$primary: #333; .header { color: $primary; @include border-radius(5px); }', 'scss', 'css'],

            // SVG vs XML
            ['<svg xmlns="http://www.w3.org/2000/svg"><path d="M10,10 L90,90"/></svg>', 'svg', 'xml'],

            // PHP vs JavaScript (with PHP tags)
            ['<?php function calculate($a, $b) { return $a + $b; } echo calculate(5, 3);', 'php', 'javascript'],
        ];
    }
}
