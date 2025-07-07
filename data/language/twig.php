<?php

declare(strict_types=1);

/**
 * Language Profile for Twig template language detection.
 * For use with Alto\LanguageDetector
 *
 * This file returns an array containing the heuristic definition for Twig.
 *
 * @return array The language profiles array containing only 'twig'.
 */

return [
    'twig' => [
        'markers' => ['{% extends', '{% use', '{#'], // Common starting elements
        'keywords' => [
            // Common Tags (give high weight to unique block tags)
            'block' => 3, 'endblock' => 3, 'extends' => 5, 'include' => 2, 'embed' => 3, 'endembed' => 3,
            'if' => 1, 'else' => 1, 'elseif' => 1, 'endif' => 3,
            'for' => 2, 'in' => 1, 'endfor' => 3,
            'set' => 2, 'endset' => 2,
            'filter' => 2, 'endfilter' => 2,
            'macro' => 3, 'endmacro' => 3, 'import' => 2, 'from' => 2,
            'with' => 1, 'only' => 1, 'ignore missing' => 1,
            'verbatim' => 3, 'endverbatim' => 3,
            'apply' => 2, 'endapply' => 2,
            'do' => 1, 'flush' => 1, 'deprecated' => 1,
            // Common Tests/Operators
            'is' => 2, 'is not' => 2, 'defined' => 2, 'empty' => 1, 'null' => 1, 'divisible by' => 1, 'same as' => 1, 'iterable' => 1, 'even' => 1, 'odd' => 1, 'constant' => 1,
            'and' => 1, 'or' => 1, 'not' => 1,
            'matches' => 1, 'starts with' => 1, 'ends with' => 1,
        ],
        'patterns' => [
            // Core Delimiters (Highest Weight)
            '/\{\{.*?\}\}/s' => 5,   // Print statement: {{ ... }}
            '/\{%.*?%\}/s' => 5,   // Tag statement: {% ... %}
            '/\{#.*?#\}/s' => 4,   // Comment: {# ... #}
            // Filters
            '/\|\s*[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/' => 3, // | filter_name
            // Function Calls (within delimiters)
            '/(?:\{\{|\{%)\s*\w+\(.*?\)\s*(?:%\}|\}\})/' => 2, // function(...) inside delimiters
            // Variable Access (simple: word chars, dots, brackets)
            '/(?:\{\{|\{%)\s*[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\.\[\]\x7f-\xff]*\s*(?:%\}|\}\})/i' => 1,
            // Whitespace Control Indicators
            '/\{[%\{#]-/' => 1, '/-[%\}#]\}/' => 1,
            // Common attribute access
            '/\.\w+/' => 0.5, // e.g., user.name (could overlap with JS/PHP)
            // Common range operator
            '/\.\./' => 1, // e.g., 1..5
            // Common null coalescing operator
            '/\?\?/' => 1,
        ],
        // Penalize other language syntax heavily
        'negative_keywords' => [
            '<?php' => -10, '<?=' => -10,       // PHP tags
            '$' => -4,                      // PHP/Sass variables (allow some $ in strings)
            '->' => -7, '::' => -7,            // PHP object/static access
            'function ' => -5, 'var ' => -7, 'let ' => -7, 'const ' => -7, // JS keywords
            'console.log' => -5, 'document.' => -7, // JS specifics
            'public ' => -7, 'private ' => -7, 'static ' => -5, // PHP/Java keywords
            '#include' => -10,              // C/C++
            'import ' => -3,                // Python/JS/Java 'import' is different from Twig's
            'def ' => -7,                   // Python
            '@mixin' => -10, '@include' => -10, // SCSS
            '{ color:' => -3, '.class' => -3, '#id' => -3, // CSS specifics
        ],
        'comment_markers' => ['{#'], // Twig specific comment
    ],
];
