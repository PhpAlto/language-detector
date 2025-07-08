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
        'patterns' => [
            '/\bconst\s+[$A-Za-z_][$\w]*\s*=/' => 3,
            '/\blet\s+[$A-Za-z_][$\w]*\s*=/' => 3,
            '/\bvar\s+[$A-Za-z_][$\w]*\s*=/' => 2,
            '/\bfunction\s+[$A-Za-z_][$\w]*\s*\(/' => 2,
            '/\b[$A-Za-z_][$\w]*\s*=>\s*{/' => 4,
            '/\b(import|export)\s+{.*}\s+from\s+[\'"]/' => 4,
            '/\b(document|window)\.(get|set)Element/' => 3,
        ],
        'keywords' => [
            'function' => 1,
            'const' => 2,
            'let' => 2,
            'var' => 1,
            'import' => 2,
            'export' => 2,
            'await' => 2,
            'async' => 2,
        ],
    ],

    'python' => [
        'patterns' => [
            '/def\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\(.*\)\s*:/' => 3,
            '/class\s+[a-zA-Z_][a-zA-Z0-9_]*\s*(\([^)]*\))?\s*:/' => 3,
            '/import\s+[a-zA-Z_][a-zA-Z0-9_]*/' => 2,
            '/from\s+[a-zA-Z_][a-zA-Z0-9_.]*\s+import/' => 2,
            '/^\s*@\w+/' => 2,
        ],
        'keywords' => [
            'def' => 2,
            'class' => 2,
            'import' => 2,
            'if' => 1,
            'elif' => 2,
            'else' => 1,
            'for' => 1,
            'while' => 1,
            'return' => 1,
            'yield' => 2,
            'with' => 1,
        ],
    ],

    'java' => [
        'patterns' => [
            '/public\s+(class|interface|enum)\s+[a-zA-Z_][a-zA-Z0-9_]*/' => 4,
            '/private|protected|public\s+\w+\s+\w+\s*\(/' => 3,
            '/import\s+[a-zA-Z_][a-zA-Z0-9_.]*;/' => 2,
            '/package\s+[a-zA-Z_][a-zA-Z0-9_.]*;/' => 3,
        ],
        'keywords' => [
            'public' => 1,
            'private' => 1,
            'protected' => 1,
            'class' => 2,
            'interface' => 2,
            'extends' => 2,
            'implements' => 2,
            'void' => 1,
        ],
    ],

    'php' => [
        'markers' => ['<?php', '<?=', '<?'],
        'patterns' => [
            '/\<\?php/' => 5,
            '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/' => 2,
            '/function\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\(/' => 2,
            '/namespace\s+[a-zA-Z_\\\\][a-zA-Z0-9_\\\\]*;/' => 3,
            '/use\s+[a-zA-Z_\\\\][a-zA-Z0-9_\\\\]*;/' => 2,
            '/->\w+/' => 2,
            '/::/' => 2,
        ],
        'keywords' => [
            'function' => 1,
            'class' => 2,
            'namespace' => 2,
            'use' => 1,
            'echo' => 1,
            'public' => 1,
            'private' => 1,
            'protected' => 1,
        ],
    ],
    'typescript' => [
        'patterns' => [
            '/interface\s+[A-Za-z_][$\w]*\s*{/' => 6, // stronger weight for interface
            '/type\s+[A-Za-z_][$\w]*\s*=/' => 5,
            '/:\s*[A-Za-z_][$\w]*(\[\])?(\s*\||\s*&)?/' => 3,
            '/<[A-Za-z_][$\w]*>/' => 2,
            '/class\s+[A-Za-z_][$\w]*\s*(<.*>)?\s*{/' => 2,
            '/enum\s+[A-Za-z_][$\w]*\s*{/' => 4,
            '/implements\s+[A-Za-z_][$\w]*/' => 3,
            '/readonly\s+[A-Za-z_][$\w]*/' => 2,
            '/public\s+[A-Za-z_][$\w]*/' => 2,
            '/private\s+[A-Za-z_][$\w]*/' => 2,
            '/protected\s+[A-Za-z_][$\w]*/' => 2,
        ],
        'keywords' => [
            'interface' => 5,
            'type' => 4,
            'enum' => 3,
            'implements' => 2,
            'readonly' => 2,
            'public' => 1,
            'private' => 1,
            'protected' => 1,
            'namespace' => 2,
        ],
    ],

    'csharp' => [
        'patterns' => [
            '/using\s+[a-zA-Z_.]+;/' => 2,
            '/namespace\s+[a-zA-Z_.]+\s*{/' => 3,
            '/public\s+(class|interface|enum|struct)\s+[a-zA-Z_][a-zA-Z0-9_]*/' => 4,
            '/private|protected|public\s+\w+(\<.*\>)?\s+\w+\s*/' => 2,
        ],
        'keywords' => [
            'using' => 2,
            'namespace' => 2,
            'class' => 2,
            'interface' => 2,
            'void' => 1,
            'var' => 1,
            'async' => 2,
            'await' => 2,
        ],
    ],

    'html' => [
        'markers' => ['<!DOCTYPE html', '<html'],
        'patterns' => [
            '/<(!DOCTYPE\s+html|html)/' => 5,
            '/<(head|body|div|span|p|a|img|script|link|meta|title|h1|h2|h3|ul|li)\b/' => 2,
            '/<\/\s*(html|head|body|div|span|p|a|script|title|h1|h2|h3|ul|li)\s*>/' => 2,
        ],
        'keywords' => [
            'html' => 2,
            'head' => 1,
            'body' => 1,
            'div' => 1,
            'class' => 1,
            'id' => 1,
            'script' => 1,
            'style' => 1,
        ],
    ],

    'css' => [
        'patterns' => [
            '/[#\.][a-zA-Z][\w-]*\s*{/' => 3,
            '/[a-z-]+\s*:\s*[^{]+;/' => 2,
            '/@media\s+/' => 3,
            '/@keyframes\s+/' => 3,
            '/!important/' => 2,
        ],
        'keywords' => [
            'color' => 1,
            'background' => 1,
            'margin' => 1,
            'padding' => 1,
            'display' => 1,
            'position' => 1,
            'width' => 1,
            'height' => 1,
        ],
    ],

    'go' => [
        'patterns' => [
            '/package\s+[a-zA-Z_][a-zA-Z0-9_]*/' => 3,
            '/import\s+\(.*\)/' => 2,
            '/func\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\(/' => 2,
            '/type\s+[a-zA-Z_][a-zA-Z0-9_]*\s+struct\s*{/' => 3,
        ],
        'keywords' => [
            'func' => 2,
            'package' => 2,
            'import' => 2,
            'type' => 2,
            'struct' => 2,
            'interface' => 2,
            'defer' => 2,
            'go' => 2,
            'chan' => 2,
        ],
    ],

    'ruby' => [
        'patterns' => [
            '/def\s+[a-zA-Z_][a-zA-Z0-9_]*(\(.+\))?/' => 3,
            '/class\s+[A-Z][a-zA-Z0-9_]*/' => 3,
            '/module\s+[A-Z][a-zA-Z0-9_]*/' => 3,
            '/require\s+[\'"][a-zA-Z0-9_\/]+[\'"]/' => 2,
        ],
        'keywords' => [
            'def' => 2,
            'class' => 2,
            'module' => 2,
            'require' => 2,
            'include' => 2,
            'attr_accessor' => 2,
            'end' => 1,
            'do' => 1,
        ],
    ],

    'rust' => [
        'patterns' => [
            '/fn\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\<?\(/' => 3,
            '/struct\s+[a-zA-Z_][a-zA-Z0-9_]*\s*(\<.*\>)?\s*{/' => 3,
            '/impl\s+(\<.*\>\s+)?[a-zA-Z_][a-zA-Z0-9_]*/' => 3,
            '/use\s+[a-zA-Z_:][a-zA-Z0-9_:]*;/' => 2,
        ],
        'keywords' => [
            'fn' => 2,
            'struct' => 2,
            'impl' => 2,
            'let' => 1,
            'mut' => 2,
            'pub' => 1,
            'use' => 1,
            'match' => 2,
            'enum' => 2,
        ],
    ],

    'kotlin' => [
        'patterns' => [
            '/fun\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\(/' => 3,
            '/class\s+[A-Z][a-zA-Z0-9_]*(\<.*\>)?(\s*:\s*[A-Z][a-zA-Z0-9_]*)?/' => 3,
            '/val\s+[a-zA-Z_][a-zA-Z0-9_]*\s*:/' => 2,
            '/var\s+[a-zA-Z_][a-zA-Z0-9_]*\s*:/' => 2,
        ],
        'keywords' => [
            'fun' => 2,
            'class' => 2,
            'val' => 2,
            'var' => 1,
            'override' => 2,
            'suspend' => 2,
            'companion' => 2,
            'object' => 2,
        ],
    ],

    'swift' => [
        'patterns' => [
            '/func\s+[a-zA-Z_][a-zA-Z0-9_]*\s*\(/' => 3,
            '/class\s+[A-Z][a-zA-Z0-9_]*/' => 3,
            '/struct\s+[A-Z][a-zA-Z0-9_]*/' => 3,
            '/let\s+[a-zA-Z_][a-zA-Z0-9_]*\s*(:|=)/' => 2,
            '/var\s+[a-zA-Z_][a-zA-Z0-9_]*\s*(:|=)/' => 2,
        ],
        'keywords' => [
            'func' => 2,
            'class' => 2,
            'struct' => 2,
            'let' => 1,
            'var' => 1,
            'guard' => 2,
            'protocol' => 2,
            'extension' => 2,
        ],
    ],

    'json' => [
        'patterns' => [
            '/^\s*{/' => 2,
            '/"\s*:\s*("|[0-9]|\{|\[|true|false|null)/' => 3,
            '/^\s*\[/' => 2,
            '/\s*},?\s*$/' => 2,
        ],
        'keywords' => [],
    ],

    'yaml' => [
        'patterns' => [
            '/^---\s*$/' => 3,
            '/^[a-zA-Z][a-zA-Z0-9_-]*:\s/' => 2,
            '/^\s+-\s+[a-zA-Z]/' => 2,
        ],
        'keywords' => [],
    ],

    'markdown' => [
        'patterns' => [
            '/^#{1,6}\s+[^\n]+$/' => 3,
            '/^\*\s+[^\n]+$/' => 2,
            '/^-\s+[^\n]+$/' => 2,
            '/^>\s+[^\n]+$/' => 2,
            '/^\[.+\]:\s+http/' => 2,
        ],
        'keywords' => [],
    ],

    'bash' => [
        'patterns' => [
            '/^#!\/bin\/(bash|sh)/' => 4,
            '/\$\{[a-zA-Z_][a-zA-Z0-9_]*\}/' => 3,
            '/\$[a-zA-Z_][a-zA-Z0-9_]*/' => 2,
            '/if\s+\[\s+.*\s+\];?\s+then/' => 3,
        ],
        'keywords' => [
            'if' => 1,
            'then' => 2,
            'else' => 1,
            'fi' => 2,
            'for' => 1,
            'while' => 1,
            'do' => 1,
            'done' => 2,
            'function' => 2,
            'export' => 2,
        ],
    ],

    'cpp' => [
        'patterns' => [
            '/\#include\s+[<"][a-zA-Z0-9_./]+[>"]/' => 2,
            '/class\s+[a-zA-Z_][a-zA-Z0-9_]*\s*(:\s*(public|private|protected)\s+[a-zA-Z_][a-zA-Z0-9_]*)?/' => 3,
            '/std::[a-zA-Z_][a-zA-Z0-9_]*/' => 3,
            '/namespace\s+[a-zA-Z_][a-zA-Z0-9_]*\s*{/' => 2,
        ],
        'keywords' => [
            'class' => 2,
            'public' => 1,
            'private' => 1,
            'protected' => 1,
            'virtual' => 2,
            'template' => 2,
            'typename' => 2,
            'namespace' => 2,
        ],
    ],

    'c' => [
        'patterns' => [
            '/\#include\s+[<"][a-zA-Z0-9_./]+[>"]/' => 2,
            '/\#define\s+[a-zA-Z_][a-zA-Z0-9_]*/' => 2,
            '/struct\s+[a-zA-Z_][a-zA-Z0-9_]*\s*{/' => 2,
            '/int\s+main\s*\(void\)|\(int\s+argc/' => 3,
        ],
        'keywords' => [
            'void' => 1,
            'int' => 1,
            'char' => 1,
            'struct' => 2,
            'typedef' => 2,
            'const' => 1,
            'static' => 1,
            'return' => 1,
        ],
    ],

    'sql' => [
        'patterns' => [
            '/SELECT\s+.*\s+FROM\s+[a-zA-Z_][a-zA-Z0-9_]*/i' => 3,
            '/INSERT\s+INTO\s+[a-zA-Z_][a-zA-Z0-9_]*/i' => 3,
            '/UPDATE\s+[a-zA-Z_][a-zA-Z0-9_]*\s+SET/i' => 3,
            '/CREATE\s+TABLE\s+[a-zA-Z_][a-zA-Z0-9_]*/i' => 3,
        ],
        'keywords' => [
            'SELECT' => 2,
            'FROM' => 2,
            'WHERE' => 2,
            'INSERT' => 2,
            'UPDATE' => 2,
            'DELETE' => 2,
            'CREATE' => 2,
            'TABLE' => 2,
            'JOIN' => 2,
        ],
    ],

    'scss' => [
        'patterns' => [
            '/\$[a-zA-Z_][\w-]*\s*:\s*[^;]+;/' => 8, // higher weight for variables
            '/@mixin\s+[a-zA-Z_][\w-]*\s*(\([^)]*\))?\s*{/' => 6,
            '/@include\s+[a-zA-Z_][\w-]*\s*(\([^)]*\))?;/' => 6,
            '/@extend\s+[.#%]?[a-zA-Z_][\w-]*;/' => 5,
            '/@import\s+[\'"][^\'\"]+[\'"];/' => 4,
            '/&(:?[\w-]+|\s*{)/' => 3,
            '/#{[^}]+}/' => 3,
            '/@(if|else|for|while|each)\s+/' => 4,
            '/@function\s+[a-zA-Z_][\w-]*\s*\(/' => 5,
        ],
        'keywords' => [
            'mixin' => 4,
            'include' => 4,
            'extend' => 4,
            'import' => 3,
            'function' => 4,
            'return' => 2,
            'if' => 2,
            'else' => 2,
        ],
    ],
];
