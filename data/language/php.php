<?php declare(strict_types=1);

/**
 * Language Profile for PHP detection (Heuristic Only).
 * For use with Alto\LanguageDetector
 *
 * This file returns an array containing the heuristic definition for PHP.
 * It assumes detection relies solely on markers, keywords, patterns etc.
 * defined here, without a special tokenizer pre-check.
 *
 * @return array The language profiles array containing only 'php'.
 */

return [
    'php' => [
        'markers' => [
            '<?php' => 10,    // Very high confidence start marker
            '<?=' => 8,        // High confidence short echo tag
        ],
        'keywords' => [
            // Core Constructs & Keywords
            'echo' => 2, 'namespace' => 5, 'use ' => 4, 'class' => 2, 'interface' => 3, 'trait' => 3, 'enum' => 3, // Added enum
            'function' => 2, '__construct' => 3, '__destruct' => 2, '__call' => 2, '__get' => 2, '__set' => 2,
            'public' => 2, 'private' => 2, 'protected' => 2, 'static' => 2, 'abstract' => 3, 'final' => 3,
            'if' => 1, 'else' => 1, 'elseif' => 1, 'endif' => 1, 'switch' => 1, 'case' => 1, 'default' => 1,
            'for' => 1, 'foreach' => 2, 'while' => 1, 'do' => 1, 'endfor' => 1, 'endforeach' => 1, 'endwhile' => 1, // Alt syntax less common now
            'break' => 1, 'continue' => 1, 'return' => 1, 'yield' => 2, 'goto' => 1,
            'try' => 1, 'catch' => 1, 'finally' => 1, 'throw' => 1,
            'new' => 1, 'clone' => 1, 'instanceof' => 2,
            'global' => 2, 'unset' => 1, 'isset' => 1, 'empty' => 1,
            'require' => 2, 'include' => 2, 'require_once' => 2, 'include_once' => 2,
            'array' => 1, 'list' => 1, // Older array/list syntax
            'match' => 3, // PHP 8+
            'true' => 1, 'false' => 1, 'null' => 1, // Case-insensitive in PHP
            // Common Superglobals (often used directly)
            '$_GET' => 2, '$_POST' => 2, '$_REQUEST' => 2, '$_SESSION' => 2, '$_COOKIE' => 2, '$_FILES' => 2, '$_SERVER' => 2, '$_ENV' => 2,
            // Magic Constants
            '__FILE__' => 2, '__DIR__' => 2, '__LINE__' => 2, '__FUNCTION__' => 2, '__CLASS__' => 2, '__TRAIT__' => 2, '__METHOD__' => 2, '__NAMESPACE__' => 2,
        ],
        'patterns' => [
            // Variables (Very Strong Indicator)
            '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/' => 4,
            // Object & Static Access (Very Strong Indicators)
            '/->/' => 5,
            '/::/' => 5,
            // Array Syntax (Fat Arrow - Strong Indicator)
            '/\s=>\s/' => 3,
            // Visibility keywords followed by function or var (stronger context)
            '/(?:public|private|protected)\s+(?:static\s+)?function\s+\w+/' => 3,
            '/(?:public|private|protected)\s+(?:static\s+)?\$[a-zA-Z_\x7f-\xff]/' => 3,
            // Type hinting (basic check for common types before var/param)
            '/\b(?:string|int|float|bool|array|object|callable|static|self)\s+\$/' => 1,
            // Return type hinting
            '/function\s+\w+\(.*\)\s*:\s*\??\s*(?:string|int|float|bool|array|object|callable|static|self|void)/' => 1,
            // Comments
            '/\/\//' => 1,
            '/#/' => 1, // Less common but valid
            '/\/\*.*?\*\//s' => 1,
        ],
        // Penalize syntax from other common languages
        'negative_keywords' => [
            'var ' => -5, 'let ' => -7, 'const ' => -7,   // JS variable declarations
            'console.log' => -5, 'document.' => -7, 'window.' => -5, // JS specifics
            'function(' => -1, // Overlap, but penalize slightly if no '$' or context matches PHP better
            'import ' => -5, 'export ' => -5,          // JS/Python modules (different from PHP use)
            'def ' => -7, 'elif' => -7,               // Python
            '@mixin' => -10, '@include' => -10,        // SCSS
            '{ color:' => -3, '.class' => -3, '#id' => -3, // CSS specifics (careful with inline styles in PHP)
            'SELECT ' => -7, 'CREATE TABLE' => -7,       // SQL
            '#include' => -10,                        // C/C++
            '{%' => -10, '{{' => -10, '{#' => -10,        // Twig
        ],
        'comment_markers' => ['//', '#', '/*'],
    ],
];

// IMPORTANT: No closing?> tag is needed or recommended in files containing only PHP code.
