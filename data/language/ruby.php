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
    'ruby' => [
        'markers' => [
            '#!/usr/bin/env ruby' => 10,      // Ruby shebang
            '#!/usr/bin/ruby' => 10,          // Ruby shebang variant
            '#!ruby' => 8,                    // Windows ruby shebang
            'require_relative' => 8,          // Ruby-specific require
            'puts "' => 7,                    // Ruby puts statement
            'p "' => 6,                       // Ruby p statement
        ],
        'keywords' => [
            // Ruby keywords and methods
            'def ' => 3, 'class ' => 3, 'module ' => 3, 'end' => 3,
            'require ' => 2, 'include ' => 2, 'extend ' => 2,
            'if ' => 1, 'elsif ' => 2, 'else' => 1, 'unless ' => 2,
            'case ' => 2, 'when ' => 2, 'then ' => 2,
            'for ' => 1, 'while ' => 1, 'until ' => 2, 'loop ' => 2,
            'begin' => 2, 'rescue' => 3, 'ensure' => 3, 'retry' => 2,
            'break' => 1, 'next' => 2, 'redo' => 2, 'return' => 1,
            'yield' => 2, 'super' => 2, 'self' => 2,
            // Ruby-specific methods
            'puts ' => 3, 'print ' => 2, 'p ' => 2, 'gets' => 2,
            'attr_reader' => 3, 'attr_writer' => 3, 'attr_accessor' => 3,
            'initialize' => 3, 'new' => 1, 'nil' => 2, 'true' => 1, 'false' => 1,
            // Ruby operators and syntax
            '=>' => 2, '<<' => 1, '>>' => 1, '**' => 1,
            '.each' => 2, '.map' => 2, '.select' => 2, '.reject' => 2,
            '.times' => 2, '.length' => 1, '.size' => 1, '.count' => 1,
            // Ruby-specific syntax patterns
            '@' => 1, '@@' => 2, '$' => 1, // Instance, class, global variables
        ],
        'patterns' => [
            '/^[ \t]*#.*$/m' => 1,                    // Ruby comments
            '/\bdef\s+\w+.*$/m' => 3,                 // Method definitions
            '/\bclass\s+\w+.*$/m' => 3,               // Class definitions
            '/\bmodule\s+\w+.*$/m' => 3,              // Module definitions
            '/\bend\s*$/m' => 2,                      // End statements
            '/@\w+/' => 2,                            // Instance variables
            '/@@\w+/' => 3,                           // Class variables
            '/\$\w+/' => 2,                           // Global variables
            '/:\w+/' => 2,                            // Symbols
            '/\w+:/' => 1,                            // Hash syntax (Ruby 1.9+)
            '/\|\w+\|/' => 2,                         // Block parameters
            '/do\s*\|.*?\|/' => 3,                    // do blocks with parameters
            '/\{.*?\|.*?\|.*?\}/' => 2,               // Inline blocks with parameters
            '/<<[-~]?\w+/' => 3,                      // Heredoc syntax
            '/=~\s*\/.*?\//' => 3,                    // Regex matching
            '/\w+\?/' => 2,                           // Method names ending with ?
            '/\w+!/' => 2,                            // Method names ending with !
            '/\bproc\s*\{/' => 3,                     // Proc syntax
            '/\blambda\s*\{/' => 3,                   // Lambda syntax
            '/\b->\s*\{/' => 3,                       // Stabby lambda (Ruby 1.9+)
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
            '$_' => -5,            // PHP (unless it's a Ruby global var)
            'public ' => -3,       // Java/C#/PHP
            'private ' => -3,      // Java/C#/PHP (Ruby uses different syntax)
            'protected ' => -3,    // Java/C#/PHP
            '#include' => -5,      // C/C++
            'using namespace' => -5, // C++
            'std::' => -5,         // C++
            '</html>' => -5,       // HTML
            '<script>' => -5,      // HTML/JavaScript
            'import ' => -2,       // Python/Java/JavaScript (Ruby uses require)
            'from ' => -3,         // Python
            'def(' => -3,          // Python function calls that look like defs
            '__init__' => -5,      // Python
            '__main__' => -5,      // Python
        ],
    ],
];
