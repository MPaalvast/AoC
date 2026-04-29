<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day01;

final class InputSanitizer
{
    public function sanitize(string $input): array
    {
        return str_split($input);
    }
}
