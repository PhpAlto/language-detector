# Alto Language Detector

A lightweight, dependency-free PHP library for detecting programming languages from code snippets using heuristic analysis.

## Features

- **Flexible Language Support**: Choose between 20+ languages (consolidated) or 8 enhanced languages (individual profiles)
- **PHP-First Detection**: Uses PHP's built-in tokenizer for high-confidence PHP detection
- **Weighted Scoring**: Advanced heuristic scoring system with configurable patterns and keywords
- **Confidence Scores**: Returns confidence levels (0.0-1.0) for detection results
- **Zero Dependencies**: No external dependencies beyond PHP 8.2+
- **Dual Profile Modes**: Consolidated file for broad coverage or individual files for enhanced accuracy

## Requirements

- PHP 8.3 or higher
- No additional dependencies

## Installation

Install via Composer:

```bash
composer require alto/language-detector
```

## Quick Start

```php
<?php

use Alto\LanguageDetector\LanguageDetector;

// Option 1: Consolidated profiles (20+ languages)
$detector = new LanguageDetector(__DIR__ . '/data/languages_signatures.php');

// Option 2: Individual profiles (8 enhanced languages)
$detector = new LanguageDetector(__DIR__ . '/data/language');

// Detect language from code snippet
$result = $detector->detect('<?php echo "Hello World";');

echo $result->getLanguage();    // 'php'
echo $result->getConfidence();  // 0.98
echo $result->isConfident();    // true
```

## Usage Examples

### Basic Detection

```php
$detector = new LanguageDetector('path/to/languages_signatures.php');

// JavaScript detection
$result = $detector->detect('const message = "Hello World";');
echo $result->getLanguage(); // 'javascript'

// Python detection
$result = $detector->detect('def hello():\n    print("Hello")');
echo $result->getLanguage(); // 'python'

// HTML detection
$result = $detector->detect('<div class="container">Content</div>');
echo $result->getLanguage(); // 'html'
```

### Working with Confidence Scores

```php
$result = $detector->detect($codeSnippet);

if ($result->isConfident(0.8)) {
    echo "High confidence: " . $result->getLanguage();
} elseif ($result->getLanguage()) {
    echo "Detected with lower confidence: " . $result->getLanguage();
} else {
    echo "Unable to detect language";
}
```

### Handling Edge Cases

```php
$result = $detector->detect('function test() {}');

// This could be JavaScript, PHP, or other languages
if ($result->getConfidence() < 0.5) {
    echo "Ambiguous result, confidence: " . $result->getConfidence();
}
```

## How It Works

The language detector uses a multi-stage approach:

1. **PHP Fast Path**: For PHP code, uses PHP's built-in tokenizer for definitive identification
2. **Marker Detection**: Checks for definitive language markers (e.g., `<?php`, `<!DOCTYPE html>`)
3. **Pattern Matching**: Applies weighted regex patterns specific to each language
4. **Keyword Analysis**: Scores based on language-specific keywords
5. **Confidence Calculation**: Considers pattern strength, ambiguity, and snippet length

### Detection Criteria

- **Minimum Length**: Snippets must be at least 10 characters
- **Confidence Threshold**: Results below 0.25 confidence return `null`
- **Weighted Scoring**: Patterns and keywords have different weights based on specificity

## Supported Languages

### Consolidated Profiles Mode (`data/languages_signatures.php`)
**20 Languages**: JavaScript, Python, Java, PHP, TypeScript, C#, HTML, CSS, Go, Ruby, Rust, Kotlin, Swift, JSON, YAML, Markdown, Bash, C++, C, SQL

### Individual Profiles Mode (`data/language/`)
**8 Enhanced Languages**: CSS/SCSS, HTML, JavaScript, PHP, SVG, Twig, TypeScript, XML

