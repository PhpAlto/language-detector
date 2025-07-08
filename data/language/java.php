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
    'java' => [
        'markers' => [
            'public class ' => 10,            // Java class declaration
            'public static void main(' => 10, // Java main method
            'package ' => 8,                  // Java package declaration
            'import java.' => 9,              // Java standard library import
            'System.out.print' => 9,          // Java output
            '@Override' => 8,                 // Java annotation
        ],
        'keywords' => [
            // Java keywords
            'public ' => 3, 'private ' => 3, 'protected ' => 3, 'static ' => 3,
            'final ' => 2, 'abstract ' => 3, 'synchronized ' => 3, 'native ' => 3,
            'class ' => 3, 'interface ' => 3, 'enum ' => 3, 'extends ' => 3,
            'implements ' => 3, 'package ' => 3, 'import ' => 2,
            'if ' => 1, 'else ' => 1, 'for ' => 1, 'while ' => 1, 'do ' => 1,
            'switch ' => 2, 'case ' => 1, 'default:' => 2, 'break' => 1,
            'continue' => 1, 'return' => 1, 'throw ' => 2, 'throws ' => 3,
            'try ' => 2, 'catch ' => 3, 'finally ' => 3,
            'new ' => 2, 'this' => 2, 'super' => 3, 'null' => 2,
            'true' => 1, 'false' => 1, 'void ' => 3, 'boolean ' => 3,
            'byte ' => 3, 'short ' => 2, 'int ' => 2, 'long ' => 2,
            'float ' => 3, 'double ' => 3, 'char ' => 3,
            // Java-specific syntax
            'String ' => 3, 'Object ' => 3, 'List<' => 3, 'Map<' => 3,
            'ArrayList<' => 4, 'HashMap<' => 4, 'HashSet<' => 4,
            'System.out.' => 4, 'System.err.' => 4, 'System.in' => 4,
            'Integer.' => 3, 'Double.' => 3, 'Boolean.' => 3,
            'Math.' => 2, 'String.' => 2, 'Arrays.' => 3,
            // Java annotations
            '@Override' => 4, '@Deprecated' => 4, '@SuppressWarnings' => 4,
            '@Test' => 3, '@Before' => 3, '@After' => 3,
            // Common Java patterns
            '.length()' => 3, '.size()' => 3, '.toString()' => 3,
            '.equals(' => 3, '.hashCode()' => 3, '.getClass()' => 3,
        ],
        'patterns' => [
            '/^package\s+[\w.]+;\s*$/m' => 4,                   // Package declaration
            '/^import\s+[\w.]+;\s*$/m' => 3,                    // Import statements
            '/public\s+class\s+\w+/' => 4,                      // Public class declaration
            '/public\s+static\s+void\s+main\s*\(/' => 5,        // Main method
            '/public\s+\w+\s+\w+\s*\([^)]*\)\s*\{/' => 3,       // Public method
            '/private\s+\w+\s+\w+\s*\([^)]*\)\s*\{/' => 3,      // Private method
            '/@\w+/' => 3,                                      // Annotations
            '/\bclass\s+\w+\s+extends\s+\w+/' => 4,             // Class inheritance
            '/\bclass\s+\w+\s+implements\s+\w+/' => 4,          // Interface implementation
            '/\binterface\s+\w+/' => 4,                         // Interface declaration
            '/\benum\s+\w+/' => 4,                              // Enum declaration
            '/\bnew\s+\w+\s*\(/' => 3,                          // Object instantiation
            '/\bthrows?\s+\w+Exception/' => 4,                  // Exception throwing
            '/\btry\s*\{[\s\S]*?\}\s*catch\s*\(/' => 4,         // Try-catch blocks
            '/System\.out\.(print|println)/' => 4,              // System output
            '/String\[\]\s+\w+/' => 3,                          // String array declaration
            '/\w+<[\w\s,<>]+>/' => 3,                           // Generics usage
            '/\/\*\*[\s\S]*?\*\//' => 2,                        // Javadoc comments
            '/\/\/.*$/m' => 1,                                  // Single-line comments
            '/\/\*[\s\S]*?\*\//' => 1,                          // Multi-line comments
        ],
        'negative_keywords' => [
            // Strong indicators of other languages
            'function(' => -5,     // JavaScript/PHP
            '<?php' => -10,        // PHP
            'echo ' => -4,         // PHP
            '$' => -4,             // PHP/Perl variables (Java doesn't use $)
            'console.log' => -5,   // JavaScript
            'document.' => -5,     // JavaScript
            'window.' => -5,       // JavaScript
            'var ' => -3,          // JavaScript (Java uses specific types)
            'let ' => -4,          // JavaScript
            'const ' => -2,        // JavaScript (different from Java final)
            'def ' => -4,          // Python/Ruby
            'puts ' => -4,         // Ruby
            'attr_' => -4,         // Ruby
            '@' => -1,             // Ruby instance variables (but Java has annotations)
            'self.' => -4,         // Python
            'import ' => -1,       // Python (but Java also uses import)
            'from ' => -4,         // Python
            '__init__' => -5,      // Python
            'package main' => -5,  // Go
            'func ' => -3,         // Go
            ':=' => -5,            // Go
            'chan ' => -5,         // Go
            '#include' => -5,      // C/C++
            'using namespace' => -5, // C++
            'std::' => -5,         // C++
            '</html>' => -5,       // HTML
            '<script>' => -5,      // HTML/JavaScript
            'using System' => -4,  // C#
            'namespace ' => -3,    // C#
        ],
    ],
];
