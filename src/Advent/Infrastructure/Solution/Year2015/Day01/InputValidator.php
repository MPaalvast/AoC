<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day01;

use InvalidArgumentException;

final class InputValidator
{
    public function validate(string $input): void
    {
        if (preg_match('/[^()]/', $input)) {
            throw new InvalidArgumentException('Input contains invalid characters. Only parentheses are allowed.');
        }
    }
}
