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

namespace Alto\LanguageDetector;

/**
 * Represents the result of a language detection attempt.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final readonly class DetectionResult
{
    private float $confidence; // 0.0 (unknown/undetected) to 1.0 (very confident)

    /**
     * Private constructor to ensure controlled instantiation.
     */
    private function __construct(
        private ?string $language,
        float $confidence,
    ) {
        $this->confidence = max(0.0, min(1.0, $confidence));
    }

    public static function create(?string $language, float $confidence): self
    {
        return new self($language, $confidence);
    }

    /**
     * Creates a result indicating no language was detected.
     */
    public static function none(): self
    {
        return new self(null, 0.0);
    }

    /**
     * Creates a result indicating a specific language with maximum confidence.
     * Useful for definitive checks like the PHP tokenizer.
     */
    public static function definitive(string $language, float $confidence = 0.98): self
    {
        return new self($language, $confidence);
    }

    /**
     * Gets the best guess for the language identifier (e.g., 'php', 'javascript').
     * Returns null if no language could be confidently identified based on internal thresholds.
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Gets the confidence score for the detected language (0.0 to 1.0).
     */
    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /**
     * Helper to check if a language was detected with a certain confidence level.
     */
    public function isConfident(float $threshold = 0.5): bool
    {
        return null !== $this->language && $this->confidence >= $threshold;
    }
}
