<?php

declare(strict_types=1);

/**
 * Language Profile for TypeScript detection.
 * For use with Alto\LanguageDetector
 *
 * This file returns an array containing the heuristic definition for TypeScript.
 * TypeScript extends JavaScript with static typing.
 *
 * @return array The language profiles array containing 'typescript'.
 */

return [
    'typescript' => [
        'keywords' => [
            // TypeScript-specific keywords
            'interface' => 5, 'type' => 4, 'namespace' => 3, 'declare' => 4, 'module' => 3,
            'enum' => 4, 'readonly' => 3, 'abstract' => 3, 'implements' => 3, 'extends' => 2,
            'public' => 3, 'private' => 3, 'protected' => 3, 'static' => 2,
            'as' => 2, 'keyof' => 3, 'typeof' => 2, 'instanceof' => 2,
            // Common JavaScript keywords (lower weight)
            'function' => 1, 'const' => 2, 'let' => 2, 'var' => 1,
            'import' => 2, 'export' => 2, 'default' => 1,
            'class' => 2, 'constructor' => 2, 'super' => 1,
            'async' => 2, 'await' => 2, 'Promise' => 1,
        ],
        'patterns' => [
            // Type annotations
            '/:\s*[A-Z][a-zA-Z0-9_<>[\]|&]*/' => 4,        // : Type
            '/:\s*[a-z][a-zA-Z0-9_]*\[\]/' => 3,            // : type[]
            '/:\s*\{[^}]*\}/' => 3,                         // : { prop: type }
            '/:\s*\([^)]*\)\s*=>\s*[A-Za-z]/' => 4,         // : (param) => ReturnType

            // Generic types
            '/\<[A-Z][a-zA-Z0-9_,\s]*\>/' => 3,            // <T>, <T, U>
            '/[a-zA-Z_][a-zA-Z0-9_]*\<[^>]*\>/' => 3,       // Array<string>

            // Interface/type definitions
            '/interface\s+[A-Z][a-zA-Z0-9_]*\s*\{/' => 5,   // interface MyInterface {
            '/type\s+[A-Z][a-zA-Z0-9_]*\s*=/' => 5,         // type MyType =
            '/enum\s+[A-Z][a-zA-Z0-9_]*\s*\{/' => 5,        // enum MyEnum {

            // Decorators
            '/@[a-zA-Z_][a-zA-Z0-9_]*/' => 3,               // @decorator

            // Access modifiers
            '/(?:public|private|protected)\s+(?:readonly\s+)?[a-zA-Z_]/' => 3,

            // Arrow functions with types
            '/\([^)]*:\s*[a-zA-Z_][^)]*\)\s*=>\s*[a-zA-Z_]/' => 4,

            // Module declarations
            '/declare\s+(?:module|namespace|global)/' => 4,

            // Import/export with types
            '/import\s+type\s+/' => 4,
            '/export\s+type\s+/' => 4,
            '/import\s+\{[^}]*type[^}]*\}/' => 3,

            // Type assertions
            '/\<[A-Za-z_][^>]*\>[a-zA-Z_]/' => 3,           // <Type>variable
            '/as\s+[A-Z][a-zA-Z0-9_]*/' => 3,               // as Type

            // Optional/nullable types
            '/\?\s*:/' => 2,                                // optional properties
            '/!\s*\./' => 2,                                // non-null assertion
        ],
        'negative_keywords' => [
            // Penalize pure JavaScript patterns that aren't TypeScript
            '<?php' => -10,
            'def ' => -5, 'print(' => -3,                   // Python
            'console.log' => -2,                            // More likely plain JS
            'document.getElementById' => -2,                // Browser JS
            'window.' => -2,                                // Browser JS
        ],
        'comment_markers' => ['//', '/*'],
    ],
];
