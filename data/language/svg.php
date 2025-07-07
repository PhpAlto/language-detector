<?php

declare(strict_types=1);

/**
 * Language Profile for SVG (Scalable Vector Graphics) detection.
 * For use with Alto\LanguageDetector
 *
 * This file returns an array containing the heuristic definition for SVG.
 * It verifies an XML structure and looks for the <svg> root element,
 * the SVG namespace, and common SVG graphical elements and attributes.
 *
 * @return array The language profiles array containing only 'svg'.
 */

return [
    'svg' => [
        'markers' => [
            '<?xml ' => 8,  // XML declaration (high confidence)
            '<svg' => 10,   // SVG root element (highest confidence)
        ],
        'keywords' => [
            // SVG relies on element and attribute names, not keywords like programming languages
        ],
        'patterns' => [
            // **SVG Specific - Highest Confidence**
            '/<svg\b/i' => 10, // Presence of the <svg> tag itself (case-insensitive)
            '/xmlns\s*=\s*["\']http:\/\/www\.w3\.org\/2000\/svg["\']/i' => 10, // The specific SVG namespace - VERY strong indicator

            // **Common SVG Elements (High Confidence)**
            '/<path\b/i' => 5, '/<circle\b/i' => 4, '/<rect\b/i' => 4, '/<ellipse\b/i' => 4, '/<line\b/i' => 4, '/<polyline\b/i' => 4, '/<polygon\b/i' => 4, '/<text\b/i' => 3, '/<tspan\b/i' => 2, '/<g\b/i' => 3, '/<defs\b/i' => 3, '/<symbol\b/i' => 3, '/<use\b/i' => 3, '/<image\b/i' => 2, '/<clipPath\b/i' => 3, '/<mask\b/i' => 3, '/<filter\b/i' => 3, '/<linearGradient\b/i' => 3, '/<radialGradient\b/i' => 3, '/<pattern\b/i' => 3, '/<marker\b/i' => 3, '/<animate\b/i' => 2, '/<set\b/i' => 2, '/<animateTransform\b/i' => 2, '/<a\b/i' => 1, // SVG link tag (<a> is also HTML)

            // **Common SVG Attributes (Moderate to High Confidence)**
            '/\bviewBox\s*=\s*["\']/' => 5,  // viewBox attribute
            '/\bd\s*=\s*["\']/i' => 4,       // path data attribute
            '/\bpoints\s*=\s*["\']/i' => 3,  // points attribute for polyline/polygon
            '/\bfill\s*=\s*["\']/i' => 2,    // fill attribute
            '/\bstroke\s*=\s*["\']/i' => 2,  // stroke attribute
            '/\bstroke-width\s*=\s*["\']/i' => 2,
            '/\btransform\s*=\s*["\']/i' => 2,
            '/\bopacity\s*=\s*["\']/i' => 1,
            '/\bxmlns:xlink\s*=\s*["\']http:\/\/www\.w3\.org\/1999\/xlink["\']/i' => 3, // Common XLink namespace
            '/\bxlink:href\s*=\s*["\']/i' => 2, // Common XLink usage

            // Embedded CSS/JS (Lower weight - also in HTML)
            '/<style\b.*?>.*?<\/style>/si' => 1,
            '/<script\b.*?>.*?<\/script>/si' => 1, // Often uses <![CDATA[...]]> inside
            '/\bstyle\s*=\s*["\']/i' => 0.5, // Style attribute

            // **Basic XML Structure Confirmation (Low Weight)**
            '/^\s*<\?xml\s+version\s*=/i' => 1, // XML declaration (optional in SVG)
            '/<\?.*\?>/' => 0.5,                // Any Processing Instruction
            '//s' => 0.5,             // Comments
            '/<!\[CDATA\[.*?\]\]>/s' => 1,      // CDATA often used in script/style
            '/\b[\w\-]+:[\w\-]+\s*=\s*["\']/i' => 0.5, // Attributes with Namespaces (e.g., xlink:href) - covered above better
            '/<([a-zA-Z_][\w\-\:\.]*)\b[^>]*\/\s*>/s' => 0.5, // Self-closing tag syntax confirmation
            '/<\/([a-zA-Z_][\w\-\:\.]*)\s*>/s' => 0.5, // Closing tag syntax confirmation

        ],
        // Penalize syntax from other common languages, especially HTML specifics
        'negative_keywords' => [
            '<!DOCTYPE html' => -10, '<!doctype html' => -10, // Penalize HTML5 doctype specifically
            '<html' => -7, '<head' => -7, '<body' => -7, '<title' => -2, // Penalize structural HTML tags (title could appear in SVG)
            '<div' => -3, '<span' => -3, '<p' => -3, '<form' => -5, '<input' => -5, // Penalize common block/inline HTML
            '<?php' => -10, '<?=' => -10,       // PHP tags
            '{%' => -10, '{{' => -10, '{#' => -10,// Twig delimiters
            '$' => -5,                      // PHP/Sass variables
            'function ' => -5, 'var ' => -7, 'let ' => -7, 'const ' => -7, // JS keywords (unless inside <script>) - hard to check context well
            '{ color:' => -5,                // CSS rule (unless inside <style>)
            'SELECT ' => -7,                 // SQL
            'def ' => -7,                    // Python
            '@mixin' => -10,                // SCSS
        ],
    ],
];
