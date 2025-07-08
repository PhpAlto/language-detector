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
    'python' => [
        'markers' => [
            '#!/usr/bin/env python' => 10,    // Python shebang
            '#!/usr/bin/python' => 10,        // Python shebang variant
            '#!python' => 8,                  // Windows python shebang
            'from __future__ import' => 9,    // Python future imports
            'if __name__ == "__main__":' => 8, // Python main guard
        ],
        'keywords' => [
            // Python keywords
            'def ' => 3, 'class ' => 3, 'import ' => 2, 'from ' => 2, 'as ' => 1,
            'if ' => 1, 'elif ' => 2, 'else:' => 2, 'for ' => 1, 'while ' => 1,
            'try:' => 2, 'except:' => 2, 'except ' => 2, 'finally:' => 2,
            'with ' => 2, 'lambda ' => 2, 'yield ' => 2, 'return ' => 1,
            'pass' => 2, 'break' => 1, 'continue' => 1, 'raise ' => 2,
            'assert ' => 2, 'global ' => 2, 'nonlocal ' => 2,
            // Python built-ins
            'print(' => 2, 'len(' => 1, 'range(' => 2, 'enumerate(' => 2,
            'zip(' => 1, 'map(' => 1, 'filter(' => 1, 'isinstance(' => 2,
            'hasattr(' => 2, 'getattr(' => 2, 'setattr(' => 2,
            '__init__' => 3, '__str__' => 2, '__repr__' => 2, '__len__' => 2,
            // Python-specific syntax
            'self.' => 2, 'cls.' => 2, '@property' => 3, '@staticmethod' => 3,
            '@classmethod' => 3, '__doc__' => 2, '__file__' => 2, '__name__' => 2,
        ],
        'patterns' => [
            '/^[ \t]*#.*$/m' => 1,                    // Python comments
            '/"""[\s\S]*?"""/m' => 2,                 // Triple-quoted docstrings
            "/'''[\s\S]*?'''/m" => 2,                 // Triple-quoted docstrings (single quotes)
            '/\bdef\s+\w+\s*\([^)]*\)\s*:/' => 3,     // Function definitions
            '/\bclass\s+\w+\s*(?:\([^)]*\))?\s*:/' => 3, // Class definitions
            '/\bfor\s+\w+\s+in\s+/' => 2,             // For loops with 'in'
            '/\bif\s+.*:\s*$/' => 2,                  // If statements ending with colon
            '/^\s*@\w+/' => 2,                        // Decorators
            '/\b\w+\s*=\s*\[.*\]/' => 1,              // List assignments
            '/\b\w+\s*=\s*\{.*\}/' => 1,              // Dict assignments
            '/\bf"[^"]*\{[^}]+\}[^"]*"/' => 3,        // f-strings
            '/\br"[^"]*"/' => 2,                      // Raw strings
        ],
        'negative_keywords' => [
            // Strong indicators of other languages
            'function(' => -5,     // JavaScript/PHP
            'var ' => -3,          // JavaScript
            'const ' => -2,        // JavaScript/C++
            'let ' => -3,          // JavaScript
            'console.log' => -5,   // JavaScript
            'document.' => -5,     // JavaScript
            'window.' => -5,       // JavaScript
            '<?php' => -10,        // PHP
            'echo ' => -3,         // PHP
            '$_' => -5,            // PHP
            'public ' => -3,       // Java/C#/PHP
            'private ' => -3,      // Java/C#/PHP
            'protected ' => -3,    // Java/C#/PHP
            '#include' => -5,      // C/C++
            'using namespace' => -5, // C++
            'std::' => -5,         // C++
            '</html>' => -5,       // HTML
            '<script>' => -5,      // HTML/JavaScript
        ],
    ],
];
