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
    'javascript' => [
        'keywords' => [
            // Core JavaScript keywords
            'function' => 2, 'var' => 2, 'let' => 3, 'const' => 3,
            'if' => 1, 'else' => 1, 'for' => 1, 'while' => 1, 'do' => 1,
            'switch' => 1, 'case' => 1, 'break' => 1, 'continue' => 1,
            'return' => 1, 'throw' => 1, 'try' => 1, 'catch' => 1, 'finally' => 1,
            'class' => 2, 'extends' => 2, 'super' => 2, 'constructor' => 2,
            'new' => 1, 'this' => 1, 'typeof' => 1, 'instanceof' => 1,
            'async' => 2, 'await' => 2, 'Promise' => 2,
            'import' => 2, 'export' => 2, 'default' => 1, 'from' => 1,
            // Browser/Node.js specific
            'console' => 2, 'document' => 2, 'window' => 2, 'require' => 2,
        ],
        'patterns' => [
            // Variable declarations
            '/\b(?:var|let|const)\s+[a-zA-Z_$][a-zA-Z0-9_$]*\s*=/' => 3,

            // Function declarations/expressions
            '/function\s+[a-zA-Z_$][a-zA-Z0-9_$]*\s*\(/' => 3,
            '/function\s*\(/' => 2,                              // anonymous function

            // Arrow functions (without types - distinguishes from TypeScript)
            '/\([^)]*\)\s*=>\s*[^A-Z:]/' => 3,                   // avoid TS type annotations
            '/[a-zA-Z_$][a-zA-Z0-9_$]*\s*=>\s*[^A-Z:]/' => 3,    // single param arrow

            // Object methods
            '/[a-zA-Z_$][a-zA-Z0-9_$]*\s*\([^)]*\)\s*\{/' => 2,  // method() {}

            // Console/DOM patterns
            '/console\.[a-z]+\s*\(/' => 3,                       // console.log(), etc.
            '/document\.[a-zA-Z]+/' => 3,                        // document.getElementById, etc.
            '/window\.[a-zA-Z]+/' => 2,                          // window.location, etc.

            // Module patterns
            '/require\s*\(["\'][^"\']+["\']\)/' => 3,             // require('module')
            '/module\.exports\s*=/' => 3,                        // module.exports =
            '/exports\.[a-zA-Z_$]/' => 2,                        // exports.something

            // ES6+ patterns
            '/import\s+[a-zA-Z_$][^;]*from\s+["\']/' => 3,       // import ... from '...'
            '/export\s+(?:default\s+)?(?:function|class|const|let)/' => 3,

            // Template literals
            '/`[^`]*\$\{[^}]*\}[^`]*`/' => 3,                    // `template ${var}`

            // Destructuring
            '/(?:const|let|var)\s*\{[^}]*\}\s*=/' => 3,          // const {a, b} = obj
            '/(?:const|let|var)\s*\[[^\]]*\]\s*=/' => 2,         // const [a, b] = arr

            // Spread operator
            '/\.\.\./' => 2,                                     // ...spread

            // JSON-like objects (but avoid actual JSON)
            '/\{[^{}]*["\'][^"\']*["\']\s*:[^{}]*\}/' => 1,      // {"key": "value"}
        ],
        'negative_keywords' => [
            // TypeScript specific - penalize heavily
            'interface' => -5, 'type ' => -4, 'namespace' => -4,
            'declare' => -4, 'readonly' => -3, 'abstract' => -3,
            'implements' => -3, 'enum' => -3, 'keyof' => -4,
            'public' => -3, 'private' => -3, 'protected' => -3,

            // Other languages
            '<?php' => -10, 'def ' => -5, 'class ' => -2,       // PHP, Python (class penalty is light)
            'SELECT' => -5, 'INSERT' => -5,                     // SQL
            '<html' => -5, '<div' => -3,                        // HTML
        ],
        'comment_markers' => ['//', '/*'],
    ],
];
