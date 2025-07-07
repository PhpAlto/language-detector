<?php

declare(strict_types=1);

/**
 * Language Profiles for CSS and SCSS (Sass) detection.
 * For use with Alto\LanguageDetector
 *
 * This file returns an array containing heuristic definitions.
 * SCSS profile focuses on SCSS-specific syntax extensions over CSS.
 * CSS profile includes penalties for SCSS-specific syntax.
 *
 * @return array The language profiles array containing 'css' and 'scss'.
 */

return [
    'css' => [
        'markers' => [
            '@charset' => 8,
            '@import url(' => 7,
            '@namespace' => 6,
        ], // Standard CSS @rules often at start
        'keywords' => [ // Common properties (non-exhaustive list)
            'color' => 1, 'background' => 1, 'background-color' => 1, 'font-family' => 1, 'font-size' => 1, 'margin' => 1, 'padding' => 1, 'width' => 1, 'height' => 1, 'border' => 1, 'display' => 1, 'position' => 1, '!important' => 2, 'content' => 1, 'flex' => 1, 'grid' => 1, 'transition' => 1, 'animation' => 1, 'transform' => 1, 'filter' => 1, 'box-shadow' => 1, 'z-index' => 1, 'opacity' => 1, 'cursor' => 1, 'list-style' => 1, 'text-decoration' => 1, 'float' => 1, 'clear' => 1,
        ],
        'patterns' => [
            '/(?<!\w)@media\s+/' => 3,         // @media rule (strong indicator) - negative lookbehind for things like email@media
            '/(?<!\w)@keyframes\s+/' => 3,     // @keyframes rule
            '/(?<!\w)@font-face\s*\{/' => 3,   // @font-face rule
            '/(?<!\w)@supports\s+\(/' => 2,    // @supports rule
            '/(?:^|\s)[#.]?[\w\-]+\s*\{/' => 2, // Standard Selector { e.g. .class {, #id {, tag {
            '/\w[\w\-]*\s*:\s*[^;\{]+;/' => 2,  // Standard property: value;
            '/:\s*hover/' => 1, '/:\s*active/' => 1, '/:\s*focus/' => 1, // Pseudo-classes
            '/::\s*before/' => 1, '/::\s*after/' => 1, // Pseudo-elements
            '/#[0-9a-fA-F]{3,8}\b/' => 1,        // Hex colors
            '/rgba?\(/i' => 1, '/hsla?\(/i' => 1, // Color functions
            '/\d+(?:\.\d+)?(?:px|em|rem|%|vw|vh)\b/' => 1, // Common Units
            '/calc\(/i' => 1,                     // calc function
            '/url\(["\']?.*?["\']?\)/i' => 1, // url() function
            '/var\(--[\w\-]+\)/' => 1,             // CSS Variables var(--name)
        ],
        // Penalize heavily for SCSS/Sass specific syntax
        'negative_keywords' => [
            '$' => -5,               // SCSS variables start with $
            '@mixin' => -10,         // SCSS mixin definition
            '@include' => -10,        // SCSS mixin include
            '@extend' => -10,         // SCSS extend
            '@function' => -10,       // SCSS function definition
            '@return' => -10,         // SCSS function return
            '@if' => -7,              // SCSS control directive
            '@else' => -7,            // SCSS control directive
            '@for' => -7,             // SCSS control directive
            '@each' => -7,            // SCSS control directive
            '@while' => -7,           // SCSS control directive
            '//' => -5,               // SCSS single line comment
            '#{' => -7,               // SCSS interpolation
            '&:' => -3,               // SCSS parent selector pseudo-class common pattern
            ' &' => -3,               // SCSS parent selector common pattern
        ],
        'comment_markers' => ['/*'], // Only standard block comments
    ],

    'scss' => [ // Assumes SCSS syntax for Sass
        'markers' => ['@import ["\'][^.]+\.(?:scss|sass)["\'];', '//', '@mixin', '@function'], // SCSS specific import, comments, directives often early
        'keywords' => [ // SCSS specific directives + common CSS properties (lower weight than in CSS profile?)
            '@mixin' => 5, '@include' => 5, '@extend' => 5, '@function' => 5, '@return' => 5, '@if' => 4, '@else' => 4, '@for' => 4, '@each' => 4, '@while' => 4, '@debug' => 2, '@warn' => 2, '@error' => 2,
            // Include some common CSS keywords too, maybe lower weight? Or rely on patterns? Let's add a few.
            'color' => 0.5, 'background' => 0.5, 'font-family' => 0.5, 'margin' => 0.5, 'padding' => 0.5, 'width' => 0.5, 'height' => 0.5, 'display' => 0.5, 'position' => 0.5, '!important' => 1,
        ],
        'patterns' => [
            '/\$[\w\-]+\s*:/' => 5,             // SCSS variable declaration: $var: value; (strong indicator)
            '/\$[\w\-]+/' => 1,                 // SCSS variable usage: $var (lower weight than declaration)
            '/#\{\s*\$[\w\-]+\s*\}/' => 4,      // SCSS interpolation: #{ $var }
            '/\/\/.*$/m' => 3,                  // SCSS single line comment
            '/\s&[\w:#\.\-\[\]]/' => 3,         // SCSS parent selector reference: & (e.g., &:hover, & + &) - needs space before typically
            '/[\w#.]\s*\{[^{}]*[\w#.]+\s*\{/' => 3, // Basic check for Nested Selectors: selector { selector { ... } } (can be inaccurate)
            '/[\+\-\*\/%]\s*(?:\$[\w\-]+|\d)/' => 1, // Basic operators used with variables or numbers
            '/url\(["\']?.*?["\']?\)/i' => 0.5, // url() - also valid CSS
            '/#[0-9a-fA-F]{3,8}\b/' => 0.5,      // Hex colors - also valid CSS
            '/:\s*hover/' => 0.5, '/:\s*active/' => 0.5, // Pseudo-classes - also valid CSS
            '/@media\s+/' => 1,                 // @media - also valid CSS but common in SCSS too
            '/@import ["\'][^.]+["\'];/i' => 4, // SCSS style import (no url(), no extension usually) - strong indicator vs CSS @import
            '/@use ["\'][^.]+["\'];/i' => 5,    // SCSS @use directive
            '/@forward ["\'][^.]+["\'];/i' => 5, // SCSS @forward directive
        ],
        'negative_keywords' => [
            '<?php' => -10,          // Penalize other languages
            '<script' => -10,
            'var ' => -5, 'let ' => -5, 'const ' => -5, // Penalize JS
            'def ' => -7, 'class ' => -7,             // Penalize Python/PHP/JS etc.
        ],
        'comment_markers' => ['//', '/*'], // Both comment types
    ],
];
