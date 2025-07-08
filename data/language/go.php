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
    'go' => [
        'markers' => [
            'package main' => 10,             // Go main package declaration
            'package ' => 8,                  // Any Go package declaration
            'func main()' => 9,               // Go main function
            'import (' => 8,                  // Go multi-line import
            'go.mod' => 10,                   // Go module file indicator
            'go mod' => 9,                    // Go module command
        ],
        'keywords' => [
            // Go keywords
            'package ' => 4, 'import ' => 3, 'func ' => 3, 'type ' => 3,
            'var ' => 2, 'const ' => 2, 'struct ' => 3, 'interface ' => 3,
            'map[' => 3, 'chan ' => 3, 'go ' => 2, 'defer ' => 3,
            'if ' => 1, 'else ' => 1, 'for ' => 1, 'range ' => 3,
            'switch ' => 2, 'case ' => 1, 'default:' => 2, 'fallthrough' => 3,
            'select ' => 3, 'break' => 1, 'continue' => 1, 'return' => 1,
            'goto ' => 2, 'nil' => 2, 'true' => 1, 'false' => 1,
            // Go built-in functions
            'make(' => 3, 'new(' => 2, 'len(' => 2, 'cap(' => 3,
            'append(' => 3, 'copy(' => 2, 'delete(' => 3,
            'panic(' => 3, 'recover(' => 3, 'close(' => 2,
            // Go-specific syntax
            ':=' => 3, '<-' => 3, // Short variable declaration, channel operator
            'fmt.Print' => 4, 'fmt.Sprintf' => 3, 'fmt.Errorf' => 3,
            'log.Print' => 3, 'log.Fatal' => 3, 'os.Exit' => 3,
            // Go common patterns
            'err != nil' => 4, 'if err != nil' => 5,
            'func(' => 2, ') error' => 3, ') (error)' => 3,
            '&' => 1, '*' => 1, // Pointers (common in Go)
        ],
        'patterns' => [
            '/^package\s+\w+\s*$/m' => 4,                     // Package declaration
            '/^import\s*\([\s\S]*?\)/m' => 4,                 // Multi-line imports
            '/^import\s+"[^"]+"\s*$/m' => 3,                  // Single-line imports
            '/func\s+\w+\s*\([^)]*\)\s*[^{]*\{/' => 4,        // Function definitions
            '/func\s+\([^)]*\)\s+\w+\s*\([^)]*\)\s*[^{]*\{/' => 4, // Method definitions
            '/type\s+\w+\s+struct\s*\{/' => 4,                // Struct definitions
            '/type\s+\w+\s+interface\s*\{/' => 4,             // Interface definitions
            '/\bmap\[[^\]]+\][^{]+/' => 3,                    // Map declarations
            '/\bchan\s+\w+/' => 3,                            // Channel declarations
            '/\bgo\s+\w+\s*\(/' => 3,                         // Goroutine calls
            '/\bdefer\s+\w+\s*\(/' => 3,                      // Defer statements
            '/\w+\s*:=\s*/' => 3,                             // Short variable declarations
            '/<-\s*\w+/' => 3,                                // Channel receives
            '/\w+\s*<-/' => 3,                                // Channel sends
            '/if\s+err\s*!=\s*nil\s*\{/' => 4,                // Error checking pattern
            '/\berr\s*!=\s*nil/' => 3,                        // Error comparison
            '/\)\s*error\s*\{/' => 3,                         // Functions returning error
            '/\bfmt\.\w+\(/' => 3,                            // fmt package usage
            '/^\/\/.*$/m' => 1,                               // Go comments
            '/\/\*[\s\S]*?\*\//' => 1,                        // Block comments
        ],
        'negative_keywords' => [
            // Strong indicators of other languages
            'function(' => -5,     // JavaScript/PHP
            '<?php' => -10,        // PHP
            'echo ' => -4,         // PHP
            '$' => -3,             // PHP/Perl variables (Go doesn't use $)
            'console.log' => -5,   // JavaScript
            'document.' => -5,     // JavaScript
            'window.' => -5,       // JavaScript
            'var ' => -2,          // JavaScript (Go uses var differently)
            'let ' => -4,          // JavaScript
            'const ' => -2,        // JavaScript (Go uses const differently)
            'def ' => -4,          // Python/Ruby
            'class ' => -3,        // Python/Ruby/Java (Go doesn't have classes)
            'import ' => -1,       // Python (but Go also uses import, so light penalty)
            'from ' => -4,         // Python
            '__init__' => -5,      // Python
            'self.' => -4,         // Python
            'puts ' => -4,         // Ruby
            'attr_' => -4,         // Ruby
            '@' => -3,             // Ruby instance variables
            'public ' => -4,       // Java/C#
            'private ' => -4,      // Java/C#
            'protected ' => -4,    // Java/C#
            '#include' => -5,      // C/C++
            'using namespace' => -5, // C++
            'std::' => -5,         // C++
            '</html>' => -5,       // HTML
            '<script>' => -5,      // HTML/JavaScript
        ],
    ],
];
