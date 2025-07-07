<?php

declare(strict_types=1);

namespace Alto\LanguageDetector;

use InvalidArgumentException;
use RuntimeException;

/**
 * Detects the programming language of a code snippet using heuristics.
 * Aims to be a lightweight, dependency-free component.
 * Loads language profiles from an external PHP file.
 */
class LanguageDetector
{
    // Minimum length for a snippet to be considered analyzable
    private const MIN_LENGTH = 10;
    // Minimum confidence score required to return a language name (vs null)
    public const CONFIDENCE_THRESHOLD = 0.25;

    /**
     * Holds the language profiles loaded from the external file.
     * Expected structure: ['lang_code' => ['markers'=>[], 'keywords'=>[], ...], ...]
     * @var array<string, array<string, mixed>>
     */
    private array $profiles;

    /**
     * Constructor - Loads language profiles from individual files or fallback file.
     *
     * @param string $profilesPath Can be either:
     *   - Path to a directory containing individual language profile files (*.php)
     *   - Path to a single PHP file containing consolidated language profiles array
     * @throws InvalidArgumentException If the path is invalid or files don't return proper arrays.
     * @throws RuntimeException If files cannot be read.
     */
    public function __construct(string $profilesPath)
    {
        if (is_dir($profilesPath)) {
            $this->loadIndividualProfiles($profilesPath);
        } elseif (is_file($profilesPath)) {
            $this->loadConsolidatedProfiles($profilesPath);
        } else {
            throw new InvalidArgumentException("Profiles path not found or not accessible: " . $profilesPath);
        }
    }

