<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2025\Day01;

use App\Advent\Domain\PuzzleSolver;

final class Part1Solver implements PuzzleSolver
{
    public function year(): int
    {
        return 2025;
    }

    public function day(): int
    {
        return 1;
    }

    public function part(): int
    {
        return 1;
    }

    public function solve(string $input): string
    {
        $lines = preg_split('/\R/', trim($input));
        $sum = 0;

        foreach ($lines as $line) {
            if ($line === '' || $line === false) {
                continue;
            }

            $sum += (int) trim($line);
        }

        return (string) $sum;
    }
}
