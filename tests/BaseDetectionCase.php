<?php

declare(strict_types=1);

namespace Alto\LanguageDetector\Tests;

use PHPUnit\Framework\TestCase;
use Alto\LanguageDetector\LanguageDetector;

abstract class BaseDetectionCase extends TestCase
{
    protected LanguageDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        // Use individual profiles directory for enhanced detection
        $profilesPath = __DIR__ . '/../data/language';
        $this->detector = new LanguageDetector($profilesPath);
    }
}