    /**
     * Loads individual language profile files from a directory.
     */
    private function loadIndividualProfiles(string $directory): void
    {
        $this->profiles = [];
        $profileFiles = glob(rtrim($directory, '/') . '/*.php');

        if (empty($profileFiles)) {
            throw new InvalidArgumentException("No profile files found in directory: " . $directory);
        }

        foreach ($profileFiles as $file) {
            try {
                $profiles = $this->includeProfileFile($file);
                if (is_array($profiles)) {
                    // Only merge valid profiles: string keys, array values
                    foreach ($profiles as $k => $v) {
                        if (is_string($k) && is_array($v)) {
                            $this->profiles[$k] = $v;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Log warning but continue with other files
                error_log("Warning: Failed to load profile file '{$file}': " . $e->getMessage());
            }
        }

        if (empty($this->profiles)) {
            throw new RuntimeException("No valid language profiles could be loaded from: " . $directory);
        }
    }

    /**
     * Loads consolidated language profiles from a single file.
     */
    private function loadConsolidatedProfiles(string $filePath): void
    {
        if (!is_readable($filePath)) {
            throw new InvalidArgumentException("Language profiles file not readable: " . $filePath);
        }

        $loadedProfiles = $this->includeProfileFile($filePath);

        if (!is_array($loadedProfiles)) {
            throw new InvalidArgumentException("Language profiles file did not return an array: " . $filePath);
        }

        // Only assign valid profiles: string keys, array values
        $filteredProfiles = [];
        foreach ($loadedProfiles as $k => $v) {
            if (is_string($k) && is_array($v)) {
                $filteredProfiles[$k] = $v;
            }
        }
        $this->profiles = $filteredProfiles;
    }

    /**
     * Safely includes a profile file and returns its contents.
     */
    private function includeProfileFile(string $filePath): mixed
    {
        set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool { return true; });
        try {
            return include $filePath;
        } catch (\Throwable $e) {
            throw new RuntimeException("Failed to include profile file '{$filePath}': " . $e->getMessage(), 0, $e);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * Detects the language of the provided code snippet.
     *
     * @param string $code The code snippet.
     * @return DetectionResult The detection result.
     */
    public function detect(string $code): DetectionResult
    {
        $code = trim($code);
        $length = strlen($code);

        if ($length < self::MIN_LENGTH) {
            return DetectionResult::none();
        }

        // 1. High-confidence PHP check using tokenizer
        // Note: The PHP profile still needs to be defined in the external file
        // if you want the heuristic scoring to work as a fallback for PHP.
        if ($this->isCertainlyPhp($code)) {
            // Check if 'php' profile exists before returning definitively
            if (isset($this->profiles['php'])) {
                return DetectionResult::definitive('php');
            } else {
                // If no PHP profile, treat as highly likely but maybe slightly less confident
                return DetectionResult::create('php', 0.90);
            }
        }

        // 2. Heuristic Scoring for all languages defined in profiles
        $scores = $this->calculateScores($code);

        // 3. Determine Best Match and Confidence
        $bestLang = null;
        $maxScore = -1.0;
        $secondMaxScore = -1.0;

        foreach ($scores as $lang => $score) {
            if ($score > $maxScore) {
                $secondMaxScore = $maxScore;
                $maxScore = $score;
                $bestLang = $lang;
            } elseif ($score > $secondMaxScore) {
                $secondMaxScore = $score;
            }
        }

        // 4. Calculate Confidence (same logic as before, requires tuning)
        $confidence = 0.0;
        if ($bestLang && $maxScore > 0.1) {
            $estimatedReasonableMaxScore = 30.0; // TUNE THIS!
            $confidence = min(1.0, $maxScore / $estimatedReasonableMaxScore);

            if ($secondMaxScore > 0 && $maxScore > 0) {
                $ratio = $secondMaxScore / $maxScore;
                if ($ratio > 0.7) {
                    $confidence *= (1.0 - $ratio * 0.8);
                } elseif ($ratio > 0.5) {
                    $confidence *= (1.0 - $ratio * 0.5);
                }
            }

            $lengthBoost = min(0.1, ($length / 2000));
            $confidence = min(1.0, $confidence + $lengthBoost);

            if ($confidence < self::CONFIDENCE_THRESHOLD) {
                return DetectionResult::create(null, $confidence);
            }

            return DetectionResult::create($bestLang, $confidence);

        } else {
            return DetectionResult::none();
        }
    }

    /**
     * Uses PHP's built-in tokenizer for a high-confidence check.
     * (Implementation is the same as before, no changes needed here)
     */
    private function isCertainlyPhp(string $code): bool
    {
        // If it doesn't even contain typical PHP start markers, skip tokenization
        if (stripos($code, '<?') === false && stripos($code, '$') === false && stripos($code, '->') === false && stripos($code, '::') === false) {
            return false;
        }

        set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool { return true; });
        try {
            if (!function_exists('token_get_all')) {
                return false;
            }
            $tokens = @token_get_all($code, TOKEN_PARSE);
        } finally {
            restore_error_handler();
        }

        if (!is_array($tokens) || count($tokens) <= 1) {
            return false;
        }

        $hasPhpSpecificTokens = false;
        $hasOpenTag = false;
        $nonWhitespaceTokens = 0;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                $tokenId = $token[0];
                $nonWhitespaceTokens++;

                if ($tokenId === T_OPEN_TAG || $tokenId === T_OPEN_TAG_WITH_ECHO) {
                    $hasOpenTag = true;
                    $hasPhpSpecificTokens = true;
                }

                if (in_array($tokenId, [
                    T_VARIABLE, T_ECHO, T_NAMESPACE, T_USE, T_CLASS, T_INTERFACE, T_TRAIT,
                    T_FUNCTION, T_PUBLIC, T_PRIVATE, T_PROTECTED, T_STATIC, T_ABSTRACT, T_FINAL,
                    T_OBJECT_OPERATOR, T_PAAMAYIM_NEKUDOTAYIM, T_GLOBAL, T_REQUIRE, T_INCLUDE,
                    T_ARRAY, T_LIST, T_EMPTY, T_ISSET, T_UNSET, T_NEW, T_CLONE, T_INSTANCEOF,
                    T_GOTO, T_YIELD, T_THROW, T_FINALLY, T_MATCH,
                ], true)) {
                    $hasPhpSpecificTokens = true;
                } elseif ($tokenId === T_STRING && in_array(strtolower($token[1]), ['__construct', '__destruct', '__call', '__get', '__set'], true)) {
                    $hasPhpSpecificTokens = true;
                }
            } elseif (is_string($token) && trim($token) !== '') {
                $nonWhitespaceTokens++;
            }
        }
        return $hasPhpSpecificTokens && ($hasOpenTag || $nonWhitespaceTokens > 5);
    }

    /**
     * Calculates heuristic scores for all defined languages using loaded profiles.
     * (Logic now uses $this->profiles instead of self::LANG_PROFILES)
     * @return array<string, float>
     */
    private function calculateScores(string $code): array
    {
        // Initialize scores based on the *loaded* profiles
        $scores = array_fill_keys(array_keys($this->profiles), 0.0);
        $codeLower = strtolower($code);

        // Loop through the loaded profiles
        foreach ($this->profiles as $lang => $profile) {
            if (!is_array($profile)) {
                continue;
            }
            // Ensure profile has expected keys before accessing to avoid errors
            // Add default empty arrays if keys are missing
            $profile['markers'] = isset($profile['markers']) ? $profile['markers'] : [];
            $profile['keywords'] = isset($profile['keywords']) ? $profile['keywords'] : [];
            $profile['patterns'] = isset($profile['patterns']) ? $profile['patterns'] : [];
            $profile['negative_keywords'] = isset($profile['negative_keywords']) ? $profile['negative_keywords'] : [];

            $currentScore = 0.0;

            // 1. Check for definitive start markers
            foreach ($profile['markers'] as $marker => $weight) {
                $markerText = is_int($marker) && is_string($weight) ? $weight : (is_string($marker) ? $marker : '');
                $markerWeight = is_int($marker) ? 10 : (is_numeric($weight) ? (float)$weight : 0.0);
                if ($markerText !== '' && str_starts_with($code, $markerText)) {
                    $currentScore += $markerWeight;
                    break;
                }
            }

            // 2. Check keywords (case-insensitive)
            foreach ($profile['keywords'] as $keyword => $weight) {
                $keywordStr = $keyword;
                $weightVal = is_numeric($weight) ? (float)$weight : 0.0;
                if ($keywordStr !== '' && str_contains($codeLower, strtolower($keywordStr))) {
                    $currentScore += $weightVal;
                }
            }

            // 3. Check regex patterns
            foreach ($profile['patterns'] as $pattern => $weight) {
                $patternStr = $pattern;
                $weightVal = is_numeric($weight) ? (float)$weight : 0.0;
                if ($patternStr !== '' && @preg_match($patternStr, $code)) {
                    $currentScore += $weightVal;
                }
            }

            // 4. Apply negative keywords (penalties)
            foreach ($profile['negative_keywords'] as $marker => $penalty) {
                $markerStr = $marker;
                $penaltyVal = is_numeric($penalty) ? (float)$penalty : 0.0;
                if ($markerStr !== '' && stripos($code, $markerStr) !== false) {
                    $currentScore += $penaltyVal;
                }
            }

            $scores[$lang] = max(0.0, $currentScore);
        }
        return $scores;
    }
}
