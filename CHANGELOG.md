# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-01-07

### Added
- Initial release of Alto Language Detector
- Support for 20+ programming languages:
  - JavaScript, Python, Java, PHP, TypeScript
  - C#, HTML, CSS, Go, Ruby, Rust, Kotlin, Swift
  - JSON, YAML, Markdown, Bash, C++, C, SQL
- Weighted heuristic scoring system with configurable patterns and keywords
- PHP-first detection using built-in tokenizer for high accuracy
- Confidence scoring (0.0-1.0) for detection results
- External language profile configuration system
- Comprehensive test suite with PHPUnit 12
- Zero external dependencies (PHP 8.2+ only)
- Extensible architecture for adding new languages
- Minimum snippet length validation (10 characters)
- Confidence threshold filtering (0.25 default)
- Language markers support for definitive detection
- Negative keyword patterns for disambiguation
- Immutable DetectionResult value object
- Complete documentation and usage examples

### Technical Features
- Strict type declarations throughout codebase
- Error handling for malformed language profiles
- Pattern validation and compilation
- Memory-efficient scoring algorithms
- Proper namespace organization (Alto\LanguageDetector)
- PSR-4 autoloading compliance
- Code style enforcement with PHP-CS-Fixer

[Unreleased]: https://github.com/alto/language-detector/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/alto/language-detector/releases/tag/v1.0.0