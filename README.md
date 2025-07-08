![Alto Language Detector](https://your-cdn.example.com/logo.svg)

# Alto Language Detector

A blazing-fast, pluggable PHP library to **identify the programming language of a code snippet** using a smart heuristic engine. 

Lightweight, extensible, and battle-tested for 12 of the most popular languages today.

## Features

* **High-precision detection** with confidence scoring
* **Heuristics-based engine** using language-specific markers, keywords & patterns
* **PHP-optimized**: Uses the native tokenizer for unmatched accuracy
* **Extensible**: Add your own languages in seconds
* **Zero dependencies**, ultra-light footprint

## 🧠 How It Works

The detector computes a score for each supported language based on:

* **Markers**: Unique syntax signatures (e.g., `fn main()` in Rust)
* **Keywords**: Common language-specific tokens (e.g., `let`, `function`)
* **Regex patterns**: Structural indicators (e.g., arrow functions, tag formats)
* **Negative markers**: Penalize false positives (e.g., `class` in procedural snippets)
* **PHP tokenizer**: Leveraged when `<?php` is found for definitive detection

Each snippet is scored and the most likely language is returned with a **confidence value between 0 and 1**.

## ✅ Supported Languages

```txt
css, go, html, java, javascript, php, python, ruby, svg, twig, typescript, xml
```

Want more? [Add your own](#adding-a-language) with just one file.

---

## 📦 Installation

```bash
composer require alto/language-detector
```

---

## 🧪 Usage

```php
use Alto\LanguageDetector\LanguageDetector;

$detector = new LanguageDetector();
$code = '<?php echo "Hello!";';

$result = $detector->detect($code);

if ($result->getLanguage()) {
    echo "Language: {$result->getLanguage()} ({$result->getConfidence()})";
} else {
    echo "Could not confidently detect language.";
}
```

---

## 🔍 Example Output

| Code Sample               | Detected Language | Confidence |
| ------------------------- | ----------------- | ---------- |
| `<?php echo "Hi";`        | php               | 0.98       |
| `console.log("Hello")`    | javascript        | 0.87       |
| `public class Hello {}`   | java              | 0.94       |
| `fn main() {}`            | go                | 0.81       |
| `???` (ambiguous snippet) | *none*            | < 0.25     |

---

## 🛠 Adding a Language

Just drop a file in `data/language/yourlang.php`:

```php
return [
    'rust' => [
        'markers' => [ 'fn main()' => 10 ],
        'keywords' => [ 'let ' => 2, 'impl ' => 3 ],
        'patterns' => [ '/fn\\s+\\w+\\s*\\(.*\\)/' => 4 ],
        'negative_keywords' => [ 'function(' => -5 ],
    ]
];
```

The detector will load it automatically. No extra config needed.

---

## 🔧 Custom Profiles

Use your own language definitions:

```php
$detector = new LanguageDetector('/my/custom/profiles.php');
// or a directory of profile files
$detector = new LanguageDetector('/profiles/');
```


## 📘 API Reference

### `LanguageDetector`

* `__construct(?string $profilesPath = null)`
* `detect(string $code): DetectionResult`

### `DetectionResult`

* `getLanguage(): ?string`
* `getConfidence(): float`
* `isDefinitive(): bool`


## 🏎 Performance Tips

* ✅ Larger snippets = better results
* ✅ PHP detection uses tokenizer, not heuristics
* ✅ Profiles are cached in memory
* ❌ Avoid mixing multiple languages in one snippet


## 🧱 Limitations

* Not designed for multi-language files (e.g., HTML+JS)
* May confuse dialects (e.g., Java vs. C#)
* Template engines (like Twig) are heuristically supported, not parsed


## 🤝 Contributing

Issues, improvements, and new profiles are welcome via pull requests.


## 🪪 License

MIT — see [LICENSE](LICENSE).

Made with ❤️ by the Alto project team.
