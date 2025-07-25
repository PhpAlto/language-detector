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
    'xml' => [
        'markers' => [
            '<?xml ' => 10,  // The XML declaration is the strongest marker
        ],
        'keywords' => [
            'xmlns' => 3, 'version' => 2, 'encoding' => 2, 'standalone' => 2,
            'CDATA' => 4, 'DOCTYPE' => 2,
        ],
        'patterns' => [
            '/\<\?xml\s+version=["\'][0-9.]+["\']/' => 5,    // <?xml version="1.0"
            '/xmlns(?::[a-zA-Z]+)?=["\'][^"\']*["\']/' => 4,  // xmlns declarations
            '/\<!\[CDATA\[.*?\]\]\>/' => 5,                  // CDATA sections
            '/\<[a-zA-Z][a-zA-Z0-9:_-]*\/>/' => 3,           // Self-closing tags
            '/\<[a-zA-Z][a-zA-Z0-9:_-]*\s[^>]*\/>/' => 3,    // Self-closing with attributes
            '/\<\/[a-zA-Z][a-zA-Z0-9:_-]*\>/' => 2,          // Closing tags
            '/[a-zA-Z]+:[a-zA-Z]+/' => 2,                    // Namespaced elements
            '/\<\?[a-zA-Z-]+.*?\?\>/' => 3,                  // Processing instructions
        ],
        'negative_keywords' => [
            'html' => -3, 'head' => -3, 'body' => -3, 'div' => -2,  // HTML elements
            'script' => -3, 'style' => -2,                           // HTML-specific
            '<?php' => -10,                                          // PHP
            'function' => -2, 'var ' => -2,                          // JavaScript
        ],
        'comment_markers' => ['<!--'],
    ],
];