| Language   | Markers           | Key Patterns                    | Notes |
|------------|-------------------|---------------------------------|-------|
| PHP        | `<?php`, `<?=`    | Variables (`$var`), arrows (`->`) | Enhanced tokenizer support |
| JavaScript | -                 | `const`, `let`, arrow functions | TypeScript disambiguation |
| TypeScript | -                 | `interface`, type annotations   | Enhanced type detection |
| HTML       | `<!DOCTYPE html>` | HTML tags, attributes           | - |
| CSS        | -                 | Selectors, properties, SCSS     | Includes SCSS support |
| SVG        | SVG namespace     | SVG-specific elements           | Individual profiles only |
| XML        | XML declaration   | XML tags, CDATA                 | Individual profiles only |
| Twig       | Twig syntax       | Template tags, filters          | Individual profiles only |

### Choosing Profile Mode

**Consolidated Profiles** (`data/languages_signatures.php`):
- ✅ **Broad Coverage**: 20+ programming languages
- ✅ **Single File**: Easy to manage and deploy
- ⚠️ **Basic Detection**: Simpler patterns, may miss edge cases
- 📦 **Languages**: JavaScript, Python, Java, PHP, TypeScript, C#, HTML, CSS, Go, Ruby, Rust, Kotlin, Swift, JSON, YAML, Markdown, Bash, C++, C, SQL

**Individual Profiles** (`data/language/`):
- ✅ **Enhanced Accuracy**: Sophisticated disambiguation (CSS/SCSS, JS/TS)
- ✅ **Specialized Features**: SVG namespace detection, Twig templates, XML processing
- ✅ **Better Confidence**: More accurate confidence scores
- ⚠️ **Limited Coverage**: Only 8 languages currently
- 📦 **Languages**: CSS/SCSS, HTML, JavaScript, PHP, SVG, Twig, TypeScript, XML

**Recommendation**: Use individual profiles if you primarily work with web technologies (HTML, CSS, JS, PHP). Use consolidated profiles if you need broader language support.

## Configuration

### Language Profiles

Languages are configured in `data/languages_signatures.php`:

```php
return [
    'php' => [
        'markers' => ['<?php', '<?='],
        'patterns' => [
            '/\<\?php/' => 5,
            '/\$[a-zA-Z_][a-zA-Z0-9_]*/' => 2,
            '/->\w+/' => 2,
        ],
        'keywords' => [
            'function' => 1,
            'class' => 2,
            'namespace' => 2,
        ],
    ],
    // ... other languages
];
```

### Adding New Languages

1. Add language configuration to `languages_signatures.php`
2. Define patterns, keywords, and optional markers
3. Assign appropriate weights based on pattern specificity
4. Test with representative code samples

## Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run specific language tests
vendor/bin/phpunit tests/Language/PhpDetectionTest.php

# Check code style
vendor/bin/php-cs-fixer fix --dry-run
```

## API Reference

### LanguageDetector

```php
class LanguageDetector
{
    public function __construct(string $profilesFilePath)
    public function detect(string $code): DetectionResult
}
```

### DetectionResult

```php
class DetectionResult
{
    public function getLanguage(): ?string
    public function getConfidence(): float
    public function isConfident(float $threshold = 0.5): bool
    
    public static function create(?string $language, float $confidence): self
    public static function none(): self
    public static function definitive(string $language, float $confidence = 0.98): self
}
```

## Performance Notes

- **Optimized for PHP**: PHP detection uses native tokenizer for speed and accuracy
- **Lightweight**: No external dependencies, minimal memory footprint
- **Caching**: Consider caching results for frequently analyzed snippets
- **Snippet Size**: Larger snippets generally provide more accurate detection

## Limitations

- **Ambiguous Snippets**: Very short or generic code may not be reliably detected
- **Mixed Languages**: Designed for single-language snippets, not multi-language files
- **Template Languages**: Limited support for template engines (Twig partially supported)
- **Dialectical Variants**: May not distinguish between similar languages (e.g., C vs C++)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure code style compliance: `composer fix-style`
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
