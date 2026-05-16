<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day05;

use InvalidArgumentException;
final class InputValidator
{
    public function validate(string $input): void
    {
        if (preg_match('/^([a-z]+)$/', $input)) {
            throw new InvalidArgumentException('Input contains invalid characters. Only letters are allowed.');
        }
    }
}
