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

namespace Alto\LanguageDetector\Tests;

use Alto\LanguageDetector\LanguageDetector;
use PHPUnit\Framework\TestCase;

abstract class BaseDetectionCase extends TestCase
{
    protected LanguageDetector $detector;

    protected function setUp(): void
    {
        parent::setUp();
        // Use individual profiles directory for enhanced detection
        $profilesPath = __DIR__.'/../data/language';
        $this->detector = new LanguageDetector($profilesPath);
    }
}
