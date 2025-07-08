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

return [
    'html' => [
        'markers' => [
            '<!DOCTYPE html', '<!doctype html', // Case variations
            '<html', '<head', '<body', '<meta', '<title', '<link', '<style', '<script', // Common top-level/head elements
        ],
        'keywords' => [
            // HTML has very few "keywords" in the programming sense, relies on tags/structure
        ],
        'patterns' => [
            // DOCTYPE (Very high confidence)
            '/^\s*<!DOCTYPE\s+html/i' => 10,
            // Opening and closing tag pair (e.g., <p>...</p>) - Handles attributes, case-insensitive tag name
            '/<([a-zA-Z0-9\-\:]+)\b[^>]*>.*?<\/\1\s*>/si' => 4,
            // Standalone opening tag (e.g., <div>, <span ...>)
            '/<[a-zA-Z0-9\-\:]+\b[^>]*[^ L]\/?>/si' => 2, // Added [^ L]\/? to avoid matching self-closing within pair regex maybe? Needs careful regex crafting. Simpler: /<[a-zA-Z0-9\-\:]+\b[^>]*>/si => 2
            // Self-closing tag (e.g., <br />, <img .../>)
            '/<[a-zA-Z0-9\-\:]+\b[^>]*\/\s*>/si' => 3,
            // HTML Comment
            '//s' => 2,
            // HTML Entities
            '/&\w+;/' => 1, // &nbsp; &lt; etc.
            '/&#?\w+;/' => 1, // Includes numeric entities
        ],
        // Penalize syntax from other languages often found nearby
        'negative_keywords' => [
            '<?php' => -7, '<?=' => -7,       // PHP tags
            '{%' => -7, '{{' => -7, '{#' => -7, // Twig delimiters
            '$' => -3,                      // PHP/Sass variables
            '->' => -5, '::' => -5,            // PHP object/static access
            'function ' => -3, 'var ' => -5, 'let ' => -5, 'const ' => -5, // JS keywords
            '@media' => -3, '@mixin' => -7, '{ color:' => -3, // CSS / SCSS
            'SELECT ' => -7,                // SQL
            'def ' => -7, 'import ' => -3,     // Python/JS
        ],
    ],
];
