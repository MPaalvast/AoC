<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day04\ValueObject;

use App\Advent\Infrastructure\Solution\Year2015\Day04\InputSanitizer;
use App\Advent\Infrastructure\Solution\Year2015\Day04\InputValidator;

final readonly class PuzzleInput
{
    private MD5Finder $finder;

    public function __construct(
        string                 $originalInput,
        private InputValidator $validator,
        private InputSanitizer $sanitizer
    ) {
        $this->validator->validate($originalInput);
        $this->finder = $this->sanitizer->sanitize($originalInput);
    }

    public function getFinder(): MD5Finder
    {
        return $this->finder;
    }

}
