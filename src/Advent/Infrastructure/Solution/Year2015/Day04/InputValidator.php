<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day04;

use InvalidArgumentException;

final class InputValidator
{
    public function validate(string $input): void
    {
        if (!preg_match('/[0-9a-xA-X]/', $input)) {
            throw new InvalidArgumentException('Input contains invalid characters. Only letters and numbers are allowed.');
        }
    }
}
